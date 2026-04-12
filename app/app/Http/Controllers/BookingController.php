<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Dentist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\DoctorSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Business hours: 08:00 – 17:00, slot step: 30 min
    private const WORK_START = 8;
    private const WORK_END   = 17;
    private const SLOT_STEP  = 30;
    private const DAYS_AHEAD = 14;

    /**
     * Show the schedule page for a dentist + service combination.
     * URL: GET /book?service=1&dentist=2
     */
    public function selectTime(\Illuminate\Http\Request $request)
    {
        $service = Service::findOrFail($request->query('service'));
        $dentist = Dentist::with(['specializations', 'ratings'])->findOrFail($request->query('dentist'));

        // Compute average rating
        $avgRating = $dentist->ratings->avg('rating') ?? 0;

        // Collect all scheduled / active appointments for this dentist in the window
        $today     = Carbon::today();
        $windowEnd = $today->clone()->addDays(self::DAYS_AHEAD + 1);

        $bookedSlots = Appointment::where('dentist_id', $dentist->id)
            ->whereIn('status', ['scheduled'])
            ->whereBetween('appointment_date', [$today->format('Y-m-d'), $windowEnd->format('Y-m-d')])
            ->with('service')
            ->get()
            ->groupBy('appointment_date');   // keyed by Y-m-d string

        // Build the days array (skip Sundays, skip today itself)
        $days = $this->buildDays($today, self::DAYS_AHEAD);

        // Build slots for each day
        $schedule = [];
        foreach ($days as $day) {
            $dateKey      = $day['date'];
            $dayBooked    = $bookedSlots->get($dateKey, collect());
            $schedule[$dateKey] = $this->buildDaySlots($dateKey, $dayBooked, $service->duration_minutes ?? 30);
        }

        return view('Booking.SelectTime', [
            'service'   => $service,
            'dentist'   => $dentist,
            'avgRating' => round($avgRating, 1),
            'days'      => $days,
            'schedule'  => $schedule,
        ]);
    }

    /**
     * Show the payment page.
     * URL: GET /book/payment?appointment=1
     * (called from confirmation step, after appointment is created)
     */
    public function showPayment(Request $request)
    {
        $appointment = Appointment::with(['service', 'dentist.specializations', 'patient'])
            ->findOrFail($request->query('appointment'));

        return view('Booking.Payment', [
            'appointment' => $appointment,
            'service'     => $appointment->service,
            'dentist'     => $appointment->dentist,
            'patient'     => $appointment->patient,
        ]);
    }

    /**
     * Process the payment (appointment already created in confirm step).
     * URL: POST /book/pay
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        // Create payment record
        Payment::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'amount'         => $appointment->service->price,
            'payment_date'   => now()->toDateString(),
            'payment_method' => 'credit_card',
            'status'         => 'completed',
        ]);

        // Redirect to done page
        return redirect()->route('booking.done', ['appointment' => $appointment->id]);
    }

    /**
     * Show the confirmation page (fresh booking details before payment).
     * URL: GET /book/confirm?service=1&dentist=2&date=2026-03-18&time=09:00
     */
    public function showConfirm(Request $request)
    {
        $service = Service::findOrFail($request->query('service'));
        $dentist = Dentist::with('specializations')->findOrFail($request->query('dentist'));
        $date    = $request->query('date');
        $time    = $request->query('time');

        return view('Booking.Confirm', [
            'service' => $service,
            'dentist' => $dentist,
            'date'    => $date,
            'time'    => $time,
        ]);
    }

    /**
     * Submit the confirmation — create the appointment and redirect to Payment.
     * URL: POST /book/confirm/submit
     */
    public function submitConfirm(Request $request)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'dentist_id'   => 'required|exists:dentists,id',
            'date'         => 'required|date',
            'time'         => 'required',
            'patient_name' => ['required','string','min:3','max:255','regex:/^[\pL\s]+$/u'],
            'patient_email'=> 'required|email|max:255',
            'patient_phone'=> ['required','regex:/^05[0-9]{8}$/'],
        ], [
            'patient_name.regex'  => 'Name may only contain letters and spaces.',
            'patient_name.min'    => 'Name must be at least 3 characters.',
            'patient_phone.regex' => 'Phone must be 10 digits and start with 05 (e.g. 0512345678).',
        ]);

        // Find or create a guest patient record
        $patient = Patient::where('email', $request->patient_email)->first();

        if (!$patient) {
            $patient = Patient::create([
                'name'     => $request->patient_name,
                'email'    => $request->patient_email,
                'phone'    => $request->patient_phone,
                'password' => bcrypt('guest_' . uniqid()),
            ]);
        }

        // Block check: if patient's account is blocked from booking
        if ($patient->booking_blocked) {
            return back()->withErrors([
                'booking_blocked' => 'Your account is blocked from booking. Please contact administration to unblock your account.',
            ])->withInput();
        }

        $patientId = $patient->id;

        $appointment = Appointment::create([
            'patient_id'       => $patientId,
            'dentist_id'       => $request->dentist_id,
            'service_id'       => $request->service_id,
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'status'           => 'scheduled',
            'notes'            => $request->input('notes', ''),
        ]);

        // Auto-reactivate idle subscription when patient books a new appointment
        DoctorSubscription::where('patient_id', $patientId)
            ->where('status', 'idle')
            ->update(['status' => 'active']);

        // Redirect to payment page with appointment ID
        return redirect()->route('booking.payment', ['appointment' => $appointment->id]);
    }

    /**
     * Show the Done / success page.
     * URL: GET /book/done?appointment=1
     */
    public function showDone(Request $request)
    {
        $appointment = Appointment::with(['service', 'dentist.specializations', 'patient'])
            ->findOrFail($request->query('appointment'));

        return view('Booking.Done', compact('appointment'));
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function buildDays(Carbon $today, int $count): array
    {
        $days = [];
        $d    = $today->clone()->addDay();   // start from tomorrow

        while (count($days) < $count) {
            // Clinic is closed on Friday and Saturday only (open Sun-Thu)
            if (!in_array($d->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY])) {
                $days[] = [
                    'date'     => $d->format('Y-m-d'),
                    'day_name' => $d->format('D'),       // Mon
                    'date_num' => $d->format('j'),       // 18
                    'month'    => $d->format('M'),       // Mar
                    'is_today' => false,
                    'is_weekend' => false,
                ];
            }
            $d->addDay();
        }

        return $days;
    }

    private function buildDaySlots(string $dateStr, $dayBooked, int $serviceDuration): array
    {
        $slots = [];

        // Break duration after this service type
        $break = $this->breakForDuration($serviceDuration);
        // Total cycle per booking = service duration + mandatory break
        $step  = $serviceDuration + $break;

        // Each existing appointment blocks [start, start + its_duration + its_break]
        $blockedRanges = [];
        foreach ($dayBooked as $appt) {
            $startMinutes  = $this->timeToMinutes($appt->appointment_time->format('H:i'));
            $dur           = $appt->service->duration_minutes ?? 30;
            $apptBreak     = $this->breakForDuration($dur);
            $blockedRanges[] = [$startMinutes, $startMinutes + $dur + $apptBreak];
        }

        $startMinutes = self::WORK_START * 60;
        $endMinutes   = self::WORK_END   * 60;

        for ($m = $startMinutes; $m < $endMinutes; $m += $step) {
            $slotEnd = $m + $serviceDuration;

            // Check overlap with any booked range
            $isBusy = false;
            foreach ($blockedRanges as [$bStart, $bEnd]) {
                if ($m < $bEnd && $slotEnd > $bStart) {
                    $isBusy = true;
                    break;
                }
            }

            $timeStr = $this->minutesToTime($m);
            $slots[] = [
                'time'     => $timeStr,
                'display'  => $this->minutesToTime12($m),
                'is_busy'  => $isBusy,
            ];
        }

        return $slots;
    }

    /**
     * Break duration after an appointment based on service length:
     *  ≤ 5 min   → 5 min break  (very quick procedure)
     *  ≤ 30 min  → 10 min break
     *  ≤ 60 min  → 15 min break
     *  > 60 min  → 20 min break
     */
    private function breakForDuration(int $duration): int
    {
        if ($duration <= 5)  return 5;
        if ($duration <= 30) return 10;
        if ($duration <= 60) return 15;
        return 20;
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $i] = explode(':', $time);
        return (int)$h * 60 + (int)$i;
    }

    private function minutesToTime(int $m): string
    {
        return sprintf('%02d:%02d', intdiv($m, 60), $m % 60);
    }

    private function minutesToTime12(int $m): string
    {
        $h   = intdiv($m, 60);
        $min = $m % 60;
        $ampm = $h >= 12 ? 'PM' : 'AM';
        $h12  = $h > 12 ? $h - 12 : ($h === 0 ? 12 : $h);
        return sprintf('%d:%02d %s', $h12, $min, $ampm);
    }
}

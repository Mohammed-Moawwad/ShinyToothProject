<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\DentistSchedule;
use App\Models\DoctorWrittenReport;
use App\Models\VacationRequest;
use App\Models\DoctorSubscription;
use App\Models\Service;
use App\Models\SubscriptionBonus;
use App\Models\SubscriptionRating;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    /**
     * Resolve the current dentist from query param.
     */
    private function dentist(Request $request): Dentist
    {
        $id = $request->query('dentist');
        abort_if(!$id, 400, 'A dentist ID is required. Please log in again.');
        return Dentist::findOrFail($id);
    }

    /* ─── OVERVIEW ──────────────────────────────────────────── */
    public function index(Request $request)
    {
        $dentist = $this->dentist($request);
        $today   = Carbon::today();
        $weekStart = $today->copy()->startOfWeek(Carbon::SUNDAY);
        $weekEnd   = $today->copy()->endOfWeek(Carbon::SATURDAY);

        // Stats
        $activeSubscriptions = DoctorSubscription::where('dentist_id', $dentist->id)
            ->whereIn('status', ['active', 'idle'])->count();
        $pendingRequests = DoctorSubscription::where('dentist_id', $dentist->id)
            ->where('status', 'pending')->count();
        $todayAppointments = Appointment::where('dentist_id', $dentist->id)
            ->whereDate('appointment_date', $today)->count();
        $weekAppointments = Appointment::where('dentist_id', $dentist->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])->count();
        $totalBonus = SubscriptionBonus::where('dentist_id', $dentist->id)->sum('bonus_amount');
        $avgRating = $dentist->ratings()->avg('rating') ?? 0;
        $totalReviews = $dentist->ratings()->count();

        // Today's appointments
        $todayAppts = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        // Pending subscription requests
        $pendingSubs = DoctorSubscription::with('patient')
            ->where('dentist_id', $dentist->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Recent ratings
        $recentRatings = SubscriptionRating::whereHas('subscription', function ($q) use ($dentist) {
            $q->where('dentist_id', $dentist->id);
        })->with('patient')->latest()->take(5)->get();

        return view('doctor.dashboard', compact(
            'dentist', 'activeSubscriptions', 'pendingRequests',
            'todayAppointments', 'weekAppointments', 'totalBonus',
            'avgRating', 'totalReviews', 'todayAppts', 'pendingSubs', 'recentRatings'
        ));
    }

    /* ─── APPOINTMENTS ──────────────────────────────────────── */
    public function appointments(Request $request)
    {
        $dentist = $this->dentist($request);
        $today   = Carbon::today();
        $weekStart = $today->copy()->startOfWeek(Carbon::SUNDAY);
        $weekEnd   = $today->copy()->endOfWeek(Carbon::SATURDAY);

        // Today's appointments
        $todayAppointments = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        // Rest of the week (excluding today)
        $weeklyAppointments = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->whereDate('appointment_date', '!=', $today)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('doctor.appointments', compact(
            'dentist', 'todayAppointments', 'weeklyAppointments', 'today', 'weekStart', 'weekEnd'
        ));
    }

    /* ─── SUBSCRIPTIONS ─────────────────────────────────────── */
    public function subscriptions(Request $request)
    {
        $dentist = $this->dentist($request);
        $filter  = $request->query('filter', 'all');

        $query = DoctorSubscription::with(['patient', 'plan.items.service', 'rating', 'bonus'])
            ->where('dentist_id', $dentist->id);

        if ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'active') {
            $query->whereIn('status', ['active', 'idle']);
        } elseif ($filter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($filter === 'history') {
            $query->whereIn('status', ['rejected', 'cancelled', 'switched', 'removed']);
        }

        $subscriptions = $query->latest()->paginate(15)->withQueryString();

        return view('doctor.subscriptions', compact('dentist', 'subscriptions', 'filter'));
    }

    /* ─── TREATMENT PLAN DESIGNER ───────────────────────────── */
    public function planDesigner(Request $request, $subscriptionId)
    {
        $dentist = $this->dentist($request);

        $subscription = DoctorSubscription::with(['patient', 'plan.items.service', 'plan.items.assignedDentist'])
            ->where('dentist_id', $dentist->id)
            ->findOrFail($subscriptionId);

        $services = Service::orderBy('category')->orderBy('name')->get();
        $dentists = Dentist::where('status', 'active')->orderBy('name')->get();

        return view('doctor.plan-designer', compact('dentist', 'subscription', 'services', 'dentists'));
    }

    /* ─── REPORTS ────────────────────────────────────────────── */
    public function reports(Request $request)
    {
        $dentist = $this->dentist($request);

        // Appointment stats
        $totalAppointments = Appointment::where('dentist_id', $dentist->id)->count();
        $completedAppointments = Appointment::where('dentist_id', $dentist->id)->where('status', 'completed')->count();
        $noShowAppointments = Appointment::where('dentist_id', $dentist->id)->where('status', 'no_show')->count();
        $cancelledAppointments = Appointment::where('dentist_id', $dentist->id)->where('status', 'cancelled')->count();
        $failedAppointments = Appointment::where('dentist_id', $dentist->id)->where('status', 'failed')->count();

        // Subscription stats
        $totalSubscriptions = DoctorSubscription::where('dentist_id', $dentist->id)->count();
        $activeSubscriptions = DoctorSubscription::where('dentist_id', $dentist->id)->whereIn('status', ['active', 'idle'])->count();
        $completedSubscriptions = DoctorSubscription::where('dentist_id', $dentist->id)->where('status', 'completed')->count();

        // Monthly appointment breakdown (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Appointment::where('dentist_id', $dentist->id)
                ->whereYear('appointment_date', $month->year)
                ->whereMonth('appointment_date', $month->month)
                ->count();
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => $count,
            ];
        }

        // Top services
        $topServices = Appointment::where('dentist_id', $dentist->id)
            ->selectRaw('service_id, COUNT(*) as total')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('service')
            ->get();

        // Bonuses earned
        $totalBonus = SubscriptionBonus::where('dentist_id', $dentist->id)->sum('bonus_amount');
        $bonusCount = SubscriptionBonus::where('dentist_id', $dentist->id)->count();

        return view('doctor.reports', compact(
            'dentist', 'totalAppointments', 'completedAppointments', 'noShowAppointments',
            'cancelledAppointments', 'failedAppointments', 'totalSubscriptions', 'activeSubscriptions',
            'completedSubscriptions', 'monthlyData', 'topServices', 'totalBonus', 'bonusCount'
        ));
    }

    /* ─── BONUSES & RATINGS ─────────────────────────────────── */
    public function bonuses(Request $request)
    {
        $dentist = $this->dentist($request);

        $bonuses = SubscriptionBonus::with(['subscription.patient', 'subscription.plan'])
            ->where('dentist_id', $dentist->id)
            ->latest()
            ->get();

        $totalBonus = $bonuses->sum('bonus_amount');

        $ratings = SubscriptionRating::whereHas('subscription', function ($q) use ($dentist) {
            $q->where('dentist_id', $dentist->id);
        })->with(['patient', 'subscription'])->latest()->get();

        $avgRating = $ratings->avg('rating') ?? 0;

        return view('doctor.bonuses', compact('dentist', 'bonuses', 'totalBonus', 'ratings', 'avgRating'));
    }

    /* ─── PROFILE ────────────────────────────────────────────── */
    public function profile(Request $request)
    {
        $dentist = $this->dentist($request);
        return view('doctor.profile', compact('dentist'));
    }

    public function updateProfile(Request $request)
    {
        $dentist = $this->dentist($request);

        $validated = $request->validate([
            'phone'              => 'nullable|string|max:20',
            'career_description' => 'nullable|string|max:5000',
        ]);

        $dentist->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
    /* ─── PATIENT REPORT ────────────────────────────────────────── */
    public function patientReport(Request $request)
    {
        $dentist = $this->dentist($request);
        $search  = trim($request->query('search', ''));
        $patient = null;

        // All patients who have ever had an appointment OR a subscription with this dentist
        $patientsQuery = \App\Models\Patient::where(function ($q) use ($dentist) {
            $q->whereHas('appointments', fn($a) => $a->where('dentist_id', $dentist->id))
              ->orWhereHas('subscriptions', fn($s) => $s->where('dentist_id', $dentist->id));
        });

        if ($search) {
            $patientsQuery->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $patientsQuery->orderBy('name')->get();

        // If a specific patient is selected
        $patientId = $request->query('patient_id');
        if ($patientId) {
            $patient = \App\Models\Patient::findOrFail($patientId);

            $appointments = \App\Models\Appointment::with('service')
                ->where('dentist_id', $dentist->id)
                ->where('patient_id', $patient->id)
                ->orderByDesc('appointment_date')
                ->get();

            $totalAppts     = $appointments->count();
            $completedAppts = $appointments->where('status', 'completed')->count();
            $cancelledAppts = $appointments->where('status', 'cancelled')->count();
            $noShowAppts    = $appointments->where('status', 'no_show')->count();
            $failedAppts    = $appointments->where('status', 'failed')->count();
            $scheduledAppts = $appointments->where('status', 'scheduled')->count();

            $subscription = \App\Models\DoctorSubscription::with('plan.items.service')
                ->where('dentist_id', $dentist->id)
                ->where('patient_id', $patient->id)
                ->latest()
                ->first();

            $writtenReports = DoctorWrittenReport::where('dentist_id', $dentist->id)
                ->where('patient_id', $patient->id)
                ->orderByDesc('created_at')
                ->get();

            return view('doctor.patient-report', compact(
                'dentist', 'patient', 'appointments', 'patients', 'search',
                'totalAppts', 'completedAppts', 'cancelledAppts',
                'noShowAppts', 'failedAppts', 'scheduledAppts', 'subscription',
                'writtenReports'
            ));
        }

        return view('doctor.patient-report', compact('dentist', 'patients', 'search'));
    }

    /* ─── WRITTEN REPORTS (PATIENT) ─────────────────────────────── */
    public function storeWrittenReport(Request $request)
    {
        $dentist = $this->dentist($request);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title'      => 'required|string|max:255',
            'content'    => 'required|string|min:10|max:10000',
            'action'     => 'required|in:draft,submitted',
        ]);

        $submittedAt = $validated['action'] === 'submitted' ? now() : null;

        DoctorWrittenReport::create([
            'dentist_id'   => $dentist->id,
            'patient_id'   => $validated['patient_id'],
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'status'       => $validated['action'],
            'submitted_at' => $submittedAt,
        ]);

        $msg = $validated['action'] === 'submitted'
            ? 'Report saved and submitted to administration.'
            : 'Report saved as draft.';

        return redirect(
            '/doctor/patient-report?dentist=' . $dentist->id . '&patient_id=' . $validated['patient_id']
        )->with('report_success', $msg);
    }

    public function submitWrittenReport(Request $request, $id)
    {
        $dentist = $this->dentist($request);

        $report = DoctorWrittenReport::where('id', $id)
            ->where('dentist_id', $dentist->id)
            ->where('status', 'draft')
            ->firstOrFail();

        $report->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('report_success', 'Report submitted to administration.');
    }

    /* ─── VACATION REQUESTS ──────────────────────────────────── */
    public function vacations(Request $request)
    {
        $dentist  = $this->dentist($request);
        $requests = VacationRequest::where('dentist_id', $dentist->id)
            ->orderByDesc('created_at')
            ->get();

        $schedules = \App\Models\DentistSchedule::where('dentist_id', $dentist->id)
            ->where('is_available', true)
            ->get()
            ->keyBy('day_of_week');

        $schedulesJson = $schedules->map(fn($s) => [
            'start' => (int) substr($s->start_time, 0, 2),
            'end'   => (int) substr($s->end_time,   0, 2),
        ]);

        return view('doctor.vacations', compact('dentist', 'requests', 'schedules', 'schedulesJson'));
    }

    public function storeVacation(Request $request)
    {
        $dentist   = $this->dentist($request);
        $isPartial = $request->input('type') === 'partial_day';

        $validated = $request->validate([
            'type'       => 'required|in:full_day,partial_day',
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required_if:type,full_day', 'nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['required_if:type,partial_day', 'nullable', 'regex:/^\d{2}:00$/'],
            'end_time'   => ['required_if:type,partial_day', 'nullable', 'regex:/^\d{2}:00$/', 'gt:start_time'],
            'reason'     => 'required|string|min:20|max:1000',
        ]);

        // Verify the selected date is a working day for this dentist
        $dateToCheck = $validated['start_date'];
        $dayOfWeek   = strtolower(\Carbon\Carbon::parse($dateToCheck)->englishDayOfWeek);
        $schedule    = \App\Models\DentistSchedule::where('dentist_id', $dentist->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return back()->withInput()->withErrors(['start_date' => 'The selected date is not a working day in your schedule.']);
        }

        if ($isPartial) {
            $workStart = (int) substr($schedule->start_time, 0, 2);
            $workEnd   = (int) substr($schedule->end_time, 0, 2);
            $fromHour  = (int) substr($validated['start_time'], 0, 2);
            $toHour    = (int) substr($validated['end_time'], 0, 2);

            if ($fromHour < $workStart || $toHour > $workEnd) {
                return back()->withInput()->withErrors(['start_time' => 'Selected hours must be within your working hours (' . \Carbon\Carbon::parse($schedule->start_time)->format('g A') . ' – ' . \Carbon\Carbon::parse($schedule->end_time)->format('g A') . ').']);
            }
        }

        VacationRequest::create([
            'dentist_id' => $dentist->id,
            'type'       => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date'   => $isPartial ? $validated['start_date'] : $validated['end_date'],
            'start_time' => $isPartial ? $validated['start_time'] : null,
            'end_time'   => $isPartial ? $validated['end_time'] : null,
            'reason'     => $validated['reason'],
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Your time-off request has been submitted to the administration.');
    }

    public function cancelVacation(Request $request, $id)
    {
        $dentist = $this->dentist($request);

        $vacationRequest = VacationRequest::where('dentist_id', $dentist->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $vacationRequest->delete();

        return back()->with('success', 'Vacation request cancelled.');
    }
}

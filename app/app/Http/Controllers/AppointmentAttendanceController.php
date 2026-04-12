<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentAttendanceController extends Controller
{
    /**
     * Doctor marks an appointment as attended or no-show.
     * POST /doctor/appointments/{id}/mark-attendance
     */
    public function markAttendance(Request $request, $id)
    {
        $request->validate([
            'attended' => 'required|boolean',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($request->attended) {
            // Mark as attended
            $appointment->update([
                'attended' => true,
                'status'   => 'completed',
            ]);
        } else {
            // Mark as no-show
            $appointment->update([
                'attended' => false,
                'status'   => 'no_show',
            ]);

            // Check if the patient should be blocked from booking
            $this->checkBookingBlock($appointment->patient_id);
        }

        return back()->with('success', $request->attended
            ? 'Appointment marked as attended.'
            : 'Appointment marked as no-show.');
    }

    /**
     * Check if the patient should be auto-blocked from booking.
     *
     * Condition A: 3 consecutive no-shows
     * Condition B: After 6+ total resolved appointments, absence rate >= 1/3
     */
    private function checkBookingBlock(int $patientId): void
    {
        $patient = Patient::find($patientId);
        if (! $patient || $patient->booking_blocked) {
            return; // Already blocked or patient not found
        }

        // Condition A: last 3 appointments are all no-shows
        $lastThree = Appointment::where('patient_id', $patientId)
            ->whereIn('status', ['completed', 'no_show'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->limit(3)
            ->pluck('status');

        if ($lastThree->count() === 3 && $lastThree->every(fn ($s) => $s === 'no_show')) {
            $patient->update(['booking_blocked' => true]);
            return;
        }

        // Condition B: 6+ resolved appointments with >= 1/3 no-show rate
        $resolved = Appointment::where('patient_id', $patientId)
            ->whereIn('status', ['completed', 'no_show'])
            ->get();

        if ($resolved->count() >= 6) {
            $noShowCount = $resolved->where('status', 'no_show')->count();
            if ($noShowCount / $resolved->count() >= 1 / 3) {
                $patient->update(['booking_blocked' => true]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentAttendanceController extends Controller
{
    /**
     * Doctor marks an appointment outcome.
     * POST /doctor/appointments/{id}/mark-attendance
     * outcome: successful | absent | failed
     */
    public function markAttendance(Request $request, $id)
    {
        $request->validate([
            'outcome' => 'required|in:successful,absent,failed',
        ]);

        $appointment = Appointment::findOrFail($id);

        switch ($request->outcome) {
            case 'successful':
                $appointment->update(['attended' => true,  'status' => 'completed']);
                $message = 'Appointment marked as successful.';
                break;

            case 'absent':
                $appointment->update(['attended' => false, 'status' => 'no_show']);
                $this->checkBookingBlock($appointment->patient_id);
                $message = 'Appointment marked as absent (no-show).';
                break;

            case 'failed':
                $appointment->update(['attended' => true,  'status' => 'failed']);
                $message = 'Appointment marked as failed.';
                break;
        }

        return back()->with('success', $message);
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

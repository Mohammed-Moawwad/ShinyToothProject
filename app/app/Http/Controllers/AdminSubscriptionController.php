<?php

namespace App\Http\Controllers;

use App\Models\DoctorSubscription;
use App\Models\Patient;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    /**
     * List all subscriptions for admin oversight.
     * GET /admin/subscriptions
     * (Stub — view will be built with admin dashboard)
     */
    public function index()
    {
        $subscriptions = DoctorSubscription::with([
            'patient', 'dentist', 'switchToDentist', 'plan', 'bonus',
        ])->latest()->get();

        // Return JSON for now; view comes with admin dashboard
        return response()->json($subscriptions);
    }

    /**
     * Admin approves a pending action (switch, cancel, or removal).
     * POST /admin/subscriptions/{id}/approve-action
     */
    public function approveAction($id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        switch ($subscription->admin_action_status) {
            case 'pending_cancel':
                $subscription->update([
                    'status'              => 'cancelled',
                    'admin_action_status' => 'none',
                ]);
                break;

            case 'pending_switch':
                // Mark current subscription as switched
                $subscription->update([
                    'status'              => 'switched',
                    'admin_action_status' => 'none',
                ]);

                // Create a new pending subscription for the target doctor
                DoctorSubscription::create([
                    'patient_id'   => $subscription->patient_id,
                    'dentist_id'   => $subscription->switch_to_dentist_id,
                    'status'       => 'pending',
                    'requested_at' => now(),
                ]);
                break;

            case 'pending_removal':
                $subscription->update([
                    'status'               => 'removed',
                    'admin_action_status'  => 'none',
                    'admin_removal_reason' => $subscription->doctor_removal_reason
                        ? 'Doctor requested: ' . $subscription->doctor_removal_reason
                        : $subscription->admin_removal_reason,
                ]);
                break;

            default:
                return back()->with('error', 'No pending admin action for this subscription.');
        }

        return back()->with('success', 'Action approved.');
    }

    /**
     * Admin rejects a pending action (keeps subscription in its current state).
     * POST /admin/subscriptions/{id}/reject-action
     */
    public function rejectAction($id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->admin_action_status === 'none') {
            return back()->with('error', 'No pending admin action to reject.');
        }

        // Clear the pending action — subscription stays as-is
        $subscription->update([
            'admin_action_status' => 'none',
        ]);

        return back()->with('success', 'Action rejected. Subscription remains unchanged.');
    }

    /**
     * Admin forcefully removes a patient from a subscription.
     * POST /admin/subscriptions/{id}/remove
     */
    public function remove(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if (in_array($subscription->status, ['removed', 'cancelled', 'rejected'])) {
            return back()->with('error', 'This subscription is already terminated.');
        }

        $subscription->update([
            'status'               => 'removed',
            'admin_action_status'  => 'none',
            'admin_removal_reason' => $request->reason,
        ]);

        return back()->with('success', 'Patient removed from subscription.');
    }

    /**
     * Admin unblocks a patient from booking.
     * POST /admin/patients/{patientId}/unblock
     */
    public function unblockPatient($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        if (! $patient->booking_blocked) {
            return back()->with('error', 'This patient is not blocked.');
        }

        $patient->update(['booking_blocked' => false]);

        return back()->with('success', 'Patient unblocked. They can now book appointments.');
    }
}

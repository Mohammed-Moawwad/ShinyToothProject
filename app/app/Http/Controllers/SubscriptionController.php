<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\DoctorSubscription;
use App\Models\Patient;
use App\Models\SubscriptionRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Patient sends a subscription request to a dentist.
     * POST /subscriptions/request
     */
    public function sendRequest(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        // Rule: patient must have attended at least one appointment
        $hasAttended = Appointment::where('patient_id', $patient->id)
            ->where('attended', true)
            ->exists();

        if (! $hasAttended) {
            return back()->with('error', 'You must attend at least one appointment before subscribing to a doctor.');
        }

        // Rule: only one active or pending subscription per patient
        $existing = DoctorSubscription::where('patient_id', $patient->id)
            ->whereIn('status', ['active', 'pending'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have an active or pending subscription.');
        }

        DoctorSubscription::create([
            'patient_id'   => $patient->id,
            'dentist_id'   => $request->dentist_id,
            'status'       => 'pending',
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Subscription request sent! The doctor will review your request.');
    }

    /**
     * Patient views their current subscription.
     * GET /my-subscription
     */
    public function mySubscription(Request $request)
    {
        $patient = Auth::user();
        $patientId = $patient->id;

        $subscription = DoctorSubscription::with([
            'dentist.specializations',
            'plan.items.service',
            'plan.items.assignedDentist',
            'rating',
        ])
            ->where('patient_id', $patientId)
            ->whereIn('status', ['active', 'pending', 'idle', 'completed'])
            ->latest()
            ->first();

        $dentists = Dentist::orderBy('name')->get(['id', 'name']);

        return view('patient.subscription', compact('subscription', 'patientId', 'patient', 'dentists'));
    }

    /**
     * Patient requests to cancel their subscription (needs admin approval).
     * POST /subscriptions/{id}/cancel-request
     */
    public function requestCancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if (! in_array($subscription->status, ['active', 'idle'])) {
            return back()->with('error', 'Only active or paused subscriptions can be cancelled.');
        }

        $subscription->update([
            'admin_action_status'     => 'pending_cancel',
            'patient_cancel_reason'   => $request->reason,
        ]);

        return back()->with('success', 'Cancellation request submitted. Awaiting admin review.');
    }

    /**
     * Patient requests to switch to a different dentist (needs admin approval).
     * POST /subscriptions/{id}/switch-request
     */
    public function requestSwitch(Request $request, $id)
    {
        $request->validate([
            'switch_to_dentist_id' => 'required|exists:dentists,id',
            'reason'               => 'required|string|max:1000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be switched.');
        }

        if ($request->switch_to_dentist_id == $subscription->dentist_id) {
            return back()->with('error', 'You are already subscribed to this doctor.');
        }

        $subscription->update([
            'admin_action_status'      => 'pending_switch',
            'switch_to_dentist_id'     => $request->switch_to_dentist_id,
            'patient_switch_reason'    => $request->reason,
        ]);

        return back()->with('success', 'Switch request submitted. Awaiting admin review.');
    }

    /**
     * Patient rates the completed plan.
     * POST /subscriptions/{id}/rate
     */
    public function ratePlan(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'nullable|string|max:2000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        // Patient can rate when all plan items are completed (status is still 'active' until doctor marks complete)
        if (! in_array($subscription->status, ['active', 'completed'])) {
            return back()->with('error', 'You can only rate an active or completed subscription.');
        }

        // Prevent duplicate ratings
        if ($subscription->rating) {
            return back()->with('error', 'You have already rated this plan.');
        }

        SubscriptionRating::create([
            'subscription_id' => $subscription->id,
            'patient_id'      => $request->patient_id,
            'rating'          => $request->rating,
            'review'          => $request->review,
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }
}

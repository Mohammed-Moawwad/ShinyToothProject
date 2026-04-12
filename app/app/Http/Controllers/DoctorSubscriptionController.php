<?php

namespace App\Http\Controllers;

use App\Models\DoctorSubscription;
use App\Models\SubscriptionBonus;
use Illuminate\Http\Request;

class DoctorSubscriptionController extends Controller
{
    /**
     * List all subscription requests/active subscriptions for a dentist.
     * GET /doctor/subscriptions
     * (Stub — view will be built with doctor dashboard)
     */
    public function index(Request $request)
    {
        $dentistId = $request->query('dentist');

        $subscriptions = DoctorSubscription::with(['patient', 'plan.items.service'])
            ->where('dentist_id', $dentistId)
            ->latest()
            ->get();

        // Return JSON for now; view comes with doctor dashboard
        return response()->json($subscriptions);
    }

    /**
     * Doctor accepts a pending subscription request.
     * POST /doctor/subscriptions/{id}/accept
     */
    public function accept($id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be accepted.');
        }

        $subscription->update([
            'status'      => 'active',
            'accepted_at' => now(),
        ]);

        return back()->with('success', 'Subscription request accepted.');
    }

    /**
     * Doctor rejects a pending subscription request with a reason.
     * POST /doctor/subscriptions/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $subscription->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Subscription request rejected.');
    }

    /**
     * Doctor marks a subscription as idle (paused).
     * POST /doctor/subscriptions/{id}/idle
     */
    public function markIdle($id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be set to idle.');
        }

        $subscription->update(['status' => 'idle']);

        return back()->with('success', 'Subscription set to idle.');
    }

    /**
     * Doctor reactivates an idle subscription back to active.
     * POST /doctor/subscriptions/{id}/active
     */
    public function reactivate($id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        if ($subscription->status !== 'idle') {
            return back()->with('error', 'Only idle subscriptions can be reactivated.');
        }

        $subscription->update(['status' => 'active']);

        return back()->with('success', 'Subscription reactivated.');
    }

    /**
     * Doctor marks a subscription plan as completed.
     * This triggers the bonus auto-calculation if rating >= 4.
     * POST /doctor/subscriptions/{id}/complete
     */
    public function markCompleted($id)
    {
        $subscription = DoctorSubscription::with(['plan', 'rating'])->findOrFail($id);

        if ($subscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be marked as completed.');
        }

        $subscription->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        // Auto-calculate bonus if rating exists and is >= 4
        $this->calculateBonus($subscription);

        return back()->with('success', 'Subscription marked as completed.');
    }

    /**
     * Doctor requests admin to remove a patient from the subscription.
     * POST /doctor/subscriptions/{id}/request-removal
     */
    public function requestRemoval(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if (! in_array($subscription->status, ['active', 'idle'])) {
            return back()->with('error', 'Only active or idle subscriptions can be flagged for removal.');
        }

        $subscription->update([
            'admin_action_status'   => 'pending_removal',
            'doctor_removal_reason' => $request->reason,
        ]);

        return back()->with('success', 'Removal request sent to administration.');
    }

    /**
     * Calculate and store the bonus if eligible.
     */
    private function calculateBonus(DoctorSubscription $subscription): void
    {
        // Must have a rating of 4 or above
        if (! $subscription->rating || $subscription->rating->rating < 4) {
            return;
        }

        // Must have a plan with items
        if (! $subscription->plan) {
            return;
        }

        // Prevent duplicate bonuses
        if ($subscription->bonus) {
            return;
        }

        $planTotal = $subscription->plan->total_price;
        $bonusAmount = round($planTotal * 0.05, 2);

        SubscriptionBonus::create([
            'subscription_id' => $subscription->id,
            'dentist_id'      => $subscription->dentist_id,
            'plan_total'      => $planTotal,
            'bonus_amount'    => $bonusAmount,
            'rating'          => $subscription->rating->rating,
            'is_paid'         => true, // auto-paid
        ]);
    }
}

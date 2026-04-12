<?php

namespace App\Http\Controllers;

use App\Models\DoctorSubscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanItem;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Create or update the treatment plan for a subscription.
     * POST /doctor/subscriptions/{id}/plan
     */
    public function createOrUpdate(Request $request, $id)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255',
            'notes'      => 'nullable|string|max:5000',
            'dentist_id' => 'required|exists:dentists,id',
        ]);

        $subscription = DoctorSubscription::findOrFail($id);

        if (! in_array($subscription->status, ['active', 'idle'])) {
            return back()->with('error', 'Plan can only be managed for active or idle subscriptions.');
        }

        $plan = SubscriptionPlan::updateOrCreate(
            ['subscription_id' => $subscription->id],
            [
                'title'                => $request->title,
                'notes'                => $request->notes,
                'created_by_dentist_id' => $request->dentist_id,
            ]
        );

        return back()->with('success', 'Plan saved.');
    }

    /**
     * Add a service item to the plan.
     * POST /doctor/subscriptions/{id}/plan/items
     */
    public function addItem(Request $request, $id)
    {
        $request->validate([
            'service_id'         => 'required|exists:services,id',
            'assigned_dentist_id' => 'nullable|exists:dentists,id',
            'notes'              => 'nullable|string|max:1000',
        ]);

        $subscription = DoctorSubscription::with('plan')->findOrFail($id);

        if (! $subscription->plan) {
            return back()->with('error', 'Create a plan first before adding items.');
        }

        $plan = $subscription->plan;

        // Determine the next order index
        $maxOrder = $plan->items()->max('order_index') ?? -1;

        SubscriptionPlanItem::create([
            'plan_id'             => $plan->id,
            'service_id'          => $request->service_id,
            'assigned_dentist_id' => $request->assigned_dentist_id,
            'order_index'         => $maxOrder + 1,
            'notes'               => $request->notes,
        ]);

        // Recalculate total
        $plan->recalculateTotal();

        return back()->with('success', 'Service added to plan.');
    }

    /**
     * Remove a service item from the plan.
     * DELETE /doctor/plans/{planId}/items/{itemId}
     */
    public function removeItem($planId, $itemId)
    {
        $item = SubscriptionPlanItem::where('plan_id', $planId)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($item->status === 'completed') {
            return back()->with('error', 'Cannot remove a completed service item.');
        }

        $plan = $item->plan;
        $item->delete();

        // Re-index remaining items
        $plan->items()->orderBy('order_index')->get()
            ->each(function ($item, $index) {
                $item->update(['order_index' => $index]);
            });

        $plan->recalculateTotal();

        return back()->with('success', 'Service removed from plan.');
    }

    /**
     * Reorder items in the plan.
     * PATCH /doctor/plans/{planId}/items/reorder
     * Expects: { "order": [itemId1, itemId2, itemId3, ...] }
     */
    public function reorder(Request $request, $planId)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:subscription_plan_items,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($planId);

        foreach ($request->order as $index => $itemId) {
            SubscriptionPlanItem::where('id', $itemId)
                ->where('plan_id', $plan->id)
                ->update(['order_index' => $index]);
        }

        return back()->with('success', 'Plan items reordered.');
    }

    /**
     * Mark a specific plan item as completed.
     * PATCH /doctor/plans/{planId}/items/{itemId}/complete
     */
    public function completeItem($planId, $itemId)
    {
        $item = SubscriptionPlanItem::where('plan_id', $planId)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($item->status === 'completed') {
            return back()->with('error', 'This service item is already completed.');
        }

        $item->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Service item marked as completed.');
    }
}

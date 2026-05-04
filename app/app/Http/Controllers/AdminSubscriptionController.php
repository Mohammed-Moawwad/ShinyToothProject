<?php

namespace App\Http\Controllers;

use App\Models\DoctorSubscription;
use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSubscriptionController extends Controller
{
    /**
     * List all subscriptions for admin oversight.
     * GET /admin/subscriptions
     * (Stub — view will be built with admin dashboard)
     */
    public function index(Request $request)
    {
        $statusFilter  = $request->input('status', '');
        $dentistFilter = $request->input('dentist_id', '');

        $query = DoctorSubscription::with([
            'patient', 'dentist', 'switchToDentist', 'plan.items', 'bonus', 'rating',
        ]);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($dentistFilter) {
            $query->where('dentist_id', $dentistFilter);
        }

        $subscriptions = $query->latest()->get();

        // ── Status breakdown (always from full table, unaffected by filters) ──
        $statusCounts = DoctorSubscription::selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->get()->keyBy('status');

        $totalCount     = DoctorSubscription::count();
        $activeCount    = $statusCounts['active']->cnt    ?? 0;
        $pendingCount   = $statusCounts['pending']->cnt   ?? 0;
        $completedCount = $statusCounts['completed']->cnt ?? 0;
        $cancelledCount = ($statusCounts['cancelled']->cnt ?? 0)
                        + ($statusCounts['removed']->cnt  ?? 0)
                        + ($statusCounts['rejected']->cnt ?? 0);
        $pendingActions = DoctorSubscription::where('admin_action_status', '!=', 'none')->count();

        // ── Financial stats ──────────────────────────────────────────────────
        $totalRevenue = DB::table('subscription_plans')
            ->join('doctor_subscriptions', 'doctor_subscriptions.id', '=', 'subscription_plans.subscription_id')
            ->whereIn('doctor_subscriptions.status', ['active', 'completed'])
            ->sum('subscription_plans.total_price');

        $totalBonuses = DB::table('subscription_bonuses')->sum('bonus_amount');

        // ── Ratings ──────────────────────────────────────────────────────────
        $avgRating  = round(DB::table('subscription_ratings')->avg('rating') ?? 0, 1);
        $ratingDist = DB::table('subscription_ratings')
            ->select('rating', DB::raw('COUNT(*) as cnt'))
            ->groupBy('rating')
            ->get()->keyBy('rating');

        // ── Top dentists by subscription count ───────────────────────────────
        $topDentists = DB::table('doctor_subscriptions')
            ->join('dentists', 'dentists.id', '=', 'doctor_subscriptions.dentist_id')
            ->select('dentists.name', DB::raw('COUNT(doctor_subscriptions.id) as sub_count'))
            ->groupBy('dentists.id', 'dentists.name')
            ->orderByDesc('sub_count')
            ->limit(5)
            ->get();

        // ── All dentists for filter dropdown ─────────────────────────────────
        $dentists = Dentist::orderBy('name')->get(['id', 'name']);

        return view('admin.subscriptions', compact(
            'subscriptions', 'statusFilter', 'dentistFilter', 'dentists',
            'totalCount', 'activeCount', 'pendingCount', 'completedCount',
            'cancelledCount', 'pendingActions',
            'totalRevenue', 'totalBonuses', 'avgRating', 'ratingDist',
            'topDentists'
        ));
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
                        : 'Approved by admin.',
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

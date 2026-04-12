<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\DentistSchedule;
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
        return Dentist::findOrFail($request->query('dentist', 1));
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

        // This week's appointments
        $weeklyAppointments = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // Upcoming (future) appointments
        $upcomingAppointments = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->where('appointment_date', '>', $today)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(20)
            ->get();

        // Past appointments needing attendance marking
        $unmarkedAppointments = Appointment::with(['patient', 'service'])
            ->where('dentist_id', $dentist->id)
            ->where('appointment_date', '<=', $today)
            ->where('status', 'scheduled')
            ->whereNull('attended')
            ->orderByDesc('appointment_date')
            ->get();

        return view('doctor.appointments', compact(
            'dentist', 'weeklyAppointments', 'upcomingAppointments', 'unmarkedAppointments', 'today', 'weekStart', 'weekEnd'
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

        $subscriptions = $query->latest()->get();

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
            'cancelledAppointments', 'totalSubscriptions', 'activeSubscriptions',
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

    /* ─── SCHEDULE ───────────────────────────────────────────── */
    public function schedule(Request $request)
    {
        $dentist   = $this->dentist($request);
        $schedules = DentistSchedule::where('dentist_id', $dentist->id)
            ->orderByRaw("FIELD(day_of_week, 'sunday','monday','tuesday','wednesday','thursday','friday','saturday')")
            ->get()
            ->keyBy('day_of_week');

        return view('doctor.schedule', compact('dentist', 'schedules'));
    }

    public function updateSchedule(Request $request)
    {
        $dentist = $this->dentist($request);

        $validated = $request->validate([
            'days'              => 'required|array',
            'days.*'            => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time'        => 'required|array',
            'start_time.*'      => 'date_format:H:i',
            'end_time'          => 'required|array',
            'end_time.*'        => 'date_format:H:i',
            'is_available'      => 'nullable|array',
            'is_available.*'    => 'in:0,1',
        ]);

        foreach ($validated['days'] as $i => $day) {
            DentistSchedule::updateOrCreate(
                ['dentist_id' => $dentist->id, 'day_of_week' => $day],
                [
                    'start_time'   => $validated['start_time'][$i],
                    'end_time'     => $validated['end_time'][$i],
                    'is_available' => $validated['is_available'][$i] ?? 0,
                ]
            );
        }

        return back()->with('success', 'Schedule updated successfully.');
    }
}

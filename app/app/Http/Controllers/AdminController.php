<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with overview
     */
    public function index()
    {
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@shinytooth.com');

            // Get statistics
            $totalPatients     = Patient::where('email', '!=', $adminEmail)->count();
            $totalDentists     = Dentist::count();
            $totalUsers        = $totalPatients + $totalDentists;
            $totalAppointments = Appointment::count();

            // Get financial stats
            $totalRevenue = Payment::where('status', 'completed')->sum('amount') ?? 0;
            $pendingPayments = Payment::where('status', 'pending')->sum('amount') ?? 0;
            $completedAppointments = Appointment::where('status', 'completed')->count();
            $cancelledAppointments = Appointment::where('status', 'cancelled')->count();

            // Get recent appointments (with select to reduce data)
            $recentAppointments = Appointment::with(['patient:id,name,email', 'dentist:id,name', 'service:id,name'])
                ->select('id', 'patient_id', 'dentist_id', 'service_id', 'appointment_date', 'appointment_time', 'status')
                ->latest()
                ->limit(5)
                ->get();

            // Get recent payments (with select to reduce data)
            $recentPayments = Payment::with(['patient:id,name', 'appointment:id,appointment_date,service_id'])
                ->select('id', 'patient_id', 'appointment_id', 'amount', 'payment_date', 'status')
                ->latest()
                ->limit(5)
                ->get();

            return view('admin.dashboard', [
                'totalPatients' => $totalPatients,
                'totalDentists' => $totalDentists,
                'totalAppointments' => $totalAppointments,
                'totalUsers' => $totalUsers,
                'totalRevenue' => $totalRevenue,
                'pendingPayments' => $pendingPayments,
                'completedAppointments' => $completedAppointments,
                'cancelledAppointments' => $cancelledAppointments,
                'recentAppointments' => $recentAppointments,
                'recentPayments' => $recentPayments,
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard', [
                'totalPatients' => 0,
                'totalDentists' => 0,
                'totalAppointments' => 0,
                'totalUsers' => 0,
                'totalRevenue' => 0,
                'pendingPayments' => 0,
                'completedAppointments' => 0,
                'cancelledAppointments' => 0,
                'recentAppointments' => collect(),
                'recentPayments' => collect(),
            ]);
        }
    }

    /**
     * Show all users
     */
    public function users(Request $request)
    {
        $search       = $request->input('search', '');
        $type         = $request->input('type', 'all');
        $statusFilter = $request->input('status', 'all');
        $genderFilter = $request->input('gender', 'all');

        $adminEmail = env('ADMIN_EMAIL', 'admin@shinytooth.com');

        $patientCount = Patient::where('email', '!=', $adminEmail)->count();
        $dentistCount = Dentist::count();

        // ── Per-tab stats ──────────────────────────────────────────────
        $stats = [];
        if ($type === 'dentists' || $type === 'all') {
            $stats['doctors'] = [
                'total'           => $dentistCount,
                'active'          => Dentist::where('status', 'active')->count(),
                'inactive'        => Dentist::where('status', 'inactive')->count(),
                'on_leave'        => Dentist::where('status', 'on_leave')->count(),
                'avg_experience'  => round(Dentist::avg('experience_years') ?? 0, 1),
                'avg_salary'      => round(Dentist::avg('salary') ?? 0, 0),
                'specializations' => Specialization::withCount('dentists')
                                        ->having('dentists_count', '>', 0)
                                        ->orderByDesc('dentists_count')
                                        ->get(['name', 'dentists_count']),
                'nationalities'   => Dentist::select('nationality', DB::raw('count(*) as total'))
                                        ->whereNotNull('nationality')
                                        ->groupBy('nationality')
                                        ->orderByDesc('total')
                                        ->limit(5)
                                        ->get(),
                'top_rated'       => Dentist::select('dentists.id', 'dentists.name', 'dentists.image',
                                            DB::raw('ROUND(AVG(doctor_ratings.rating),1) as avg_rating'),
                                            DB::raw('COUNT(doctor_ratings.id) as rating_count'))
                                        ->join('doctor_ratings', 'dentists.id', '=', 'doctor_ratings.dentist_id')
                                        ->groupBy('dentists.id', 'dentists.name', 'dentists.image')
                                        ->orderByDesc('avg_rating')
                                        ->orderByDesc('rating_count')
                                        ->limit(3)
                                        ->get(),
                'highest_salary'  => Dentist::orderByDesc('salary')->limit(3)->get(['id','name','image','salary','status']),
                'lowest_salary'   => Dentist::orderBy('salary')->limit(3)->get(['id','name','image','salary','status']),
            ];
        }
        if ($type === 'patients' || $type === 'all') {
            $pBase = Patient::where('email', '!=', $adminEmail);
            $stats['patients'] = [
                'total'         => $patientCount,
                'male'          => (clone $pBase)->where('gender', 'male')->count(),
                'female'        => (clone $pBase)->where('gender', 'female')->count(),
                'blocked'       => (clone $pBase)->where('booking_blocked', true)->count(),
                'with_appts'    => (clone $pBase)->whereHas('appointments')->count(),
                'blood_types'   => (clone $pBase)
                                        ->select('blood_type', DB::raw('count(*) as total'))
                                        ->whereNotNull('blood_type')
                                        ->groupBy('blood_type')
                                        ->orderByDesc('total')
                                        ->get(),
                'nationalities' => (clone $pBase)
                                        ->select('nationality', DB::raw('count(*) as total'))
                                        ->whereNotNull('nationality')
                                        ->groupBy('nationality')
                                        ->orderByDesc('total')
                                        ->limit(5)
                                        ->get(),
                'most_booking'  => Patient::where('patients.email', '!=', $adminEmail)
                                        ->select('patients.id', 'patients.name', 'patients.email',
                                            DB::raw('COUNT(appointments.id) as appt_count'))
                                        ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                                        ->groupBy('patients.id', 'patients.name', 'patients.email')
                                        ->orderByDesc('appt_count')
                                        ->limit(3)
                                        ->get(),
            ];
        }

        $dq = Dentist::withCount('appointments');
        $pq = Patient::withCount('appointments')->where('email', '!=', $adminEmail);

        if ($search) {
            $dq->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
            $pq->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($type === 'dentists') {
            if ($statusFilter !== 'all') {
                $dq->where('status', $statusFilter);
            }
            $doctors  = $dq->latest()->paginate(15);
            $patients = collect();
        } elseif ($type === 'patients') {
            if ($genderFilter !== 'all') {
                $pq->where('gender', $genderFilter);
            }
            $patients = $pq->latest()->paginate(15);
            $doctors  = collect();
        } else {
            $doctors  = $dq->latest()->paginate(8, ['*'], 'doc');
            $patients = $pq->latest()->paginate(8, ['*'], 'pat');
        }

        return view('admin.users', [
            'doctors'      => $doctors,
            'patients'     => $patients,
            'type'         => $type,
            'search'       => $search,
            'patientCount' => $patientCount,
            'dentistCount' => $dentistCount,
            'statusFilter' => $statusFilter,
            'genderFilter' => $genderFilter,
            'stats'        => $stats,
        ]);
    }

    /**
     * Show all appointments
     */
    public function appointments(Request $request)
    {
        $search  = $request->input('search', '');
        $status  = $request->input('status', 'all');
        $sort    = $request->input('sort', 'latest');
        $dentist = $request->input('dentist', '');
        $date    = $request->input('date', '');

        $query = Appointment::with(['patient', 'dentist', 'service', 'payment']);

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($dentist) {
            $query->whereHas('dentist', function ($q) use ($dentist) {
                $q->where('name', 'like', "%{$dentist}%");
            });
        }

        if ($date) {
            $query->whereDate('appointment_date', $date);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($sort === 'latest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        }

        $appointments = $query->paginate(15);

        $total = Appointment::count();
        $statuses = [
            'pending'   => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        $apptStats = [
            'total'             => $total,
            'today'             => Appointment::whereDate('appointment_date', today())->count(),
            'this_month'        => Appointment::whereMonth('appointment_date', now()->month)
                                        ->whereYear('appointment_date', now()->year)->count(),
            'completion_rate'   => $total > 0 ? round(($statuses['completed'] / $total) * 100) : 0,
            'cancellation_rate' => $total > 0 ? round(($statuses['cancelled'] / $total) * 100) : 0,
            'top_services'      => \App\Models\Service::select('services.id', 'services.name',
                                        DB::raw('COUNT(appointments.id) as appt_count'))
                                        ->join('appointments', 'services.id', '=', 'appointments.service_id')
                                        ->groupBy('services.id', 'services.name')
                                        ->orderByDesc('appt_count')
                                        ->limit(5)
                                        ->get(),
            'top_dentists'      => Dentist::select('dentists.id', 'dentists.name', 'dentists.image',
                                        DB::raw('COUNT(appointments.id) as appt_count'),
                                        DB::raw('SUM(CASE WHEN appointments.status = "completed" THEN 1 ELSE 0 END) as completed_count'))
                                        ->join('appointments', 'dentists.id', '=', 'appointments.dentist_id')
                                        ->groupBy('dentists.id', 'dentists.name', 'dentists.image')
                                        ->orderByDesc('appt_count')
                                        ->limit(3)
                                        ->get(),
            'busiest_days'      => Appointment::select(
                                        DB::raw('DAYNAME(appointment_date) as day_name'),
                                        DB::raw('COUNT(*) as total'))
                                        ->groupBy('day_name')
                                        ->orderByDesc('total')
                                        ->get(),
            'monthly_trend'     => Appointment::select(
                                        DB::raw('DATE_FORMAT(appointment_date, "%b %Y") as month_label'),
                                        DB::raw('DATE_FORMAT(appointment_date, "%Y-%m") as month_sort'),
                                        DB::raw('COUNT(*) as total'))
                                        ->where('appointment_date', '>=', now()->subMonths(5)->startOfMonth())
                                        ->groupBy('month_label', 'month_sort')
                                        ->orderBy('month_sort')
                                        ->get(),
        ];

        // ── Monthly Breakdown (all months since system start) ────────────────
        $monthlyRaw = Appointment::select(
                DB::raw('DATE_FORMAT(appointment_date, "%Y-%m") as month_sort'),
                DB::raw('DATE_FORMAT(appointment_date, "%b %Y") as month_label'),
                'status',
                DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('month_sort', 'month_label', 'status')
            ->orderBy('month_sort', 'desc')
            ->get();

        $monthlyData = [];
        foreach ($monthlyRaw->groupBy('month_sort') as $ms => $rows) {
            $sc    = $rows->pluck('cnt', 'status')->toArray();
            $mtot  = array_sum($sc);
            $monthlyData[$ms] = (object)[
                'month_sort'        => $ms,
                'month_label'       => $rows->first()->month_label,
                'total'             => $mtot,
                'pending'           => $sc['pending']   ?? 0,
                'confirmed'         => $sc['confirmed'] ?? 0,
                'completed'         => $sc['completed'] ?? 0,
                'cancelled'         => $sc['cancelled'] ?? 0,
                'no_show'           => $sc['no_show']   ?? 0,
                'completion_rate'   => $mtot > 0 ? round((($sc['completed'] ?? 0) / $mtot) * 100) : 0,
                'cancellation_rate' => $mtot > 0 ? round((($sc['cancelled'] ?? 0) / $mtot) * 100) : 0,
            ];
        }

        // Top 3 services per month
        $mSvcRows = DB::table('appointments')
            ->join('services', 'services.id', '=', 'appointments.service_id')
            ->select(
                DB::raw('DATE_FORMAT(appointments.appointment_date, "%Y-%m") as ms'),
                'services.name',
                DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('ms', 'services.name')
            ->orderBy('ms')->orderByDesc('cnt')
            ->get()->groupBy('ms');

        // Top 3 dentists per month
        $mDentRows = DB::table('appointments')
            ->join('dentists', 'dentists.id', '=', 'appointments.dentist_id')
            ->select(
                DB::raw('DATE_FORMAT(appointments.appointment_date, "%Y-%m") as ms'),
                'dentists.name',
                'dentists.image',
                DB::raw('COUNT(*) as appt_count'),
                DB::raw('SUM(CASE WHEN appointments.status = "completed" THEN 1 ELSE 0 END) as completed_count')
            )
            ->groupBy('ms', 'dentists.name', 'dentists.image')
            ->orderBy('ms')->orderByDesc('appt_count')
            ->get()->groupBy('ms');

        // Unique patients per month
        $mPatients = DB::table('appointments')
            ->select(
                DB::raw('DATE_FORMAT(appointment_date, "%Y-%m") as ms'),
                DB::raw('COUNT(DISTINCT patient_id) as up')
            )
            ->groupBy('ms')->get()->pluck('up', 'ms');

        // Payment stats per month (grouped by method + status for full breakdown)
        $mPayRows = DB::table('payments')
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as ms'),
                'payment_method',
                'status',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('ms', 'payment_method', 'status')
            ->get()->groupBy('ms');

        // Financial records keyed by "YYYY-MM" (from the financial management table)
        $mFinancial = DB::table('financial')
            ->select('month', 'year', 'total_revenue', 'total_costs', 'profit', 'notes')
            ->get()
            ->keyBy(fn($r) => sprintf('%04d-%02d', $r->year, $r->month));

        $monthlyBreakdown = collect(array_values($monthlyData))->map(
            function ($m) use ($mSvcRows, $mDentRows, $mPatients, $mPayRows, $mFinancial) {
                $m->top_services    = ($mSvcRows[$m->month_sort]  ?? collect())->take(3);
                $m->top_dentists    = ($mDentRows[$m->month_sort] ?? collect())->take(3);
                $m->unique_patients = $mPatients[$m->month_sort]  ?? 0;

                // --- Payments ---
                $pr = $mPayRows[$m->month_sort] ?? collect();
                $m->pay_revenue  = $pr->where('status', 'completed')->sum('total_amount');
                $m->pay_pending  = $pr->where('status', 'pending')->sum('total_amount');
                $m->pay_refunded = $pr->where('status', 'refunded')->sum('total_amount');
                $m->pay_count    = (int) $pr->where('status', 'completed')->sum('cnt');
                $m->pay_credit   = $m->pay_revenue; // only payment method in use

                // --- Financial record (admin-entered) ---
                $fin = $mFinancial[$m->month_sort] ?? null;
                $m->fin_revenue  = $fin ? (float)$fin->total_revenue : null;
                $m->fin_costs    = $fin ? (float)$fin->total_costs   : null;
                $m->fin_profit   = $fin ? (float)$fin->profit        : null;
                $m->fin_notes    = $fin ? $fin->notes                : null;

                return $m;
            }
        );
        // ─────────────────────────────────────────────────────────────────────

        return view('admin.appointments', [
            'appointments'     => $appointments,
            'status'           => $status,
            'search'           => $search,
            'sort'             => $sort,
            'dentist'          => $dentist,
            'date'             => $date,
            'statuses'         => $statuses,
            'apptStats'        => $apptStats,
            'monthlyBreakdown' => $monthlyBreakdown,
        ]);
    }

    /**
     * Show appointments by specific dentist
     */
    public function appointmentsByDentist($dentistId)
    {
        $dentist = Dentist::findOrFail($dentistId);
        $appointments = $dentist->appointments()->with(['patient', 'service'])->paginate(15);

        return view('admin.appointments-by-dentist', [
            'dentist' => $dentist,
            'appointments' => $appointments,
        ]);
    }

    /**
     * Show all payments and financial reports
     */
    public function payments(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');
        $dateFrom = $request->input('date_from', '');
        $dateTo = $request->input('date_to', '');

        $query = Payment::with(['patient', 'appointment']);

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('payment_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('payment_date', '<=', $dateTo);
        }

        $payments = $query->latest()->paginate(15);

        // ── Core stats ───────────────────────────────────────────────────────
        $totalRevenue   = Payment::where('status', 'completed')->sum('amount');
        $pendingAmount  = Payment::where('status', 'pending')->sum('amount');
        $refundedAmount = Payment::where('status', 'refunded')->sum('amount');
        $failedAmount   = $refundedAmount; // alias kept for view compat

        $statuses = [
            'completed' => Payment::where('status', 'completed')->count(),
            'pending'   => Payment::where('status', 'pending')->count(),
            'refunded'  => Payment::where('status', 'refunded')->count(),
        ];
        $totalCount = array_sum($statuses);

        // ── Extended stats ───────────────────────────────────────────────────
        $todayRevenue     = Payment::where('status','completed')->whereDate('payment_date', today())->sum('amount');
        $thisMonthRevenue = Payment::where('status','completed')
                                ->whereMonth('payment_date', now()->month)
                                ->whereYear('payment_date',  now()->year)->sum('amount');

        // Credit Card stats (only method in use)
        $creditStats = Payment::where('status','completed')->where('payment_method','credit_card')
            ->selectRaw('COUNT(*) as cnt, SUM(amount) as total, MAX(amount) as max_amount, MIN(amount) as min_amount')
            ->first();

        // Top 5 paying patients
        $topPatients = DB::table('payments')
            ->join('patients','patients.id','=','payments.patient_id')
            ->where('payments.status','completed')
            ->select('patients.id','patients.name',
                     DB::raw('SUM(payments.amount) as total_paid'),
                     DB::raw('COUNT(*) as pay_count'))
            ->groupBy('patients.id','patients.name')
            ->orderByDesc('total_paid')->limit(5)->get();

        // Top 5 services by revenue (via appointments)
        $topServices = DB::table('payments')
            ->join('appointments','appointments.id','=','payments.appointment_id')
            ->join('services','services.id','=','appointments.service_id')
            ->where('payments.status','completed')
            ->select('services.name',
                     DB::raw('SUM(payments.amount) as total_rev'),
                     DB::raw('COUNT(*) as pay_count'))
            ->groupBy('services.name')
            ->orderByDesc('total_rev')->limit(5)->get();

        // 6-month revenue trend
        $monthlyTrend = Payment::where('status','completed')
            ->where('payment_date','>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(payment_date,"%b %Y") as month_label'),
                     DB::raw('DATE_FORMAT(payment_date,"%Y-%m") as month_sort'),
                     DB::raw('SUM(amount) as total'),
                     DB::raw('COUNT(*) as cnt'))
            ->groupBy('month_label','month_sort')->orderBy('month_sort')->get();

        $payStats = [
            'total_revenue'       => $totalRevenue,
            'net_revenue'         => $totalRevenue - $refundedAmount,
            'pending_amount'      => $pendingAmount,
            'refunded_amount'     => $refundedAmount,
            'today_revenue'       => $todayRevenue,
            'this_month_revenue'  => $thisMonthRevenue,
            'total_count'         => $totalCount,
            'avg_amount'          => ($statuses['completed'] > 0) ? round($totalRevenue / $statuses['completed'], 2) : 0,
            'collection_rate'     => $totalCount > 0 ? round(($statuses['completed'] / $totalCount) * 100) : 0,
            'credit_stats'        => $creditStats,
            'top_patients'        => $topPatients,
            'top_services'        => $topServices,
            'monthly_trend'       => $monthlyTrend,
        ];

        // ── Monthly full breakdown (all months since start) ──────────────────
        $mPayRaw = Payment::select(
                DB::raw('DATE_FORMAT(payment_date,"%Y-%m") as month_sort'),
                DB::raw('DATE_FORMAT(payment_date,"%b %Y") as month_label'),
                'status', 'payment_method',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month_sort','month_label','status','payment_method')
            ->orderBy('month_sort','desc')->get();

        $mPayData = [];
        foreach ($mPayRaw->groupBy('month_sort') as $ms => $rows) {
            $byS = []; $byMeth = ['credit_card'=>0];
            foreach ($rows as $r) {
                $byS[$r->status]['cnt']   = ($byS[$r->status]['cnt']   ?? 0) + $r->cnt;
                $byS[$r->status]['total'] = ($byS[$r->status]['total'] ?? 0) + $r->total;
                if ($r->status === 'completed') {
                    $byMeth[$r->payment_method] = ($byMeth[$r->payment_method] ?? 0) + $r->total;
                }
            }
            $rev = $byS['completed']['total'] ?? 0;
            $ref = $byS['refunded']['total']  ?? 0;
            $mPayData[$ms] = (object)[
                'month_sort'      => $ms,
                'month_label'     => $rows->first()->month_label,
                'revenue'         => $rev,
                'count'           => $byS['completed']['cnt'] ?? 0,
                'pending_amount'  => $byS['pending']['total']  ?? 0,
                'pending_count'   => $byS['pending']['cnt']    ?? 0,
                'refunded_amount' => $ref,
                'refunded_count'  => $byS['refunded']['cnt']   ?? 0,
                'net'             => $rev - $ref,
                'credit_card'     => $byMeth['credit_card'],
            ];
        }

        // Top 3 patients per month
        $mTopPat = DB::table('payments')
            ->join('patients','patients.id','=','payments.patient_id')
            ->where('payments.status','completed')
            ->select(DB::raw('DATE_FORMAT(payments.payment_date,"%Y-%m") as ms'),
                     'patients.name',
                     DB::raw('SUM(payments.amount) as total_paid'),
                     DB::raw('COUNT(*) as pay_count'))
            ->groupBy('ms','patients.name')
            ->orderBy('ms')->orderByDesc('total_paid')
            ->get()->groupBy('ms');

        // Financial records from financial table
        $mFinancial = DB::table('financial')
            ->select('month','year','total_revenue','total_costs','profit','notes')
            ->get()->keyBy(fn($r) => sprintf('%04d-%02d', $r->year, $r->month));

        $monthlyPayBreakdown = collect(array_values($mPayData))->map(
            function ($m) use ($mTopPat, $mFinancial) {
                $m->top_patients = ($mTopPat[$m->month_sort] ?? collect())->take(3);
                $fin = $mFinancial[$m->month_sort] ?? null;
                $m->fin_revenue  = $fin ? (float)$fin->total_revenue : null;
                $m->fin_costs    = $fin ? (float)$fin->total_costs   : null;
                $m->fin_profit   = $fin ? (float)$fin->profit        : null;
                $m->fin_notes    = $fin ? $fin->notes                : null;
                return $m;
            }
        );
        // ─────────────────────────────────────────────────────────────────────

        return view('admin.payments', [
            'payments'             => $payments,
            'search'               => $search,
            'status'               => $status,
            'dateFrom'             => $dateFrom,
            'dateTo'               => $dateTo,
            'totalRevenue'         => $totalRevenue,
            'pendingAmount'        => $pendingAmount,
            'failedAmount'         => $failedAmount,
            'statuses'             => $statuses,
            'payStats'             => $payStats,
            'monthlyPayBreakdown'  => $monthlyPayBreakdown,
        ]);
    }

    /**
     * Show all services
     */
    public function services(Request $request)
    {
        $search   = $request->input('search', '');
        $category = $request->input('category', '');

        $query = Service::withCount('appointments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $services = $query->paginate(15);

        // Distinct categories for filter dropdown
        $categories = Service::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Revenue per service (completed payments via appointments)
        $serviceRevenue = DB::table('payments')
            ->join('appointments', 'appointments.id', '=', 'payments.appointment_id')
            ->where('payments.status', 'completed')
            ->select('appointments.service_id', DB::raw('SUM(payments.amount) as revenue'))
            ->groupBy('appointments.service_id')
            ->get()->keyBy('service_id');

        // Completed count per service
        $serviceCompleted = DB::table('appointments')
            ->where('status', 'completed')
            ->select('service_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('service_id')
            ->get()->keyBy('service_id');

        // Cancelled count per service
        $serviceCancelled = DB::table('appointments')
            ->where('status', 'cancelled')
            ->select('service_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('service_id')
            ->get()->keyBy('service_id');

        // Overall summary stats
        $totalServices = \App\Models\Service::count();
        $mostBookedSvc = Service::withCount('appointments')->orderByDesc('appointments_count')->first();

        return view('admin.services', [
            'services'         => $services,
            'search'           => $search,
            'category'         => $category,
            'categories'       => $categories,
            'serviceRevenue'   => $serviceRevenue,
            'serviceCompleted' => $serviceCompleted,
            'serviceCancelled' => $serviceCancelled,
            'totalServices'    => $totalServices,
            'mostBookedSvc'    => $mostBookedSvc,
        ]);
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'category'         => 'nullable|string|max:100',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services')->with('success', 'Service added successfully.');
    }

    /**
     * Show analytics and reports
     */
    public function analytics()
    {
        // ── Appointment stats ────────────────────────────────────────────
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->get()->keyBy('status');

        $totalAppointments  = Appointment::count();
        $completedAppts     = $appointmentsByStatus['completed']->count ?? 0;
        $cancelledAppts     = $appointmentsByStatus['cancelled']->count ?? 0;
        $pendingAppts       = $appointmentsByStatus['pending']->count   ?? 0;
        $completionRate     = $totalAppointments > 0 ? round($completedAppts / $totalAppointments * 100, 1) : 0;
        $cancellationRate   = $totalAppointments > 0 ? round($cancelledAppts / $totalAppointments * 100, 1) : 0;

        $appointmentsByMonth = Appointment::selectRaw('DATE_FORMAT(appointment_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')->orderBy('month', 'asc')->limit(12)->get();

        // Day-of-week distribution
        $apptByDow = Appointment::selectRaw('DAYNAME(appointment_date) as day_name, DAYOFWEEK(appointment_date) as dow_num, COUNT(*) as count')
            ->groupBy('day_name', 'dow_num')
            ->orderBy('dow_num')
            ->get();

        // ── Payment stats ────────────────────────────────────────────────
        $paymentsByStatus = Payment::selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')->get()->keyBy('status');

        $totalRevenue   = $paymentsByStatus['completed']->total ?? 0;
        $totalPending   = $paymentsByStatus['pending']->total   ?? 0;
        $totalRefunded  = $paymentsByStatus['refunded']->total  ?? 0;
        $avgTransaction = ($paymentsByStatus['completed']->count ?? 0) > 0
            ? round($totalRevenue / $paymentsByStatus['completed']->count, 2) : 0;

        $paymentsByMonth = Payment::selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
            ->where('status', 'completed')->whereNotNull('payment_date')
            ->groupBy('month')->orderBy('month', 'asc')->limit(12)->get();

        // ── User stats ───────────────────────────────────────────────────
        $totalPatients  = Patient::count();
        $totalDentists  = Dentist::count();

        $patientGrowth = Patient::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')->orderBy('month', 'asc')->limit(12)->get();

        $dentistGrowth = Dentist::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')->orderBy('month', 'asc')->limit(12)->get();

        // ── Service stats ────────────────────────────────────────────────
        $popularServices = Service::withCount('appointments')
            ->orderByDesc('appointments_count')->limit(10)->get();

        $serviceRevenue = DB::table('payments')
            ->join('appointments', 'appointments.id', '=', 'payments.appointment_id')
            ->join('services', 'services.id', '=', 'appointments.service_id')
            ->where('payments.status', 'completed')
            ->select('services.name', DB::raw('SUM(payments.amount) as revenue'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('revenue')->limit(8)->get();

        // ── Doctor stats ─────────────────────────────────────────────────
        $topDentistsByAppts = DB::table('appointments')
            ->join('dentists', 'dentists.id', '=', 'appointments.dentist_id')
            ->select('dentists.name', DB::raw('COUNT(appointments.id) as appt_count'))
            ->groupBy('dentists.id', 'dentists.name')
            ->orderByDesc('appt_count')->limit(5)->get();

        $topDentistsByRevenue = DB::table('payments')
            ->join('appointments', 'appointments.id', '=', 'payments.appointment_id')
            ->join('dentists', 'dentists.id', '=', 'appointments.dentist_id')
            ->where('payments.status', 'completed')
            ->select('dentists.name', DB::raw('SUM(payments.amount) as revenue'))
            ->groupBy('dentists.id', 'dentists.name')
            ->orderByDesc('revenue')->limit(5)->get();

        $avgDoctorRating = round(DB::table('doctor_ratings')->avg('rating') ?? 0, 1);
        $topRatedDoctors = DB::table('doctor_ratings')
            ->join('dentists', 'dentists.id', '=', 'doctor_ratings.dentist_id')
            ->select('dentists.name', DB::raw('ROUND(AVG(doctor_ratings.rating),1) as avg_rating'), DB::raw('COUNT(*) as total_reviews'))
            ->groupBy('dentists.id', 'dentists.name')
            ->having('total_reviews', '>=', 1)
            ->orderByDesc('avg_rating')->limit(5)->get();

        $ratingDistribution = DB::table('doctor_ratings')
            ->select('rating', DB::raw('COUNT(*) as cnt'))
            ->groupBy('rating')->orderBy('rating')->get()->keyBy('rating');

        // ── Financial table records ───────────────────────────────────────
        $financialRecords = DB::table('financial')
            ->orderByDesc('year')->orderByDesc('month')->limit(12)->get();

        $financialSummary = DB::table('financial')->selectRaw(
            'SUM(total_revenue) as total_revenue, SUM(total_costs) as total_costs, SUM(profit) as profit'
        )->first();

        return view('admin.analytics', compact(
            'appointmentsByStatus', 'totalAppointments', 'completedAppts', 'cancelledAppts',
            'pendingAppts', 'completionRate', 'cancellationRate',
            'appointmentsByMonth', 'apptByDow',
            'paymentsByStatus', 'totalRevenue', 'totalPending', 'totalRefunded', 'avgTransaction',
            'paymentsByMonth',
            'totalPatients', 'totalDentists', 'patientGrowth', 'dentistGrowth',
            'popularServices', 'serviceRevenue',
            'topDentistsByAppts', 'topDentistsByRevenue',
            'avgDoctorRating', 'topRatedDoctors', 'ratingDistribution',
            'financialRecords', 'financialSummary'
        ));
    }

    // ─── Generate Monthly Performance Report (PDF) ──────────────────────────

    public function generateReport(Request $request)
    {
        // Determine report period: default = last complete month
        $year  = (int) $request->input('year',  now()->subMonth()->year);
        $month = (int) $request->input('month', now()->subMonth()->month);

        $periodStart = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $periodEnd   = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $monthLabel  = $periodStart->format('F Y');
        $generatedAt = now()->format('M d, Y \a\t H:i');
        $lastDayOfMonth = $periodEnd->format('M d, Y');

        // ── Appointments (using actual enum statuses: scheduled/completed/cancelled/no_show/failed) ──
        $apptTotal     = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->count();
        $apptCompleted = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->where('status', 'completed')->count();
        $apptCancelled = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->where('status', 'cancelled')->count();
        $apptScheduled = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->where('status', 'scheduled')->count();
        $apptNoShow    = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->where('status', 'no_show')->count();
        $apptFailed    = Appointment::whereBetween('appointment_date', [$periodStart, $periodEnd])->where('status', 'failed')->count();
        $completionRate = $apptTotal > 0 ? round($apptCompleted / $apptTotal * 100, 1) : 0;

        $allTimeAppts     = Appointment::count();
        $allTimeCompleted = Appointment::where('status', 'completed')->count();

        // ── Revenue (from payments table) ───────────────────────────────────
        $revenue = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$periodStart, $periodEnd])
            ->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')
            ->whereBetween('payment_date', [$periodStart, $periodEnd])
            ->sum('amount');
        $refunds = Payment::where('status', 'refunded')
            ->whereBetween('payment_date', [$periodStart, $periodEnd])
            ->sum('amount');
        $txnCount = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$periodStart, $periodEnd])
            ->count();
        $avgTransaction = $txnCount > 0 ? round($revenue / $txnCount, 2) : 0;

        // ── Costs (from actual database records) ─────────────────────────────
        $activeDentists = Dentist::where('status', 'active')->count();
        // Actual salary total from each dentist's salary column
        $actualSalaries = (float) Dentist::where('status', 'active')->sum('salary');

        // Profit is always calculated from real DB data
        $totalCosts  = $actualSalaries;
        $grossProfit = round($revenue - $totalCosts, 2);
        $profitMargin = $revenue > 0 ? round($grossProfit / $revenue * 100, 1) : 0;

        // Financial table record shown as supplementary reference only (not used to override)
        $financialRecord = DB::table('financial')
            ->where('year', $year)->where('month', $month)->first();
        $financialNotes = $financialRecord?->notes;

        // ── Patients ────────────────────────────────────────────────────────
        $newPatients   = Patient::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalPatients = Patient::count();
        $activePatients = Patient::whereHas('appointments', fn($q) =>
            $q->whereBetween('appointment_date', [$periodStart, $periodEnd])
        )->count();

        // ── Dentists ─────────────────────────────────────────────────────────
        $newDentists   = Dentist::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalDentists = Dentist::count();
        $topDentists   = Dentist::withCount(['appointments' => fn($q) =>
            $q->whereBetween('appointment_date', [$periodStart, $periodEnd])])
            ->orderByDesc('appointments_count')->limit(5)->get();

        // Actual salary details per active dentist
        $dentistSalaries = Dentist::where('status', 'active')
            ->select('name', 'salary')
            ->orderByDesc('salary')
            ->get();

        // ── Services (with both period and all-time bookings) ────────────────
        $topServices = Service::withCount([
            'appointments as period_bookings' => fn($q) =>
                $q->whereBetween('appointment_date', [$periodStart, $periodEnd]),
            'appointments as alltime_bookings'
        ])->orderByDesc('period_bookings')->limit(8)->get();

        $serviceRevenue = DB::table('payments')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('payments.status', 'completed')
            ->whereBetween('payments.payment_date', [$periodStart, $periodEnd])
            ->selectRaw('services.name, SUM(payments.amount) as revenue, COUNT(*) as cnt')
            ->groupBy('services.name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        // ── Vacation Requests ─────────────────────────────────────────────
        $vacApproved = VacationRequest::where('status', 'approved')
            ->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $vacPending  = VacationRequest::where('status', 'pending')
            ->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $vacRejected = VacationRequest::where('status', 'rejected')
            ->whereBetween('created_at', [$periodStart, $periodEnd])->count();

        // ── Ratings (all-time + period-specific) ─────────────────────────────
        $avgRating         = round((float) DB::table('doctor_ratings')->avg('rating'), 1);
        $ratingCount       = DB::table('doctor_ratings')->count();
        $avgRatingPeriod   = round((float) DB::table('doctor_ratings')
            ->whereBetween('created_at', [$periodStart, $periodEnd])->avg('rating'), 1);
        $ratingCountPeriod = DB::table('doctor_ratings')
            ->whereBetween('created_at', [$periodStart, $periodEnd])->count();

        $data = compact(
            'year', 'month', 'monthLabel', 'generatedAt', 'lastDayOfMonth',
            'periodStart', 'periodEnd',
            'apptTotal', 'apptCompleted', 'apptCancelled', 'apptScheduled',
            'apptNoShow', 'apptFailed', 'completionRate',
            'allTimeAppts', 'allTimeCompleted',
            'revenue', 'pendingPayments', 'refunds', 'txnCount', 'avgTransaction',
            'actualSalaries', 'totalCosts', 'grossProfit', 'profitMargin',
            'financialRecord', 'financialNotes',
            'newPatients', 'totalPatients', 'activePatients',
            'newDentists', 'totalDentists', 'activeDentists', 'topDentists',
            'dentistSalaries',
            'topServices', 'serviceRevenue',
            'vacApproved', 'vacPending', 'vacRejected',
            'avgRating', 'ratingCount', 'avgRatingPeriod', 'ratingCountPeriod'
        );

        $pdf = Pdf::loadView('admin.report_pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'ShinyTooth_Report_' . $periodStart->format('Y_m') . '.pdf';
        return $pdf->download($filename);
    }

    // ─── Doctors CRUD ───────────────────────────────────────────────────────

    public function doctors(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');

        $query = Dentist::withCount('appointments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $doctors = $query->latest()->paginate(15);

        return view('admin.doctors', [
            'doctors' => $doctors,
            'search'  => $search,
            'status'  => $status,
        ]);
    }

    public function storeDoctor(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:dentists,email',
            'password'         => 'required|string|min:8',
            'phone'            => 'required|string|unique:dentists,phone',
            'salary'           => 'required|numeric|min:0',
            'hire_date'        => 'required|date',
            'status'           => 'required|in:active,inactive,on_leave',
            'experience_years' => 'nullable|integer|min:0',
            'university'       => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'place_of_birth'   => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/doctors', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $validated['password'] = bcrypt($validated['password']);
        Dentist::create($validated);

        return redirect()->route('admin.users', ['type' => 'dentists'])->with('success', 'Doctor added successfully.');
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = Dentist::findOrFail($id);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:dentists,email,' . $id,
            'phone'            => 'required|string|unique:dentists,phone,' . $id,
            'salary'           => 'required|numeric|min:0',
            'hire_date'        => 'required|date',
            'status'           => 'required|in:active,inactive,on_leave',
            'experience_years' => 'nullable|integer|min:0',
            'university'       => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'place_of_birth'   => 'nullable|string|max:255',
            'password'         => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $doctor->update($validated);

        return redirect()->route('admin.users', ['type' => 'dentists'])->with('success', 'Doctor updated successfully.');
    }

    public function deleteDoctor($id)
    {
        $doctor = Dentist::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.users', ['type' => 'dentists'])->with('success', 'Doctor deleted successfully.');
    }

    // ─── Patients CRUD ──────────────────────────────────────────────────────

    public function patients(Request $request)
    {
        $search = $request->input('search', '');
        $gender = $request->input('gender', 'all');

        $query = Patient::withCount('appointments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($gender !== 'all') {
            $query->where('gender', $gender);
        }

        $patients = $query->latest()->paginate(15);

        return view('admin.patients', [
            'patients' => $patients,
            'search'   => $search,
            'gender'   => $gender,
        ]);
    }

    public function storePatient(Request $request)
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@shinytooth.com');

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:patients,email|not_in:' . $adminEmail,
            'password'      => 'required|string|min:8',
            'phone'         => 'required|string|unique:patients,phone',
            'date_of_birth' => 'required|date',
            'gender'        => 'required|in:male,female',
            'address'       => 'required|string|max:500',
            'blood_type'    => 'nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
            'nationality'   => 'nullable|string|max:100',
            'place_of_birth'=> 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Patient::create($validated);

        return redirect()->route('admin.users', ['type' => 'patients'])->with('success', 'Patient added successfully.');
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:patients,email,' . $id,
            'phone'         => 'required|string|unique:patients,phone,' . $id,
            'date_of_birth' => 'required|date',
            'gender'        => 'required|in:male,female',
            'address'       => 'required|string|max:500',
            'blood_type'    => 'nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
            'nationality'   => 'nullable|string|max:100',
            'place_of_birth'=> 'nullable|string|max:255',
            'password'      => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $patient->update($validated);

        return redirect()->route('admin.users', ['type' => 'patients'])->with('success', 'Patient updated successfully.');
    }

    public function deletePatient($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('admin.users', ['type' => 'patients'])->with('success', 'Patient deleted successfully.');
    }

    public function unblockPatient($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $patient->update(['booking_blocked' => false]);

        return redirect()->back()->with('success', 'Patient unblocked successfully.');
    }

    // ─── Vacation Requests Management ───────────────────────────────────

    public function vacations(Request $request)
    {
        $status    = $request->input('status', 'all');
        $dentistId = $request->input('dentist_id', 'all');

        $query = VacationRequest::with('dentist')->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($dentistId !== 'all') {
            $query->where('dentist_id', $dentistId);
        }

        $vacations = $query->get();

        // Counts
        $totalCount    = VacationRequest::count();
        $pendingCount  = VacationRequest::where('status', 'pending')->count();
        $approvedCount = VacationRequest::where('status', 'approved')->count();
        $rejectedCount = VacationRequest::where('status', 'rejected')->count();

        // Rates
        $approvalRate  = $totalCount > 0 ? round($approvedCount / $totalCount * 100) : 0;
        $rejectionRate = $totalCount > 0 ? round($rejectedCount / $totalCount * 100) : 0;

        // Total approved days (full_day only, computed from date columns)
        $totalApprovedDays = VacationRequest::where('status', 'approved')
            ->where('type', 'full_day')
            ->selectRaw('COALESCE(SUM(DATEDIFF(end_date, start_date) + 1), 0) as total_days')
            ->value('total_days') ?? 0;

        // Top dentists by approved vacation days
        $topDentistsByDays = VacationRequest::where('status', 'approved')
            ->where('type', 'full_day')
            ->selectRaw('dentist_id, SUM(DATEDIFF(end_date, start_date) + 1) as total_days, COUNT(*) as req_count')
            ->groupBy('dentist_id')
            ->orderByDesc('total_days')
            ->with('dentist')
            ->limit(5)
            ->get();

        // Requests submitted per month (last 12 months)
        $requestsByMonth = VacationRequest::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // All pending (for quick-action panel)
        $pendingVacations = VacationRequest::with('dentist')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        // Dentist list for filter dropdown
        $dentists = Dentist::orderBy('name')->get(['id', 'name']);

        return view('admin.vacations', compact(
            'vacations', 'status', 'dentistId',
            'totalCount', 'pendingCount', 'approvedCount', 'rejectedCount',
            'approvalRate', 'rejectionRate',
            'totalApprovedDays', 'topDentistsByDays',
            'requestsByMonth', 'pendingVacations', 'dentists'
        ));
    }

    public function approveVacation($id)
    {
        $vacation = VacationRequest::findOrFail($id);

        if ($vacation->status !== 'pending') {
            return back()->with('error', 'This request is not pending.');
        }

        $vacation->update(['status' => 'approved']);

        return back()->with('success', 'Vacation request approved.');
    }

    public function rejectVacation(Request $request, $id)
    {
        $vacation = VacationRequest::findOrFail($id);

        if ($vacation->status !== 'pending') {
            return back()->with('error', 'This request is not pending.');
        }

        $vacation->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note', ''),
        ]);

        return back()->with('success', 'Vacation request rejected.');
    }
}

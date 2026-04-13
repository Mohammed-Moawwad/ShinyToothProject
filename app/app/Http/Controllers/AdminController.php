<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

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
        ]);
    }

    /**
     * Show all appointments
     */
    public function appointments(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'latest');

        $query = Appointment::with(['patient', 'dentist', 'service', 'payment']);

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
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

        $statuses = [
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments', [
            'appointments' => $appointments,
            'status' => $status,
            'search' => $search,
            'sort' => $sort,
            'statuses' => $statuses,
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

        // Calculate statistics
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');
        $failedAmount = Payment::where('status', 'failed')->sum('amount');

        $statuses = [
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.payments', [
            'payments' => $payments,
            'search' => $search,
            'status' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totalRevenue' => $totalRevenue,
            'pendingAmount' => $pendingAmount,
            'failedAmount' => $failedAmount,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show all services
     */
    public function services(Request $request)
    {
        $search = $request->input('search', '');

        $query = Service::withCount('appointments');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $services = $query->paginate(15);

        return view('admin.services', [
            'services' => $services,
            'search' => $search,
        ]);
    }

    /**
     * Show analytics and reports
     */
    public function analytics()
    {
        // Appointment statistics
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $appointmentsByMonth = Appointment::selectRaw('DATE_FORMAT(appointment_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Payment statistics
        $paymentsByStatus = Payment::selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $paymentsByMonth = Payment::selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->whereNotNull('payment_date')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // User growth
        $patientGrowth = Patient::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $dentistGrowth = Dentist::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Service popularity
        $popularServices = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics', [
            'appointmentsByStatus' => $appointmentsByStatus,
            'appointmentsByMonth' => $appointmentsByMonth,
            'paymentsByStatus' => $paymentsByStatus,
            'paymentsByMonth' => $paymentsByMonth,
            'patientGrowth' => $patientGrowth,
            'dentistGrowth' => $dentistGrowth,
            'popularServices' => $popularServices,
        ]);
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
        ]);

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
}

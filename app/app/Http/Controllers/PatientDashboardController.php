<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\DoctorSubscription;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $patient = Auth::user();

        // Upcoming appointments (scheduled, in the future)
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now()->toDateString())
            ->with(['dentist', 'service'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        // Past appointments (completed, cancelled, no_show, failed)
        $pastAppointments = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', ['completed', 'cancelled', 'no_show', 'failed'])
            ->with(['dentist', 'service', 'rating'])
            ->orderByDesc('appointment_date')
            ->take(5)
            ->get();

        // Appointment stats
        $totalAppointments = Appointment::where('patient_id', $patient->id)->count();
        $completedCount = Appointment::where('patient_id', $patient->id)->where('status', 'completed')->count();
        $upcomingCount = Appointment::where('patient_id', $patient->id)->where('status', 'scheduled')
            ->where('appointment_date', '>=', now()->toDateString())->count();

        // Payments
        $totalSpent = Payment::where('patient_id', $patient->id)->where('status', 'completed')->sum('amount');
        $recentPayments = Payment::where('patient_id', $patient->id)
            ->with('appointment.service')
            ->orderByDesc('payment_date')
            ->take(5)
            ->get();

        // Active subscription
        $subscription = DoctorSubscription::where('patient_id', $patient->id)
            ->whereIn('status', ['active', 'pending'])
            ->with('dentist')
            ->first();

        // Available services for booking
        $services = Service::orderBy('name')->get();

        return view('patient.dashboard', compact(
            'patient',
            'upcomingAppointments',
            'pastAppointments',
            'totalAppointments',
            'completedCount',
            'upcomingCount',
            'totalSpent',
            'recentPayments',
            'subscription',
            'services',
        ));
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\WebAuthController;

// Admin routes (protected by IsAdmin middleware)
Route::middleware('auth', 'admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('admin.appointments');
    Route::get('/appointments/dentist/{dentistId}', [AdminController::class, 'appointmentsByDentist'])->name('admin.appointments.by-dentist');
    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

    // Doctors CRUD
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('admin.doctors');
    Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('admin.doctors.store');
    Route::put('/doctors/{id}', [AdminController::class, 'updateDoctor'])->name('admin.doctors.update');
    Route::delete('/doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('admin.doctors.delete');

    // Patients CRUD
    Route::get('/patients', [AdminController::class, 'patients'])->name('admin.patients');
    Route::post('/patients', [AdminController::class, 'storePatient'])->name('admin.patients.store');
    Route::put('/patients/{id}', [AdminController::class, 'updatePatient'])->name('admin.patients.update');
    Route::delete('/patients/{id}', [AdminController::class, 'deletePatient'])->name('admin.patients.delete');
});

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/{id}', [ServicesController::class, 'show'])->where('id', '[0-9]+');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::post('/login', [WebAuthController::class, 'loginWeb'])->name('login.web');

// Test route
Route::get('/test-admin', function() {
    $p = \App\Models\Patient::where('email', 'admin@shinytooth.com')->first();
    if ($p) {
        return "Found: {$p->email}, Password Hash: {$p->password}";
    }
    return "Admin not found";
});

// Reset admin password
Route::get('/reset-admin-password', function() {
    $p = \App\Models\Patient::where('email', 'admin@shinytooth.com')->first();
    if ($p) {
        $p->password = 'admin123456';
        $p->save();
        return "Admin password reset to: admin123456";
    }
    return "Admin not found";
});

// Debug login attempt
Route::post('/debug-login', function(\Illuminate\Http\Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    $patient = \App\Models\Patient::where('email', $email)->first();
    
    if (!$patient) {
        return "Patient not found";
    }
    
    if (!\Illuminate\Support\Facades\Hash::check($password, $patient->password)) {
        return "Password mismatch. Hash: {$patient->password}";
    }
    
    \Illuminate\Support\Facades\Auth::guard('web')->login($patient, true);
    $request->session()->regenerate();
    
    return "Login successful. User ID: {$patient->id}, Email: {$patient->email}";
});

// Protected routes (after login)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');

    Route::get('/dentist/dashboard', function () {
        return view('dentist.dashboard');
    })->name('dentist.dashboard');
});

// Booking flow
Route::get('/book', [BookingController::class, 'selectTime'])->name('booking.time');
Route::get('/book/payment',  [BookingController::class, 'showPayment'])->name('booking.payment');
Route::post('/book/pay',     [BookingController::class, 'processPayment'])->name('booking.pay');
Route::get('/book/confirm',        [BookingController::class, 'showConfirm'])->name('booking.confirm');
Route::post('/book/confirm/submit', [BookingController::class, 'submitConfirm'])->name('booking.confirm.submit');
Route::get('/book/done', [BookingController::class, 'showDone'])->name('booking.done');

// Doctor dashboard routes
Route::get('/doctor/dashboard',           [DoctorDashboardController::class, 'index']);
Route::get('/doctor/appointments',        [DoctorDashboardController::class, 'appointments']);
Route::get('/doctor/subscriptions-view',  [DoctorDashboardController::class, 'subscriptions']);
Route::get('/doctor/subscriptions/{id}/plan', [DoctorDashboardController::class, 'planDesigner']);
Route::get('/doctor/reports',             [DoctorDashboardController::class, 'reports']);
Route::get('/doctor/bonuses',             [DoctorDashboardController::class, 'bonuses']);
Route::get('/doctor/profile',             [DoctorDashboardController::class, 'profile']);
Route::post('/doctor/profile/update',     [DoctorDashboardController::class, 'updateProfile']);
Route::get('/doctor/schedule',            [DoctorDashboardController::class, 'schedule']);
Route::post('/doctor/schedule/update',    [DoctorDashboardController::class, 'updateSchedule']);

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

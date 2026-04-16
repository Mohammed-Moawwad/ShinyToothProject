<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DoctorSubscriptionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\AppointmentAttendanceController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\WebAuthController;

// Admin login (session-based, outside auth middleware)
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login.form');
Route::post('/admin/login', [WebAuthController::class, 'loginWeb'])->name('admin.login');

// Admin routes (protected by IsAdmin middleware)
Route::middleware('admin')->prefix('admin')->group(function () {
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
    Route::post('/patients/{patientId}/unblock', [AdminController::class, 'unblockPatient'])->name('admin.patients.unblock');

    // Subscriptions management
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin.subscriptions.index');
    Route::post('/subscriptions/{id}/approve-action', [AdminSubscriptionController::class, 'approveAction'])->name('admin.subscriptions.approve');
    Route::post('/subscriptions/{id}/reject-action', [AdminSubscriptionController::class, 'rejectAction'])->name('admin.subscriptions.reject');
    Route::post('/subscriptions/{id}/remove', [AdminSubscriptionController::class, 'remove'])->name('admin.subscriptions.remove');

    // Vacation requests management
    Route::get('/vacations', [AdminController::class, 'vacations'])->name('admin.vacations');
    Route::post('/vacations/{id}/approve', [AdminController::class, 'approveVacation'])->name('admin.vacations.approve');
    Route::post('/vacations/{id}/reject', [AdminController::class, 'rejectVacation'])->name('admin.vacations.reject');
});

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/{id}', [ServicesController::class, 'show'])->where('id', '[0-9]+');

// ─── Authentication routes ──────────────────────────────────────────────
// Authentication pages — no middleware, controller handles all logic
Route::get('/login',     fn() => view('auth.login'))->name('login');
Route::get('/register',  fn() => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'registerPatient'])->name('register.store');
Route::post('/login',    [AuthController::class, 'loginPatient'])->name('login.store');

// ─── Protected routes (after login) ──────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');

    Route::get('/dentist/dashboard', function () {
        return view('dentist.dashboard');
    })->name('dentist.dashboard');
});

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Step 3 – pick date/time
Route::get('/book', [BookingController::class, 'selectTime'])->name('booking.time');
Route::get('/book/done', [BookingController::class, 'showDone'])->name('booking.done');

// ─── Doctors (public) ──────────────────────────────────────────────────
Route::get('/doctors',      [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{id}', [DoctorController::class, 'show'])->where('id', '[0-9]+')->name('doctors.show');

// ─── Patient subscription actions ──────────────────────────────────────
Route::post('/subscriptions/request',              [SubscriptionController::class, 'sendRequest'])->name('subscriptions.request');
Route::get('/my-subscription',                     [SubscriptionController::class, 'mySubscription'])->name('subscriptions.my');
Route::post('/subscriptions/{id}/cancel-request',  [SubscriptionController::class, 'requestCancel'])->name('subscriptions.cancel');
Route::post('/subscriptions/{id}/switch-request',  [SubscriptionController::class, 'requestSwitch'])->name('subscriptions.switch');
Route::post('/subscriptions/{id}/rate',            [SubscriptionController::class, 'ratePlan'])->name('subscriptions.rate');

// ─── Doctor subscription management (dashboard-ready) ─────────────────
Route::get('/doctor/subscriptions',                        [DoctorSubscriptionController::class, 'index'])->name('doctor.subscriptions.index');
Route::post('/doctor/subscriptions/{id}/accept',           [DoctorSubscriptionController::class, 'accept'])->name('doctor.subscriptions.accept');
Route::post('/doctor/subscriptions/{id}/reject',           [DoctorSubscriptionController::class, 'reject'])->name('doctor.subscriptions.reject');
Route::post('/doctor/subscriptions/{id}/idle',             [DoctorSubscriptionController::class, 'markIdle'])->name('doctor.subscriptions.idle');
Route::post('/doctor/subscriptions/{id}/active',           [DoctorSubscriptionController::class, 'reactivate'])->name('doctor.subscriptions.reactivate');
Route::post('/doctor/subscriptions/{id}/complete',         [DoctorSubscriptionController::class, 'markCompleted'])->name('doctor.subscriptions.complete');
Route::post('/doctor/subscriptions/{id}/request-removal',  [DoctorSubscriptionController::class, 'requestRemoval'])->name('doctor.subscriptions.requestRemoval');

// ─── Doctor plan management (dashboard-ready) ─────────────────────────
Route::post('/doctor/subscriptions/{id}/plan',              [PlanController::class, 'createOrUpdate'])->name('doctor.plan.save');
Route::post('/doctor/subscriptions/{id}/plan/items',        [PlanController::class, 'addItem'])->name('doctor.plan.addItem');
Route::delete('/doctor/plans/{planId}/items/{itemId}',      [PlanController::class, 'removeItem'])->name('doctor.plan.removeItem');
Route::patch('/doctor/plans/{planId}/items/reorder',        [PlanController::class, 'reorder'])->name('doctor.plan.reorder');
Route::patch('/doctor/plans/{planId}/items/{itemId}/complete', [PlanController::class, 'completeItem'])->name('doctor.plan.completeItem');

// ─── Doctor attendance marking (dashboard-ready) ──────────────────────
Route::post('/doctor/appointments/{id}/mark-attendance', [AppointmentAttendanceController::class, 'markAttendance'])->name('doctor.appointments.markAttendance');

// ─── Doctor dashboard views ───────────────────────────────────────────
Route::get('/doctor/dashboard',                          [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
Route::get('/doctor/appointments',                       [DoctorDashboardController::class, 'appointments'])->name('doctor.appointments');
Route::get('/doctor/subscriptions-view',                 [DoctorDashboardController::class, 'subscriptions'])->name('doctor.subscriptions.view');
Route::get('/doctor/subscriptions/{id}/plan',            [DoctorDashboardController::class, 'planDesigner'])->name('doctor.plan.designer');
Route::get('/doctor/reports',                            [DoctorDashboardController::class, 'reports'])->name('doctor.reports');
Route::get('/doctor/patient-report',                     [DoctorDashboardController::class, 'patientReport'])->name('doctor.patient.report');
Route::post('/doctor/patient-report/report/store',       [DoctorDashboardController::class, 'storeWrittenReport'])->name('doctor.written-report.store');
Route::post('/doctor/patient-report/report/{id}/submit', [DoctorDashboardController::class, 'submitWrittenReport'])->name('doctor.written-report.submit');
Route::get('/doctor/bonuses',                            [DoctorDashboardController::class, 'bonuses'])->name('doctor.bonuses');
Route::get('/doctor/profile',                            [DoctorDashboardController::class, 'profile'])->name('doctor.profile');
Route::post('/doctor/profile/update',                    [DoctorDashboardController::class, 'updateProfile'])->name('doctor.profile.update');
Route::get('/doctor/vacations',                          [DoctorDashboardController::class, 'vacations'])->name('doctor.vacations');
Route::post('/doctor/vacations/store',                   [DoctorDashboardController::class, 'storeVacation'])->name('doctor.vacations.store');
Route::delete('/doctor/vacations/{id}/cancel',           [DoctorDashboardController::class, 'cancelVacation'])->name('doctor.vacations.cancel');



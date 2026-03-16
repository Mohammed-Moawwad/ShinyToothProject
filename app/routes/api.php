<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DoctorRatingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinancialController;

/*
|--------------------------------------------------------------------------
| ShinyTooth API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically by Laravel.
| Example: Route::get('/patients') → http://localhost/api/patients
*/

// ─── Public Auth Routes ───────────────────────────────────────────────────────

// Patient auth
Route::post('/auth/patient/register', [AuthController::class, 'registerPatient']);
Route::post('/auth/patient/login',    [AuthController::class, 'loginPatient']);

// Dentist auth
Route::post('/auth/dentist/register', [AuthController::class, 'registerDentist']);
Route::post('/auth/dentist/login',    [AuthController::class, 'loginDentist']);

// Public read-only routes (no auth required)
Route::apiResource('specializations', SpecializationController::class)->only(['index', 'show']);
Route::apiResource('services', ServiceController::class)->only(['index', 'show']);

// ─── Protected Routes (require valid Sanctum token) ───────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth Management
    Route::post('/auth/patient/logout',  [AuthController::class, 'logoutPatient']);
    Route::post('/auth/dentist/logout',  [AuthController::class, 'logoutDentist']);
    Route::get('/auth/me',               [AuthController::class, 'me']);

    // Patients (full CRUD)
    Route::apiResource('patients', PatientController::class);

    // Dentists (full CRUD)
    Route::apiResource('dentists', DentistController::class);

    // Specializations (write operations protected)
    Route::apiResource('specializations', SpecializationController::class)->except(['index', 'show']);

    // Services (write operations protected)
    Route::apiResource('services', ServiceController::class)->except(['index', 'show']);

    // Appointments
    Route::apiResource('appointments', AppointmentController::class);

    // Payments
    Route::apiResource('payments', PaymentController::class);

    // Doctor Ratings
    Route::apiResource('ratings', DoctorRatingController::class);

    // Reports
    Route::apiResource('reports', ReportController::class);

    // Financial Records
    Route::apiResource('financials', FinancialController::class);
});


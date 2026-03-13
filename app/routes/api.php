<?php

use Illuminate\Support\Facades\Route;
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

// Patients
Route::apiResource('patients', PatientController::class);

// Dentists
Route::apiResource('dentists', DentistController::class);

// Specializations
Route::apiResource('specializations', SpecializationController::class);

// Services
Route::apiResource('services', ServiceController::class);

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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorDashboardController;

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

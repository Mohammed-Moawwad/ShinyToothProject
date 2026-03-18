<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/services', [ServicesController::class, 'index']);

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

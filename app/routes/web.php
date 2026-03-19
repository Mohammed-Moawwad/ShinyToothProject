<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/{id}', [ServicesController::class, 'show'])->where('id', '[0-9]+');

// Step 3 – pick date/time
Route::get('/book', [BookingController::class, 'selectTime'])->name('booking.time');

// Step 4 – payment
Route::get('/book/payment',  [BookingController::class, 'showPayment'])->name('booking.payment');
Route::post('/book/pay',     [BookingController::class, 'processPayment'])->name('booking.pay');

// Step 5 – confirmation
Route::get('/book/confirm',        [BookingController::class, 'showConfirm'])->name('booking.confirm');
Route::post('/book/confirm/submit', [BookingController::class, 'submitConfirm'])->name('booking.confirm.submit');

// Step 6 – done
Route::get('/book/done', [BookingController::class, 'showDone'])->name('booking.done');

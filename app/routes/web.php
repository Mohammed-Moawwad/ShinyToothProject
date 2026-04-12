<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DoctorSubscriptionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\AppointmentAttendanceController;
use App\Http\Controllers\DoctorDashboardController;

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
Route::get('/doctor/bonuses',                            [DoctorDashboardController::class, 'bonuses'])->name('doctor.bonuses');
Route::get('/doctor/profile',                            [DoctorDashboardController::class, 'profile'])->name('doctor.profile');
Route::post('/doctor/profile/update',                    [DoctorDashboardController::class, 'updateProfile'])->name('doctor.profile.update');
Route::get('/doctor/schedule',                           [DoctorDashboardController::class, 'schedule'])->name('doctor.schedule');
Route::post('/doctor/schedule/update',                   [DoctorDashboardController::class, 'updateSchedule'])->name('doctor.schedule.update');

// ─── Admin subscription management (dashboard-ready) ──────────────────
Route::get('/admin/subscriptions',                         [AdminSubscriptionController::class, 'index'])->name('admin.subscriptions.index');
Route::post('/admin/subscriptions/{id}/approve-action',    [AdminSubscriptionController::class, 'approveAction'])->name('admin.subscriptions.approve');
Route::post('/admin/subscriptions/{id}/reject-action',     [AdminSubscriptionController::class, 'rejectAction'])->name('admin.subscriptions.reject');
Route::post('/admin/subscriptions/{id}/remove',            [AdminSubscriptionController::class, 'remove'])->name('admin.subscriptions.remove');
Route::post('/admin/patients/{patientId}/unblock',         [AdminSubscriptionController::class, 'unblockPatient'])->name('admin.patients.unblock');

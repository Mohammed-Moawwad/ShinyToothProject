@extends('patient.layout')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
@if($patient->booking_blocked)
<div class="alert mb-4 d-flex align-items-start gap-3" style="background:#fff3cd; border:1.5px solid #ffc107; border-radius:14px; padding:16px 20px;">
    <i class="bi bi-exclamation-triangle-fill" style="color:#e65c00; font-size:1.4rem; flex-shrink:0; margin-top:2px;"></i>
    <div>
        <div class="fw-bold" style="color:#7c3a00; font-size:.95rem;">Booking Restricted</div>
        <div style="color:#7c3a00; font-size:.85rem; margin-top:2px;">Your account has been restricted from booking new appointments due to repeated no-shows. Please contact the clinic to resolve this.</div>
    </div>
</div>
@endif
<!-- ── STAT CARDS ─────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e6f5f3; color:var(--teal);"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-value">{{ $upcomingCount }}</div>
            <div class="stat-label">Upcoming Appointments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d4f5e4; color:#0f6b3a;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value">{{ $completedCount }}</div>
            <div class="stat-label">Completed Visits</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e7f1ff; color:#0056b3;"><i class="bi bi-clipboard2-pulse-fill"></i></div>
            <div class="stat-value">{{ $totalAppointments }}</div>
            <div class="stat-label">Total Appointments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0ecff; color:#6f42c1;"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">SAR {{ number_format($totalSpent, 0) }}</div>
            <div class="stat-label">Total Spent</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ── UPCOMING APPOINTMENTS ────────────────────────── -->
    <div class="col-lg-12">
        <div class="dash-card" id="my-appointments">
            <div class="card-header-custom">
                <h6><i class="bi bi-calendar-event me-2" style="color:var(--teal);"></i>Upcoming Appointments</h6>
                <a href="/services" class="btn btn-sm btn-outline-teal" @if($patient->booking_blocked) style="pointer-events:none; opacity:.45;" title="Booking restricted" @endif>Book New</a>
            </div>

            @if($upcomingAppointments->count())
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Doctor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingAppointments as $appt)
                            <tr>
                                <td>
                                    <strong>{{ $appt->appointment_date->format('M d') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</small>
                                </td>
                                <td>{{ $appt->service->name ?? 'Dental Service' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($appt->dentist->name ?? 'D', 0, 1)) }}</span>
                                        Dr. {{ $appt->dentist->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td><span class="badge-status badge-scheduled">Scheduled</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x d-block"></i>
                    <p class="mb-2">No upcoming appointments</p>
                    <a href="/services" class="btn btn-sm btn-teal" @if($patient->booking_blocked) style="pointer-events:none; opacity:.45;" title="Booking restricted" @endif>Book Now</a>
                </div>
            @endif
        </div>
    </div>

    <!-- ── SECOND ROW: HISTORY ─────────────────── -->
    <div class="col-lg-12">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-clock-history me-2" style="color:#6c757d;"></i>Recent History</h6>
            </div>

            @if($pastAppointments->count())
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Doctor</th>
                            <th>Rating</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastAppointments as $appt)
                            <tr>
                                <td><strong>{{ $appt->appointment_date->format('M d, Y') }}</strong></td>
                                <td>{{ $appt->service->name ?? 'Dental Service' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($appt->dentist->name ?? 'D', 0, 1)) }}</span>
                                        Dr. {{ $appt->dentist->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($appt->rating)
                                        <span class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $appt->rating->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:.8rem;">—</span>
                                    @endif
                                </td>
                                <td><span class="badge-status badge-{{ $appt->status }}">{{ ucfirst(str_replace('_', ' ', $appt->status)) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox d-block"></i>
                    <p class="mb-0">No past appointments yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ── RECENT PAYMENTS ─────────────────── -->
    <div class="col-lg-12">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-credit-card me-2" style="color:#6f42c1;"></i>Recent Payments</h6>
            </div>

            @if($recentPayments->count())
                @foreach($recentPayments as $pay)
                    <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;">{{ $pay->appointment->service->name ?? 'Payment' }}</div>
                            <small class="text-muted">
                                {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') : 'N/A' }}
                                · {{ ucfirst($pay->payment_method ?? 'N/A') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div style="font-weight:700; color:var(--dark-blue);">SAR {{ number_format($pay->amount, 2) }}</div>
                            <span class="badge-status badge-{{ $pay->status }}">{{ ucfirst($pay->status) }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-receipt d-block"></i>
                    <p class="mb-0">No payment history</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

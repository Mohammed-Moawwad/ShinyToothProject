@extends('doctor.layout')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- ── STAT CARDS ─────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e6f5f3; color:var(--teal);"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $activeSubscriptions }}</div>
            <div class="stat-label">Active Subscriptions</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5; color:#b86e00;"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-value">{{ $pendingRequests }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e7f1ff; color:#0056b3;"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-value">{{ $todayAppointments }}</div>
            <div class="stat-label">Today's Appointments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0ecff; color:#6f42c1;"><i class="bi bi-trophy-fill"></i></div>
            <div class="stat-value">SAR {{ number_format($totalBonus, 0) }}</div>
            <div class="stat-label">Total Bonuses Earned</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e6f5f3; color:var(--teal);"><i class="bi bi-calendar-week"></i></div>
            <div class="stat-value">{{ $weekAppointments }}</div>
            <div class="stat-label">This Week's Appointments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff9db; color:#f9a825;"><i class="bi bi-star-fill"></i></div>
            <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
            <div class="stat-label">Average Rating ({{ $totalReviews }} reviews)</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ── TODAY'S APPOINTMENTS ────────────────────────────── -->
    <div class="col-lg-7">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-calendar-day me-2" style="color:var(--teal);"></i>Today's Appointments</h6>
                <a href="/doctor/appointments?dentist={{ $dentist->id }}" class="btn btn-sm btn-outline-teal">View All</a>
            </div>

            @if($todayAppts->count())
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayAppts as $appt)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($appt->patient->name ?? 'P', 0, 1)) }}</span>
                                        {{ $appt->patient->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>{{ $appt->service->name ?? 'N/A' }}</td>
                                <td><span class="badge-status badge-{{ $appt->status }}">{{ ucfirst(str_replace('_', ' ', $appt->status)) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x d-block"></i>
                    <p class="mb-0">No appointments scheduled for today</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ── PENDING SUBSCRIPTION REQUESTS ──────────────────── -->
    <div class="col-lg-5">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-person-plus-fill me-2" style="color:#b86e00;"></i>Pending Requests</h6>
                <a href="/doctor/subscriptions-view?dentist={{ $dentist->id }}&filter=pending" class="btn btn-sm btn-outline-teal">View All</a>
            </div>

            @if($pendingSubs->count())
                @foreach($pendingSubs as $sub)
                    <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <span class="patient-avatar">{{ strtoupper(substr($sub->patient->name ?? 'P', 0, 1)) }}</span>
                            <div>
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $sub->patient->name ?? 'N/A' }}</div>
                                <small class="text-muted">Requested {{ $sub->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="/doctor/subscriptions/{{ $sub->id }}/accept" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-teal"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $sub->id }}" style="border-radius:10px;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Reject modal -->
                    <div class="modal fade" id="rejectModal{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius:16px; border:none;">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="modal-title fw-bold">Reject Request</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="/doctor/subscriptions/{{ $sub->id }}/reject" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label fw-semibold">Reason for rejection</label>
                                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Explain why you're rejecting this request..." style="border-radius:10px;"></textarea>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                                        <button type="submit" class="btn btn-danger" style="border-radius:10px;">Reject Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox d-block"></i>
                    <p class="mb-0">No pending subscription requests</p>
                </div>
            @endif
        </div>

        <!-- ── RECENT RATINGS ─────────────────────────────── -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-star-fill me-2" style="color:#f9a825;"></i>Recent Ratings</h6>
                <a href="/doctor/bonuses?dentist={{ $dentist->id }}" class="btn btn-sm btn-outline-teal">View All</a>
            </div>

            @if($recentRatings->count())
                @foreach($recentRatings as $rr)
                    <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <span class="patient-avatar">{{ strtoupper(substr($rr->patient->name ?? 'P', 0, 1)) }}</span>
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem;">{{ $rr->patient->name ?? 'Patient' }}</div>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rr->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            @if($rr->review)
                                <p class="text-muted mb-0 mt-1" style="font-size:.82rem;">{{ Str::limit($rr->review, 100) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-star d-block"></i>
                    <p class="mb-0">No ratings yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('doctor.layout')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<!-- ── APPOINTMENT STATS ──────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e7f1ff; color:#0056b3;"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-value">{{ $totalAppointments }}</div>
            <div class="stat-label">Total Appointments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d4f5e4; color:#0f6b3a;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value">{{ $completedAppointments }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff0f0; color:#dc3545;"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-value">{{ $noShowAppointments }}</div>
            <div class="stat-label">No-Shows</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e9ecef; color:#495057;"><i class="bi bi-slash-circle-fill"></i></div>
            <div class="stat-value">{{ $cancelledAppointments }}</div>
            <div class="stat-label">Cancelled</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ── MONTHLY BREAKDOWN ──────────────────────────── -->
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-bar-chart-fill me-2" style="color:var(--teal);"></i>Monthly Appointments (Last 6 Months)</h6>
            </div>
            <div class="table-responsive">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Appointments</th>
                            <th>Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxCount = collect($monthlyData)->max('count') ?: 1; @endphp
                        @foreach($monthlyData as $md)
                            <tr>
                                <td class="fw-semibold">{{ $md['month'] }}</td>
                                <td>{{ $md['count'] }}</td>
                                <td style="width:50%;">
                                    <div class="progress" style="height:18px; border-radius:8px;">
                                        <div class="progress-bar" style="width:{{ ($md['count'] / $maxCount) * 100 }}%; background: linear-gradient(90deg, var(--teal), #5de8d5); border-radius:8px; font-size:.72rem; font-weight:600;">
                                            {{ $md['count'] }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── TOP SERVICES ───────────────────────────── -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-trophy me-2" style="color:#f9a825;"></i>Top Services</h6>
            </div>
            @if($topServices->count())
                @foreach($topServices as $i => $ts)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center justify-content-center"
                             style="width:32px; height:32px; border-radius:50%; font-weight:700; font-size:.85rem;
                                    {{ $i === 0 ? 'background:#fff9db; color:#f9a825;' : 'background:#e9f0f5; color:var(--dark-blue);' }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.9rem;">{{ $ts->service->name ?? 'Unknown' }}</div>
                            <small class="text-muted">{{ $ts->service->category ?? '' }}</small>
                        </div>
                        <div class="fw-bold" style="color:var(--dark-blue);">{{ $ts->total }} appointments</div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-bar-chart d-block"></i>
                    <p class="mb-0">No appointment data yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ── SUBSCRIPTION STATS ─────────────────────────── -->
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-people-fill me-2" style="color:var(--dark-blue);"></i>Subscription Stats</h6>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:.85rem;">Total Subscriptions</span>
                    <span class="fw-bold">{{ $totalSubscriptions }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:.85rem;">Active / Idle</span>
                    <span class="fw-bold text-success">{{ $activeSubscriptions }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:.85rem;">Completed</span>
                    <span class="fw-bold" style="color:var(--teal);">{{ $completedSubscriptions }}</span>
                </div>
            </div>
        </div>

        <!-- Completion rate -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-pie-chart-fill me-2" style="color:var(--teal);"></i>Appointment Success Rate</h6>
            </div>
            @php
                $resolved = $completedAppointments + $noShowAppointments;
                $successRate = $resolved > 0 ? round(($completedAppointments / $resolved) * 100) : 0;
            @endphp
            <div class="text-center py-3">
                <div style="position:relative; width:120px; height:120px; margin:0 auto;">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#e9f0f5" stroke-width="12"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="var(--teal)" stroke-width="12"
                                stroke-dasharray="{{ $successRate * 3.27 }} {{ 327 - ($successRate * 3.27) }}"
                                stroke-dashoffset="82" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                    </svg>
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                        <span style="font-size:1.4rem; font-weight:700; color:var(--dark-blue);">{{ $successRate }}%</span>
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:.82rem;">{{ $completedAppointments }} attended / {{ $resolved }} resolved</p>
            </div>
        </div>

        <!-- Bonus summary -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-trophy-fill me-2" style="color:#f9a825;"></i>Bonus Summary</h6>
            </div>
            <div class="text-center py-2">
                <div class="stat-value" style="color:var(--teal);">SAR {{ number_format($totalBonus, 2) }}</div>
                <small class="text-muted">{{ $bonusCount }} bonus(es) earned</small>
            </div>
        </div>
    </div>
</div>
@endsection

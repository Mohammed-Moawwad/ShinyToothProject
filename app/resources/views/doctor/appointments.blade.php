@extends('doctor.layout')

@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">Appointments</li>
@endsection

@section('content')

{{-- ── TODAY'S APPOINTMENTS ──────────────────────────────── --}}
<div class="dash-card">
    <div class="card-header-custom">
        <h6><i class="bi bi-calendar-check me-2" style="color:var(--teal);"></i>Today's Appointments</h6>
        <span class="text-muted" style="font-size:.82rem;">{{ $today->format('l, M d, Y') }} &nbsp;·&nbsp; {{ $todayAppointments->count() }} appointment(s)</span>
    </div>

    @if($todayAppointments->count())
        <table class="dash-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todayAppointments as $appt)
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
                        <td>
                            @if($appt->status === 'scheduled')
                                <div class="d-flex gap-1 flex-wrap">
                                    <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                        @csrf
                                        <input type="hidden" name="outcome" value="successful">
                                        <button class="btn btn-sm btn-teal" title="Patient attended and service completed">
                                            <i class="bi bi-check-circle-fill me-1"></i> Successful
                                        </button>
                                    </form>
                                    <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                        @csrf
                                        <input type="hidden" name="outcome" value="absent">
                                        <button class="btn btn-sm" style="background:#f59e0b;color:#fff;border-radius:10px;border:none;" title="Patient did not show up">
                                            <i class="bi bi-person-x-fill me-1"></i> Absent
                                        </button>
                                    </form>
                                    <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                        @csrf
                                        <input type="hidden" name="outcome" value="failed">
                                        <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" title="Something went wrong">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Failed
                                        </button>
                                    </form>
                                </div>
                            @elseif($appt->status === 'completed')
                                <span class="text-success fw-semibold" style="font-size:.82rem;"><i class="bi bi-check-circle-fill"></i> Successful</span>
                            @elseif($appt->status === 'no_show')
                                <span class="fw-semibold" style="font-size:.82rem;color:#f59e0b;"><i class="bi bi-person-x-fill"></i> Absent</span>
                            @elseif($appt->status === 'failed')
                                <span class="text-danger fw-semibold" style="font-size:.82rem;"><i class="bi bi-exclamation-triangle-fill"></i> Failed</span>
                            @else
                                <span class="text-muted" style="font-size:.82rem;">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-check d-block"></i>
            <p class="mb-0">No appointments scheduled for today</p>
        </div>
    @endif
</div>

{{-- ── THIS WEEK ─────────────────────────────────────────── --}}
<div class="dash-card">
    <div class="card-header-custom">
        <h6><i class="bi bi-calendar-week me-2" style="color:var(--dark-blue);"></i>This Week ({{ $weekStart->format('M d') }} – {{ $weekEnd->format('M d, Y') }})</h6>
        <span class="text-muted" style="font-size:.82rem;">{{ $weeklyAppointments->count() }} appointment(s) — excluding today</span>
    </div>

    @if($weeklyAppointments->count())
        @php $groupedByDay = $weeklyAppointments->groupBy(fn($a) => $a->appointment_date->format('Y-m-d')); @endphp
        @foreach($groupedByDay as $date => $dayAppts)
            @php $dateObj = \Carbon\Carbon::parse($date); @endphp
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill bg-secondary" style="font-size:.78rem;">
                        {{ $dateObj->format('l') }}
                    </span>
                    <span class="text-muted" style="font-size:.82rem;">{{ $dateObj->format('M d, Y') }}</span>
                </div>
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
                        @foreach($dayAppts as $appt)
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
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-x d-block"></i>
            <p class="mb-0">No other appointments this week</p>
        </div>
    @endif
</div>

@endsection

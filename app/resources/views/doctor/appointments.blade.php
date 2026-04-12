@extends('doctor.layout')

@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">Appointments</li>
@endsection

@section('content')
<!-- ── NEEDS ATTENDANCE MARKING ────────────────────────── -->
@if($unmarkedAppointments->count())
<div class="dash-card" style="border-left: 4px solid #dc3545;">
    <div class="card-header-custom">
        <h6><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Needs Attendance Marking ({{ $unmarkedAppointments->count() }})</h6>
    </div>
    <table class="dash-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Service</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unmarkedAppointments as $appt)
                <tr>
                    <td>{{ $appt->appointment_date->format('M d, Y') }}</td>
                    <td><strong>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</strong></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="patient-avatar">{{ strtoupper(substr($appt->patient->name ?? 'P', 0, 1)) }}</span>
                            {{ $appt->patient->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td>{{ $appt->service->name ?? 'N/A' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                @csrf
                                <input type="hidden" name="attended" value="1">
                                <button class="btn btn-sm btn-teal" title="Mark as Attended">
                                    <i class="bi bi-check-circle me-1"></i> Attended
                                </button>
                            </form>
                            <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                @csrf
                                <input type="hidden" name="attended" value="0">
                                <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" title="Mark as No-Show">
                                    <i class="bi bi-x-circle me-1"></i> No-Show
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- ── THIS WEEK ──────────────────────────────────────── -->
<div class="dash-card">
    <div class="card-header-custom">
        <h6><i class="bi bi-calendar-week me-2" style="color:var(--teal);"></i>This Week ({{ $weekStart->format('M d') }} – {{ $weekEnd->format('M d, Y') }})</h6>
        <span class="text-muted" style="font-size:.82rem;">{{ $weeklyAppointments->count() }} appointment(s)</span>
    </div>

    @if($weeklyAppointments->count())
        @php $groupedByDay = $weeklyAppointments->groupBy(fn($a) => $a->appointment_date->format('Y-m-d')); @endphp
        @foreach($groupedByDay as $date => $dayAppts)
            @php $dateObj = \Carbon\Carbon::parse($date); @endphp
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill {{ $dateObj->isToday() ? 'bg-success' : 'bg-secondary' }}" style="font-size:.78rem;">
                        {{ $dateObj->isToday() ? 'TODAY' : $dateObj->format('l') }}
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
                            <th>Action</th>
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
                                <td>
                                    @if($appt->status === 'scheduled' && is_null($appt->attended) && $appt->appointment_date <= $today)
                                        <div class="d-flex gap-2">
                                            <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                                @csrf
                                                <input type="hidden" name="attended" value="1">
                                                <button class="btn btn-sm btn-teal"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <form action="/doctor/appointments/{{ $appt->id }}/mark-attendance" method="POST">
                                                @csrf
                                                <input type="hidden" name="attended" value="0">
                                                <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;"><i class="bi bi-x-lg"></i></button>
                                            </form>
                                        </div>
                                    @elseif($appt->attended === true)
                                        <span class="text-success fw-semibold" style="font-size:.82rem;"><i class="bi bi-check-circle-fill"></i> Attended</span>
                                    @elseif($appt->attended === false)
                                        <span class="text-danger fw-semibold" style="font-size:.82rem;"><i class="bi bi-x-circle-fill"></i> No-Show</span>
                                    @else
                                        <span class="text-muted" style="font-size:.82rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-x d-block"></i>
            <p class="mb-0">No appointments this week</p>
        </div>
    @endif
</div>

<!-- ── UPCOMING ───────────────────────────────────────── -->
<div class="dash-card">
    <div class="card-header-custom">
        <h6><i class="bi bi-arrow-right-circle me-2" style="color:var(--dark-blue);"></i>Upcoming Appointments</h6>
        <span class="text-muted" style="font-size:.82rem;">{{ $upcomingAppointments->count() }} upcoming</span>
    </div>

    @if($upcomingAppointments->count())
        <table class="dash-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upcomingAppointments as $appt)
                    <tr>
                        <td>
                            <strong>{{ $appt->appointment_date->format('M d') }}</strong>
                            <br><small class="text-muted">{{ $appt->appointment_date->format('l') }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="patient-avatar">{{ strtoupper(substr($appt->patient->name ?? 'P', 0, 1)) }}</span>
                                {{ $appt->patient->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td>{{ $appt->service->name ?? 'N/A' }}</td>
                        <td><span class="badge-status badge-{{ $appt->status }}">{{ ucfirst($appt->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-plus d-block"></i>
            <p class="mb-0">No upcoming appointments</p>
        </div>
    @endif
</div>
@endsection

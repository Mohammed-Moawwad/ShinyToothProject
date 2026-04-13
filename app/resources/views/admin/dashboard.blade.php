@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">

    {{-- Row 1: Main KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-people-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(0,201,177,0.13);color:#00c9b1;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-num">{{ $totalPatients }}</div>
                <div class="stat-lbl">Total Patients</div>
                <div class="stat-hint">Registered in system</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-person-badge-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(96,165,250,0.13);color:#60a5fa;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-num">{{ $totalDentists }}</div>
                <div class="stat-lbl">Total Dentists</div>
                <div class="stat-hint">Active practitioners</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-calendar2-check-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(192,132,252,0.13);color:#c084fc;">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
                <div class="stat-num">{{ $totalAppointments }}</div>
                <div class="stat-lbl">Appointments</div>
                <div class="stat-hint">All time total</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-cash-stack stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(74,222,128,0.13);color:#4ade80;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-num">${{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-lbl">Total Revenue</div>
                <div class="stat-hint">All collected payments</div>
            </div>
        </div>
    </div>

    {{-- Row 2: Status KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #4ade80;">
                <i class="bi bi-check-circle-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(74,222,128,0.13);color:#4ade80;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-num" style="color:#4ade80;">{{ $completedAppointments }}</div>
                <div class="stat-lbl">Completed</div>
                <div class="stat-hint">Successfully done</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #f87171;">
                <i class="bi bi-x-circle-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(248,113,113,0.13);color:#f87171;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-num" style="color:#f87171;">{{ $cancelledAppointments }}</div>
                <div class="stat-lbl">Cancelled</div>
                <div class="stat-hint">Appointments cancelled</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #facc15;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(250,204,21,0.13);color:#facc15;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-num" style="color:#facc15;">${{ number_format($pendingPayments, 0) }}</div>
                <div class="stat-lbl">Pending Payments</div>
                <div class="stat-hint">Awaiting collection</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #60a5fa;">
                <i class="bi bi-person-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(96,165,250,0.13);color:#60a5fa;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="stat-num" style="color:#60a5fa;">{{ $totalUsers }}</div>
                <div class="stat-lbl">Total Users</div>
                <div class="stat-hint">System accounts</div>
            </div>
        </div>
    </div>

    {{-- Row 3: Tables --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">
                        <i class="bi bi-calendar2-check-fill"></i>Recent Appointments
                    </div>
                    <a href="{{ route('admin.appointments') }}" class="view-all-link">View All &rarr;</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Dentist</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments as $appointment)
                            <tr>
                                <td><strong>{{ $appointment->patient->name ?? 'N/A' }}</strong></td>
                                <td>{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge-status {{ strtolower($appointment->status) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4" style="color:var(--text-muted);">
                                    No appointments yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">
                        <i class="bi bi-credit-card-2-front-fill"></i>Recent Payments
                    </div>
                    <a href="{{ route('admin.payments') }}" class="view-all-link">View All &rarr;</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td><strong>{{ $payment->patient->name ?? 'N/A' }}</strong></td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge-status {{ strtolower($payment->status) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4" style="color:var(--text-muted);">
                                    No payments yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

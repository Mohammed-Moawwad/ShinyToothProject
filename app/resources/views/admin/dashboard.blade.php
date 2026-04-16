@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">

    {{-- Row 1: Main KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-people-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3; color:#059386;">
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
                <div class="stat-badge" style="background:#e7f1ff; color:#0056b3;">
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
                <div class="stat-badge" style="background:#f0ecff; color:#6f42c1;">
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
                <div class="stat-badge" style="background:#d4f5e4; color:#0f6b3a;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-num">SAR {{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-lbl">Total Revenue</div>
                <div class="stat-hint">All collected payments</div>
            </div>
        </div>
    </div>

    {{-- Row 2: Status KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #0f6b3a;">
                <i class="bi bi-check-circle-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#d4f5e4; color:#0f6b3a;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-num" style="color:#0f6b3a;">{{ $completedAppointments }}</div>
                <div class="stat-lbl">Completed</div>
                <div class="stat-hint">Successfully done</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #c0392b;">
                <i class="bi bi-x-circle-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fde8e8; color:#c0392b;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-num" style="color:#c0392b;">{{ $cancelledAppointments }}</div>
                <div class="stat-lbl">Cancelled</div>
                <div class="stat-hint">Appointments cancelled</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #b86e00;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fff4e5; color:#b86e00;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-num" style="color:#b86e00;">SAR {{ number_format($pendingPayments, 0) }}</div>
                <div class="stat-lbl">Pending Payments</div>
                <div class="stat-hint">Awaiting collection</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left: 3px solid #0056b3;">
                <i class="bi bi-person-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e7f1ff; color:#0056b3;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="stat-num" style="color:#0056b3;">{{ $totalUsers }}</div>
                <div class="stat-lbl">Total Users</div>
                <div class="stat-hint">System accounts</div>
            </div>
        </div>
    </div>

    {{-- Row 3: Tables --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">
                        <i class="bi bi-calendar2-check-fill"></i>Recent Appointments
                    </div>
                    <a href="{{ route('admin.appointments') }}" class="view-all-link">View All</a>
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
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($appointment->patient->name ?? 'P', 0, 1)) }}</span>
                                        <strong>{{ $appointment->patient->name ?? 'N/A' }}</strong>
                                    </div>
                                </td>
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
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bi bi-calendar-x d-block"></i>
                                        <p class="mb-0">No appointments yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">
                        <i class="bi bi-credit-card-2-front-fill"></i>Recent Payments
                    </div>
                    <a href="{{ route('admin.payments') }}" class="view-all-link">View All</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($payment->patient->name ?? 'P', 0, 1)) }}</span>
                                        <div>
                                            <strong>{{ $payment->patient->name ?? 'N/A' }}</strong>
                                            <div style="font-size:.78rem;color:#6c757d;">{{ $payment->payment_date?->format('M d, Y') ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong style="color:#059386;">SAR {{ number_format($payment->amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge-status {{ strtolower($payment->status) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-credit-card d-block"></i>
                                        <p class="mb-0">No payments yet</p>
                                    </div>
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

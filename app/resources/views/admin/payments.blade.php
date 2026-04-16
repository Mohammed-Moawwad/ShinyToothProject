@extends('admin.layout')
@section('page-title', 'Payments & Financial Reports')
@section('content')
<div class="container-fluid px-0">

    {{-- Financial Overview --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #0f6b3a;">
                <i class="bi bi-cash-stack stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-num" style="color:#059386;">SAR {{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-lbl">Total Revenue</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #b86e00;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-num" style="color:#b86e00;">SAR {{ number_format($pendingAmount, 0) }}</div>
                <div class="stat-lbl">Pending Payments</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #c0392b;">
                <i class="bi bi-x-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-x-circle"></i></div>
                <div class="stat-num" style="color:#c0392b;">SAR {{ number_format($failedAmount, 0) }}</div>
                <div class="stat-lbl">Failed Payments</div>
            </div>
        </div>
    </div>

    {{-- Status Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-check-double stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-check-double"></i></div>
                <div class="stat-num">{{ $statuses['completed'] ?? 0 }}</div>
                <div class="stat-lbl">Completed</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-hourglass stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-hourglass"></i></div>
                <div class="stat-num">{{ $statuses['pending'] ?? 0 }}</div>
                <div class="stat-lbl">Pending</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-x-circle-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-x-circle-fill"></i></div>
                <div class="stat-num">{{ $statuses['failed'] ?? 0 }}</div>
                <div class="stat-lbl">Failed</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Patient</label>
                <form method="GET" action="{{ route('admin.payments') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Name or Email..." value="{{ $search }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <form method="GET" action="{{ route('admin.payments') }}">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="all"       {{ $status === 'all'       ? 'selected' : '' }}>All</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending"   {{ $status === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="failed"    {{ $status === 'failed'    ? 'selected' : '' }}>Failed</option>
                    </select>
                </form>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <form method="GET" action="{{ route('admin.payments') }}" class="d-flex gap-2">
                    <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-filter"></i></button>
                </form>
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <form method="GET" action="{{ route('admin.payments') }}" class="d-flex gap-2">
                    <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-filter"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-credit-card-2-front-fill"></i>All Payments</div>
            <span class="count-badge">{{ $payments->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th><th>Appointment</th><th>Amount</th><th>Date</th><th>Method</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <strong>{{ $payment->patient->name ?? 'N/A' }}</strong><br>
                                <span style="font-size:0.75rem;color:var(--text-muted);">{{ $payment->patient->email ?? '' }}</span>
                            </td>
                            <td>
                                @if($payment->appointment)
                                    {{ $payment->appointment->appointment_date->format('M d, Y') }}<br>
                                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $payment->appointment->service->name ?? '' }}</span>
                                @else � @endif
                            </td>
                            <td><strong style="color:#059386;">SAR {{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_date?->format('M d, Y') ?? '�' }}</td>
                            <td>{{ ucfirst($payment->payment_method ?? '�') }}</td>
                            <td><span class="badge-status {{ strtolower($payment->status) }}">{{ ucfirst($payment->status) }}</span></td>
                            <td>
                                <button class="btn-info" data-bs-toggle="modal" data-bs-target="#payModal{{ $payment->id }}">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                                <div class="modal fade" id="payModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Payment Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Patient:</strong> {{ $payment->patient->name ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $payment->patient->email ?? 'N/A' }}</p>
                                                <p><strong>Amount:</strong> SAR {{ number_format($payment->amount, 2) }}</p>
                                                <p><strong>Method:</strong> {{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                                                <p><strong>Date:</strong> {{ $payment->payment_date?->format('M d, Y H:i') ?? 'N/A' }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
                                                @if($payment->appointment)
                                                    <p><strong>Appointment:</strong> {{ $payment->appointment->appointment_date->format('M d, Y') }}</p>
                                                    <p><strong>Service:</strong> {{ $payment->appointment->service->name ?? 'N/A' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $payments->appends(['search' => $search, 'status' => $status, 'date_from' => $dateFrom, 'date_to' => $dateTo])->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

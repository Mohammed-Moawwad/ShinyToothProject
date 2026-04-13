@extends('admin.layout')
@section('page-title', 'Appointments Management')
@section('content')
<div class="container-fluid px-0">

    {{-- Status Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #facc15;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(250,204,21,0.13);color:#facc15;"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-num" style="color:#facc15;">{{ $statuses['pending'] ?? 0 }}</div>
                <div class="stat-lbl">Pending</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #60a5fa;">
                <i class="bi bi-check-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(96,165,250,0.13);color:#60a5fa;"><i class="bi bi-check-circle"></i></div>
                <div class="stat-num" style="color:#60a5fa;">{{ $statuses['confirmed'] ?? 0 }}</div>
                <div class="stat-lbl">Confirmed</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #4ade80;">
                <i class="bi bi-check-double stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(74,222,128,0.13);color:#4ade80;"><i class="bi bi-check-double"></i></div>
                <div class="stat-num" style="color:#4ade80;">{{ $statuses['completed'] ?? 0 }}</div>
                <div class="stat-lbl">Completed</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #f87171;">
                <i class="bi bi-x-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(248,113,113,0.13);color:#f87171;"><i class="bi bi-x-circle"></i></div>
                <div class="stat-num" style="color:#f87171;">{{ $statuses['cancelled'] ?? 0 }}</div>
                <div class="stat-lbl">Cancelled</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Patient</label>
                <form method="GET" action="{{ route('admin.appointments') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Name or Email..." value="{{ $search }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <form method="GET" action="{{ route('admin.appointments') }}">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="all"       {{ $status === 'all'       ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending"   {{ $status === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sort By</label>
                <form method="GET" action="{{ route('admin.appointments') }}">
                    <select class="form-select" name="sort" onchange="this.form.submit()">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-calendar2-check-fill"></i>All Appointments</div>
            <span class="count-badge">{{ $appointments->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th><th>Dentist</th><th>Service</th><th>Date & Time</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <strong>{{ $appointment->patient->name ?? 'N/A' }}</strong><br>
                                <span style="font-size:0.75rem;color:var(--text-muted);">{{ $appointment->patient->email ?? '' }}</span>
                            </td>
                            <td>{{ $appointment->dentist->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                            <td>
                                {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                <span style="font-size:0.75rem;color:var(--text-muted);">{{ $appointment->appointment_time->format('H:i') }}</span>
                            </td>
                            <td><span class="badge-status {{ strtolower($appointment->status) }}">{{ ucfirst($appointment->status) }}</span></td>
                            <td>
                                <button class="btn-info" data-bs-toggle="modal" data-bs-target="#apptModal{{ $appointment->id }}">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                                <div class="modal fade" id="apptModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Appointment Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Patient:</strong> {{ $appointment->patient->name ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $appointment->patient->email ?? 'N/A' }}</p>
                                                <p><strong>Phone:</strong> {{ $appointment->patient->phone ?? 'N/A' }}</p>
                                                <p><strong>Dentist:</strong> {{ $appointment->dentist->name ?? 'N/A' }}</p>
                                                <p><strong>Service:</strong> {{ $appointment->service->name ?? 'N/A' }}</p>
                                                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                                                <p><strong>Time:</strong> {{ $appointment->appointment_time->format('H:i') }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                @if($appointment->notes)
                                                    <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
                                                @endif
                                                @if($appointment->payment)
                                                    <p><strong>Payment:</strong> {{ ucfirst($appointment->payment->status) }} — ${{ number_format($appointment->payment->amount, 2) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No appointments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $appointments->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

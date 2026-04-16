@extends('admin.layout')
@section('page-title', 'Vacation Requests')

@section('content')
<div class="container-fluid px-0">

    {{-- Overview Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #059386;">
                <i class="bi bi-calendar-check stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-num">{{ $totalCount }}</div>
                <div class="stat-lbl">Total Requests</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #b86e00;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-num" style="color:#b86e00;">{{ $pendingCount }}</div>
                <div class="stat-lbl">Pending</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #0f6b3a;">
                <i class="bi bi-check-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-check-circle"></i></div>
                <div class="stat-num" style="color:#0f6b3a;">{{ $approvedCount }}</div>
                <div class="stat-lbl">Approved</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Filter by Status</label>
                <form method="GET" action="{{ route('admin.vacations') }}">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="all"      {{ $status === 'all'      ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-calendar2-week-fill"></i> Vacation Requests</div>
            <span class="count-badge">{{ $vacations->count() }} results</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Doctor</th><th>Type</th><th>Dates</th><th>Duration</th><th>Reason</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacations as $vac)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar" style="background:linear-gradient(135deg,#003263,#059386);">{{ strtoupper(substr($vac->dentist->name ?? 'D', 0, 1)) }}</div>
                                <div>
                                    <strong>Dr. {{ $vac->dentist->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $vac->dentist->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($vac->type === 'full_day')
                                <span style="background:#e7f1ff;color:#0056b3;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:6px;">Full Day</span>
                            @else
                                <span style="background:#fff4e5;color:#b86e00;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:6px;">Partial Day</span>
                            @endif
                        </td>
                        <td>
                            {{ $vac->start_date->format('M d, Y') }}
                            @if($vac->type === 'full_day' && $vac->end_date && !$vac->start_date->eq($vac->end_date))
                                → {{ $vac->end_date->format('M d, Y') }}
                            @endif
                            @if($vac->type === 'partial_day' && $vac->start_time && $vac->end_time)
                                <br><span style="font-size:0.75rem;color:var(--text-muted);">{{ $vac->start_time }} – {{ $vac->end_time }}</span>
                            @endif
                        </td>
                        <td>
                            @if($vac->type === 'full_day')
                                {{ $vac->days_count }} day{{ $vac->days_count > 1 ? 's' : '' }}
                            @else
                                Partial
                            @endif
                        </td>
                        <td style="max-width:200px;font-size:0.82rem;">{{ Str::limit($vac->reason, 60) }}</td>
                        <td>
                            @php
                                $sc = ['pending' => 'pending', 'approved' => 'completed', 'rejected' => 'cancelled'];
                            @endphp
                            <span class="badge-status {{ $sc[$vac->status] ?? 'pending' }}">{{ ucfirst($vac->status) }}</span>
                        </td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info" data-bs-toggle="modal" data-bs-target="#vacDetail{{ $vac->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($vac->status === 'pending')
                                <form method="POST" action="{{ route('admin.vacations.approve', $vac->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-teal" onclick="return confirm('Approve this vacation request?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <button class="btn-outline-teal" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $vac->id }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Details Modal --}}
                    <div class="modal fade" id="vacDetail{{ $vac->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-calendar2-week me-2"></i>Vacation Request #{{ $vac->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Doctor:</strong> Dr. {{ $vac->dentist->name ?? 'N/A' }}</p>
                                    <p><strong>Type:</strong> {{ $vac->type === 'full_day' ? 'Full Day' : 'Partial Day' }}</p>
                                    <p><strong>Start Date:</strong> {{ $vac->start_date->format('M d, Y') }}</p>
                                    @if($vac->type === 'full_day')
                                        <p><strong>End Date:</strong> {{ $vac->end_date?->format('M d, Y') ?? '—' }}</p>
                                        <p><strong>Duration:</strong> {{ $vac->days_count }} day{{ $vac->days_count > 1 ? 's' : '' }}</p>
                                    @else
                                        <p><strong>Time:</strong> {{ $vac->start_time }} – {{ $vac->end_time }}</p>
                                    @endif
                                    <p><strong>Status:</strong> <span class="badge-status {{ $sc[$vac->status] ?? 'pending' }}">{{ ucfirst($vac->status) }}</span></p>
                                    <p><strong>Reason:</strong></p>
                                    <p style="background:var(--body-bg);padding:10px;border-radius:8px;font-size:0.85rem;">{{ $vac->reason }}</p>
                                    @if($vac->admin_note)
                                        <p><strong>Admin Note:</strong></p>
                                        <p style="background:#fde8e8;padding:10px;border-radius:8px;font-size:0.85rem;">{{ $vac->admin_note }}</p>
                                    @endif
                                    <p><strong>Submitted:</strong> {{ $vac->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reject Modal --}}
                    @if($vac->status === 'pending')
                    <div class="modal fade" id="rejectModal{{ $vac->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Vacation Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.vacations.reject', $vac->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Reject vacation request from <strong>Dr. {{ $vac->dentist->name ?? 'N/A' }}</strong>?</p>
                                        <p style="font-size:0.82rem;color:var(--text-muted);">{{ $vac->start_date->format('M d, Y') }} @if($vac->type === 'full_day' && $vac->end_date)→ {{ $vac->end_date->format('M d, Y') }}@endif</p>
                                        <div class="mb-3">
                                            <label class="form-label">Admin Note (optional)</label>
                                            <textarea name="admin_note" class="form-control" rows="3" placeholder="Reason for rejection..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" style="background:#fde8e8;border:1px solid #f5c6c6;color:#c0392b;font-size:0.82rem;font-weight:600;padding:6px 16px;border-radius:8px;cursor:pointer;">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-calendar2-week"></i>
                                <p>No vacation requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

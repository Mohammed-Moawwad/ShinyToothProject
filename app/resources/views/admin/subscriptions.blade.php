@extends('admin.layout')
@section('page-title', 'Subscriptions Management')

@section('content')
<div class="container-fluid px-0">

    {{-- Overview Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #059386;">
                <i class="bi bi-collection stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-collection"></i></div>
                <div class="stat-num">{{ $totalCount }}</div>
                <div class="stat-lbl">Total Subscriptions</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #0f6b3a;">
                <i class="bi bi-check-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-check-circle"></i></div>
                <div class="stat-num" style="color:#0f6b3a;">{{ $activeCount }}</div>
                <div class="stat-lbl">Active</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card" style="border-left:3px solid #b86e00;">
                <i class="bi bi-exclamation-triangle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-num" style="color:#b86e00;">{{ $pendingActions }}</div>
                <div class="stat-lbl">Pending Actions</div>
            </div>
        </div>
    </div>

    {{-- Pending Admin Actions --}}
    @php $pending = $subscriptions->where('admin_action_status', '!=', 'none'); @endphp
    @if($pending->count())
    <div class="panel mb-4">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-bell-fill"></i> Pending Admin Actions</div>
            <span class="count-badge">{{ $pending->count() }} pending</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th><th>Doctor</th><th>Action</th><th>Reason</th><th>Date</th><th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $sub)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar">{{ strtoupper(substr($sub->patient->name ?? 'N', 0, 1)) }}</div>
                                <div>
                                    <strong>{{ $sub->patient->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $sub->patient->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>Dr. {{ $sub->dentist->name ?? 'N/A' }}</td>
                        <td>
                            @if($sub->admin_action_status === 'pending_cancel')
                                <span class="badge-status pending">Cancel Request</span>
                            @elseif($sub->admin_action_status === 'pending_switch')
                                <span class="badge-status pending">Switch to Dr. {{ $sub->switchToDentist->name ?? '?' }}</span>
                            @elseif($sub->admin_action_status === 'pending_removal')
                                <span class="badge-status cancelled">Removal Request</span>
                            @endif
                        </td>
                        <td style="max-width:200px;">
                            {{ $sub->patient_cancel_reason ?? $sub->patient_switch_reason ?? $sub->doctor_removal_reason ?? '—' }}
                        </td>
                        <td>{{ $sub->updated_at?->format('M d, Y') ?? '—' }}</td>
                        <td style="white-space:nowrap;">
                            <form method="POST" action="{{ route('admin.subscriptions.approve', $sub->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-teal" onclick="return confirm('Approve this action?')">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.subscriptions.reject', $sub->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:#fde8e8;border:1px solid #f5c6c6;color:#c0392b;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;" onclick="return confirm('Reject this action?')">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- All Subscriptions --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-card-list"></i> All Subscriptions</div>
            <span class="count-badge">{{ $totalCount }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th><th>Doctor</th><th>Status</th><th>Plan</th><th>Requested</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar">{{ strtoupper(substr($sub->patient->name ?? 'N', 0, 1)) }}</div>
                                <div>
                                    <strong>{{ $sub->patient->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $sub->patient->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>Dr. {{ $sub->dentist->name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusMap = [
                                    'active'    => 'completed',
                                    'pending'   => 'pending',
                                    'idle'      => 'pending',
                                    'completed' => 'completed',
                                    'cancelled' => 'cancelled',
                                    'switched'  => 'pending',
                                    'removed'   => 'cancelled',
                                    'rejected'  => 'cancelled',
                                ];
                            @endphp
                            <span class="badge-status {{ $statusMap[$sub->status] ?? 'pending' }}">{{ ucfirst($sub->status) }}</span>
                        </td>
                        <td>
                            @if($sub->plan)
                                <strong>{{ $sub->plan->title }}</strong><br>
                                <span style="font-size:0.75rem;color:#059386;">SAR {{ number_format($sub->plan->total_price, 2) }}</span>
                            @else
                                <span style="color:var(--text-muted);">No plan</span>
                            @endif
                        </td>
                        <td>{{ $sub->requested_at?->format('M d, Y') ?? '—' }}</td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info" data-bs-toggle="modal" data-bs-target="#subDetail{{ $sub->id }}">
                                <i class="bi bi-eye"></i> Details
                            </button>
                            @if(!in_array($sub->status, ['removed', 'cancelled', 'rejected']))
                                <button class="btn-outline-teal" data-bs-toggle="modal" data-bs-target="#removeModal{{ $sub->id }}">
                                    <i class="bi bi-person-dash"></i> Remove
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Details Modal --}}
                    <div class="modal fade" id="subDetail{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Subscription #{{ $sub->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Patient:</strong> {{ $sub->patient->name ?? 'N/A' }} ({{ $sub->patient->email ?? '' }})</p>
                                    <p><strong>Doctor:</strong> Dr. {{ $sub->dentist->name ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> <span class="badge-status {{ $statusMap[$sub->status] ?? 'pending' }}">{{ ucfirst($sub->status) }}</span></p>
                                    <p><strong>Admin Action:</strong> {{ $sub->admin_action_status === 'none' ? 'None' : ucfirst(str_replace('_', ' ', $sub->admin_action_status)) }}</p>
                                    <p><strong>Requested:</strong> {{ $sub->requested_at?->format('M d, Y H:i') ?? '—' }}</p>
                                    <p><strong>Accepted:</strong> {{ $sub->accepted_at?->format('M d, Y H:i') ?? '—' }}</p>
                                    <p><strong>Completed:</strong> {{ $sub->completed_at?->format('M d, Y H:i') ?? '—' }}</p>
                                    @if($sub->plan)
                                        <hr>
                                        <p><strong>Plan:</strong> {{ $sub->plan->title }}</p>
                                        <p><strong>Total Price:</strong> SAR {{ number_format($sub->plan->total_price, 2) }}</p>
                                    @endif
                                    @if($sub->rejection_reason)
                                        <hr><p><strong>Rejection Reason:</strong> {{ $sub->rejection_reason }}</p>
                                    @endif
                                    @if($sub->patient_cancel_reason)
                                        <p><strong>Cancel Reason:</strong> {{ $sub->patient_cancel_reason }}</p>
                                    @endif
                                    @if($sub->patient_switch_reason)
                                        <p><strong>Switch Reason:</strong> {{ $sub->patient_switch_reason }}</p>
                                    @endif
                                    @if($sub->admin_removal_reason)
                                        <p><strong>Admin Removal Reason:</strong> {{ $sub->admin_removal_reason }}</p>
                                    @endif
                                    @if($sub->doctor_removal_reason)
                                        <p><strong>Doctor Removal Reason:</strong> {{ $sub->doctor_removal_reason }}</p>
                                    @endif
                                    @if($sub->bonus)
                                        <hr><p><strong>Bonus:</strong> SAR {{ number_format($sub->bonus->amount ?? 0, 2) }}</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Remove Modal --}}
                    @if(!in_array($sub->status, ['removed', 'cancelled', 'rejected']))
                    <div class="modal fade" id="removeModal{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-person-dash me-2"></i>Remove Patient</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.subscriptions.remove', $sub->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Remove <strong>{{ $sub->patient->name ?? 'N/A' }}</strong> from Dr. {{ $sub->dentist->name ?? 'N/A' }}'s subscription?</p>
                                        <div class="mb-3">
                                            <label class="form-label">Reason <span style="color:#c0392b;">*</span></label>
                                            <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this patient is being removed..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" style="background:#fde8e8;border:1px solid #f5c6c6;color:#c0392b;font-size:0.82rem;font-weight:600;padding:6px 16px;border-radius:8px;cursor:pointer;">
                                            <i class="bi bi-person-dash"></i> Remove Patient
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-collection"></i>
                                <p>No subscriptions found</p>
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

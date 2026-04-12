@extends('doctor.layout')

@section('title', 'My Patients')
@section('page-title', 'Subscription Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Patients</li>
@endsection

@section('content')
<!-- ── FILTER TABS ─────────────────────────────────────── -->
<div class="dash-card" style="padding:16px 24px;">
    <div class="d-flex flex-wrap gap-2">
        @php $filters = ['all'=>'All','pending'=>'Pending','active'=>'Active / Idle','completed'=>'Completed','history'=>'History']; @endphp
        @foreach($filters as $key => $label)
            <a href="/doctor/subscriptions-view?dentist={{ $dentist->id }}&filter={{ $key }}"
               class="btn btn-sm {{ $filter === $key ? 'btn-teal' : 'btn-outline-teal' }}">
                {{ $label }}
                @if($key === 'pending')
                    @php $pc = $subscriptions->where('status','pending')->count(); @endphp
                    @if($filter !== 'pending' && ($pc2 = \App\Models\DoctorSubscription::where('dentist_id',$dentist->id)->where('status','pending')->count()) > 0)
                        <span class="badge bg-danger ms-1">{{ $pc2 }}</span>
                    @endif
                @endif
            </a>
        @endforeach
    </div>
</div>

@if($subscriptions->count())
    @foreach($subscriptions as $sub)
        <div class="dash-card">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <!-- Patient info -->
                <div class="d-flex align-items-center gap-3">
                    <span class="patient-avatar" style="width:48px;height:48px;font-size:1.1rem;">
                        {{ strtoupper(substr($sub->patient->name ?? 'P', 0, 1)) }}
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $sub->patient->name ?? 'Unknown Patient' }}</h6>
                        <small class="text-muted">{{ $sub->patient->email ?? '' }} &middot; {{ $sub->patient->phone ?? '' }}</small>
                        <br>
                        <small class="text-muted">Requested {{ $sub->created_at->format('M d, Y') }}</small>
                    </div>
                </div>

                <!-- Status badge + actions -->
                <div class="text-end">
                    <span class="badge-status badge-{{ $sub->status }}">
                        @if($sub->status === 'active') <i class="bi bi-check-circle-fill"></i>
                        @elseif($sub->status === 'pending') <i class="bi bi-hourglass-split"></i>
                        @elseif($sub->status === 'idle') <i class="bi bi-pause-circle-fill"></i>
                        @elseif($sub->status === 'completed') <i class="bi bi-trophy-fill"></i>
                        @elseif($sub->status === 'rejected') <i class="bi bi-x-circle-fill"></i>
                        @else <i class="bi bi-dash-circle"></i>
                        @endif
                        {{ ucfirst($sub->status) }}
                    </span>

                    @if($sub->admin_action_status && $sub->admin_action_status !== 'none')
                        <br><small class="text-warning fw-semibold mt-1 d-inline-block">
                            <i class="bi bi-info-circle"></i> {{ ucfirst(str_replace('_', ' ', $sub->admin_action_status)) }}
                        </small>
                    @endif
                </div>
            </div>

            <!-- ── ACTION BUTTONS ─────────────────────────── -->
            <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                @if($sub->status === 'pending')
                    <form action="/doctor/subscriptions/{{ $sub->id }}/accept" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-teal"><i class="bi bi-check-lg me-1"></i>Accept</button>
                    </form>
                    <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $sub->id }}">
                        <i class="bi bi-x-lg me-1"></i>Reject
                    </button>
                @endif

                @if($sub->status === 'active')
                    <a href="/doctor/subscriptions/{{ $sub->id }}/plan?dentist={{ $dentist->id }}" class="btn btn-sm btn-teal">
                        <i class="bi bi-journal-medical me-1"></i>Manage Plan
                    </a>
                    <form action="/doctor/subscriptions/{{ $sub->id }}/idle" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-teal"><i class="bi bi-pause-fill me-1"></i>Set Idle</button>
                    </form>
                    <form action="/doctor/subscriptions/{{ $sub->id }}/complete" method="POST" onsubmit="return confirm('Mark as completed? This cannot be undone.')">
                        @csrf
                        <button class="btn btn-sm btn-outline-teal"><i class="bi bi-check-circle me-1"></i>Complete</button>
                    </form>
                    <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#removeModal{{ $sub->id }}">
                        <i class="bi bi-person-dash me-1"></i>Request Removal
                    </button>
                @endif

                @if($sub->status === 'idle')
                    <a href="/doctor/subscriptions/{{ $sub->id }}/plan?dentist={{ $dentist->id }}" class="btn btn-sm btn-teal">
                        <i class="bi bi-journal-medical me-1"></i>Manage Plan
                    </a>
                    <form action="/doctor/subscriptions/{{ $sub->id }}/active" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-teal"><i class="bi bi-play-fill me-1"></i>Reactivate</button>
                    </form>
                    <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#removeModal{{ $sub->id }}">
                        <i class="bi bi-person-dash me-1"></i>Request Removal
                    </button>
                @endif

                @if($sub->status === 'completed')
                    @if($sub->bonus)
                        <span class="btn btn-sm btn-outline-teal disabled">
                            <i class="bi bi-trophy-fill me-1"></i>Bonus: SAR {{ number_format($sub->bonus->bonus_amount, 2) }}
                        </span>
                    @endif
                    @if($sub->rating)
                        <span class="btn btn-sm btn-outline-teal disabled stars">
                            @for($i=1;$i<=5;$i++) <i class="bi bi-star{{ $i<=$sub->rating->rating ? '-fill' : '' }}"></i> @endfor
                            {{ $sub->rating->rating }}/5
                        </span>
                    @endif
                @endif
            </div>

            <!-- ── PLAN SUMMARY (if exists) ───────────────── -->
            @if($sub->plan && $sub->plan->items->count())
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="fw-semibold text-muted"><i class="bi bi-journal-text me-1"></i>Treatment Plan</small>
                        <small class="fw-bold" style="color:var(--teal);">SAR {{ number_format($sub->plan->total_price, 2) }}</small>
                    </div>
                    @php
                        $total = $sub->plan->items->count();
                        $done  = $sub->plan->items->where('status','completed')->count();
                        $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
                    @endphp
                    <div class="progress" style="height:8px; border-radius:8px;">
                        <div class="progress-bar" style="width:{{ $pct }}%; background:var(--teal); border-radius:8px;"></div>
                    </div>
                    <small class="text-muted">{{ $done }}/{{ $total }} items completed ({{ $pct }}%)</small>
                </div>
            @endif
        </div>

        <!-- Reject modal -->
        @if($sub->status === 'pending')
        <div class="modal fade" id="rejectModal{{ $sub->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px; border:none;">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold">Reject {{ $sub->patient->name ?? 'Patient' }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="/doctor/subscriptions/{{ $sub->id }}/reject" method="POST">
                        @csrf
                        <div class="modal-body">
                            <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Reason for rejecting..." style="border-radius:10px;"></textarea>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                            <button type="submit" class="btn btn-danger" style="border-radius:10px;">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Remove modal -->
        @if(in_array($sub->status, ['active','idle']))
        <div class="modal fade" id="removeModal{{ $sub->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px; border:none;">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold">Request Removal</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="/doctor/subscriptions/{{ $sub->id }}/request-removal" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted" style="font-size:.88rem;">This will send a removal request to administration for review.</p>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Reason for removing this patient..." style="border-radius:10px;"></textarea>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                            <button type="submit" class="btn btn-danger" style="border-radius:10px;">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@else
    <div class="dash-card">
        <div class="empty-state">
            <i class="bi bi-people d-block"></i>
            <h6 class="text-muted mt-2">No subscriptions found</h6>
            <p class="text-muted" style="font-size:.85rem;">
                @if($filter === 'pending') No pending requests at the moment.
                @elseif($filter === 'active') No active subscriptions.
                @elseif($filter === 'completed') No completed subscriptions yet.
                @elseif($filter === 'history') No subscription history.
                @else No subscriptions to display.
                @endif
            </p>
        </div>
    </div>
@endif
@endsection

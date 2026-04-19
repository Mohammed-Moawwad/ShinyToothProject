@extends('admin.layout')
@section('page-title', 'Subscriptions Management')
@section('content')
<style>
/* ── Subscription page styles ───────────────────────────── */
.sub-status-bar { height: 8px; border-radius: 4px; overflow: hidden; background: var(--border); margin-top: .4rem; }
.sub-status-fill { height: 100%; border-radius: 4px; }
.rating-star { color: #f59e0b; font-size: .85rem; }
.rating-star.empty { color: var(--border); }
.plan-item-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .18rem .5rem; border-radius: 10px;
    font-size: .68rem; font-weight: 600;
    border: 1px solid;
}
.plan-item-badge.completed  { background:rgba(16,185,129,.1);  border-color:rgba(16,185,129,.4);  color:#10b981; }
.plan-item-badge.in_progress{ background:rgba(59,130,246,.1);  border-color:rgba(59,130,246,.4);  color:#3b82f6; }
.plan-item-badge.pending    { background:rgba(245,158,11,.1);  border-color:rgba(245,158,11,.4);  color:#f59e0b; }
.top-dentist-row { display:flex; align-items:center; gap:.6rem; padding:.5rem 0; border-bottom:1px solid var(--border); }
.top-dentist-row:last-child { border-bottom:none; }
.top-dentist-avatar {
    width:32px; height:32px; border-radius:50%;
    background: linear-gradient(135deg,#0d9e8a,#3b82f6);
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; color:#fff; flex-shrink:0;
}
</style>

<div class="container-fluid px-0">

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

    {{-- ── Row 1: Core stat cards ─────────────────────────── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(13,158,138,.15);color:#0d9e8a;"><i class="bi bi-collection-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalCount }}</div>
                    <div class="stat-label">Total Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color:#10b981;">{{ $activeCount }}</div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color:#f59e0b;">{{ $pendingCount }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(99,102,241,.15);color:#818cf8;"><i class="bi bi-patch-check-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color:#818cf8;">{{ $completedCount }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Financial + alert cards ─────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(239,68,68,.15);color:#ef4444;"><i class="bi bi-x-circle-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color:#ef4444;">{{ $cancelledCount }}</div>
                    <div class="stat-label">Cancelled / Removed</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(239,68,68,.15);color:#ef4444;"><i class="bi bi-bell-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color:#ef4444;">{{ $pendingActions }}</div>
                    <div class="stat-label">Pending Admin Actions</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-info">
                    <div class="stat-value">SAR {{ number_format($totalRevenue, 0) }}</div>
                    <div class="stat-label">Total Plan Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-star-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $avgRating > 0 ? $avgRating.'/5' : 'N/A' }}</div>
                    <div class="stat-label">Avg. Patient Rating</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Breakdown + Top Dentists panels ────────────────── --}}
    <div class="row g-3 mb-4">
        {{-- Status breakdown --}}
        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-bar-chart-fill"></i> Status Breakdown</div>
                </div>
                <div style="padding:.75rem 1.25rem;">
                    @php
                        $breakdown = [
                            ['label'=>'Active',    'count'=>$activeCount,    'color'=>'#10b981', 'icon'=>'check-circle-fill'],
                            ['label'=>'Pending',   'count'=>$pendingCount,   'color'=>'#f59e0b', 'icon'=>'hourglass-split'],
                            ['label'=>'Completed', 'count'=>$completedCount, 'color'=>'#818cf8', 'icon'=>'patch-check-fill'],
                            ['label'=>'Cancelled / Removed', 'count'=>$cancelledCount, 'color'=>'#ef4444', 'icon'=>'x-circle-fill'],
                        ];
                    @endphp
                    @foreach($breakdown as $b)
                    @php $pct = $totalCount > 0 ? round($b['count'] / $totalCount * 100) : 0; @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-size:.82rem;font-weight:600;color:var(--text-main);">
                                <i class="bi bi-{{ $b['icon'] }}" style="color:{{ $b['color'] }};margin-right:.35rem;"></i>{{ $b['label'] }}
                            </span>
                            <span style="font-size:.78rem;color:var(--text-muted);">{{ $b['count'] }} &nbsp;({{ $pct }}%)</span>
                        </div>
                        <div class="sub-status-bar">
                            <div class="sub-status-fill" style="width:{{ $pct }}%;background:{{ $b['color'] }};"></div>
                        </div>
                    </div>
                    @endforeach

                    @if($avgRating > 0)
                    <div style="border-top:1px solid var(--border);margin-top:1rem;padding-top:1rem;">
                        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:.5rem;">Rating Distribution</div>
                        @for($s=5; $s>=1; $s--)
                        @php $rc = $ratingDist[$s]->cnt ?? 0; $rPct = ($ratingDist->sum('cnt') > 0) ? round($rc / $ratingDist->sum('cnt') * 100) : 0; @endphp
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="font-size:.72rem;color:var(--text-muted);width:14px;">{{ $s }}</span>
                            <i class="bi bi-star-fill" style="color:#f59e0b;font-size:.72rem;"></i>
                            <div class="sub-status-bar" style="flex:1;height:6px;">
                                <div class="sub-status-fill" style="width:{{ $rPct }}%;background:#f59e0b;"></div>
                            </div>
                            <span style="font-size:.70rem;color:var(--text-muted);width:24px;text-align:right;">{{ $rc }}</span>
                        </div>
                        @endfor
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top dentists --}}
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-trophy-fill"></i> Top Dentists by Subscriptions</div>
                </div>
                <div style="padding:.75rem 1.25rem;">
                    @forelse($topDentists as $i => $d)
                    <div class="top-dentist-row">
                        <div class="top-dentist-avatar">{{ strtoupper(substr($d->name, 0, 1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.82rem;font-weight:600;color:var(--text-main);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Dr. {{ $d->name }}</div>
                            <div style="font-size:.70rem;color:var(--text-muted);">{{ $d->sub_count }} subscription{{ $d->sub_count != 1 ? 's' : '' }}</div>
                        </div>
                        <span class="count-badge">{{ $d->sub_count }}</span>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.82rem;">No data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pending Admin Actions ────────────────────────────── --}}
    @php $pending = $subscriptions->where('admin_action_status', '!=', 'none'); @endphp
    @if($pending->count())
    <div class="panel mb-4" style="border:1px solid rgba(239,68,68,.3);">
        <div class="panel-head" style="background:rgba(239,68,68,.06);">
            <div class="panel-head-title" style="color:#ef4444;"><i class="bi bi-bell-fill"></i> Pending Admin Actions</div>
            <span class="count-badge" style="background:rgba(239,68,68,.15);color:#ef4444;border-color:rgba(239,68,68,.3);">{{ $pending->count() }} pending</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr><th>Patient</th><th>Doctor</th><th>Requested Action</th><th>Reason</th><th>Date</th><th>Manage</th></tr>
                </thead>
                <tbody>
                    @foreach($pending as $sub)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar">{{ strtoupper(substr($sub->patient->name ?? 'N', 0, 1)) }}</div>
                                <div>
                                    <strong>{{ $sub->patient->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:.73rem;color:var(--text-muted);">{{ $sub->patient->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>Dr. {{ $sub->dentist->name ?? 'N/A' }}</td>
                        <td>
                            @if($sub->admin_action_status === 'pending_cancel')
                                <span class="badge-status pending"><i class="bi bi-x-circle me-1"></i>Cancel Request</span>
                            @elseif($sub->admin_action_status === 'pending_switch')
                                <span class="badge-status pending"><i class="bi bi-arrow-left-right me-1"></i>Switch &rarr; Dr. {{ $sub->switchToDentist->name ?? '?' }}</span>
                            @elseif($sub->admin_action_status === 'pending_removal')
                                <span class="badge-status cancelled"><i class="bi bi-person-dash me-1"></i>Removal Request</span>
                            @endif
                        </td>
                        <td style="max-width:200px;font-size:.78rem;">
                            {{ $sub->patient_cancel_reason ?? $sub->patient_switch_reason ?? $sub->doctor_removal_reason ?? '—' }}
                        </td>
                        <td style="font-size:.78rem;">{{ $sub->updated_at?->format('M d, Y') ?? '—' }}</td>
                        <td style="white-space:nowrap;">
                            <form method="POST" action="{{ route('admin.subscriptions.approve', $sub->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-teal" onclick="return confirm('Approve this action?')">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.subscriptions.reject', $sub->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444;font-size:.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;" onclick="return confirm('Reject this action?')">
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

    {{-- ── Filter bar ──────────────────────────────────────── --}}
    <div class="panel mb-3" style="padding:1rem 1.25rem;">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="d-flex gap-2 align-items-center flex-wrap">
            <i class="bi bi-funnel" style="color:var(--text-muted);"></i>
            <select class="form-control" name="status" style="max-width:170px;">
                <option value="">All Statuses</option>
                @foreach(['active','pending','idle','completed','cancelled','switched','removed','rejected'] as $st)
                    <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <select class="form-control" name="dentist_id" style="max-width:220px;">
                <option value="">All Dentists</option>
                @foreach($dentists as $d)
                    <option value="{{ $d->id }}" {{ $dentistFilter == $d->id ? 'selected' : '' }}>Dr. {{ $d->name }}</option>
                @endforeach
            </select>
            <button class="btn-primary" type="submit"><i class="bi bi-funnel-fill"></i> Filter</button>
            @if($statusFilter || $dentistFilter)
                <a href="{{ route('admin.subscriptions.index') }}" class="btn-info"><i class="bi bi-x-circle"></i> Clear</a>
            @endif
            <span style="color:var(--text-muted);font-size:.82rem;margin-left:auto;">
                Showing {{ $subscriptions->count() }} subscription{{ $subscriptions->count() != 1 ? 's' : '' }}
            </span>
        </form>
    </div>

    {{-- ── All Subscriptions table ──────────────────────────── --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-card-list"></i> All Subscriptions</div>
            <span class="count-badge">{{ $subscriptions->count() }} shown</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Plan</th>
                        <th>Services</th>
                        <th>Rating</th>
                        <th>Bonus</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
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
                    @forelse($subscriptions as $sub)
                    @php
                        $itemCount     = $sub->plan?->items?->count() ?? 0;
                        $itemsDone     = $sub->plan?->items?->where('status','completed')->count() ?? 0;
                        $itemProgress  = $itemCount > 0 ? round($itemsDone / $itemCount * 100) : 0;
                    @endphp
                    <tr>
                        <td style="font-size:.75rem;color:var(--text-muted);">{{ $sub->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar">{{ strtoupper(substr($sub->patient->name ?? 'N', 0, 1)) }}</div>
                                <div>
                                    <strong style="font-size:.82rem;">{{ $sub->patient->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:.70rem;color:var(--text-muted);">{{ $sub->patient->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.82rem;">Dr. {{ $sub->dentist->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge-status {{ $statusMap[$sub->status] ?? 'pending' }}">{{ ucfirst($sub->status) }}</span>
                            @if($sub->admin_action_status !== 'none')
                                <br><span style="font-size:.65rem;color:#ef4444;"><i class="bi bi-bell-fill"></i> action pending</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->plan)
                                <strong style="font-size:.80rem;">{{ $sub->plan->title }}</strong><br>
                                <span style="font-size:.72rem;color:#10b981;">SAR {{ number_format($sub->plan->total_price, 2) }}</span>
                            @else
                                <span style="color:var(--text-muted);font-size:.78rem;">No plan</span>
                            @endif
                        </td>
                        <td>
                            @if($itemCount > 0)
                                <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:.2rem;">{{ $itemsDone }}/{{ $itemCount }} done</div>
                                <div class="sub-status-bar" style="width:70px;">
                                    <div class="sub-status-fill" style="width:{{ $itemProgress }}%;background:#10b981;"></div>
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:.75rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->rating)
                                @for($s=1;$s<=5;$s++)
                                    <i class="bi bi-star-fill {{ $s <= $sub->rating->rating ? 'rating-star' : 'rating-star empty' }}"></i>
                                @endfor
                            @else
                                <span style="color:var(--text-muted);font-size:.75rem;">—</span>
                            @endif
                        </td>
                        <td style="font-size:.78rem;">
                            @if($sub->bonus)
                                <span style="color:#10b981;font-weight:600;">SAR {{ number_format($sub->bonus->bonus_amount, 2) }}</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size:.75rem;color:var(--text-muted);">{{ $sub->requested_at?->format('M d, Y') ?? '—' }}</td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info" style="font-size:.73rem;padding:.28rem .6rem;" data-bs-toggle="modal" data-bs-target="#subDetail{{ $sub->id }}">
                                <i class="bi bi-eye"></i> Details
                            </button>
                            @if(!in_array($sub->status, ['removed', 'cancelled', 'rejected']))
                                <button style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444;font-size:.73rem;font-weight:600;padding:.28rem .6rem;border-radius:6px;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#removeModal{{ $sub->id }}">
                                    <i class="bi bi-person-dash"></i> Remove
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- ── Details Modal ─────────────────────── --}}
                    <div class="modal fade" id="subDetail{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                                    <div>
                                        <h5 class="modal-title mb-0">Subscription #{{ $sub->id }}</h5>
                                        <small style="color:var(--text-muted);">{{ $sub->patient->name ?? 'N/A' }} &rarr; Dr. {{ $sub->dentist->name ?? 'N/A' }}</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Status row --}}
                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div class="svc-stat-tile" style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                <span class="badge-status {{ $statusMap[$sub->status] ?? 'pending' }}">{{ ucfirst($sub->status) }}</span>
                                                <div style="font-size:.63rem;color:var(--text-muted);margin-top:.25rem;">Status</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                <span style="font-size:.88rem;font-weight:700;color:var(--text-main);">{{ $sub->admin_action_status === 'none' ? 'None' : ucfirst(str_replace('_',' ',$sub->admin_action_status)) }}</span>
                                                <div style="font-size:.63rem;color:var(--text-muted);">Admin Action</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                @if($sub->rating)
                                                    <div>@for($s=1;$s<=5;$s++)<i class="bi bi-star-fill {{ $s <= $sub->rating->rating ? 'rating-star' : 'rating-star empty' }}" style="font-size:.7rem;"></i>@endfor</div>
                                                @else
                                                    <span style="font-size:.82rem;color:var(--text-muted);">No rating</span>
                                                @endif
                                                <div style="font-size:.63rem;color:var(--text-muted);">Patient Rating</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dates --}}
                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                <span style="font-size:.78rem;font-weight:600;color:var(--text-main);display:block;">{{ $sub->requested_at?->format('M d, Y') ?? '—' }}</span>
                                                <div style="font-size:.63rem;color:var(--text-muted);">Requested</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                <span style="font-size:.78rem;font-weight:600;color:var(--text-main);display:block;">{{ $sub->accepted_at?->format('M d, Y') ?? '—' }}</span>
                                                <div style="font-size:.63rem;color:var(--text-muted);">Accepted</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.5rem;text-align:center;">
                                                <span style="font-size:.78rem;font-weight:600;color:var(--text-main);display:block;">{{ $sub->completed_at?->format('M d, Y') ?? '—' }}</span>
                                                <div style="font-size:.63rem;color:var(--text-muted);">Completed</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Plan + items --}}
                                    @if($sub->plan)
                                    <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:.75rem;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong style="font-size:.88rem;">{{ $sub->plan->title }}</strong>
                                            <span style="color:#10b981;font-weight:700;">SAR {{ number_format($sub->plan->total_price, 2) }}</span>
                                        </div>
                                        @if($sub->plan->notes)
                                            <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:.6rem;">{{ $sub->plan->notes }}</p>
                                        @endif
                                        @if($sub->plan->items && $sub->plan->items->count())
                                        <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em;">Plan Items</div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($sub->plan->items as $item)
                                                <span class="plan-item-badge {{ $item->status }}">
                                                    <i class="bi bi-{{ $item->status === 'completed' ? 'check' : ($item->status === 'in_progress' ? 'arrow-clockwise' : 'clock') }}"></i>
                                                    {{ $item->service->name ?? 'Service #'.$item->service_id }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- Bonus --}}
                                    @if($sub->bonus)
                                    <div style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.25);border-radius:10px;padding:.75rem;margin-bottom:.75rem;">
                                        <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:.25rem;text-transform:uppercase;letter-spacing:.05em;">Doctor Bonus</div>
                                        <span style="font-weight:700;color:#10b981;">SAR {{ number_format($sub->bonus->bonus_amount, 2) }}</span>
                                        <span style="font-size:.75rem;color:var(--text-muted);margin-left:.5rem;">from plan total SAR {{ number_format($sub->bonus->plan_total, 2) }}</span>
                                    </div>
                                    @endif

                                    {{-- Review --}}
                                    @if($sub->rating?->review)
                                    <div style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.25);border-radius:10px;padding:.75rem;margin-bottom:.75rem;">
                                        <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:.25rem;text-transform:uppercase;letter-spacing:.05em;">Patient Review</div>
                                        <p style="font-size:.84rem;color:var(--text-main);margin:0;">"{{ $sub->rating->review }}"</p>
                                    </div>
                                    @endif

                                    {{-- Reasons --}}
                                    @php
                                        $reasons = array_filter([
                                            'Rejection Reason'      => $sub->rejection_reason,
                                            'Cancel Reason'         => $sub->patient_cancel_reason,
                                            'Switch Reason'         => $sub->patient_switch_reason,
                                            'Admin Removal Reason'  => $sub->admin_removal_reason,
                                            'Doctor Removal Reason' => $sub->doctor_removal_reason,
                                        ]);
                                    @endphp
                                    @if(count($reasons))
                                    <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:.75rem;">
                                        @foreach($reasons as $label => $text)
                                            <div style="font-size:.72rem;color:#ef4444;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">{{ $label }}</div>
                                            <p style="font-size:.82rem;color:var(--text-main);margin:0 0 .4rem;">{{ $text }}</p>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer" style="border-top:1px solid var(--border);">
                                    <button type="button" class="btn-info" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Remove Modal ───────────────────────── --}}
                    @if(!in_array($sub->status, ['removed', 'cancelled', 'rejected']))
                    <div class="modal fade" id="removeModal{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                                    <h5 class="modal-title"><i class="bi bi-person-dash me-2"></i>Remove Patient</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.subscriptions.remove', $sub->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p style="font-size:.88rem;">Remove <strong>{{ $sub->patient->name ?? 'N/A' }}</strong> from <strong>Dr. {{ $sub->dentist->name ?? 'N/A' }}</strong>'s subscription?</p>
                                        <div class="mb-3">
                                            <label class="form-label">Reason <span style="color:#ef4444;">*</span></label>
                                            <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this patient is being removed..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top:1px solid var(--border);">
                                        <button type="button" class="btn-info" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" style="background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.4);color:#ef4444;font-size:.82rem;font-weight:600;padding:6px 16px;border-radius:8px;cursor:pointer;">
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
                        <td colspan="10" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-collection" style="font-size:2rem;opacity:.4;display:block;margin-bottom:.5rem;"></i>
                            No subscriptions found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

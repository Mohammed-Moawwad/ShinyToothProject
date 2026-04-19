@extends('admin.layout')
@section('page-title', 'Vacation Requests')

@section('content')
<style>
.vac-kpi {
    background:var(--panel-bg); border:1px solid var(--border); border-radius:14px;
    padding:1.1rem 1.25rem; display:flex; align-items:center; gap:1rem;
}
.vac-kpi-icon {
    width:46px; height:46px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.vac-kpi-val { font-size:1.35rem; font-weight:800; color:var(--text-main); line-height:1.1; }
.vac-kpi-lbl { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.rate-bar    { height:7px; border-radius:4px; background:var(--border); overflow:hidden; margin-top:.3rem; }
.rate-fill   { height:100%; border-radius:4px; }
.pending-banner {
    background:linear-gradient(135deg,#fff4e5,#fde8e8);
    border:1.5px solid #f59e0b; border-radius:14px; padding:1rem 1.25rem; margin-bottom:1.25rem;
}
.pending-banner-title { font-size:.9rem; font-weight:700; color:#b45309; margin-bottom:.6rem; display:flex; align-items:center; gap:.5rem; }
.pending-item {
    display:flex; align-items:center; gap:.75rem;
    background:rgba(255,255,255,.7); border:1px solid rgba(245,158,11,.3);
    border-radius:10px; padding:.6rem .85rem; margin-bottom:.45rem;
}
.pending-item:last-child { margin-bottom:0; }
.vac-avatar {
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:700; color:#fff;
    background:linear-gradient(135deg,#003263,#059386);
}
.lb-row { display:flex; align-items:center; gap:.7rem; padding:.5rem 0; border-bottom:1px solid var(--border); }
.lb-row:last-child { border-bottom:none; }
.lb-bar-wrap { flex:1; }
.lb-bar { height:5px; border-radius:3px; background:var(--border); overflow:hidden; margin-top:.2rem; }
.lb-bar-fill { height:100%; border-radius:3px; }
.modal-tile {
    background:var(--sidebar-bg); border:1px solid var(--border); border-radius:10px;
    padding:.7rem .9rem; text-align:center;
}
.modal-tile-val { font-size:1rem; font-weight:800; color:var(--text-main); }
.modal-tile-lbl { font-size:.68rem; color:var(--text-muted); margin-top:.1rem; }
</style>

<div class="container-fluid px-0">

    {{-- ── KPI Row ──────────────────────────────────────────── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <div class="vac-kpi">
                <div class="vac-kpi-icon" style="background:rgba(13,158,138,.12);color:#0d9e8a;"><i class="bi bi-calendar2-week-fill"></i></div>
                <div>
                    <div class="vac-kpi-val">{{ number_format($totalCount) }}</div>
                    <div class="vac-kpi-lbl">Total Requests</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vac-kpi">
                <div class="vac-kpi-icon" style="background:rgba(245,158,11,.12);color:#f59e0b;"><i class="bi bi-hourglass-split"></i></div>
                <div style="flex:1;">
                    <div class="vac-kpi-val" style="color:#f59e0b;">{{ number_format($pendingCount) }}</div>
                    <div class="vac-kpi-lbl">Pending Review</div>
                    @if($totalCount > 0)
                    <div class="rate-bar"><div class="rate-fill" style="width:{{ round($pendingCount/$totalCount*100) }}%;background:#f59e0b;"></div></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vac-kpi">
                <div class="vac-kpi-icon" style="background:rgba(16,185,129,.12);color:#10b981;"><i class="bi bi-check-circle-fill"></i></div>
                <div style="flex:1;">
                    <div class="vac-kpi-val" style="color:#10b981;">{{ number_format($approvedCount) }}</div>
                    <div class="vac-kpi-lbl">Approved &mdash; {{ $approvalRate }}%</div>
                    <div class="rate-bar"><div class="rate-fill" style="width:{{ $approvalRate }}%;background:#10b981;"></div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vac-kpi">
                <div class="vac-kpi-icon" style="background:rgba(239,68,68,.12);color:#ef4444;"><i class="bi bi-x-circle-fill"></i></div>
                <div style="flex:1;">
                    <div class="vac-kpi-val" style="color:#ef4444;">{{ number_format($rejectedCount) }}</div>
                    <div class="vac-kpi-lbl">Rejected &mdash; {{ $rejectionRate }}%</div>
                    <div class="rate-bar"><div class="rate-fill" style="width:{{ $rejectionRate }}%;background:#ef4444;"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pending Actions Banner ──────────────────────────── --}}
    @if($pendingVacations->isNotEmpty())
    <div class="pending-banner">
        <div class="pending-banner-title">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $pendingVacations->count() }} Pending Request{{ $pendingVacations->count() > 1 ? 's' : '' }} Awaiting Review
        </div>
        @foreach($pendingVacations->take(4) as $pv)
        <div class="pending-item">
            <div class="vac-avatar">{{ strtoupper(substr($pv->dentist->name ?? 'D', 0, 1)) }}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.8rem;font-weight:700;color:var(--text-main);">Dr. {{ $pv->dentist->name ?? 'N/A' }}</div>
                <div style="font-size:.7rem;color:var(--text-muted);">
                    {{ $pv->start_date->format('M d') }}
                    @if($pv->type === 'full_day' && $pv->end_date && !$pv->start_date->eq($pv->end_date))
                        → {{ $pv->end_date->format('M d, Y') }}
                    @else
                        , {{ $pv->start_date->format('Y') }}
                    @endif
                    &middot; {{ $pv->type === 'full_day' ? $pv->days_count.' day'.($pv->days_count>1?'s':'') : 'Partial day' }}
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <form method="POST" action="{{ route('admin.vacations.approve', $pv->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-teal" style="font-size:.72rem;padding:4px 10px;">
                        <i class="bi bi-check-lg"></i> Approve
                    </button>
                </form>
                <button class="btn-outline-teal" style="font-size:.72rem;padding:4px 10px;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pv->id }}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        @endforeach
        @if($pendingVacations->count() > 4)
        <div style="font-size:.72rem;color:#b45309;margin-top:.4rem;text-align:center;">
            + {{ $pendingVacations->count() - 4 }} more — use the filter below to view all pending
        </div>
        @endif
    </div>
    @endif

    {{-- ── Stats + Top Dentists ────────────────────────────── --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-pie-chart-fill"></i> Request Breakdown</div>
                </div>
                <div style="padding:1rem 1.25rem;">
                    @php
                        $bkItems = [
                            ['label'=>'Approved','count'=>$approvedCount,'pct'=>$approvalRate,'color'=>'#10b981'],
                            ['label'=>'Pending', 'count'=>$pendingCount, 'pct'=>$totalCount>0?round($pendingCount/$totalCount*100):0,'color'=>'#f59e0b'],
                            ['label'=>'Rejected','count'=>$rejectedCount,'pct'=>$rejectionRate,'color'=>'#ef4444'],
                        ];
                    @endphp
                    @foreach($bkItems as $item)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $item['color'] }};flex-shrink:0;"></div>
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-main);width:70px;">{{ $item['label'] }}</span>
                        <div class="rate-bar" style="flex:1;">
                            <div class="rate-fill" style="width:{{ $item['pct'] }}%;background:{{ $item['color'] }};"></div>
                        </div>
                        <span style="font-size:.75rem;font-weight:700;color:{{ $item['color'] }};width:36px;text-align:right;">{{ $item['count'] }}</span>
                    </div>
                    @endforeach

                    <div style="border-top:1px solid var(--border);margin-top:.5rem;padding-top:.75rem;">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="modal-tile">
                                    <div class="modal-tile-val" style="color:#10b981;">{{ number_format($totalApprovedDays) }}</div>
                                    <div class="modal-tile-lbl">Total Approved Days</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="modal-tile">
                                    <div class="modal-tile-val">{{ $requestsByMonth->count() > 0 ? round($totalCount / $requestsByMonth->count(), 1) : 0 }}</div>
                                    <div class="modal-tile-lbl">Avg. Requests / Month</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-trophy-fill"></i> Top Dentists by Approved Days</div>
                </div>
                <div style="padding:.75rem 1.25rem;">
                    @php $maxDays = $topDentistsByDays->max('total_days') ?: 1; @endphp
                    @forelse($topDentistsByDays as $td)
                    <div class="lb-row">
                        <span style="font-size:.7rem;color:var(--text-muted);width:16px;text-align:center;">{{ $loop->iteration }}</span>
                        <div class="vac-avatar">{{ strtoupper(substr($td->dentist->name ?? 'D', 0, 1)) }}</div>
                        <div class="lb-bar-wrap">
                            <div class="d-flex justify-content-between">
                                <span style="font-size:.8rem;font-weight:600;color:var(--text-main);">Dr. {{ $td->dentist->name ?? 'N/A' }}</span>
                                <div class="text-end">
                                    <span style="font-size:.75rem;color:#10b981;font-weight:700;">{{ $td->total_days }} days</span>
                                    <span style="font-size:.65rem;color:var(--text-muted);display:block;">{{ $td->req_count }} requests</span>
                                </div>
                            </div>
                            <div class="lb-bar">
                                <div class="lb-bar-fill" style="width:{{ round($td->total_days/$maxDays*100) }}%;background:#10b981;"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.82rem;">No approved vacation days yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filters ──────────────────────────────────────────── --}}
    <div class="filter-panel mb-3">
        <form method="GET" action="{{ route('admin.vacations') }}" class="row g-3 align-items-end">
            <div class="col-sm-4 col-lg-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="all"      {{ $status === 'all'      ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-sm-4 col-lg-3">
                <label class="form-label">Dentist</label>
                <select class="form-select" name="dentist_id">
                    <option value="all">All Dentists</option>
                    @foreach($dentists as $d)
                    <option value="{{ $d->id }}" {{ $dentistId == $d->id ? 'selected' : '' }}>Dr. {{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn-teal">
                    <i class="bi bi-funnel-fill me-1"></i>Apply
                </button>
                <a href="{{ route('admin.vacations') }}" class="btn-outline-teal ms-1">Reset</a>
            </div>
        </form>
    </div>

    {{-- ── Table ────────────────────────────────────────────── --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-calendar2-week-fill"></i> Vacation Requests</div>
            <span class="count-badge">{{ $vacations->count() }} results</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Duration</th>
                        <th>Reason</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacations as $vac)
                    @php $sc = ['pending'=>'pending','approved'=>'completed','rejected'=>'cancelled']; @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="vac-avatar">{{ strtoupper(substr($vac->dentist->name ?? 'D', 0, 1)) }}</div>
                                <div>
                                    <strong>Dr. {{ $vac->dentist->name ?? 'N/A' }}</strong><br>
                                    <span style="font-size:.72rem;color:var(--text-muted);">{{ $vac->dentist->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($vac->type === 'full_day')
                                <span style="background:#e7f1ff;color:#0056b3;font-size:.72rem;font-weight:600;padding:3px 8px;border-radius:6px;"><i class="bi bi-calendar2-check me-1"></i>Full Day</span>
                            @else
                                <span style="background:#fff4e5;color:#b86e00;font-size:.72rem;font-weight:600;padding:3px 8px;border-radius:6px;"><i class="bi bi-clock me-1"></i>Partial</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $vac->start_date->format('M d, Y') }}</strong>
                            @if($vac->type === 'full_day' && $vac->end_date && !$vac->start_date->eq($vac->end_date))
                                <br><span style="font-size:.72rem;color:var(--text-muted);">→ {{ $vac->end_date->format('M d, Y') }}</span>
                            @endif
                            @if($vac->type === 'partial_day' && $vac->start_time && $vac->end_time)
                                <br><span style="font-size:.72rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($vac->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($vac->end_time)->format('g:i A') }}</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($vac->type === 'full_day')
                                <span style="font-size:.8rem;font-weight:700;color:var(--text-main);">{{ $vac->days_count }}</span>
                                <span style="font-size:.68rem;color:var(--text-muted);display:block;">day{{ $vac->days_count > 1 ? 's' : '' }}</span>
                            @else
                                <span style="font-size:.72rem;color:var(--text-muted);">Partial</span>
                            @endif
                        </td>
                        <td style="max-width:180px;font-size:.8rem;color:var(--text-main);">{{ Str::limit($vac->reason, 55) }}</td>
                        <td style="font-size:.75rem;color:var(--text-muted);">{{ $vac->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge-status {{ $sc[$vac->status] ?? 'pending' }}">{{ ucfirst($vac->status) }}</span>
                            @if($vac->admin_note)
                                <div style="font-size:.65rem;color:var(--text-muted);margin-top:.2rem;max-width:100px;">{{ Str::limit($vac->admin_note, 30) }}</div>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info" data-bs-toggle="modal" data-bs-target="#vacDetail{{ $vac->id }}" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($vac->status === 'pending')
                                <form method="POST" action="{{ route('admin.vacations.approve', $vac->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-teal" title="Approve" onclick="return confirm('Approve this vacation request?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <button class="btn-outline-teal" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $vac->id }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- ── Detail Modal ── --}}
                    <div class="modal fade" id="vacDetail{{ $vac->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                                    <h5 class="modal-title" style="font-size:.95rem;">
                                        <i class="bi bi-calendar2-week me-2" style="color:var(--teal);"></i>
                                        Vacation Request #{{ $vac->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" style="padding:1.25rem;">
                                    {{-- Doctor info --}}
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="vac-avatar" style="width:42px;height:42px;font-size:.9rem;">{{ strtoupper(substr($vac->dentist->name ?? 'D', 0, 1)) }}</div>
                                        <div>
                                            <div style="font-weight:700;color:var(--text-main);">Dr. {{ $vac->dentist->name ?? 'N/A' }}</div>
                                            <div style="font-size:.75rem;color:var(--text-muted);">{{ $vac->dentist->email ?? '' }}</div>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge-status {{ $sc[$vac->status] ?? 'pending' }}">{{ ucfirst($vac->status) }}</span>
                                        </div>
                                    </div>
                                    {{-- Stat tiles --}}
                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div class="modal-tile">
                                                <div class="modal-tile-val">{{ $vac->type === 'full_day' ? 'Full Day' : 'Partial' }}</div>
                                                <div class="modal-tile-lbl">Type</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="modal-tile">
                                                <div class="modal-tile-val">{{ $vac->start_date->format('M d') }}</div>
                                                <div class="modal-tile-lbl">Start Date</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="modal-tile">
                                                @if($vac->type === 'full_day')
                                                    <div class="modal-tile-val">{{ $vac->days_count }}</div>
                                                    <div class="modal-tile-lbl">Day{{ $vac->days_count > 1 ? 's' : '' }}</div>
                                                @else
                                                    <div class="modal-tile-val" style="font-size:.78rem;">{{ $vac->start_time }} – {{ $vac->end_time }}</div>
                                                    <div class="modal-tile-lbl">Hours</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Full date range for multi-day --}}
                                    @if($vac->type === 'full_day' && $vac->end_date && !$vac->start_date->eq($vac->end_date))
                                    <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;margin-bottom:.75rem;font-size:.82rem;">
                                        <i class="bi bi-calendar-range me-2" style="color:var(--teal);"></i>
                                        <strong>{{ $vac->start_date->format('M d, Y') }}</strong>
                                        &nbsp;→&nbsp;
                                        <strong>{{ $vac->end_date->format('M d, Y') }}</strong>
                                    </div>
                                    @endif
                                    {{-- Reason --}}
                                    <div style="margin-bottom:.75rem;">
                                        <div style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">Reason</div>
                                        <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:8px;padding:.75rem;font-size:.84rem;color:var(--text-main);line-height:1.5;">{{ $vac->reason }}</div>
                                    </div>
                                    {{-- Admin note --}}
                                    @if($vac->admin_note)
                                    <div>
                                        <div style="font-size:.72rem;font-weight:700;color:#b91c1c;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;"><i class="bi bi-shield-exclamation me-1"></i>Admin Note</div>
                                        <div style="background:#fde8e8;border:1px solid #f5c6c6;border-radius:8px;padding:.75rem;font-size:.84rem;color:#7f1d1d;">{{ $vac->admin_note }}</div>
                                    </div>
                                    @endif
                                    <div style="font-size:.72rem;color:var(--text-muted);margin-top:.75rem;">
                                        <i class="bi bi-clock me-1"></i>Submitted {{ $vac->created_at->format('M d, Y \a\t H:i') }}
                                    </div>
                                </div>
                                <div class="modal-footer" style="border-top:1px solid var(--border);">
                                    @if($vac->status === 'pending')
                                    <form method="POST" action="{{ route('admin.vacations.approve', $vac->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-teal" onclick="return confirm('Approve this request?')"><i class="bi bi-check-lg me-1"></i>Approve</button>
                                    </form>
                                    <button type="button" class="btn-outline-teal" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $vac->id }}">
                                        <i class="bi bi-x-lg me-1"></i>Reject
                                    </button>
                                    @endif
                                    <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Reject Modal ── --}}
                    @if($vac->status === 'pending')
                    <div class="modal fade" id="rejectModal{{ $vac->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                                    <h5 class="modal-title" style="font-size:.95rem;">
                                        <i class="bi bi-x-circle me-2" style="color:#ef4444;"></i>Reject Vacation Request
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.vacations.reject', $vac->id) }}">
                                    @csrf
                                    <div class="modal-body" style="padding:1.25rem;">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="vac-avatar" style="background:linear-gradient(135deg,#ef4444,#b91c1c);">{{ strtoupper(substr($vac->dentist->name ?? 'D', 0, 1)) }}</div>
                                            <div>
                                                <div style="font-weight:700;color:var(--text-main);">Dr. {{ $vac->dentist->name ?? 'N/A' }}</div>
                                                <div style="font-size:.75rem;color:var(--text-muted);">
                                                    {{ $vac->start_date->format('M d, Y') }}
                                                    @if($vac->type === 'full_day' && $vac->end_date && !$vac->start_date->eq($vac->end_date))
                                                        → {{ $vac->end_date->format('M d, Y') }}
                                                    @endif
                                                    &middot; {{ $vac->type === 'full_day' ? $vac->days_count.' day'.($vac->days_count>1?'s':'') : 'Partial day' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background:#fde8e8;border:1px solid #f5c6c6;border-radius:8px;padding:.65rem .9rem;font-size:.8rem;color:#7f1d1d;margin-bottom:1rem;">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            The dentist will be notified of the rejection. This action cannot be undone.
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-size:.8rem;font-weight:600;">Admin Note <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                                            <textarea name="admin_note" class="form-control" rows="3" placeholder="Provide a reason for rejection..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top:1px solid var(--border);">
                                        <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" style="background:#fde8e8;border:1px solid #f5c6c6;color:#b91c1c;font-size:.82rem;font-weight:600;padding:6px 16px;border-radius:8px;cursor:pointer;">
                                            <i class="bi bi-x-circle me-1"></i>Reject Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="8">
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

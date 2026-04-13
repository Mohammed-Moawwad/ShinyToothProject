@extends('doctor.layout')

@section('title', 'Patient Report')
@section('page-title', 'Patient Report')
@section('breadcrumb')
    <li class="breadcrumb-item active">Patient Report</li>
@endsection

@section('content')

<div class="row g-4" style="align-items: flex-start;">

    {{-- ── LEFT: Search + Patient List ──────────────────────── --}}
    <div class="col-lg-4">
        <div class="dash-card" style="position:sticky; top:20px;">
            <div class="card-header-custom">
                <h6><i class="bi bi-people-fill me-2" style="color:var(--teal);"></i>My Patients</h6>
                <span class="text-muted" style="font-size:.82rem;">{{ $patients->count() }}</span>
            </div>

            {{-- Search form --}}
            <form method="GET" action="/doctor/patient-report" class="mb-3">
                <input type="hidden" name="dentist" value="{{ $dentist->id }}">
                @if(request('patient_id'))
                    <input type="hidden" name="patient_id" value="{{ request('patient_id') }}">
                @endif
                <div class="d-flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                           class="form-control-pr" style="flex:1;"
                           placeholder="Name, email or phone…">
                    <button type="submit" class="btn btn-teal" style="padding:0 14px; height:38px;">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(!empty($search))
                        <a href="/doctor/patient-report?dentist={{ $dentist->id }}"
                           class="btn btn-outline-secondary" style="padding:0 10px; height:38px; line-height:36px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Patient list --}}
            <div style="max-height:600px; overflow-y:auto; display:flex; flex-direction:column; gap:6px;">
                @forelse($patients as $p)
                    <a href="/doctor/patient-report?dentist={{ $dentist->id }}&patient_id={{ $p->id }}{{ !empty($search) ? '&search=' . urlencode($search) : '' }}"
                       class="patient-row {{ isset($patient) && $patient->id === $p->id ? 'patient-row--active' : '' }}">
                        <div class="patient-avatar-sm">{{ strtoupper(substr($p->name,0,1)) }}</div>
                        <div style="flex:1; min-width:0;">
                            <div class="fw-semibold text-truncate" style="color:var(--dark-blue); font-size:.88rem;">{{ $p->name }}</div>
                            <small class="text-muted text-truncate d-block" style="font-size:.75rem;">{{ $p->email }}</small>
                        </div>
                        <i class="bi bi-chevron-right" style="color:#d1d5db; font-size:.8rem; flex-shrink:0;"></i>
                    </a>
                @empty
                    <div style="color:#b45309; font-size:.85rem; padding:8px 0;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        No patients found{{ !empty($search) ? ' for "' . $search . '"' : '' }}.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Report ─────────────────────────────────────── --}}
    <div class="col-lg-8">

        @if(session('report_success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="border-radius:10px; font-size:.88rem;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('report_success') }}
            </div>
        @endif

        @if(isset($patient))

            {{-- Patient header --}}
            <div class="dash-card mb-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="patient-avatar-lg">{{ strtoupper(substr($patient->name,0,1)) }}</div>
                    <div style="flex:1;">
                        <div class="d-flex align-items-center gap-3 flex-wrap mb-1">
                            <h5 class="mb-0 fw-bold" style="color:var(--dark-blue);">{{ $patient->name }}</h5>
                            <button type="button" class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#writeReportModal"
                                    style="padding:4px 14px; font-size:.8rem;">
                                <i class="bi bi-pencil-square me-1"></i>Write Report
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-3" style="font-size:.83rem; color:#6b7280;">
                            <span><i class="bi bi-envelope me-1"></i>{{ $patient->email }}</span>
                            @if($patient->phone)
                                <span><i class="bi bi-telephone me-1"></i>{{ $patient->phone }}</span>
                            @endif
                            @if($patient->date_of_birth)
                                <span><i class="bi bi-cake me-1"></i>{{ $patient->date_of_birth->format('M d, Y') }} ({{ $patient->date_of_birth->age }} yrs)</span>
                            @endif
                            @if($patient->gender)
                                <span><i class="bi bi-person me-1"></i>{{ ucfirst($patient->gender) }}</span>
                            @endif
                            @if($patient->blood_type)
                                <span><i class="bi bi-droplet-fill me-1" style="color:#dc2626;"></i>{{ $patient->blood_type }}</span>
                            @endif
                            @if($patient->nationality)
                                <span><i class="bi bi-flag me-1"></i>{{ $patient->nationality }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="row g-2 mb-4">
                @php
                    $stats = [
                        ['label'=>'Total Visits',  'value'=>$totalAppts,     'color'=>'#1e3a5f', 'icon'=>'bi-calendar-check'],
                        ['label'=>'Completed',     'value'=>$completedAppts, 'color'=>'#059386', 'icon'=>'bi-check-circle-fill'],
                        ['label'=>'Scheduled',     'value'=>$scheduledAppts, 'color'=>'#2563eb', 'icon'=>'bi-clock-fill'],
                        ['label'=>'No Show',       'value'=>$noShowAppts,    'color'=>'#b45309', 'icon'=>'bi-person-x-fill'],
                        ['label'=>'Failed',        'value'=>$failedAppts,    'color'=>'#b91c1c', 'icon'=>'bi-x-octagon-fill'],
                        ['label'=>'Cancelled',     'value'=>$cancelledAppts, 'color'=>'#6b7280', 'icon'=>'bi-slash-circle'],
                    ];
                @endphp
                @foreach($stats as $s)
                    <div class="col-4 col-md-2">
                        <div class="dash-card text-center py-2 px-1">
                            <i class="bi {{ $s['icon'] }} d-block mb-1" style="font-size:1.2rem; color:{{ $s['color'] }};"></i>
                            <div class="fw-bold" style="font-size:1.3rem; color:{{ $s['color'] }};">{{ $s['value'] }}</div>
                            <div style="font-size:.68rem; color:#6b7280; font-weight:600;">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Appointment history --}}
            <div class="dash-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-calendar3 me-2" style="color:var(--teal);"></i>Appointment History</h6>
                    <span class="text-muted" style="font-size:.82rem;">{{ $totalAppts }} total</span>
                </div>
                @if($appointments->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:.84rem;">
                            <thead style="background:#f1f5f9;">
                                <tr>
                                    <th style="padding:9px 12px; font-weight:700; color:var(--dark-blue);">Date</th>
                                    <th style="padding:9px 12px; font-weight:700; color:var(--dark-blue);">Time</th>
                                    <th style="padding:9px 12px; font-weight:700; color:var(--dark-blue);">Service</th>
                                    <th style="padding:9px 12px; font-weight:700; color:var(--dark-blue);">Status</th>
                                    <th style="padding:9px 12px; font-weight:700; color:var(--dark-blue);">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appt)
                                    @php
                                        $badgeStyle = match($appt->status) {
                                            'completed' => 'background:#d4f5e4; color:#0f6b3a;',
                                            'scheduled' => 'background:#dbeafe; color:#1d4ed8;',
                                            'cancelled' => 'background:#f3f4f6; color:#6b7280;',
                                            'no_show'   => 'background:#fff9db; color:#b45309;',
                                            'failed'    => 'background:#fee2e2; color:#b91c1c;',
                                            default     => 'background:#f3f4f6; color:#6b7280;',
                                        };
                                    @endphp
                                    <tr>
                                        <td style="padding:9px 12px;">{{ $appt->appointment_date->format('M d, Y') }}</td>
                                        <td style="padding:9px 12px;">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</td>
                                        <td style="padding:9px 12px;">{{ $appt->service->name ?? '—' }}</td>
                                        <td style="padding:9px 12px;">
                                            <span style="{{ $badgeStyle }} padding:3px 10px; border-radius:20px; font-size:.73rem; font-weight:700; white-space:nowrap;">
                                                {{ ucfirst(str_replace('_',' ', $appt->status)) }}
                                            </span>
                                        </td>
                                        <td style="padding:9px 12px; color:#9ca3af; font-style:italic;">{{ $appt->notes ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-calendar-x d-block"></i>
                        <p class="mb-0">No appointments found</p>
                    </div>
                @endif
            </div>

            {{-- Subscription + Treatment Plan --}}
            @if(isset($subscription))
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="dash-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-card-checklist me-2" style="color:var(--teal);"></i>Subscription</h6>
                                @php
                                    $subBadge = match($subscription->status) {
                                        'active'    => 'background:#d4f5e4; color:#0f6b3a;',
                                        'idle'      => 'background:#dbeafe; color:#1d4ed8;',
                                        'pending'   => 'background:#fff9db; color:#b45309;',
                                        'completed' => 'background:#e0e7ff; color:#3730a3;',
                                        default     => 'background:#f3f4f6; color:#6b7280;',
                                    };
                                @endphp
                                <span style="{{ $subBadge }} padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:700;">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                            <div style="font-size:.85rem; color:#374151; line-height:2.2;">
                                @if($subscription->requested_at)
                                    <div><span class="fw-semibold">Requested:</span> {{ $subscription->requested_at->format('M d, Y') }}</div>
                                @endif
                                @if($subscription->accepted_at)
                                    <div><span class="fw-semibold">Started:</span> {{ $subscription->accepted_at->format('M d, Y') }}</div>
                                @endif
                                @if($subscription->completed_at)
                                    <div><span class="fw-semibold">Completed:</span> {{ $subscription->completed_at->format('M d, Y') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($subscription->plan && $subscription->plan->items->count())
                    <div class="col-md-7">
                        <div class="dash-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-clipboard2-pulse me-2" style="color:var(--teal);"></i>Treatment Plan</h6>
                                @php
                                    $done  = $subscription->plan->items->where('is_completed', true)->count();
                                    $total = $subscription->plan->items->count();
                                    $pct   = $total ? round($done / $total * 100) : 0;
                                @endphp
                                <span style="font-size:.8rem; color:#6b7280;">{{ $done }}/{{ $total }}</span>
                            </div>
                            <div style="background:#e5e7eb; border-radius:99px; height:7px; margin-bottom:14px;">
                                <div style="background:var(--teal); height:7px; border-radius:99px; width:{{ $pct }}%;"></div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @foreach($subscription->plan->items->sortBy('order') as $item)
                                    <div class="d-flex align-items-start gap-2"
                                         style="padding:7px 10px; border-radius:8px;
                                                background:{{ $item->is_completed ? '#f0fdf4' : '#f8fafc' }};
                                                border:1px solid {{ $item->is_completed ? '#bbf7d0' : '#e5e7eb' }};">
                                        <i class="bi {{ $item->is_completed ? 'bi-check-circle-fill' : 'bi-circle' }} mt-1"
                                           style="color:{{ $item->is_completed ? '#059386' : '#d1d5db' }}; font-size:.9rem; flex-shrink:0;"></i>
                                        <div style="font-size:.82rem;">
                                            <div class="fw-semibold" style="color:var(--dark-blue);">
                                                {{ $item->service->name ?? 'Service #'.$item->service_id }}
                                            </div>
                                            @if($item->notes)
                                                <div style="color:#6b7280; font-size:.76rem;">{{ $item->notes }}</div>
                                            @endif
                                            @if($item->is_completed && $item->completed_at)
                                                <div style="color:#059386; font-size:.74rem;">Done {{ \Carbon\Carbon::parse($item->completed_at)->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif

            {{-- ── Written Reports ───────────────────────────────── --}}
            <div class="dash-card mt-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-file-text me-2" style="color:var(--teal);"></i>Written Reports</h6>
                    <button type="button" class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#writeReportModal"
                            style="padding:4px 14px; font-size:.78rem;">
                        <i class="bi bi-plus-lg me-1"></i>New Report
                    </button>
                </div>

                @if(isset($writtenReports) && $writtenReports->count())
                    <div class="d-flex flex-column gap-3">
                        @foreach($writtenReports as $wr)
                            <div class="written-report-card {{ $wr->status === 'submitted' ? 'written-report-card--submitted' : '' }}">
                                <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                                    <div>
                                        <div class="fw-semibold" style="color:var(--dark-blue); font-size:.92rem;">{{ $wr->title }}</div>
                                        <small class="text-muted">{{ $wr->created_at->format('M d, Y · g:i A') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($wr->status === 'submitted')
                                            <span class="badge-report badge-submitted">
                                                <i class="bi bi-send-fill me-1"></i>Submitted
                                            </span>
                                        @else
                                            <span class="badge-report badge-draft">
                                                <i class="bi bi-pencil me-1"></i>Draft
                                            </span>
                                            <form method="POST" action="/doctor/patient-report/report/{{ $wr->id }}/submit?dentist={{ $dentist->id }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm" style="padding:3px 10px; font-size:.75rem; background:#1e3a5f; color:#fff; border-radius:8px; border:none;">
                                                    <i class="bi bi-send me-1"></i>Submit to Admin
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div style="font-size:.84rem; color:#374151; white-space:pre-wrap; background:#f8fafc; border-radius:8px; padding:10px 14px; border:1px solid #e5e7eb;">{{ $wr->content }}</div>
                                @if($wr->submitted_at)
                                    <div class="mt-1" style="font-size:.75rem; color:#059386;">
                                        <i class="bi bi-clock me-1"></i>Submitted {{ $wr->submitted_at->format('M d, Y · g:i A') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding:30px 20px;">
                        <i class="bi bi-file-earmark-text d-block" style="font-size:2rem; color:#d1d5db;"></i>
                        <p class="mb-0 text-muted" style="font-size:.85rem;">No reports written yet for this patient.</p>
                    </div>
                @endif
            </div>

            {{-- ── Write Report Modal ─────────────────────────────── --}}
            <div class="modal fade" id="writeReportModal" tabindex="-1" aria-labelledby="writeReportModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">
                        <div class="modal-header" style="background:var(--dark-blue); border-radius:16px 16px 0 0; padding:16px 24px;">
                            <h6 class="modal-title fw-bold" id="writeReportModalLabel" style="color:#fff; margin:0;">
                                <i class="bi bi-pencil-square me-2"></i>Write Patient Report
                            </h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="/doctor/patient-report/report/store?dentist={{ $dentist->id }}">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <div class="modal-body" style="padding:24px;">
                                <div class="mb-3">
                                    <label class="form-label-pr">Patient</label>
                                    <div style="font-size:.88rem; color:var(--dark-blue); font-weight:600; padding:8px 12px; background:#f0fdfc; border-radius:8px; border:1.5px solid var(--teal);">
                                        <i class="bi bi-person-fill me-2" style="color:var(--teal);"></i>{{ $patient->name }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-pr" for="reportTitle">Report Title <span style="color:#dc2626;">*</span></label>
                                    <input type="text" id="reportTitle" name="title" class="form-control-pr"
                                           placeholder="e.g. Follow-up Assessment, Treatment Summary…"
                                           maxlength="255" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label-pr" for="reportContent">Report Content <span style="color:#dc2626;">*</span></label>
                                    <textarea id="reportContent" name="content"
                                              class="form-control-pr" rows="10"
                                              style="height:auto; resize:vertical; min-height:160px;"
                                              placeholder="Write your clinical notes, observations, diagnosis, recommendations…"
                                              minlength="10" maxlength="10000" required></textarea>
                                    <div class="d-flex justify-content-end mt-1">
                                        <small id="charCount" style="color:#9ca3af; font-size:.75rem;">0 / 10,000</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding:16px 24px; border-top:1px solid #e5e7eb; gap:10px;">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:9px;">
                                    Cancel
                                </button>
                                <button type="submit" name="action" value="draft"
                                        class="btn" style="background:#f3f4f6; color:#374151; border:1.5px solid #e5e7eb; border-radius:9px; padding:8px 20px;">
                                    <i class="bi bi-floppy me-1"></i>Save as Draft
                                </button>
                                <button type="submit" name="action" value="submitted"
                                        class="btn btn-teal" style="border-radius:9px; padding:8px 20px;">
                                    <i class="bi bi-send me-1"></i>Save & Submit to Admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @else
            <div class="dash-card" style="min-height:300px;">
                <div class="empty-state" style="padding:80px 20px;">
                    <i class="bi bi-file-earmark-person d-block" style="font-size:3rem; color:#d1d5db;"></i>
                    <p class="mb-1 fw-semibold" style="color:var(--dark-blue);">Select a patient</p>
                    <p class="mb-0 text-muted" style="font-size:.85rem;">Click any patient on the left to view their report.</p>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .form-control-pr {
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        padding: 8px 12px;
        font-size: .87rem;
        background: #f8fafc;
        height: 38px;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }
    .form-control-pr:focus {
        outline: none;
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5,147,134,.1);
    }
    .patient-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        text-decoration: none;
        background: #fafafa;
        transition: border-color .15s, background .15s;
    }
    .patient-row:hover  { border-color: var(--teal); background: #f0fdfc; }
    .patient-row--active { border-color: var(--teal) !important; background: #e6f7f6 !important; }
    .patient-avatar-sm {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: var(--teal);
        color: #fff;
        font-weight: 700;
        font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .patient-avatar-lg {
        width: 56px; height: 56px;
        border-radius: 50%;
        background: var(--teal);
        color: #fff;
        font-weight: 700;
        font-size: 1.4rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 40px 20px;
    }
    .written-report-card {
        padding: 14px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        transition: border-color .15s;
    }
    .written-report-card--submitted { border-color: #a7f3d0; background: #f0fdf9; }
    .badge-report {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .73rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-draft     { background: #fef9c3; color: #854d0e; }
    .badge-submitted { background: #d4f5e4; color: #0f6b3a; }
</style>

@push('scripts')
<script>
    const reportContent = document.getElementById('reportContent');
    const charCount     = document.getElementById('charCount');
    if (reportContent && charCount) {
        reportContent.addEventListener('input', function () {
            charCount.textContent = this.value.length.toLocaleString() + ' / 10,000';
        });
    }
</script>
@endpush

@endsection

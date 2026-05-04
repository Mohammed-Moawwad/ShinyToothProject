@extends('admin.layout')
@section('page-title', 'Appointments Management')
@section('content')
<div class="container-fluid px-0">

    {{-- View Mode Tabs --}}
    <div class="d-flex gap-2 mb-4">
        <button id="btn-tab-all" onclick="apptShowTab('all')"
            style="padding:9px 20px;border-radius:10px;border:none;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:var(--teal);color:#fff;box-shadow:0 2px 6px rgba(5,147,134,.3);">
            <i class="bi bi-list-ul"></i> All Appointments
        </button>
        <button id="btn-tab-months" onclick="apptShowTab('months')"
            style="padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);">
            <i class="bi bi-calendar3"></i> By Month
        </button>
    </div>

    {{-- ═══════════════════════════ TAB: ALL APPOINTMENTS ═══════════════════════════ --}}
    <div id="tab-all">

    {{-- Row 1: Status Stats --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-calendar2-check-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-calendar2-check-fill"></i></div>
                <div class="stat-num">{{ $apptStats['total'] }}</div>
                <div class="stat-lbl">Total Appointments</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #0056b3;">
                <i class="bi bi-calendar-day stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-calendar-day"></i></div>
                <div class="stat-num" style="color:#0056b3;">{{ $apptStats['today'] }}</div>
                <div class="stat-lbl">Today</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #6f42c1;">
                <i class="bi bi-calendar-month stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#f0ecff;color:#6f42c1;"><i class="bi bi-calendar-month"></i></div>
                <div class="stat-num" style="color:#6f42c1;">{{ $apptStats['this_month'] }}</div>
                <div class="stat-lbl">This Month</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #059386;">
                <i class="bi bi-graph-up-arrow stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="stat-num" style="color:#059386;">{{ $apptStats['completion_rate'] }}<small style="font-size:.5em;">%</small></div>
                <div class="stat-lbl">Completion Rate</div>
            </div>
        </div>
    </div>

    {{-- Row 2: Status breakdown --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #b86e00;">
                <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-num" style="color:#b86e00;">{{ $statuses['scheduled'] ?? 0 }}</div>
                <div class="stat-lbl">Scheduled</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #0056b3;">
                <i class="bi bi-check-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-check-circle"></i></div>
                <div class="stat-num" style="color:#0056b3;">{{ $statuses['no_show'] ?? 0 }}</div>
                <div class="stat-lbl">No-Show</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #0f6b3a;">
                <i class="bi bi-check-double stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-check-double"></i></div>
                <div class="stat-num" style="color:#059386;">{{ $statuses['completed'] ?? 0 }}</div>
                <div class="stat-lbl">Completed</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="border-left:3px solid #c0392b;">
                <i class="bi bi-x-circle stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-x-circle"></i></div>
                <div class="stat-num" style="color:#c0392b;">{{ $statuses['cancelled'] ?? 0 }}</div>
                <div class="stat-lbl">Cancelled</div>
            </div>
        </div>
    </div>

    {{-- Row 3: Leaderboards & breakdowns --}}
    <div class="row g-3 mb-4">

        {{-- Top 3 Dentists --}}
        <div class="col-12 col-md-4">
            <div class="panel" style="margin-bottom:0;height:100%;">
                <div class="panel-head"><div class="panel-head-title"><i class="bi bi-trophy-fill" style="color:#f5a623;"></i> Busiest Doctors</div></div>
                <div style="padding:12px 16px;">
                    @forelse($apptStats['top_dentists'] as $i => $doc)
                    <div class="d-flex align-items-center gap-3" style="padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                        <span style="font-size:1.2rem;font-weight:800;color:{{ $i===0 ? '#f5a623' : ($i===1 ? '#9e9e9e' : '#cd7f32') }};min-width:22px;">#{{ $i+1 }}</span>
                        @if($doc->image)
                            <img src="{{ asset($doc->image) }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
                        @else
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.9rem;">{{ strtoupper(substr($doc->name,0,1)) }}</div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">{{ $doc->completed_count }} completed</div>
                        </div>
                        <span class="count-badge">{{ $doc->appt_count }} appts</span>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Services --}}
        <div class="col-12 col-md-4">
            <div class="panel" style="margin-bottom:0;height:100%;">
                <div class="panel-head"><div class="panel-head-title"><i class="bi bi-heart-pulse-fill" style="color:#c0392b;"></i> Most Booked Services</div></div>
                <div style="padding:12px 16px;">
                    @forelse($apptStats['top_services'] as $svc)
                    @php $pct = $apptStats['total'] > 0 ? round(($svc->appt_count / $apptStats['total']) * 100) : 0; @endphp
                    <div style="margin-bottom:10px;">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span style="font-weight:600;">{{ $svc->name }}</span>
                            <span style="color:var(--text-muted);">{{ $svc->appt_count }} ({{ $pct }}%)</span>
                        </div>
                        <div style="background:#e9ecef;border-radius:4px;height:6px;">
                            <div style="background:var(--teal);width:{{ $pct }}%;height:6px;border-radius:4px;transition:width .3s;"></div>
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Busiest Days --}}
        <div class="col-12 col-md-4">
            <div class="panel" style="margin-bottom:0;height:100%;">
                <div class="panel-head"><div class="panel-head-title"><i class="bi bi-bar-chart-fill" style="color:#6f42c1;"></i> Busiest Days of Week</div></div>
                <div style="padding:12px 16px;">
                    @php $maxDay = $apptStats['busiest_days']->max('total') ?: 1; @endphp
                    @forelse($apptStats['busiest_days'] as $day)
                    @php $pct = round(($day->total / $maxDay) * 100); @endphp
                    <div style="margin-bottom:10px;">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span>{{ $day->day_name }}</span>
                            <span style="color:var(--text-muted);">{{ $day->total }}</span>
                        </div>
                        <div style="background:#e9ecef;border-radius:4px;height:6px;">
                            <div style="background:#6f42c1;width:{{ $pct }}%;height:6px;border-radius:4px;"></div>
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 6-Month Trend --}}
        @if($apptStats['monthly_trend']->isNotEmpty())
        <div class="col-12">
            <div class="panel" style="margin-bottom:0;">
                <div class="panel-head"><div class="panel-head-title"><i class="bi bi-graph-up-arrow" style="color:#059386;"></i> Appointments — Last 6 Months</div></div>
                <div style="padding:16px 20px;">
                    <div class="d-flex align-items-flex-end gap-3" style="height:80px;align-items:flex-end;">
                        @php $maxMonth = $apptStats['monthly_trend']->max('total') ?: 1; @endphp
                        @foreach($apptStats['monthly_trend'] as $month)
                        @php $barH = round(($month->total / $maxMonth) * 70); @endphp
                        <div class="d-flex flex-column align-items-center" style="flex:1;gap:4px;">
                            <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;">{{ $month->total }}</span>
                            <div style="width:100%;height:{{ max($barH,4) }}px;background:var(--teal);border-radius:4px 4px 0 0;opacity:.85;"></div>
                            <span style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;">{{ $month->month_label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Filters --}}
    <div class="filter-panel mb-4">
        <form method="GET" action="{{ route('admin.appointments') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search Patient</label>
                    <input type="text" class="form-control" name="search" placeholder="Patient name or email..." value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search Dentist</label>
                    <input type="text" class="form-control" name="dentist" placeholder="Dentist name..." value="{{ $dentist }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="{{ $date }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="all"       {{ $status === 'all'       ? 'selected' : '' }}>All Statuses</option>
                        <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="no_show"   {{ $status === 'no_show'   ? 'selected' : '' }}>No-Show</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" name="sort">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn-primary" type="submit"><i class="bi bi-search me-1"></i>Search</button>
                    @if($search || $dentist || $date || $status !== 'all')
                        <a href="{{ route('admin.appointments') }}" class="btn" style="background:#f0f2f5;border:1px solid #dee2e6;color:#495057;padding:8px 16px;border-radius:10px;font-size:.85rem;text-decoration:none;"><i class="bi bi-x-lg me-1"></i>Clear</a>
                    @endif
                </div>
            </div>
        </form>
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
                                                    <p><strong>Payment:</strong> {{ ucfirst($appointment->payment->status) }} � SAR {{ number_format($appointment->payment->amount, 2) }}</p>
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
            <div class="d-flex justify-content-center p-3">{{ $appointments->appends(['search' => $search, 'dentist' => $dentist, 'date' => $date, 'status' => $status, 'sort' => $sort])->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>{{-- /panel --}}

    </div>{{-- /tab-all --}}

    {{-- ═══════════════════════════ TAB: BY MONTH ═══════════════════════════ --}}
    <div id="tab-months" style="display:none;">

        @if($monthlyBreakdown->isEmpty())
            <div class="panel" style="text-align:center;padding:48px;color:var(--text-muted);">
                <i class="bi bi-calendar-x" style="font-size:2.5rem;opacity:.4;"></i>
                <p style="margin-top:12px;">No appointment data available yet.</p>
            </div>
        @else

        {{-- Month overview bar (all months at a glance) --}}
        <div class="panel mb-4">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-bar-chart-fill" style="color:var(--teal);"></i> All-Time Monthly Overview</div>
                <span class="count-badge">{{ $monthlyBreakdown->count() }} month{{ $monthlyBreakdown->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div style="padding:16px 20px;overflow-x:auto;">
                <div style="display:flex;align-items:flex-end;gap:8px;min-height:90px;">
                    @php $maxMo = $monthlyBreakdown->max('total') ?: 1; @endphp
                    @foreach($monthlyBreakdown->sortBy('month_sort') as $mo)
                    @php
                        $barH   = max(round(($mo->total / $maxMo) * 70), 4);
                        $isNow  = $mo->month_sort === now()->format('Y-m');
                    @endphp
                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;min-width:40px;cursor:pointer;"
                         onclick="scrollToMonth('{{ $mo->month_sort }}')">
                        <span style="font-size:.7rem;color:var(--text-muted);font-weight:600;">{{ $mo->total }}</span>
                        <div style="width:100%;height:{{ $barH }}px;background:{{ $isNow ? 'var(--teal)' : '#b2dfdb' }};border-radius:4px 4px 0 0;opacity:.9;transition:.2s;"
                             title="{{ $mo->month_label }}: {{ $mo->total }} appts"></div>
                        <span style="font-size:.65rem;color:{{ $isNow ? 'var(--teal)' : 'var(--text-muted)' }};font-weight:{{ $isNow ? '700' : '400' }};white-space:nowrap;">{{ $mo->month_label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Monthly accordion cards --}}
        @foreach($monthlyBreakdown as $mo)
        @php
            $moId   = 'mo-' . str_replace('-', '', $mo->month_sort);
            $isFirst = $loop->first;
        @endphp
        <div class="panel mb-3" id="anchor-{{ str_replace('-', '', $mo->month_sort) }}" style="border-radius:14px;overflow:hidden;">

            {{-- Month header (collapsible toggle) --}}
            <div data-bs-toggle="collapse" data-bs-target="#{{ $moId }}"
                 style="padding:14px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:{{ $isFirst ? 'linear-gradient(90deg,#e6f5f3,#f9fffe)' : '' }};">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="background:{{ $isFirst ? 'var(--teal)' : '#e9ecef' }};color:{{ $isFirst ? '#fff' : 'var(--text-main)' }};border-radius:10px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem;{{ $isFirst ? 'color:var(--teal);' : '' }}">{{ $mo->month_label }}{{ $isFirst ? ' — Current Month' : '' }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $mo->total }} appointments &nbsp;·&nbsp; {{ $mo->unique_patients }} unique patients</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="background:#d4f5e4;color:#0f6b3a;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600;">{{ $mo->completion_rate }}% done</span>
                    @if($mo->no_show > 0)
                        <span style="background:#fff3e0;color:#b86e00;padding:3px 10px;border-radius:20px;font-size:.75rem;">{{ $mo->no_show }} no-show</span>
                    @endif
                    <i class="bi bi-chevron-down" style="color:var(--text-muted);transition:.2s;"></i>
                </div>
            </div>

            {{-- Collapsible body --}}
            <div class="collapse {{ $isFirst ? 'show' : '' }}" id="{{ $moId }}">
                <div style="padding:20px;border-top:1px solid var(--border);">

                    {{-- 5 mini stat cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#f9fffe;border:1px solid #b2dfdb;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:var(--teal);">{{ $mo->total }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Total</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#fff4e5;border:1px solid #ffd59e;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:#b86e00;">{{ $mo->pending }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Pending</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#e7f1ff;border:1px solid #9ec5fe;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:#0056b3;">{{ $mo->confirmed }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Confirmed</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#d4f5e4;border:1px solid #86e0b4;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:#059386;">{{ $mo->completed }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Completed</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#fde8e8;border:1px solid #f5a0a0;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:#c0392b;">{{ $mo->cancelled }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Cancelled</div>
                            </div>
                        </div>
                        @if($mo->no_show > 0)
                        <div class="col-6 col-sm-4 col-xl">
                            <div style="background:#f0ecff;border:1px solid #c9b8f0;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.4rem;font-weight:800;color:#6f42c1;">{{ $mo->no_show }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">No-show</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Completion & cancellation rate bars --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div style="font-size:.8rem;font-weight:600;margin-bottom:6px;display:flex;justify-content:space-between;">
                                <span><i class="bi bi-check-circle-fill" style="color:#059386;"></i> Completion Rate</span>
                                <span style="color:#059386;">{{ $mo->completion_rate }}%</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:8px;height:10px;">
                                <div style="background:var(--teal);width:{{ $mo->completion_rate }}%;height:10px;border-radius:8px;transition:width .4s;"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:.8rem;font-weight:600;margin-bottom:6px;display:flex;justify-content:space-between;">
                                <span><i class="bi bi-x-circle-fill" style="color:#c0392b;"></i> Cancellation Rate</span>
                                <span style="color:#c0392b;">{{ $mo->cancellation_rate }}%</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:8px;height:10px;">
                                <div style="background:#c0392b;width:{{ $mo->cancellation_rate }}%;height:10px;border-radius:8px;transition:width .4s;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Top services + Top dentists --}}
                    <div class="row g-3">

                        {{-- Top Services --}}
                        <div class="col-md-6">
                            <div style="background:#f9fffe;border-radius:10px;border:1px solid var(--border);padding:14px 16px;">
                                <div style="font-weight:700;font-size:.85rem;margin-bottom:12px;"><i class="bi bi-heart-pulse-fill" style="color:#c0392b;"></i> Top Services</div>
                                @forelse($mo->top_services as $svc)
                                @php $spct = $mo->total > 0 ? round(($svc->cnt / $mo->total) * 100) : 0; @endphp
                                <div style="margin-bottom:8px;">
                                    <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:3px;">
                                        <span style="font-weight:600;">{{ $svc->name }}</span>
                                        <span style="color:var(--text-muted);">{{ $svc->cnt }} ({{ $spct }}%)</span>
                                    </div>
                                    <div style="background:#e9ecef;border-radius:4px;height:5px;">
                                        <div style="background:var(--teal);width:{{ $spct }}%;height:5px;border-radius:4px;"></div>
                                    </div>
                                </div>
                                @empty
                                <p style="color:var(--text-muted);font-size:.78rem;">No service data</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Top Dentists --}}
                        <div class="col-md-6">
                            <div style="background:#f9fffe;border-radius:10px;border:1px solid var(--border);padding:14px 16px;">
                                <div style="font-weight:700;font-size:.85rem;margin-bottom:12px;"><i class="bi bi-person-badge-fill" style="color:#0056b3;"></i> Top Dentists</div>
                                @forelse($mo->top_dentists as $di => $doc)
                                <div style="display:flex;align-items:center;gap:10px;{{ !$loop->last ? 'padding-bottom:8px;margin-bottom:8px;border-bottom:1px solid var(--border);' : '' }}">
                                    <span style="font-size:1rem;font-weight:800;color:{{ $di===0 ? '#f5a623' : ($di===1 ? '#9e9e9e' : '#cd7f32') }};min-width:20px;">#{{ $di+1 }}</span>
                                    @if($doc->image)
                                        <img src="{{ asset($doc->image) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
                                    @else
                                        <div style="width:32px;height:32px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.8rem;">{{ strtoupper(substr($doc->name,0,1)) }}</div>
                                    @endif
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-weight:600;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
                                        <div style="font-size:.7rem;color:var(--text-muted);">{{ $doc->completed_count }} completed</div>
                                    </div>
                                    <span style="background:#e6f5f3;color:#059386;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:600;">{{ $doc->appt_count }}</span>
                                </div>
                                @empty
                                <p style="color:var(--text-muted);font-size:.78rem;">No dentist data</p>
                                @endforelse
                            </div>
                        </div>

                    </div>{{-- /row services+dentists --}}

                    {{-- ── Financial Section ────────────────────────────────── --}}
                    <div style="margin-top:20px;">
                        <div style="font-weight:700;font-size:.87rem;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-cash-coin" style="color:#059386;font-size:1rem;"></i> Financial Summary
                        </div>

                        {{-- Payments from payment records --}}
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-sm-3">
                                <div style="background:#f9fffe;border:1px solid #b2dfdb;border-radius:10px;padding:12px 14px;text-align:center;">
                                    <div style="font-size:1.2rem;font-weight:800;color:var(--teal);">SAR {{ number_format($mo->pay_revenue, 0) }}</div>
                                    <div style="font-size:.7rem;color:var(--text-muted);">Revenue Collected</div>
                                    <div style="font-size:.68rem;color:var(--text-muted);margin-top:2px;">{{ $mo->pay_count }} paid</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div style="background:#fff4e5;border:1px solid #ffd59e;border-radius:10px;padding:12px 14px;text-align:center;">
                                    <div style="font-size:1.2rem;font-weight:800;color:#b86e00;">SAR {{ number_format($mo->pay_pending, 0) }}</div>
                                    <div style="font-size:.7rem;color:var(--text-muted);">Pending Payments</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div style="background:#fde8e8;border:1px solid #f5a0a0;border-radius:10px;padding:12px 14px;text-align:center;">
                                    <div style="font-size:1.2rem;font-weight:800;color:#c0392b;">SAR {{ number_format($mo->pay_refunded, 0) }}</div>
                                    <div style="font-size:.7rem;color:var(--text-muted);">Refunded</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div style="background:#f0ecff;border:1px solid #c9b8f0;border-radius:10px;padding:12px 14px;text-align:center;">
                                    @php
                                        $netPay = $mo->pay_revenue - $mo->pay_refunded;
                                    @endphp
                                    <div style="font-size:1.2rem;font-weight:800;color:#6f42c1;">SAR {{ number_format($netPay, 0) }}</div>
                                    <div style="font-size:.7rem;color:var(--text-muted);">Net Collected</div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment method: Credit Card only --}}
                        @if($mo->pay_revenue > 0)
                        <div style="background:#e7f1ff;border:1px solid #9ec5fe;border-radius:10px;padding:11px 16px;margin-bottom:14px;display:flex;align-items:center;gap:10px;">
                            <i class="bi bi-credit-card-fill" style="color:#0056b3;font-size:1.2rem;flex-shrink:0;"></i>
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:.82rem;color:#0056b3;">All payments via Credit Card</div>
                                <div style="font-size:.7rem;color:var(--text-muted);">{{ $mo->pay_count }} completed &nbsp;·&nbsp; SAR {{ number_format($mo->pay_revenue, 0) }} collected</div>
                            </div>
                            <span style="background:#0056b3;color:#fff;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;">SAR {{ number_format($mo->pay_credit, 0) }}</span>
                        </div>
                        @endif

                        {{-- Financial record (admin-entered data) --}}
                        @if($mo->fin_revenue !== null)
                        <div style="background:linear-gradient(135deg,#e6f5f3,#f9fffe);border:1.5px solid var(--teal);border-radius:10px;padding:16px 18px;">
                            <div style="font-weight:700;font-size:.82rem;color:var(--teal);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                                <i class="bi bi-bar-chart-steps"></i> Financial Report (Admin Records)
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div style="text-align:center;">
                                        <div style="font-size:1.15rem;font-weight:800;color:#059386;">SAR {{ number_format($mo->fin_revenue, 2) }}</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Total Revenue</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div style="text-align:center;">
                                        <div style="font-size:1.15rem;font-weight:800;color:#c0392b;">SAR {{ number_format($mo->fin_costs, 2) }}</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Total Costs</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div style="text-align:center;">
                                        @php $profitColor = $mo->fin_profit >= 0 ? '#059386' : '#c0392b'; @endphp
                                        <div style="font-size:1.15rem;font-weight:800;color:{{ $profitColor }};">
                                            {{ $mo->fin_profit >= 0 ? '+' : '' }}SAR {{ number_format($mo->fin_profit, 2) }}
                                        </div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Net Profit</div>
                                    </div>
                                </div>
                            </div>
                            @if($mo->fin_revenue > 0)
                            <div style="margin-top:12px;">
                                <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:4px;">
                                    Profit margin: <strong style="color:{{ $profitColor }};">{{ $mo->fin_revenue > 0 ? round(($mo->fin_profit / $mo->fin_revenue) * 100, 1) : 0 }}%</strong>
                                </div>
                                <div style="background:#e9ecef;border-radius:8px;height:8px;">
                                    @php $marginPct = $mo->fin_revenue > 0 ? max(0, min(100, round(($mo->fin_profit / $mo->fin_revenue) * 100))) : 0; @endphp
                                    <div style="background:{{ $profitColor }};width:{{ $marginPct }}%;height:8px;border-radius:8px;transition:width .4s;"></div>
                                </div>
                            </div>
                            @endif
                            @if($mo->fin_notes)
                            <div style="margin-top:10px;font-size:.78rem;color:var(--text-muted);background:#fff;border-radius:6px;padding:8px 10px;border-left:3px solid var(--teal);">
                                <i class="bi bi-sticky me-1"></i>{{ $mo->fin_notes }}
                            </div>
                            @endif
                        </div>
                        @else
                        <div style="background:#f9f9f9;border:1px dashed #ccc;border-radius:10px;padding:12px 16px;font-size:.78rem;color:var(--text-muted);text-align:center;">
                            <i class="bi bi-info-circle me-1"></i>No financial report recorded for this month.
                        </div>
                        @endif

                    </div>{{-- /financial section --}}
            </div>{{-- /collapse --}}

        </div>{{-- /panel month card --}}
        @endforeach

        @endif

    </div>{{-- /tab-months --}}

</div>{{-- /container-fluid --}}

<script>
function apptShowTab(tab) {
    document.getElementById('tab-all').style.display    = tab === 'all'    ? '' : 'none';
    document.getElementById('tab-months').style.display = tab === 'months' ? '' : 'none';

    var btnAll    = document.getElementById('btn-tab-all');
    var btnMonths = document.getElementById('btn-tab-months');

    if (tab === 'all') {
        btnAll.style.cssText    = 'padding:9px 20px;border-radius:10px;border:none;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:var(--teal);color:#fff;box-shadow:0 2px 6px rgba(5,147,134,.3);';
        btnMonths.style.cssText = 'padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);';
    } else {
        btnMonths.style.cssText = 'padding:9px 20px;border-radius:10px;border:none;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:var(--teal);color:#fff;box-shadow:0 2px 6px rgba(5,147,134,.3);';
        btnAll.style.cssText    = 'padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);';
    }
}

function scrollToMonth(monthSort) {
    apptShowTab('months');
    var el = document.getElementById('anchor-' + monthSort.replace('-', ''));
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // auto-expand if collapsed
        var collapseEl = el.querySelector('.collapse');
        if (collapseEl && !collapseEl.classList.contains('show')) {
            new bootstrap.Collapse(collapseEl, { toggle: true });
        }
    }
}
</script>
@endsection

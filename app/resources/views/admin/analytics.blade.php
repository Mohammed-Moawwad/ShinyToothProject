@extends('admin.layout')
@section('page-title', 'Analytics & Reports')
@section('content')
<style>
.analytics-tab-bar { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.analytics-tab {
    padding:.45rem 1.1rem; border-radius:20px; font-size:.8rem; font-weight:600;
    border:1px solid var(--border); color:var(--text-muted); background:var(--panel-bg);
    cursor:pointer; transition:all .15s ease;
}
.analytics-tab.active, .analytics-tab:hover {
    background:var(--teal); color:#fff; border-color:var(--teal);
}
.analytics-section { display:none; }
.analytics-section.active { display:block; }
.kpi-card {
    background:var(--panel-bg); border:1px solid var(--border); border-radius:14px;
    padding:1.1rem 1.25rem; display:flex; align-items:center; gap:1rem;
}
.kpi-icon {
    width:46px; height:46px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0;
}
.kpi-val { font-size:1.35rem; font-weight:800; color:var(--text-main); line-height:1.1; }
.kpi-lbl { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.rate-bar { height:8px; border-radius:4px; background:var(--border); overflow:hidden; margin-top:.3rem; }
.rate-fill { height:100%; border-radius:4px; }
.leaderboard-row {
    display:flex; align-items:center; gap:.7rem;
    padding:.55rem 0; border-bottom:1px solid var(--border);
}
.leaderboard-row:last-child { border-bottom:none; }
.lb-avatar {
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:700; color:#fff;
    background:linear-gradient(135deg,#0d9e8a,#3b82f6);
}
.lb-rank { font-size:.7rem; color:var(--text-muted); width:16px; text-align:center; flex-shrink:0; }
.lb-bar-wrap { flex:1; }
.lb-bar { height:5px; border-radius:3px; background:var(--border); overflow:hidden; margin-top:.2rem; }
.lb-bar-fill { height:100%; border-radius:3px; }
.dow-bar-wrap { display:flex; flex-direction:column; align-items:center; gap:.3rem; flex:1; }
.dow-bar-col { width:100%; background:var(--border); border-radius:4px 4px 0 0; overflow:hidden; position:relative; }
.dow-bar-inner { position:absolute; bottom:0; width:100%; border-radius:4px 4px 0 0; }
.star-full { color:#f59e0b; }
.star-empty { color:var(--border); }
.fin-positive { color:#10b981; font-weight:700; }
.fin-negative { color:#ef4444; font-weight:700; }
.chart-box { height:220px; }
</style>

<div class="container-fluid px-0">

    {{-- ── Report Generation Bar ───────────────────────────── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem;background:linear-gradient(135deg,#003263,#0d9e8a);border-radius:14px;padding:1rem 1.4rem;">
        <div>
            <div style="font-size:.95rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:.5rem;">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Monthly Performance Report
            </div>
            <div style="font-size:.75rem;color:rgba(255,255,255,.75);margin-top:.2rem;">
                Generate a full PDF report — revenue, costs, profit, appointments, patients &amp; more
            </div>
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#reportModal"
                style="background:#fff;color:#003263;border:none;border-radius:10px;padding:.55rem 1.25rem;font-size:.82rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.5rem;white-space:nowrap;">
            <i class="bi bi-download"></i> Generate PDF Report
        </button>
    </div>

    {{-- ── Report Period Modal ──────────────────────────────── --}}
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                    <h5 class="modal-title" style="font-size:.95rem;">
                        <i class="bi bi-file-earmark-pdf-fill me-2" style="color:#ef4444;"></i>Generate Performance Report
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('admin.analytics.report') }}" target="_blank">
                    <div class="modal-body" style="padding:1.25rem;">
                        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1rem;">
                            Select the month you want to generate the report for. Reports are based on the last day of the selected month.
                        </p>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label" style="font-size:.8rem;font-weight:600;">Month</label>
                                <select name="month" class="form-select">
                                    @php $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December']; @endphp
                                    @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ $num == now()->subMonth()->month ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:.8rem;font-weight:600;">Year</label>
                                <select name="year" class="form-select">
                                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ $y == now()->subMonth()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div style="background:rgba(13,158,138,.08);border:1px solid rgba(13,158,138,.2);border-radius:8px;padding:.65rem .9rem;margin-top:1rem;font-size:.78rem;color:var(--text-main);">
                            <i class="bi bi-info-circle me-1" style="color:#0d9e8a;"></i>
                            The report covers the full calendar month and includes estimated operational costs. Reports are dated as of the last day of the selected month.
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--border);">
                        <button type="button" class="btn-outline-teal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" style="background:#ef4444;border:none;color:#fff;font-size:.82rem;font-weight:700;padding:.5rem 1.25rem;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:.4rem;">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── KPI Row ──────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(13,158,138,.15);color:#0d9e8a;"><i class="bi bi-calendar2-check-fill"></i></div>
                <div>
                    <div class="kpi-val">{{ number_format($totalAppointments) }}</div>
                    <div class="kpi-lbl">Total Appointments</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <div class="kpi-val">SAR {{ number_format($totalRevenue, 0) }}</div>
                    <div class="kpi-lbl">Total Revenue Collected</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(59,130,246,.15);color:#3b82f6;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="kpi-val">{{ number_format($totalPatients) }}</div>
                    <div class="kpi-lbl">Total Patients</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6;"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="kpi-val">{{ number_format($totalDentists) }}</div>
                    <div class="kpi-lbl">Total Dentists</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab Bar ──────────────────────────────────────────── --}}
    <div class="analytics-tab-bar">
        <button class="analytics-tab active" onclick="switchTab('appointments',this)"><i class="bi bi-calendar2-check me-1"></i>Appointments</button>
        <button class="analytics-tab" onclick="switchTab('revenue',this)"><i class="bi bi-graph-up-arrow me-1"></i>Revenue</button>
        <button class="analytics-tab" onclick="switchTab('growth',this)"><i class="bi bi-people me-1"></i>User Growth</button>
        <button class="analytics-tab" onclick="switchTab('services',this)"><i class="bi bi-briefcase me-1"></i>Services</button>
        <button class="analytics-tab" onclick="switchTab('doctors',this)"><i class="bi bi-person-badge me-1"></i>Doctors</button>
        <button class="analytics-tab" onclick="switchTab('financial',this)"><i class="bi bi-file-earmark-bar-graph me-1"></i>Financial</button>
    </div>

    {{-- ══ TAB: APPOINTMENTS ══════════════════════════════════ --}}
    <div id="tab-appointments" class="analytics-section active">
        {{-- Rate stat cards --}}
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-check-circle-fill"></i></div>
                    <div style="flex:1;">
                        <div class="kpi-val" style="color:#10b981;">{{ $completedAppts }}</div>
                        <div class="kpi-lbl">Completed</div>
                        <div class="rate-bar"><div class="rate-fill" style="width:{{ $completionRate }}%;background:#10b981;"></div></div>
                        <div style="font-size:.65rem;color:var(--text-muted);margin-top:.15rem;">{{ $completionRate }}% completion rate</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-hourglass-split"></i></div>
                    <div style="flex:1;">
                        <div class="kpi-val" style="color:#f59e0b;">{{ $pendingAppts }}</div>
                        <div class="kpi-lbl">Pending</div>
                        <div class="rate-bar"><div class="rate-fill" style="width:{{ $totalAppointments > 0 ? round($pendingAppts/$totalAppointments*100) : 0 }}%;background:#f59e0b;"></div></div>
                        <div style="font-size:.65rem;color:var(--text-muted);margin-top:.15rem;">{{ $totalAppointments > 0 ? round($pendingAppts/$totalAppointments*100) : 0 }}% of total</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444;"><i class="bi bi-x-circle-fill"></i></div>
                    <div style="flex:1;">
                        <div class="kpi-val" style="color:#ef4444;">{{ $cancelledAppts }}</div>
                        <div class="kpi-lbl">Cancelled</div>
                        <div class="rate-bar"><div class="rate-fill" style="width:{{ $cancellationRate }}%;background:#ef4444;"></div></div>
                        <div style="font-size:.65rem;color:var(--text-muted);margin-top:.15rem;">{{ $cancellationRate }}% cancellation rate</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#818cf8;"><i class="bi bi-bar-chart-fill"></i></div>
                    <div>
                        <div class="kpi-val">{{ $appointmentsByMonth->count() > 0 ? number_format(round($totalAppointments / $appointmentsByMonth->count())) : 0 }}</div>
                        <div class="kpi-lbl">Avg. Appts / Month</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly chart + day-of-week --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-8">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-bar-chart-fill"></i> Appointments by Month</div>
                    </div>
                    <div style="padding:1.25rem;"><div class="chart-box"><canvas id="appointmentsChart"></canvas></div></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-calendar-week"></i> By Day of Week</div>
                    </div>
                    <div style="padding:1.25rem 1rem;">
                        @php $maxDow = $apptByDow->max('count') ?: 1; @endphp
                        @foreach($apptByDow as $dow)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span style="font-size:.72rem;color:var(--text-muted);width:26px;">{{ substr($dow->day_name,0,3) }}</span>
                            <div class="rate-bar" style="flex:1;height:7px;">
                                <div class="rate-fill" style="width:{{ round($dow->count/$maxDow*100) }}%;background:var(--teal);"></div>
                            </div>
                            <span style="font-size:.72rem;font-weight:700;color:var(--text-main);width:24px;text-align:right;">{{ $dow->count }}</span>
                        </div>
                        @endforeach
                        @if($apptByDow->isEmpty())
                            <p style="color:var(--text-muted);font-size:.82rem;">No data yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Status breakdown panel --}}
        <div class="panel mb-3">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-pie-chart-fill"></i> Status Breakdown</div>
            </div>
            <div style="padding:1rem 1.25rem;">
                <div class="row g-3">
                    @php
                        $allStatuses = ['completed' => ['#10b981','check-circle-fill'], 'pending' => ['#f59e0b','hourglass-split'], 'cancelled' => ['#ef4444','x-circle-fill'], 'confirmed' => ['#3b82f6','calendar-check-fill']];
                    @endphp
                    @foreach($appointmentsByStatus as $st => $row)
                    @php $col = $allStatuses[$st][0] ?? '#6b7280'; $ico = $allStatuses[$st][1] ?? 'circle'; $pct = $totalAppointments > 0 ? round($row->count/$totalAppointments*100) : 0; @endphp
                    <div class="col-sm-6 col-lg-3">
                        <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.75rem;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.78rem;font-weight:600;color:var(--text-main);"><i class="bi bi-{{ $ico }}" style="color:{{ $col }};margin-right:.3rem;"></i>{{ ucfirst($st) }}</span>
                                <span style="font-size:.72rem;color:{{ $col }};font-weight:700;">{{ $pct }}%</span>
                            </div>
                            <div class="rate-bar"><div class="rate-fill" style="width:{{ $pct }}%;background:{{ $col }};"></div></div>
                            <div style="font-size:.7rem;color:var(--text-muted);margin-top:.3rem;">{{ number_format($row->count) }} appointments</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: REVENUE ═══════════════════════════════════════ --}}
    <div id="tab-revenue" class="analytics-section">
        {{-- Revenue KPIs --}}
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-check-lg"></i></div>
                    <div><div class="kpi-val">SAR {{ number_format($totalRevenue, 0) }}</div><div class="kpi-lbl">Collected (Completed)</div></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="kpi-val">SAR {{ number_format($totalPending, 0) }}</div><div class="kpi-lbl">Pending Payments</div></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444;"><i class="bi bi-arrow-counterclockwise"></i></div>
                    <div><div class="kpi-val">SAR {{ number_format($totalRefunded, 0) }}</div><div class="kpi-lbl">Refunded</div></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#818cf8;"><i class="bi bi-calculator"></i></div>
                    <div><div class="kpi-val">SAR {{ number_format($avgTransaction, 0) }}</div><div class="kpi-lbl">Avg. Transaction</div></div>
                </div>
            </div>
        </div>

        {{-- Revenue chart + service revenue --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-graph-up-arrow"></i> Monthly Revenue (Completed)</div>
                    </div>
                    <div style="padding:1.25rem;"><div class="chart-box"><canvas id="paymentsChart"></canvas></div></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-briefcase-fill"></i> Revenue by Service</div>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @php $maxSvcRev = $serviceRevenue->max('revenue') ?: 1; @endphp
                        @forelse($serviceRevenue as $svc)
                        <div class="leaderboard-row">
                            <span class="lb-rank">{{ $loop->iteration }}</span>
                            <div class="lb-bar-wrap">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.78rem;font-weight:600;color:var(--text-main);">{{ $svc->name }}</span>
                                    <span style="font-size:.72rem;color:#10b981;font-weight:700;">SAR {{ number_format($svc->revenue, 0) }}</span>
                                </div>
                                <div class="lb-bar"><div class="lb-bar-fill" style="width:{{ round($svc->revenue/$maxSvcRev*100) }}%;background:#10b981;"></div></div>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--text-muted);font-size:.82rem;">No revenue data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment status breakdown --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-credit-card-2-front-fill"></i> Payment Status Breakdown</div>
            </div>
            <div style="padding:1rem 1.25rem;">
                <div class="row g-3">
                    @foreach($paymentsByStatus as $st => $row)
                    @php
                        $cols = ['completed'=>'#10b981','pending'=>'#f59e0b','refunded'=>'#ef4444'];
                        $col  = $cols[$st] ?? '#6b7280';
                    @endphp
                    <div class="col-sm-4">
                        <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:.9rem;text-align:center;">
                            <div style="font-size:1.1rem;font-weight:800;color:{{ $col }};">SAR {{ number_format($row->total ?? 0, 0) }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">{{ ucfirst($st) }} &mdash; {{ number_format($row->count) }} txns</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: USER GROWTH ═══════════════════════════════════ --}}
    <div id="tab-growth" class="analytics-section">
        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(59,130,246,.15);color:#3b82f6;"><i class="bi bi-person-fill-add"></i></div>
                    <div>
                        <div class="kpi-val">{{ $patientGrowth->sum('count') }}</div>
                        <div class="kpi-lbl">Patients (last 12 months)</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6;"><i class="bi bi-person-badge-fill"></i></div>
                    <div>
                        <div class="kpi-val">{{ $dentistGrowth->sum('count') }}</div>
                        <div class="kpi-lbl">Dentists (last 12 months)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-people-fill"></i> Patient Growth</div>
                    </div>
                    <div style="padding:1.25rem;"><div class="chart-box"><canvas id="patientGrowthChart"></canvas></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-person-badge-fill"></i> Dentist Growth</div>
                    </div>
                    <div style="padding:1.25rem;"><div class="chart-box"><canvas id="dentistGrowthChart"></canvas></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: SERVICES ══════════════════════════════════════ --}}
    <div id="tab-services" class="analytics-section">
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-trophy-fill"></i> Most Booked Services</div>
                        <span class="count-badge">Top {{ $popularServices->count() }}</span>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @php $maxAppts = $popularServices->max('appointments_count') ?: 1; @endphp
                        @forelse($popularServices as $svc)
                        <div class="leaderboard-row">
                            <span class="lb-rank">{{ $loop->iteration }}</span>
                            <div class="lb-bar-wrap">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.82rem;font-weight:600;color:var(--text-main);">{{ $svc->name }}</span>
                                    <span style="font-size:.75rem;color:var(--teal);font-weight:700;">{{ number_format($svc->appointments_count) }}</span>
                                </div>
                                <div class="lb-bar"><div class="lb-bar-fill" style="width:{{ round($svc->appointments_count/$maxAppts*100) }}%;background:var(--teal);"></div></div>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--text-muted);font-size:.82rem;">No data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-cash-coin"></i> Highest Revenue Services</div>
                        <span class="count-badge">Top {{ $serviceRevenue->count() }}</span>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @php $maxRev = $serviceRevenue->max('revenue') ?: 1; @endphp
                        @forelse($serviceRevenue as $svc)
                        <div class="leaderboard-row">
                            <span class="lb-rank">{{ $loop->iteration }}</span>
                            <div class="lb-bar-wrap">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.82rem;font-weight:600;color:var(--text-main);">{{ $svc->name }}</span>
                                    <div class="text-end">
                                        <span style="font-size:.75rem;color:#10b981;font-weight:700;">SAR {{ number_format($svc->revenue, 0) }}</span>
                                        <span style="font-size:.65rem;color:var(--text-muted);display:block;">{{ $svc->cnt }} paid</span>
                                    </div>
                                </div>
                                <div class="lb-bar"><div class="lb-bar-fill" style="width:{{ round($svc->revenue/$maxRev*100) }}%;background:#10b981;"></div></div>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--text-muted);font-size:.82rem;">No revenue data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: DOCTORS ═══════════════════════════════════════ --}}
    <div id="tab-doctors" class="analytics-section">
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <div class="kpi-val">{{ $avgDoctorRating > 0 ? $avgDoctorRating.'/5' : 'N/A' }}</div>
                        <div class="kpi-lbl">Avg. Doctor Rating</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-chat-quote-fill"></i></div>
                    <div>
                        <div class="kpi-val">{{ number_format($ratingDistribution->sum('cnt')) }}</div>
                        <div class="kpi-lbl">Total Reviews</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            {{-- Top by appts --}}
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-calendar2-check"></i> Top by Appointments</div>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @php $maxDA = $topDentistsByAppts->max('appt_count') ?: 1; @endphp
                        @forelse($topDentistsByAppts as $d)
                        <div class="leaderboard-row">
                            <div class="lb-avatar">{{ strtoupper(substr($d->name,0,1)) }}</div>
                            <div class="lb-bar-wrap">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.78rem;font-weight:600;color:var(--text-main);">Dr. {{ $d->name }}</span>
                                    <span style="font-size:.72rem;color:var(--teal);font-weight:700;">{{ $d->appt_count }}</span>
                                </div>
                                <div class="lb-bar"><div class="lb-bar-fill" style="width:{{ round($d->appt_count/$maxDA*100) }}%;background:var(--teal);"></div></div>
                            </div>
                        </div>
                        @empty<p style="color:var(--text-muted);font-size:.82rem;">No data.</p>@endforelse
                    </div>
                </div>
            </div>
            {{-- Top by revenue --}}
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-cash-coin"></i> Top by Revenue</div>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @php $maxDR = $topDentistsByRevenue->max('revenue') ?: 1; @endphp
                        @forelse($topDentistsByRevenue as $d)
                        <div class="leaderboard-row">
                            <div class="lb-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">{{ strtoupper(substr($d->name,0,1)) }}</div>
                            <div class="lb-bar-wrap">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.78rem;font-weight:600;color:var(--text-main);">Dr. {{ $d->name }}</span>
                                    <span style="font-size:.72rem;color:#10b981;font-weight:700;">SAR {{ number_format($d->revenue,0) }}</span>
                                </div>
                                <div class="lb-bar"><div class="lb-bar-fill" style="width:{{ round($d->revenue/$maxDR*100) }}%;background:#10b981;"></div></div>
                            </div>
                        </div>
                        @empty<p style="color:var(--text-muted);font-size:.82rem;">No revenue data.</p>@endforelse
                    </div>
                </div>
            </div>
            {{-- Ratings --}}
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-star-fill"></i> Top Rated Doctors</div>
                    </div>
                    <div style="padding:.75rem 1.25rem;">
                        @forelse($topRatedDoctors as $d)
                        <div class="leaderboard-row">
                            <div class="lb-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">{{ strtoupper(substr($d->name,0,1)) }}</div>
                            <div style="flex:1;">
                                <div style="font-size:.78rem;font-weight:600;color:var(--text-main);">Dr. {{ $d->name }}</div>
                                <div style="display:flex;align-items:center;gap:.3rem;margin-top:.15rem;">
                                    @for($s=1;$s<=5;$s++)<i class="bi bi-star-fill {{ $s <= round($d->avg_rating) ? 'star-full' : 'star-empty' }}" style="font-size:.65rem;"></i>@endfor
                                    <span style="font-size:.65rem;color:var(--text-muted);">{{ $d->avg_rating }} ({{ $d->total_reviews }} reviews)</span>
                                </div>
                            </div>
                        </div>
                        @empty<p style="color:var(--text-muted);font-size:.82rem;">No ratings yet.</p>@endforelse
                        @if($ratingDistribution->isNotEmpty())
                        <div style="border-top:1px solid var(--border);margin-top:.75rem;padding-top:.75rem;">
                            <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:.4rem;">Rating Distribution</div>
                            @for($s=5;$s>=1;$s--)
                            @php $rc = $ratingDistribution[$s]->cnt ?? 0; $rTotal = $ratingDistribution->sum('cnt'); $rPct = $rTotal > 0 ? round($rc/$rTotal*100) : 0; @endphp
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span style="font-size:.68rem;width:10px;color:var(--text-muted);">{{ $s }}</span>
                                <i class="bi bi-star-fill star-full" style="font-size:.65rem;"></i>
                                <div class="rate-bar" style="flex:1;height:5px;"><div class="rate-fill" style="width:{{ $rPct }}%;background:#f59e0b;"></div></div>
                                <span style="font-size:.65rem;color:var(--text-muted);width:20px;text-align:right;">{{ $rc }}</span>
                            </div>
                            @endfor
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: FINANCIAL ═════════════════════════════════════ --}}
    <div id="tab-financial" class="analytics-section">
        {{-- Financial KPIs from financial table --}}
        @if($financialSummary && ($financialSummary->total_revenue > 0 || $financialSummary->total_costs > 0))
        <div class="row g-3 mb-3">
            <div class="col-sm-4">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <div class="kpi-val fin-positive">SAR {{ number_format($financialSummary->total_revenue, 0) }}</div>
                        <div class="kpi-lbl">Total Revenue (Reports)</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444;"><i class="bi bi-graph-down-arrow"></i></div>
                    <div>
                        <div class="kpi-val fin-negative">SAR {{ number_format($financialSummary->total_costs, 0) }}</div>
                        <div class="kpi-lbl">Total Costs (Reports)</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:{{ $financialSummary->profit >= 0 ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)' }};color:{{ $financialSummary->profit >= 0 ? '#10b981' : '#ef4444' }};"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="kpi-val {{ $financialSummary->profit >= 0 ? 'fin-positive' : 'fin-negative' }}">SAR {{ number_format($financialSummary->profit, 0) }}</div>
                        <div class="kpi-lbl">Net Profit (Reports)</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Financial records table --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-file-earmark-bar-graph-fill"></i> Financial Records</div>
                <span class="count-badge">{{ $financialRecords->count() }} records</span>
            </div>
            @if($financialRecords->isNotEmpty())
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Revenue</th>
                            <th>Costs</th>
                            <th>Profit</th>
                            <th>Margin</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($financialRecords as $rec)
                        @php
                            $monthName = \Carbon\Carbon::createFromDate($rec->year, $rec->month, 1)->format('M Y');
                            $margin    = $rec->total_revenue > 0 ? round($rec->profit / $rec->total_revenue * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $monthName }}</strong></td>
                            <td style="color:#10b981;font-weight:600;">SAR {{ number_format($rec->total_revenue, 2) }}</td>
                            <td style="color:#ef4444;">SAR {{ number_format($rec->total_costs, 2) }}</td>
                            <td class="{{ $rec->profit >= 0 ? 'fin-positive' : 'fin-negative' }}">SAR {{ number_format($rec->profit, 2) }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rate-bar" style="width:60px;"><div class="rate-fill" style="width:{{ max(0,min(100,$margin)) }}%;background:{{ $margin >= 0 ? '#10b981' : '#ef4444' }};"></div></div>
                                    <span style="font-size:.75rem;">{{ $margin }}%</span>
                                </div>
                            </td>
                            <td style="font-size:.75rem;color:var(--text-muted);max-width:200px;">{{ $rec->notes ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="bi bi-file-earmark-bar-graph" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:.5rem;"></i>
                No financial records found.
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('extra-js')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('.analytics-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.analytics-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

const darkGrid  = 'rgba(0,50,99,0.08)';
const tickColor = '#6c757d';
const baseOpts  = () => ({
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: darkGrid }, ticks: { color: tickColor } },
        x: { grid: { display: false }, ticks: { color: tickColor, maxRotation: 45 } }
    }
});

new Chart(document.getElementById('appointmentsChart'), {
    type: 'bar',
    data: {
        labels: @json($appointmentsByMonth->pluck('month')),
        datasets: [{ label: 'Appointments', data: @json($appointmentsByMonth->pluck('count')),
            backgroundColor: 'rgba(13,158,138,0.2)', borderColor: '#0d9e8a',
            borderWidth: 2, borderRadius: 6 }]
    },
    options: baseOpts()
});

new Chart(document.getElementById('paymentsChart'), {
    type: 'line',
    data: {
        labels: @json($paymentsByMonth->pluck('month')),
        datasets: [{ label: 'Revenue (SAR)', data: @json($paymentsByMonth->pluck('total')),
            borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.07)',
            tension: 0.4, fill: true, pointBackgroundColor: '#10b981',
            pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4 }]
    },
    options: baseOpts()
});

new Chart(document.getElementById('patientGrowthChart'), {
    type: 'line',
    data: {
        labels: @json($patientGrowth->pluck('month')),
        datasets: [{ label: 'New Patients', data: @json($patientGrowth->pluck('count')),
            borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.07)',
            tension: 0.4, fill: true, pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4 }]
    },
    options: baseOpts()
});

new Chart(document.getElementById('dentistGrowthChart'), {
    type: 'line',
    data: {
        labels: @json($dentistGrowth->pluck('month')),
        datasets: [{ label: 'New Dentists', data: @json($dentistGrowth->pluck('count')),
            borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.07)',
            tension: 0.4, fill: true, pointBackgroundColor: '#8b5cf6',
            pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4 }]
    },
    options: baseOpts()
});
</script>
@endsection

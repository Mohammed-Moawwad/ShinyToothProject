@extends('admin.layout')
@section('page-title', 'Payments & Financial Reports')
@section('content')
<div class="container-fluid px-0">

    {{-- Tab toggle --}}
    <div class="d-flex gap-2 mb-4">
        <button id="btn-tab-overview" onclick="payShowTab('overview')"
            style="padding:9px 20px;border-radius:10px;border:none;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:var(--teal);color:#fff;box-shadow:0 2px 6px rgba(5,147,134,.3);">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </button>
        <button id="btn-tab-list" onclick="payShowTab('list')"
            style="padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);">
            <i class="bi bi-list-ul"></i> All Payments
        </button>
        <button id="btn-tab-months" onclick="payShowTab('months')"
            style="padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);">
            <i class="bi bi-calendar3"></i> By Month
        </button>
    </div>

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TAB: OVERVIEW â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <div id="tab-overview">

        {{-- Row 1: Key financial metrics --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #059386;">
                    <i class="bi bi-cash-stack stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-num" style="color:#059386;">SAR {{ number_format($payStats['total_revenue'], 0) }}</div>
                    <div class="stat-lbl">Total Revenue</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #0f6b3a;">
                    <i class="bi bi-wallet2 stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-wallet2"></i></div>
                    <div class="stat-num" style="color:#0f6b3a;">SAR {{ number_format($payStats['net_revenue'], 0) }}</div>
                    <div class="stat-lbl">Net Revenue</div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:2px;">after refunds</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #0056b3;">
                    <i class="bi bi-calendar-day stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-calendar-day"></i></div>
                    <div class="stat-num" style="color:#0056b3;">SAR {{ number_format($payStats['today_revenue'], 0) }}</div>
                    <div class="stat-lbl">Today</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #6f42c1;">
                    <i class="bi bi-calendar-month stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#f0ecff;color:#6f42c1;"><i class="bi bi-calendar-month"></i></div>
                    <div class="stat-num" style="color:#6f42c1;">SAR {{ number_format($payStats['this_month_revenue'], 0) }}</div>
                    <div class="stat-lbl">This Month</div>
                </div>
            </div>
        </div>

        {{-- Row 2: Count / rate metrics --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <i class="bi bi-check-double stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-check-double"></i></div>
                    <div class="stat-num" style="color:#059386;">{{ $statuses['completed'] }}</div>
                    <div class="stat-lbl">Completed</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #b86e00;">
                    <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-num" style="color:#b86e00;">{{ $statuses['pending'] }}</div>
                    <div class="stat-lbl">Pending</div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:2px;">SAR {{ number_format($payStats['pending_amount'], 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #c0392b;">
                    <i class="bi bi-arrow-counterclockwise stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-arrow-counterclockwise"></i></div>
                    <div class="stat-num" style="color:#c0392b;">{{ $statuses['refunded'] }}</div>
                    <div class="stat-lbl">Refunded</div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:2px;">SAR {{ number_format($payStats['refunded_amount'], 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #059386;">
                    <i class="bi bi-graph-up-arrow stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-num" style="color:#059386;">{{ $payStats['collection_rate'] }}<small style="font-size:.45em;">%</small></div>
                    <div class="stat-lbl">Collection Rate</div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:2px;">avg SAR {{ number_format($payStats['avg_amount'], 0) }}/payment</div>
                </div>
            </div>
        </div>

        {{-- Row 3: Charts & leaderboards --}}
        <div class="row g-3 mb-4">

            {{-- Payment Performance panel --}}
            <div class="col-12 col-md-4">
                <div class="panel" style="margin-bottom:0;height:100%;">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-credit-card-fill" style="color:#0056b3;"></i> Payment Performance</div>
                    </div>
                    <div style="padding:14px 16px;">

                        {{-- Credit card badge --}}
                        <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#e7f1ff;border-radius:10px;margin-bottom:16px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:#0056b3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-credit-card-fill" style="color:#fff;font-size:1.1rem;"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:.88rem;color:#0056b3;">Credit Card — Only Method</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ $payStats['credit_stats']->cnt ?? $statuses['completed'] }} completed transactions</div>
                            </div>
                        </div>

                        {{-- Key metrics --}}
                        @php
                            $cs = $payStats['credit_stats'];
                        @endphp
                        <div class="row g-2 mb-14" style="margin-bottom:14px;">
                            <div class="col-6">
                                <div style="background:#f9fffe;border:1px solid #b2dfdb;border-radius:8px;padding:9px 12px;text-align:center;">
                                    <div style="font-size:1rem;font-weight:800;color:var(--teal);">SAR {{ number_format($cs->max_amount ?? 0, 0) }}</div>
                                    <div style="font-size:.68rem;color:var(--text-muted);">Highest</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="background:#f9fffe;border:1px solid #b2dfdb;border-radius:8px;padding:9px 12px;text-align:center;">
                                    <div style="font-size:1rem;font-weight:800;color:var(--teal);">SAR {{ number_format($cs->min_amount ?? 0, 0) }}</div>
                                    <div style="font-size:.68rem;color:var(--text-muted);">Lowest</div>
                                </div>
                            </div>
                        </div>

                        {{-- Status distribution stacked bar --}}
                        <div style="margin-bottom:10px;">
                            <div style="font-size:.78rem;font-weight:600;margin-bottom:6px;">Transaction Status</div>
                            @php
                                $tc  = $payStats['total_count'] ?: 1;
                                $cPct = round(($statuses['completed'] / $tc) * 100);
                                $pPct = round(($statuses['pending']   / $tc) * 100);
                                $rPct = 100 - $cPct - $pPct;
                            @endphp
                            <div style="display:flex;height:12px;border-radius:6px;overflow:hidden;">
                                <div style="width:{{ $cPct }}%;background:#059386;" title="Completed {{ $cPct }}%"></div>
                                <div style="width:{{ $pPct }}%;background:#f5a623;" title="Pending {{ $pPct }}%"></div>
                                <div style="width:{{ $rPct }}%;background:#c0392b;" title="Refunded {{ $rPct }}%"></div>
                            </div>
                            <div style="display:flex;gap:12px;margin-top:6px;">
                                <span style="font-size:.68rem;color:var(--text-muted);"><span style="color:#059386;">&#9632;</span> {{ $cPct }}% done</span>
                                <span style="font-size:.68rem;color:var(--text-muted);"><span style="color:#f5a623;">&#9632;</span> {{ $pPct }}% pending</span>
                                <span style="font-size:.68rem;color:var(--text-muted);"><span style="color:#c0392b;">&#9632;</span> {{ $rPct }}% refunded</span>
                            </div>
                        </div>

                        {{-- Collection rate bar --}}
                        <div style="padding-top:10px;border-top:1px solid var(--border);">
                            <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:4px;">
                                <span style="font-weight:600;"><i class="bi bi-graph-up" style="color:var(--teal);"></i> Collection Rate</span>
                                <span style="color:#059386;font-weight:700;">{{ $payStats['collection_rate'] }}%</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:6px;height:10px;">
                                <div style="background:var(--teal);width:{{ $payStats['collection_rate'] }}%;height:10px;border-radius:6px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top 5 paying patients --}}
            <div class="col-12 col-md-4">
                <div class="panel" style="margin-bottom:0;height:100%;">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-trophy-fill" style="color:#f5a623;"></i> Top Paying Patients</div>
                    </div>
                    <div style="padding:12px 16px;">
                        @php $maxPat = $payStats['top_patients']->max('total_paid') ?: 1; @endphp
                        @forelse($payStats['top_patients'] as $i => $pat)
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                <span style="font-size:1rem;font-weight:800;min-width:20px;color:{{ $i===0?'#f5a623':($i===1?'#9e9e9e':'#cd7f32') }};">#{{ $i+1 }}</span>
                                <div style="width:28px;height:28px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.75rem;flex-shrink:0;">{{ strtoupper(substr($pat->name,0,1)) }}</div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-weight:600;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $pat->name }}</div>
                                    <div style="font-size:.7rem;color:var(--text-muted);">{{ $pat->pay_count }} payments</div>
                                </div>
                                <span style="background:#e6f5f3;color:#059386;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:700;white-space:nowrap;">SAR {{ number_format($pat->total_paid,0) }}</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:4px;height:5px;">
                                @php $pp = round(($pat->total_paid / $maxPat)*100); @endphp
                                <div style="background:{{ $i===0?'#f5a623':($i===1?'#9e9e9e':($i===2?'#cd7f32':'var(--teal)')) }};width:{{ $pp }}%;height:5px;border-radius:4px;"></div>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No data yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top 5 services by revenue --}}
            <div class="col-12 col-md-4">
                <div class="panel" style="margin-bottom:0;height:100%;">
                    <div class="panel-head">
                        <div class="panel-head-title"><i class="bi bi-heart-pulse-fill" style="color:#c0392b;"></i> Revenue by Service</div>
                    </div>
                    <div style="padding:12px 16px;">
                        @php $maxSvc = $payStats['top_services']->max('total_rev') ?: 1; @endphp
                        @forelse($payStats['top_services'] as $svc)
                        @php $sp = $payStats['total_revenue'] > 0 ? round(($svc->total_rev / $payStats['total_revenue'])*100) : 0; @endphp
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:3px;">
                                <span style="font-weight:600;">{{ $svc->name }}</span>
                                <span style="color:var(--text-muted);">SAR {{ number_format($svc->total_rev,0) }} <span style="font-size:.7rem;">({{ $sp }}%)</span></span>
                            </div>
                            <div style="font-size:.7rem;color:var(--text-muted);margin-bottom:3px;">{{ $svc->pay_count }} payments</div>
                            <div style="background:#e9ecef;border-radius:4px;height:6px;">
                                @php $sw = round(($svc->total_rev / $maxSvc)*100); @endphp
                                <div style="background:#c0392b;width:{{ $sw }}%;height:6px;border-radius:4px;transition:width .4s;"></div>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No data yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue trend bar chart (6 months) --}}
        @if($payStats['monthly_trend']->isNotEmpty())
        <div class="panel mb-4">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-bar-chart-line-fill" style="color:var(--teal);"></i> Revenue Trend â€” Last 6 Months</div>
                <span class="count-badge">SAR {{ number_format($payStats['monthly_trend']->sum('total'), 0) }} total</span>
            </div>
            <div style="padding:20px 24px;">
                @php $maxTrend = $payStats['monthly_trend']->max('total') ?: 1; @endphp
                <div style="display:flex;align-items:flex-end;gap:12px;height:100px;">
                    @foreach($payStats['monthly_trend'] as $mo)
                    @php
                        $barH  = max(round(($mo->total / $maxTrend) * 80), 4);
                        $isNow = $mo->month_sort === now()->format('Y-m');
                    @endphp
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                        <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;">SAR {{ number_format($mo->total,0) }}</span>
                        <div title="{{ $mo->month_label }}"
                             style="width:100%;height:{{ $barH }}px;background:{{ $isNow ? 'var(--teal)' : '#b2dfdb' }};border-radius:5px 5px 0 0;transition:.2s;position:relative;">
                        </div>
                        <span style="font-size:.7rem;white-space:nowrap;color:{{ $isNow ? 'var(--teal)' : 'var(--text-muted)' }};font-weight:{{ $isNow ? '700' : '400' }};">{{ $mo->month_label }}</span>
                        <span style="font-size:.68rem;color:var(--text-muted);">{{ $mo->cnt }}</span>
                    </div>
                    @endforeach
                </div>
                <div style="text-align:center;font-size:.72rem;color:var(--text-muted);margin-top:6px;">number below bar = transaction count</div>
            </div>
        </div>
        @endif

    </div>{{-- /tab-overview --}}

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TAB: ALL PAYMENTS â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <div id="tab-list" style="display:none;">

        {{-- Filters --}}
        <div class="filter-panel mb-4">
            <form method="GET" action="{{ route('admin.payments') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search Patient</label>
                        <input type="text" class="form-control" name="search" placeholder="Name or email..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="all"       {{ $status==='all'       ?'selected':'' }}>All</option>
                            <option value="completed" {{ $status==='completed' ?'selected':'' }}>Completed</option>
                            <option value="pending"   {{ $status==='pending'   ?'selected':'' }}>Pending</option>
                            <option value="refunded"  {{ $status==='refunded'  ?'selected':'' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button class="btn-primary" type="submit"><i class="bi bi-search me-1"></i>Search</button>
                        @if($search || $status !== 'all' || $dateFrom || $dateTo)
                            <a href="{{ route('admin.payments') }}" class="btn" style="background:#f0f2f5;border:1px solid #dee2e6;color:#495057;padding:8px 16px;border-radius:10px;font-size:.85rem;text-decoration:none;"><i class="bi bi-x-lg me-1"></i>Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-credit-card-2-front-fill"></i> All Payments</div>
                <span class="count-badge">{{ $payments->total() }} total</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr><th>Patient</th><th>Appointment</th><th>Amount</th><th>Date</th><th>Method</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>
                                <strong>{{ $payment->patient->name ?? 'N/A' }}</strong><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $payment->patient->email ?? '' }}</span>
                            </td>
                            <td>
                                @if($payment->appointment)
                                    {{ $payment->appointment->appointment_date->format('M d, Y') }}<br>
                                    <span style="font-size:.75rem;color:var(--text-muted);">{{ $payment->appointment->service->name ?? '' }}</span>
                                @else â€” @endif
                            </td>
                            <td><strong style="color:#059386;">SAR {{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_date?->format('M d, Y') ?? 'â€”' }}</td>
                            <td>
                                <i class="bi bi-credit-card" style="color:#0056b3;"></i> Credit Card
                            </td>
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
                                                <p><strong>Method:</strong> <i class="bi bi-credit-card" style="color:#0056b3;"></i> Credit Card</p>
                                                <p><strong>Date:</strong> {{ $payment->payment_date?->format('M d, Y') ?? 'N/A' }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
                                                @if($payment->appointment)
                                                    <p><strong>Appointment:</strong> {{ $payment->appointment->appointment_date->format('M d, Y') }}</p>
                                                    <p><strong>Service:</strong> {{ $payment->appointment->service->name ?? 'N/A' }}</p>
                                                    <p><strong>Dentist:</strong> {{ $payment->appointment->dentist->name ?? 'N/A' }}</p>
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
                <div class="d-flex justify-content-center p-3">{{ $payments->appends(['search'=>$search,'status'=>$status,'date_from'=>$dateFrom,'date_to'=>$dateTo])->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

    </div>{{-- /tab-list --}}

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TAB: BY MONTH â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <div id="tab-months" style="display:none;">

        @if($monthlyPayBreakdown->isEmpty())
            <div class="panel" style="text-align:center;padding:48px;color:var(--text-muted);">
                <i class="bi bi-calendar-x" style="font-size:2.5rem;opacity:.4;"></i>
                <p style="margin-top:12px;">No payment data available yet.</p>
            </div>
        @else

        {{-- All-months revenue overview bar --}}
        <div class="panel mb-4">
            <div class="panel-head">
                <div class="panel-head-title"><i class="bi bi-bar-chart-fill" style="color:var(--teal);"></i> Revenue â€” All-Time Monthly Overview</div>
                <span class="count-badge">{{ $monthlyPayBreakdown->count() }} month{{ $monthlyPayBreakdown->count()!==1?'s':'' }}</span>
            </div>
            <div style="padding:16px 20px;overflow-x:auto;">
                @php $maxMoRev = $monthlyPayBreakdown->max('revenue') ?: 1; @endphp
                <div style="display:flex;align-items:flex-end;gap:8px;min-height:100px;">
                    @foreach($monthlyPayBreakdown->sortBy('month_sort') as $mo)
                    @php
                        $barH  = max(round(($mo->revenue / $maxMoRev)*75), 4);
                        $isNow = $mo->month_sort === now()->format('Y-m');
                    @endphp
                    <div style="flex:1;min-width:36px;display:flex;flex-direction:column;align-items:center;gap:3px;cursor:pointer;"
                         onclick="scrollToPayMonth('{{ $mo->month_sort }}')">
                        <span style="font-size:.65rem;color:var(--text-muted);font-weight:600;">{{ number_format($mo->revenue,0) }}</span>
                        <div style="width:100%;height:{{ $barH }}px;background:{{ $isNow ? 'var(--teal)' : '#b2dfdb' }};border-radius:4px 4px 0 0;"
                             title="{{ $mo->month_label }}: SAR {{ number_format($mo->revenue,2) }}"></div>
                        <span style="font-size:.62rem;white-space:nowrap;color:{{ $isNow?'var(--teal)':'var(--text-muted)' }};font-weight:{{ $isNow?'700':'400' }};">{{ $mo->month_label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Monthly accordion --}}
        @foreach($monthlyPayBreakdown as $mo)
        @php
            $moId   = 'pmo-' . str_replace('-','', $mo->month_sort);
            $isFirst = $loop->first;
        @endphp
        <div class="panel mb-3" id="panchor-{{ str_replace('-','', $mo->month_sort) }}" style="border-radius:14px;overflow:hidden;">

            {{-- Header --}}
            <div data-bs-toggle="collapse" data-bs-target="#{{ $moId }}"
                 style="padding:14px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:{{ $isFirst ? 'linear-gradient(90deg,#e6f5f3,#f9fffe)' : '' }};">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="background:{{ $isFirst ? 'var(--teal)' : '#e9ecef' }};color:{{ $isFirst ? '#fff' : 'var(--text-main)' }};border-radius:10px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem;{{ $isFirst ? 'color:var(--teal);' : '' }}">{{ $mo->month_label }}{{ $isFirst ? ' â€” Current Month' : '' }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $mo->count }} paid &nbsp;Â·&nbsp; SAR {{ number_format($mo->revenue,0) }} collected</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="background:#d4f5e4;color:#0f6b3a;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600;">Net SAR {{ number_format($mo->net,0) }}</span>
                    @if($mo->refunded_amount > 0)
                        <span style="background:#fde8e8;color:#c0392b;padding:3px 10px;border-radius:20px;font-size:.75rem;">-SAR {{ number_format($mo->refunded_amount,0) }}</span>
                    @endif
                    <i class="bi bi-chevron-down" style="color:var(--text-muted);"></i>
                </div>
            </div>

            <div class="collapse {{ $isFirst ? 'show' : '' }}" id="{{ $moId }}">
                <div style="padding:20px;border-top:1px solid var(--border);">

                    {{-- 4 stat tiles --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-sm-3">
                            <div style="background:#f9fffe;border:1px solid #b2dfdb;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.25rem;font-weight:800;color:var(--teal);">SAR {{ number_format($mo->revenue,0) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Revenue</div>
                                <div style="font-size:.68rem;color:var(--text-muted);">{{ $mo->count }} transactions</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div style="background:#d4f5e4;border:1px solid #86e0b4;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.25rem;font-weight:800;color:#059386;">SAR {{ number_format($mo->net,0) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Net</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div style="background:#fff4e5;border:1px solid #ffd59e;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.25rem;font-weight:800;color:#b86e00;">SAR {{ number_format($mo->pending_amount,0) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Pending</div>
                                <div style="font-size:.68rem;color:var(--text-muted);">{{ $mo->pending_count }} payments</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div style="background:#fde8e8;border:1px solid #f5a0a0;border-radius:10px;padding:12px 14px;text-align:center;">
                                <div style="font-size:1.25rem;font-weight:800;color:#c0392b;">SAR {{ number_format($mo->refunded_amount,0) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Refunded</div>
                                <div style="font-size:.68rem;color:var(--text-muted);">{{ $mo->refunded_count }} payments</div>
                            </div>
                        </div>
                    </div>

                    {{-- Credit Card note + status distribution --}}
                    <div style="background:#e7f1ff;border:1px solid #9ec5fe;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
                        <i class="bi bi-credit-card-fill" style="color:#0056b3;font-size:1.3rem;flex-shrink:0;"></i>
                        <div style="flex:1;">
                            <div style="font-weight:700;font-size:.83rem;color:#0056b3;">All payments via Credit Card</div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px;">{{ $mo->count }} completed &nbsp;·&nbsp; {{ $mo->pending_count }} pending &nbsp;·&nbsp; {{ $mo->refunded_count }} refunded</div>
                        </div>
                        <span style="background:#0056b3;color:#fff;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;">SAR {{ number_format($mo->credit_card, 0) }}</span>
                    </div>

                    {{-- Top patients --}}
                    @if($mo->top_patients->isNotEmpty())
                    <div style="background:#f9fffe;border:1px solid var(--border);border-radius:10px;padding:14px 16px;margin-bottom:16px;">
                        <div style="font-weight:700;font-size:.82rem;margin-bottom:10px;"><i class="bi bi-trophy-fill" style="color:#f5a623;"></i> Top Patients This Month</div>
                        @foreach($mo->top_patients as $pi => $pat)
                        <div style="display:flex;align-items:center;gap:10px;{{ !$loop->last ? 'padding-bottom:8px;margin-bottom:8px;border-bottom:1px solid var(--border);' : '' }}">
                            <span style="font-size:1rem;font-weight:800;min-width:20px;color:{{ $pi===0?'#f5a623':($pi===1?'#9e9e9e':'#cd7f32') }};">#{{ $pi+1 }}</span>
                            <div style="width:28px;height:28px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.75rem;flex-shrink:0;">{{ strtoupper(substr($pat->name,0,1)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $pat->name }}</div>
                                <div style="font-size:.7rem;color:var(--text-muted);">{{ $pat->pay_count }} payment{{ $pat->pay_count!=1?'s':'' }}</div>
                            </div>
                            <span style="background:#e6f5f3;color:#059386;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:700;">SAR {{ number_format($pat->total_paid,0) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Financial record --}}
                    @if($mo->fin_revenue !== null)
                    <div style="background:linear-gradient(135deg,#e6f5f3,#f9fffe);border:1.5px solid var(--teal);border-radius:10px;padding:16px 18px;">
                        <div style="font-weight:700;font-size:.82rem;color:var(--teal);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-bar-chart-steps"></i> Financial Report (Admin Records)
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-4" style="text-align:center;">
                                <div style="font-size:1.1rem;font-weight:800;color:#059386;">SAR {{ number_format($mo->fin_revenue,2) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Total Revenue</div>
                            </div>
                            <div class="col-sm-4" style="text-align:center;">
                                <div style="font-size:1.1rem;font-weight:800;color:#c0392b;">SAR {{ number_format($mo->fin_costs,2) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Total Costs</div>
                            </div>
                            <div class="col-sm-4" style="text-align:center;">
                                @php $pc = $mo->fin_profit >= 0 ? '#059386' : '#c0392b'; @endphp
                                <div style="font-size:1.1rem;font-weight:800;color:{{ $pc }};">{{ $mo->fin_profit>=0?'+':'' }}SAR {{ number_format($mo->fin_profit,2) }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Net Profit</div>
                            </div>
                        </div>
                        @if($mo->fin_revenue > 0)
                        @php $mg = round(($mo->fin_profit / $mo->fin_revenue)*100,1); @endphp
                        <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Profit margin</span><strong style="color:{{ $pc }};">{{ $mg }}%</strong>
                        </div>
                        <div style="background:#e9ecef;border-radius:8px;height:8px;">
                            <div style="background:{{ $pc }};width:{{ max(0,min(100,$mg)) }}%;height:8px;border-radius:8px;"></div>
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

                </div>
            </div>
        </div>
        @endforeach

        @endif
    </div>{{-- /tab-months --}}

</div>{{-- /container --}}

<script>
function payShowTab(tab) {
    ['overview','list','months'].forEach(function(t) {
        document.getElementById('tab-' + t).style.display = (t === tab) ? '' : 'none';
    });
    var styles = {
        active:   'padding:9px 20px;border-radius:10px;border:none;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:var(--teal);color:#fff;box-shadow:0 2px 6px rgba(5,147,134,.3);',
        inactive: 'padding:9px 20px;border-radius:10px;border:1px solid var(--border);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;background:#fff;color:var(--text-main);'
    };
    document.getElementById('btn-tab-overview').style.cssText = (tab==='overview') ? styles.active : styles.inactive;
    document.getElementById('btn-tab-list').style.cssText     = (tab==='list')     ? styles.active : styles.inactive;
    document.getElementById('btn-tab-months').style.cssText   = (tab==='months')   ? styles.active : styles.inactive;
}

function scrollToPayMonth(monthSort) {
    payShowTab('months');
    var id = 'panchor-' + monthSort.replace('-','');
    var el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior:'smooth', block:'start' });
        var cc = el.querySelector('.collapse');
        if (cc && !cc.classList.contains('show')) {
            new bootstrap.Collapse(cc, { toggle: true });
        }
    }
}
</script>
@endsection

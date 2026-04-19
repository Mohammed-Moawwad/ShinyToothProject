<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size:10px; color:#1a1a2e; background:#fff; }

/* ── Cover / Header ── */
.cover {
    background: linear-gradient(135deg, #003263 0%, #0d9e8a 100%);
    color:#fff; padding:32px 36px 28px; margin-bottom:0;
}
.cover-logo { font-size:22px; font-weight:900; letter-spacing:-.5px; margin-bottom:4px; }
.cover-logo span { color:#5efde4; }
.cover-sub  { font-size:10px; opacity:.8; margin-bottom:20px; }
.cover-title { font-size:19px; font-weight:700; margin-bottom:4px; }
.cover-period { font-size:12px; opacity:.85; }
.cover-meta {
    display:flex; gap:24px; margin-top:18px; border-top:1px solid rgba(255,255,255,.25); padding-top:14px;
}
.cover-meta-item { font-size:9px; opacity:.8; }
.cover-meta-item strong { display:block; font-size:11px; opacity:1; }

/* ── Section Header ── */
.section-header {
    background:#003263; color:#fff; font-size:10px; font-weight:700;
    padding:6px 12px; margin:18px 0 8px; letter-spacing:.04em; text-transform:uppercase;
}

/* ── KPI Grid ── */
.kpi-grid { display:table; width:100%; border-collapse:collapse; margin-bottom:10px; }
.kpi-cell { display:table-cell; width:25%; padding:8px 10px; border:1px solid #e5e7eb; vertical-align:top; }
.kpi-cell.green  { border-top:3px solid #10b981; }
.kpi-cell.teal   { border-top:3px solid #0d9e8a; }
.kpi-cell.blue   { border-top:3px solid #3b82f6; }
.kpi-cell.orange { border-top:3px solid #f59e0b; }
.kpi-cell.red    { border-top:3px solid #ef4444; }
.kpi-cell.purple { border-top:3px solid #8b5cf6; }
.kpi-val { font-size:15px; font-weight:900; color:#003263; line-height:1.1; }
.kpi-lbl { font-size:8px; color:#6b7280; margin-top:2px; }

/* ── Tables ── */
table { width:100%; border-collapse:collapse; margin-bottom:10px; }
thead tr { background:#003263; color:#fff; }
thead th { padding:6px 8px; font-size:8.5px; font-weight:700; text-align:left; }
tbody tr:nth-child(even) { background:#f8fafc; }
tbody td { padding:5px 8px; font-size:9px; border-bottom:1px solid #e5e7eb; }

/* ── Two-column layout ── */
.two-col { display:table; width:100%; border-collapse:collapse; margin-bottom:10px; }
.col-left  { display:table-cell; width:50%; padding-right:8px; vertical-align:top; }
.col-right { display:table-cell; width:50%; padding-left:8px; vertical-align:top; }

/* ── Finance summary rows ── */
.fin-row { display:table; width:100%; border-collapse:collapse; margin-bottom:3px; }
.fin-label { display:table-cell; width:60%; font-size:9px; color:#4b5563; padding:4px 8px; border:1px solid #e5e7eb; }
.fin-val   { display:table-cell; width:40%; font-size:9px; font-weight:700; padding:4px 8px; border:1px solid #e5e7eb; text-align:right; }
.fin-total .fin-label { background:#003263; color:#fff; font-weight:700; }
.fin-total .fin-val   { background:#003263; color:#fff; font-weight:700; }
.fin-profit .fin-label { background:#d1fae5; }
.fin-profit .fin-val   { background:#d1fae5; color:#065f46; }
.fin-loss   .fin-label { background:#fee2e2; }
.fin-loss   .fin-val   { background:#fee2e2; color:#991b1b; }

/* ── Progress bar ── */
.bar-wrap { background:#e5e7eb; border-radius:3px; height:6px; margin-top:2px; overflow:hidden; }
.bar-fill  { height:6px; border-radius:3px; }

/* ── Badge ── */
.badge { font-size:8px; font-weight:700; padding:2px 6px; border-radius:4px; }
.badge-green  { background:#d1fae5; color:#065f46; }
.badge-orange { background:#fef3c7; color:#92400e; }
.badge-red    { background:#fee2e2; color:#991b1b; }

/* ── Footer ── */
.footer { margin-top:24px; border-top:1px solid #e5e7eb; padding-top:8px; color:#9ca3af; font-size:8px; display:table; width:100%; }
.footer-left  { display:table-cell; }
.footer-right { display:table-cell; text-align:right; }

/* ── Info note ── */
.info-note {
    background:#eff6ff; border:1px solid #bfdbfe; border-radius:4px;
    padding:6px 10px; font-size:8px; color:#1e40af; margin-bottom:8px;
}
.disclaimer {
    background:#fffbeb; border:1px solid #fde68a; border-radius:4px;
    padding:8px 12px; font-size:8px; color:#78350f; margin-top:10px;
}
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════ HEADER ═══ --}}
<div class="cover">
    <div class="cover-logo">Shiny<span>Tooth</span> Dental Clinic</div>
    <div class="cover-sub">Administrative Management System</div>
    <div class="cover-title">Monthly Performance Report</div>
    <div class="cover-period">Period: {{ $monthLabel }} &nbsp;|&nbsp; Report Date: {{ $lastDayOfMonth }}</div>
    <div class="cover-meta">
        <div class="cover-meta-item"><strong>Generated</strong>{{ $generatedAt }}</div>
        <div class="cover-meta-item"><strong>Report Type</strong>Monthly Performance Summary</div>
        <div class="cover-meta-item"><strong>Prepared By</strong>Admin System (Auto-generated)</div>
        <div class="cover-meta-item"><strong>Confidentiality</strong>Internal Use Only</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ KPI ROW ══ --}}
<div class="section-header">Executive Summary — {{ $monthLabel }}</div>

<div class="kpi-grid">
    <div class="kpi-cell teal">
        <div class="kpi-val">{{ number_format($apptTotal) }}</div>
        <div class="kpi-lbl">Total Appointments</div>
    </div>
    <div class="kpi-cell green">
        <div class="kpi-val">SAR {{ number_format($revenue, 0) }}</div>
        <div class="kpi-lbl">Revenue Collected</div>
    </div>
    <div class="kpi-cell {{ $grossProfit >= 0 ? 'green' : 'red' }}">
        <div class="kpi-val" style="color:{{ $grossProfit >= 0 ? '#065f46' : '#991b1b' }};">SAR {{ number_format($grossProfit, 0) }}</div>
        <div class="kpi-lbl">Net Profit / Loss</div>
    </div>
    <div class="kpi-cell blue">
        <div class="kpi-val">{{ $profitMargin }}%</div>
        <div class="kpi-lbl">Profit Margin</div>
    </div>
</div>
<div class="kpi-grid">
    <div class="kpi-cell blue">
        <div class="kpi-val">{{ number_format($newPatients) }}</div>
        <div class="kpi-lbl">New Patients This Month</div>
    </div>
    <div class="kpi-cell orange">
        <div class="kpi-val">{{ number_format($activePatients) }}</div>
        <div class="kpi-lbl">Active Patients</div>
    </div>
    <div class="kpi-cell purple">
        <div class="kpi-val">{{ number_format($activeDentists) }}</div>
        <div class="kpi-lbl">Active Dentists</div>
    </div>
    <div class="kpi-cell teal">
        <div class="kpi-val">{{ $completionRate }}%</div>
        <div class="kpi-lbl">Appointment Completion Rate</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ FINANCIALS --}}
<div class="section-header">Financial Breakdown</div>

<div class="info-note">
    Revenue is sourced from completed payments in the database. Costs reflect actual dentist salaries recorded in the system.
    @if($financialRecord) &nbsp;|&nbsp; A financial record exists for this period (recorded costs: SAR {{ number_format($financialRecord->total_costs, 2) }}, recorded profit: SAR {{ number_format($financialRecord->profit, 2) }}){{ $financialNotes ? ' — ' . $financialNotes : '' }}. @endif
</div>

<div class="two-col">
    <div class="col-left">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Revenue (from Payments)</div>
        <div class="fin-row">
            <div class="fin-label">Completed Payments</div>
            <div class="fin-val" style="color:#065f46;">SAR {{ number_format($revenue, 2) }}</div>
        </div>
        <div class="fin-row">
            <div class="fin-label">Pending Payments</div>
            <div class="fin-val" style="color:#92400e;">SAR {{ number_format($pendingPayments, 2) }}</div>
        </div>
        <div class="fin-row">
            <div class="fin-label">Refunds Issued</div>
            <div class="fin-val" style="color:#991b1b;">SAR {{ number_format($refunds, 2) }}</div>
        </div>
        <div class="fin-row">
            <div class="fin-label">Transactions</div>
            <div class="fin-val">{{ number_format($txnCount) }}</div>
        </div>
        <div class="fin-row">
            <div class="fin-label">Avg. Transaction Value</div>
            <div class="fin-val">SAR {{ number_format($avgTransaction, 2) }}</div>
        </div>
    </div>
    <div class="col-right">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Costs &amp; Profit</div>
        <div class="fin-row">
            <div class="fin-label">Dentist Salaries ({{ $activeDentists }} active dentists)</div>
            <div class="fin-val" style="color:#991b1b;">SAR {{ number_format($actualSalaries, 2) }}</div>
        </div>
        <div class="fin-row fin-total">
            <div class="fin-label">Total Costs (dentist salaries)</div>
            <div class="fin-val">SAR {{ number_format($totalCosts, 2) }}</div>
        </div>
        <div class="fin-row {{ $grossProfit >= 0 ? 'fin-profit' : 'fin-loss' }}">
            <div class="fin-label">Net Profit (Revenue − Costs)</div>
            <div class="fin-val">SAR {{ number_format($grossProfit, 2) }}</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ APPOINTMENTS --}}
<div class="section-header">Appointment Analysis</div>

<div class="kpi-grid" style="margin-bottom:10px;">
    <div class="kpi-cell green">
        <div class="kpi-val">{{ number_format($apptCompleted) }}</div>
        <div class="kpi-lbl">Completed</div>
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $apptTotal>0?round($apptCompleted/$apptTotal*100):0 }}%;background:#10b981;"></div></div>
    </div>
    <div class="kpi-cell blue">
        <div class="kpi-val">{{ number_format($apptScheduled) }}</div>
        <div class="kpi-lbl">Scheduled</div>
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $apptTotal>0?round($apptScheduled/$apptTotal*100):0 }}%;background:#3b82f6;"></div></div>
    </div>
    <div class="kpi-cell red">
        <div class="kpi-val">{{ number_format($apptCancelled) }}</div>
        <div class="kpi-lbl">Cancelled</div>
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $apptTotal>0?round($apptCancelled/$apptTotal*100):0 }}%;background:#ef4444;"></div></div>
    </div>
    <div class="kpi-cell orange">
        <div class="kpi-val">{{ number_format($apptNoShow + $apptFailed) }}</div>
        <div class="kpi-lbl">No-Show / Failed</div>
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $apptTotal>0?round(($apptNoShow+$apptFailed)/$apptTotal*100):0 }}%;background:#f59e0b;"></div></div>
    </div>
</div>

<div style="font-size:8px;color:#6b7280;margin-bottom:8px;">
    All-Time: {{ number_format($allTimeAppts) }} total appointments &bull; {{ number_format($allTimeCompleted) }} completed
</div>

{{-- Top Dentists by Appointments --}}
<div class="two-col">
    <div class="col-left">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Top 5 Dentists by Appointments This Month</div>
        @php $maxDA = $topDentists->max('appointments_count') ?: 1; @endphp
        <table>
            <thead><tr><th>#</th><th>Dentist</th><th>Appointments</th><th>Share</th></tr></thead>
            <tbody>
            @forelse($topDentists as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>Dr. {{ $d->name }}</td>
                <td>{{ $d->appointments_count }}</td>
                <td>{{ $maxDA > 0 ? round($d->appointments_count/$maxDA*100) : 0 }}%</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#9ca3af;">No data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-right">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Service Revenue This Month</div>
        <table>
            <thead><tr><th>Service</th><th>Revenue (SAR)</th><th>Paid Appts</th></tr></thead>
            <tbody>
            @forelse($serviceRevenue as $svc)
            <tr>
                <td>{{ $svc->name }}</td>
                <td>{{ number_format($svc->revenue, 2) }}</td>
                <td>{{ $svc->cnt }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:#9ca3af;">No data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ PATIENTS --}}
<div class="section-header">Patient Statistics</div>

<div class="kpi-grid" style="margin-bottom:10px;">
    <div class="kpi-cell blue">
        <div class="kpi-val">{{ number_format($newPatients) }}</div>
        <div class="kpi-lbl">New Patients This Month</div>
    </div>
    <div class="kpi-cell teal">
        <div class="kpi-val">{{ number_format($activePatients) }}</div>
        <div class="kpi-lbl">Active (Had Appt This Month)</div>
    </div>
    <div class="kpi-cell green">
        <div class="kpi-val">{{ number_format($totalPatients) }}</div>
        <div class="kpi-lbl">Total Patients (All Time)</div>
    </div>
    <div class="kpi-cell orange">
        <div class="kpi-val">{{ $totalPatients > 0 ? round($activePatients/$totalPatients*100) : 0 }}%</div>
        <div class="kpi-lbl">Patient Utilisation Rate</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ DENTISTS --}}
<div class="section-header">Dental Staff Overview</div>

<div class="kpi-grid" style="margin-bottom:10px;">
    <div class="kpi-cell purple">
        <div class="kpi-val">{{ number_format($activeDentists) }}</div>
        <div class="kpi-lbl">Active Dentists</div>
    </div>
    <div class="kpi-cell teal">
        <div class="kpi-val">{{ number_format($totalDentists) }}</div>
        <div class="kpi-lbl">Total Dentists (All Time)</div>
    </div>
    <div class="kpi-cell blue">
        <div class="kpi-val">{{ number_format($newDentists) }}</div>
        <div class="kpi-lbl">New Dentists Onboarded</div>
    </div>
    <div class="kpi-cell orange">
        <div class="kpi-val">{{ $ratingCountPeriod > 0 ? number_format($avgRatingPeriod, 1) : ($ratingCount > 0 ? number_format($avgRating, 1) : 'N/A') }}</div>
        <div class="kpi-lbl">Avg. Rating This Month{{ $ratingCountPeriod > 0 ? " ({$ratingCountPeriod} reviews)" : ($ratingCount > 0 ? ' (all-time)' : '') }}</div>
    </div>
</div>

<div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Salary Details — Active Dentists (from Database)</div>
<table>
    <thead><tr><th>#</th><th>Dentist</th><th>Monthly Salary (SAR)</th></tr></thead>
    <tbody>
        @forelse($dentistSalaries as $ds)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>Dr. {{ $ds->name }}</td>
            <td>{{ number_format($ds->salary, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;color:#9ca3af;">No active dentists</td></tr>
        @endforelse
        <tr style="background:#f0fdf4;font-weight:700;">
            <td colspan="2">Total Monthly Salary Cost</td>
            <td>SAR {{ number_format($actualSalaries, 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- ═══════════════════════════════════════════════════════════════ SERVICES --}}
<div class="section-header">Service Performance</div>

<table>
    <thead><tr><th>#</th><th>Service Name</th><th>Bookings This Month</th><th>All-Time Bookings</th></tr></thead>
    <tbody>
    @forelse($topServices->take(8) as $svc)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $svc->name }}</td>
        <td>{{ $svc->period_bookings }}</td>
        <td>{{ $svc->alltime_bookings }}</td>
    </tr>
    @empty
    <tr><td colspan="4" style="text-align:center;color:#9ca3af;">No service data</td></tr>
    @endforelse
    </tbody>
</table>

{{-- ═══════════════════════════════════════════════════════════════ OPERATIONS --}}
<div class="section-header">Operational Details</div>

<div class="two-col">
    <div class="col-left">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Vacation Requests (This Month)</div>
        <table>
            <thead><tr><th>Status</th><th>Count</th></tr></thead>
            <tbody>
                <tr><td><span class="badge badge-green">Approved</span></td><td>{{ $vacApproved }}</td></tr>
                <tr><td><span class="badge badge-orange">Pending</span></td><td>{{ $vacPending }}</td></tr>
                <tr><td><span class="badge badge-red">Rejected</span></td><td>{{ $vacRejected }}</td></tr>
                <tr style="font-weight:700;"><td>Total</td><td>{{ $vacApproved + $vacPending + $vacRejected }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-right">
        <div style="font-size:9px;font-weight:700;color:#374151;margin-bottom:5px;">Ratings Summary</div>
        <table>
            <thead><tr><th>Metric</th><th>Value</th></tr></thead>
            <tbody>
                <tr><td>This Month — Avg. Rating</td><td>{{ $ratingCountPeriod > 0 ? number_format($avgRatingPeriod, 1) . ' / 5' : 'No reviews' }}</td></tr>
                <tr><td>This Month — Total Reviews</td><td>{{ $ratingCountPeriod }}</td></tr>
                <tr><td>All-Time — Avg. Rating</td><td>{{ $ratingCount > 0 ? number_format($avgRating, 1) . ' / 5' : 'No reviews' }}</td></tr>
                <tr><td>All-Time — Total Reviews</td><td>{{ $ratingCount }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ DISCLAIMER --}}
<div class="disclaimer">
    <strong>Data Source Notice:</strong>
    All figures in this report are sourced directly from the ShinyTooth database.
    Revenue and payment data come from the payments table (completed/pending/refunded transactions).
    Salary costs and profit are calculated from actual dentist salaries recorded in the system.
    Actual total costs may be higher if other expenses (facility, utilities, etc.) are not recorded in the database.
    This report is auto-generated by the ShinyTooth Administration System and is for internal use only.
</div>

{{-- ═══════════════════════════════════════════════════════════════ FOOTER ══ --}}
<div class="footer" style="margin-top:20px;">
    <div class="footer-left">ShinyTooth Dental Clinic &mdash; Confidential Report &mdash; {{ $monthLabel }}</div>
    <div class="footer-right">Generated: {{ $generatedAt }}</div>
</div>

</body>
</html>

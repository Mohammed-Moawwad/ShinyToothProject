@extends('admin.layout')

@section('page-title', 'Analytics & Reports')

@section('content')
<div class="container-fluid px-0">

    {{-- Appointment Status Cards --}}
    <div class="row g-3 mb-4">
        @foreach($appointmentsByStatus as $stat)
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="bi bi-calendar2-check-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:rgba(0,201,177,0.13);color:#00c9b1;">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
                <div class="stat-num">{{ $stat->count }}</div>
                <div class="stat-lbl">{{ ucfirst($stat->status) }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts Row 1 --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-bar-chart-fill"></i>Appointments by Month</div>
                </div>
                <div style="padding:20px;">
                    <div class="chart-box"><canvas id="appointmentsChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel" style="height:100%;">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-credit-card-2-front-fill"></i>Payment Overview</div>
                </div>
                <div style="padding:20px;">
                    @foreach($paymentsByStatus as $stat)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border);">
                        <div>
                            <div style="font-size:0.78rem;font-weight:700;color:var(--text-main);">{{ ucfirst($stat->status) }}</div>
                            <div style="font-size:0.67rem;color:var(--text-muted);">{{ $stat->count }} transactions</div>
                        </div>
                        <div style="font-size:1rem;font-weight:800;color:#00c9b1;">${{ number_format($stat->total, 0) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-graph-up-arrow"></i>Revenue by Month</div>
                </div>
                <div style="padding:20px;">
                    <div class="chart-box"><canvas id="paymentsChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-people-fill"></i>Patient Growth</div>
                </div>
                <div style="padding:20px;">
                    <div class="chart-box"><canvas id="patientGrowthChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 3 + Services --}}
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-person-badge-fill"></i>Dentist Growth</div>
                </div>
                <div style="padding:20px;">
                    <div class="chart-box"><canvas id="dentistGrowthChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-star-fill"></i>Most Popular Services</div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th>Appointments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($popularServices as $i => $service)
                            <tr>
                                <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                                <td><strong>{{ $service->name }}</strong></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="flex:1;background:var(--border);border-radius:20px;height:6px;overflow:hidden;">
                                            <div style="height:100%;width:{{ min(100, ($service->appointments_count / max($popularServices->max('appointments_count'), 1)) * 100) }}%;background:var(--teal);border-radius:20px;"></div>
                                        </div>
                                        <span style="font-weight:700;color:#00c9b1;font-size:0.82rem;">{{ $service->appointments_count }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color:var(--text-muted);">No service data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-js')
<script>
    const appointmentMonths = @json($appointmentsByMonth->pluck('month')->reverse());
    const appointmentCounts = @json($appointmentsByMonth->pluck('count')->reverse());
    const paymentMonths     = @json($paymentsByMonth->pluck('month')->reverse());
    const paymentTotals     = @json($paymentsByMonth->pluck('total')->reverse());
    const patientMonths     = @json($patientGrowth->pluck('month')->reverse());
    const patientCounts     = @json($patientGrowth->pluck('count')->reverse());
    const dentistMonths     = @json($dentistGrowth->pluck('month')->reverse());
    const dentistCounts     = @json($dentistGrowth->pluck('count')->reverse());

    const darkGrid   = 'rgba(255,255,255,0.06)';
    const tickColor  = '#64748b';
    const baseOpts = (extra) => ({
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: darkGrid }, ticks: { color: tickColor } },
            x: { grid: { display: false },               ticks: { color: tickColor } }
        },
        ...extra
    });

    new Chart(document.getElementById('appointmentsChart'), {
        type: 'bar',
        data: {
            labels: appointmentMonths,
            datasets: [{ label: 'Appointments', data: appointmentCounts,
                backgroundColor: 'rgba(0,201,177,0.25)', borderColor: '#00c9b1',
                borderWidth: 2, borderRadius: 6 }]
        },
        options: baseOpts({})
    });

    new Chart(document.getElementById('paymentsChart'), {
        type: 'line',
        data: {
            labels: paymentMonths,
            datasets: [{ label: 'Revenue ($)', data: paymentTotals,
                borderColor: '#60a5fa', backgroundColor: 'rgba(96,165,250,0.08)',
                tension: 0.4, fill: true, pointBackgroundColor: '#60a5fa',
                pointBorderColor: '#161f2e', pointBorderWidth: 2, pointRadius: 4 }]
        },
        options: baseOpts({})
    });

    new Chart(document.getElementById('patientGrowthChart'), {
        type: 'line',
        data: {
            labels: patientMonths,
            datasets: [{ label: 'New Patients', data: patientCounts,
                borderColor: '#4ade80', backgroundColor: 'rgba(74,222,128,0.08)',
                tension: 0.4, fill: true, pointBackgroundColor: '#4ade80',
                pointBorderColor: '#161f2e', pointBorderWidth: 2, pointRadius: 4 }]
        },
        options: baseOpts({})
    });

    new Chart(document.getElementById('dentistGrowthChart'), {
        type: 'line',
        data: {
            labels: dentistMonths,
            datasets: [{ label: 'New Dentists', data: dentistCounts,
                borderColor: '#facc15', backgroundColor: 'rgba(250,204,21,0.08)',
                tension: 0.4, fill: true, pointBackgroundColor: '#facc15',
                pointBorderColor: '#161f2e', pointBorderWidth: 2, pointRadius: 4 }]
        },
        options: baseOpts({})
    });
</script>
@endsection

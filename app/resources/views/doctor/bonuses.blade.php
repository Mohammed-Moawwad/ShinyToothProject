@extends('doctor.layout')

@section('title', 'Bonuses & Ratings')
@section('page-title', 'Bonuses & Ratings')
@section('breadcrumb')
    <li class="breadcrumb-item active">Bonuses & Ratings</li>
@endsection

@section('content')
<!-- ── SUMMARY CARDS ──────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff9db; color:#f9a825;"><i class="bi bi-trophy-fill"></i></div>
            <div class="stat-value">SAR {{ number_format($totalBonus, 2) }}</div>
            <div class="stat-label">Total Bonuses Earned</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e6f5f3; color:var(--teal);"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">{{ $bonuses->count() }}</div>
            <div class="stat-label">Bonuses Received</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5; color:#b86e00;"><i class="bi bi-star-fill"></i></div>
            <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
            <div class="stat-label">Average Rating ({{ $ratings->count() }} reviews)</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ── BONUSES TABLE ──────────────────────────────── -->
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-trophy-fill me-2" style="color:#f9a825;"></i>Bonus History</h6>
            </div>

            @if($bonuses->count())
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Plan Total</th>
                            <th>Bonus (5%)</th>
                            <th>Rating</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bonuses as $bonus)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="patient-avatar">{{ strtoupper(substr($bonus->subscription->patient->name ?? 'P', 0, 1)) }}</span>
                                        {{ $bonus->subscription->patient->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>SAR {{ number_format($bonus->plan_total, 2) }}</td>
                                <td class="fw-bold" style="color:var(--teal);">SAR {{ number_format($bonus->bonus_amount, 2) }}</td>
                                <td>
                                    <span class="stars">
                                        @for($i=1;$i<=5;$i++) <i class="bi bi-star{{ $i<=$bonus->rating ? '-fill' : '' }}" style="font-size:.78rem;"></i> @endfor
                                    </span>
                                </td>
                                <td>
                                    @if($bonus->is_paid)
                                        <span class="badge-status badge-active"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                    @else
                                        <span class="badge-status badge-pending"><i class="bi bi-clock"></i> Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-trophy d-block"></i>
                    <p class="mb-1">No bonuses earned yet</p>
                    <small class="text-muted">Complete subscriptions with 4+ star ratings to earn bonuses</small>
                </div>
            @endif
        </div>
    </div>

    <!-- ── RATINGS LIST ───────────────────────────────── -->
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-star-fill me-2" style="color:#f9a825;"></i>Patient Reviews</h6>
            </div>

            @if($ratings->count())
                @foreach($ratings as $rating)
                    <div class="py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-start gap-3">
                            <span class="patient-avatar">{{ strtoupper(substr($rating->patient->name ?? 'P', 0, 1)) }}</span>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-semibold mb-0" style="font-size:.9rem;">{{ $rating->patient->name ?? 'Patient' }}</h6>
                                    <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="stars my-1">
                                    @for($i=1;$i<=5;$i++)
                                        <i class="bi bi-star{{ $i<=$rating->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="text-muted ms-1" style="font-size:.82rem;">{{ $rating->rating }}/5</span>
                                </div>
                                @if($rating->review)
                                    <p class="text-muted mb-0" style="font-size:.85rem;">{{ $rating->review }}</p>
                                @else
                                    <p class="text-muted mb-0 fst-italic" style="font-size:.85rem;">No written review</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-star d-block"></i>
                    <p class="mb-0">No reviews from patients yet</p>
                </div>
            @endif
        </div>

        <!-- ── RATING BREAKDOWN ───────────────────────── -->
        @if($ratings->count())
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-bar-chart me-2" style="color:var(--dark-blue);"></i>Rating Breakdown</h6>
            </div>
            @for($star = 5; $star >= 1; $star--)
                @php
                    $count = $ratings->where('rating', $star)->count();
                    $pct   = $ratings->count() > 0 ? round(($count / $ratings->count()) * 100) : 0;
                @endphp
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="width:40px; font-size:.82rem; font-weight:600; color:var(--dark-blue);">{{ $star }} <i class="bi bi-star-fill" style="color:#f9a825; font-size:.7rem;"></i></span>
                    <div class="progress flex-grow-1" style="height:8px; border-radius:8px;">
                        <div class="progress-bar" style="width:{{ $pct }}%; background:#f9a825; border-radius:8px;"></div>
                    </div>
                    <span style="width:28px; font-size:.78rem; color:#6c757d;">{{ $count }}</span>
                </div>
            @endfor
        </div>
        @endif
    </div>
</div>
@endsection

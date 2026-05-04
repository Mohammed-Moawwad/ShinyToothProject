@extends('patient.layout')

@section('title', 'My Subscription')
@section('page-title', 'My Subscription')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Subscription</li>
@endsection

@push('styles')
<style>
    /* â”€â”€ STATUS BADGE â”€â”€ */
    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 18px; border-radius: 25px;
        font-size: .8rem; font-weight: 700; letter-spacing: .3px;
    }
    .status-active    { background: #d4f5e4; color: #0d7a3e; }
    .status-pending   { background: #fff4e5; color: #b07a1a; }
    .status-idle      { background: #e9ecef; color: #555; }
    .status-completed { background: #e6f5f3; color: var(--teal); }

    /* â”€â”€ DOCTOR MINI â”€â”€ */
    .doctor-mini {
        display: flex; align-items: center; gap: 16px;
        background: #f9fafb; border-radius: 16px; padding: 18px 20px;
    }
    .doctor-mini-avatar {
        width: 56px; height: 56px; border-radius: 50%;
        background: linear-gradient(135deg, #003263, #059386);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; font-weight: 700;
        flex-shrink: 0; overflow: hidden;
    }
    .doctor-mini-avatar img { width: 100%; height: 100%; object-fit: cover; }

    /* â”€â”€ PLAN ITEMS â”€â”€ */
    .plan-item {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 18px; background: #fff;
        border-radius: 14px; border: 2px solid #f0f2f5; transition: all .2s;
    }
    .plan-item:hover { border-color: #dde8e6; }
    .plan-item.done         { border-color: #d4f5e4; background: #f8fdf9; }
    .plan-item.in-progress  { border-color: #c7e0ff; background: #f5f9ff; }

    .plan-step {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem; font-weight: 700; flex-shrink: 0;
    }
    .plan-step-pending   { background: #f0f2f5; color: #999; }
    .plan-step-progress  { background: #e6f0ff; color: var(--dark-blue); }
    .plan-step-completed { background: #d4f5e4; color: #0d7a3e; }

    .plan-service-name { font-weight: 600; color: var(--dark-blue); font-size: .92rem; }
    .plan-service-doc  { font-size: .78rem; color: #888; }

    .plan-item-status {
        font-size: .72rem; font-weight: 700; padding: 3px 10px;
        border-radius: 20px; letter-spacing: .3px; margin-left: auto; white-space: nowrap;
    }
    .st-completed   { background: #d4f5e4; color: #0d7a3e; }
    .st-in-progress { background: #e6f0ff; color: #2563eb; }
    .st-pending     { background: #f0f2f5; color: #888; }

    /* â”€â”€ ACTION BUTTONS â”€â”€ */
    .btn-action {
        border-radius: 14px; padding: 11px 24px;
        font-weight: 600; font-size: .88rem; border: none;
        transition: all .2s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-action-cancel { background: #fef2f2; color: #dc2626; }
    .btn-action-cancel:hover { background: #fde8e8; transform: translateY(-2px); }
    .btn-action-switch { background: #eaf0ff; color: var(--dark-blue); }
    .btn-action-switch:hover { background: #dde8ff; transform: translateY(-2px); }
    .btn-action-rate { background: linear-gradient(135deg, #07b89e, #059386); color: #fff; }
    .btn-action-rate:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(5,147,134,.35); }

    /* â”€â”€ PENDING BANNER â”€â”€ */
    .pending-banner {
        background: #fff9eb; border: 2px solid #f5dfa0;
        border-radius: 14px; padding: 14px 18px;
        display: flex; align-items: center; gap: 12px;
    }
    .pending-banner-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #fff4e5; color: #f5a623;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    /* â”€â”€ PRICE ROW â”€â”€ */
    .price-row {
        background: linear-gradient(135deg, #f0faf8, #e6f5f3);
        border-radius: 14px; padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .price-label { color: #666; font-size: .88rem; }
    .price-value { color: var(--dark-blue); font-size: 1.5rem; font-weight: 900; }
</style>
@endpush

@section('content')

@if ($subscription)
    @php
        $doc  = $subscription->dentist;
        $plan = $subscription->plan;
        $items = $plan ? $plan->items : collect();
        $completedCount = $items->where('status', 'completed')->count();
        $totalCount     = $items->count();
        $progress       = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">

            <!-- â”€â”€ HEADER CARD â”€â”€ -->
            <div class="dash-card mb-0" style="background:linear-gradient(135deg, var(--dark-blue), var(--teal)); border:none; color:#fff; margin-bottom:0;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div style="font-size:.75rem; font-weight:600; letter-spacing:.4px; color:rgba(255,255,255,.6);">SUBSCRIPTION</div>
                        <h5 class="fw-bold mb-0 mt-1 text-white">
                            @switch($subscription->status)
                                @case('active')    Active Plan @break
                                @case('pending')   Awaiting Approval @break
                                @case('idle')      Paused @break
                                @case('completed') Completed @break
                            @endswitch
                        </h5>
                    </div>
                    <span class="status-badge status-{{ $subscription->status }}">
                        @switch($subscription->status)
                            @case('active')    <i class="bi bi-check-circle-fill"></i> Active @break
                            @case('pending')   <i class="bi bi-hourglass-split"></i> Pending @break
                            @case('idle')      <i class="bi bi-pause-circle-fill"></i> Idle @break
                            @case('completed') <i class="bi bi-trophy-fill"></i> Completed @break
                        @endswitch
                    </span>
                </div>
            </div>

            <!-- â”€â”€ BODY â”€â”€ -->
            <div class="dash-card">

                {{-- Pending banner --}}
                @if ($subscription->admin_action_status !== 'none')
                    <div class="pending-banner mb-4">
                        <div class="pending-banner-icon"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="fw-bold" style="color:#b07a1a; font-size:.88rem;">
                                @if ($subscription->admin_action_status === 'pending_cancel')
                                    Cancellation Request Pending
                                @elseif ($subscription->admin_action_status === 'pending_switch')
                                    Switch Request Pending
                                @endif
                            </div>
                            <div class="text-muted" style="font-size:.8rem;">Your request is being reviewed by the admin team.</div>
                        </div>
                    </div>
                @endif

                {{-- Doctor info --}}
                <div class="doctor-mini mb-4">
                    <div class="doctor-mini-avatar">
                        @if ($doc && $doc->image)
                            <img src="{{ asset($doc->image) }}" alt="{{ $doc->name }}">
                        @else
                            {{ $doc ? strtoupper(substr($doc->name, 0, 1)) : '?' }}
                        @endif
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color:var(--dark-blue);">Dr. {{ $doc ? $doc->name : 'Unknown' }}</h6>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @if ($doc)
                                @foreach ($doc->specializations as $spec)
                                    <span style="font-size:.72rem; background:#e6f5f3; color:var(--teal); padding:2px 8px; border-radius:12px; font-weight:600;">{{ $spec->name }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <a href="/doctors/{{ $doc ? $doc->id : '' }}" class="ms-auto text-decoration-none" style="color:var(--teal); font-size:.85rem; font-weight:600;">
                        <i class="bi bi-box-arrow-up-right"></i> Profile
                    </a>
                </div>

                {{-- Progress bar --}}
                @if ($plan && $totalCount > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:.85rem; font-weight:600; color:var(--dark-blue);">Treatment Progress</span>
                            <span style="font-size:.85rem; font-weight:700; color:var(--teal);">{{ $completedCount }}/{{ $totalCount }}</span>
                        </div>
                        <div class="progress" style="height:10px; border-radius:8px; background:#f0f2f5;">
                            <div class="progress-bar" role="progressbar"
                                 style="width:{{ $progress }}%; background:linear-gradient(90deg, #059386, #07c5b3); border-radius:8px;"
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @endif

                {{-- Plan items --}}
                @if ($plan && $totalCount > 0)
                    <h6 class="fw-bold mb-3" style="color:var(--dark-blue);">
                        <i class="bi bi-list-check me-1" style="color:var(--teal);"></i> Treatment Plan
                    </h6>
                    <div class="d-flex flex-column gap-2 mb-4">
                        @foreach ($items as $item)
                            @php
                                $sClass    = match($item->status) { 'completed' => 'done', 'in_progress' => 'in-progress', default => '' };
                                $stepClass = match($item->status) { 'completed' => 'plan-step-completed', 'in_progress' => 'plan-step-progress', default => 'plan-step-pending' };
                                $stClass   = match($item->status) { 'completed' => 'st-completed', 'in_progress' => 'st-in-progress', default => 'st-pending' };
                            @endphp
                            <div class="plan-item {{ $sClass }}">
                                <div class="plan-step {{ $stepClass }}">
                                    @if ($item->status === 'completed') <i class="bi bi-check-lg"></i>
                                    @else {{ $item->order_index }} @endif
                                </div>
                                <div>
                                    <div class="plan-service-name">{{ $item->service ? $item->service->name : 'Service' }}</div>
                                    @if ($item->assignedDentist && $item->assignedDentist->id !== ($doc ? $doc->id : null))
                                        <div class="plan-service-doc"><i class="bi bi-person-fill"></i> Dr. {{ $item->assignedDentist->name }}</div>
                                    @endif
                                </div>
                                <span class="plan-item-status {{ $stClass }}">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if ($plan->total_price)
                        <div class="price-row mb-4">
                            <span class="price-label"><i class="bi bi-receipt me-1"></i> Total Plan Cost</span>
                            <span class="price-value">SAR {{ number_format($plan->total_price, 2) }}</span>
                        </div>
                    @endif

                @elseif ($subscription->status === 'pending')
                    <div class="text-center py-4" style="background:#f9fafb; border-radius:14px;">
                        <i class="bi bi-clock-history" style="font-size:2rem; color:#ccc;"></i>
                        <p class="text-muted mt-2 mb-0">Your doctor hasn't created a treatment plan yet.<br>Check back soon!</p>
                    </div>
                @endif

                {{-- Action buttons --}}
                @if ($subscription->status === 'active' && $subscription->admin_action_status === 'none')
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button class="btn-action btn-action-cancel" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Request Cancellation
                        </button>
                        <button class="btn-action btn-action-switch" data-bs-toggle="modal" data-bs-target="#switchModal">
                            <i class="bi bi-arrow-left-right"></i> Switch Doctor
                        </button>
                    </div>
                @endif

                {{-- Rate button --}}
                @if (($subscription->status === 'completed' || ($plan && $completedCount === $totalCount && $totalCount > 0)) && !$subscription->rating)
                    <div class="mt-4 text-center">
                        <button class="btn-action btn-action-rate" data-bs-toggle="modal" data-bs-target="#rateModal">
                            <i class="bi bi-star-fill"></i> Rate Your Experience
                        </button>
                    </div>
                @endif

                {{-- Existing rating --}}
                @if ($subscription->rating)
                    <div class="mt-4 p-3" style="background:#f9fafb; border-radius:14px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-bold" style="color:var(--dark-blue); font-size:.9rem;">Your Rating</span>
                            <div style="color:#f9a825;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $subscription->rating->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if ($subscription->rating->review)
                            <p class="mb-0 text-muted" style="font-size:.88rem;">{{ $subscription->rating->review }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- â”€â”€ RIGHT: QUICK ACTIONS â”€â”€ -->
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-lightning-charge-fill me-2" style="color:var(--teal);"></i>Quick Actions</h6>
                </div>
                <a href="/patient/dashboard" class="d-flex align-items-center gap-3 py-3 border-bottom text-decoration-none" style="color:var(--dark-blue);">
                    <div style="width:38px;height:38px;border-radius:10px;background:#e6f5f3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-grid-1x2-fill" style="color:var(--teal);"></i>
                    </div>
                    <span style="font-weight:600;font-size:.9rem;">Back to Dashboard</span>
                </a>
                <a href="/doctors" class="d-flex align-items-center gap-3 py-3 border-bottom text-decoration-none" style="color:var(--dark-blue);">
                    <div style="width:38px;height:38px;border-radius:10px;background:#e7f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-people-fill" style="color:#0056b3;"></i>
                    </div>
                    <span style="font-weight:600;font-size:.9rem;">Browse Doctors</span>
                </a>
                <a href="/services" class="d-flex align-items-center gap-3 py-3 text-decoration-none" style="color:var(--dark-blue);">
                    <div style="width:38px;height:38px;border-radius:10px;background:#f0ecff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-calendar-plus" style="color:#6f42c1;"></i>
                    </div>
                    <span style="font-weight:600;font-size:.9rem;">Book Appointment</span>
                </a>
            </div>
        </div>
    </div>

@else

    <!-- â”€â”€ EMPTY STATE â”€â”€ -->
    <div class="dash-card text-center" style="padding:60px 30px;">
        <div style="width:100px;height:100px;border-radius:50%;background:#e6f5f3;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:var(--teal);margin:0 auto 20px;">
            <i class="bi bi-heart-pulse"></i>
        </div>
        <h4 class="fw-bold" style="color:var(--dark-blue);">No Active Subscription</h4>
        <p class="text-muted mb-4" style="max-width:400px; margin:0 auto 24px;">
            You don't have an active subscription yet. Browse our doctors and subscribe to get a personalized treatment plan.
        </p>
        <a href="/doctors" class="btn btn-teal px-4">
            <i class="bi bi-search-heart me-2"></i>Explore Doctors
        </a>
    </div>

@endif

@endsection

@push('scripts')
{{-- Cancel Modal --}}
@if ($subscription && $subscription->status === 'active')
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#003263,#059386); border:none; padding:20px 24px;">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-x-circle me-2"></i>Request Cancellation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/subscriptions/{{ $subscription->id }}/cancel-request" method="POST">
                @csrf
                <div class="modal-body" style="padding:28px;">
                    <p class="text-muted mb-3" style="font-size:.9rem;">Your request will be reviewed by our admin team.</p>
                    <label class="form-label fw-semibold" style="color:var(--dark-blue);">Reason for cancellation</label>
                    <textarea name="reason" class="form-control" rows="3" required placeholder="Describe your reason..." style="border-radius:12px; border:2px solid #e8edf2;"></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f2f5; padding:16px 24px;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Nevermind</button>
                    <button type="submit" class="btn rounded-pill px-4 fw-bold" style="background:#dc2626; color:#fff;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Switch Modal --}}
<div class="modal fade" id="switchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#003263,#059386); border:none; padding:20px 24px;">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-arrow-left-right me-2"></i>Switch Doctor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/subscriptions/{{ $subscription->id }}/switch-request" method="POST">
                @csrf
                <div class="modal-body" style="padding:28px;">
                    <p class="text-muted mb-3" style="font-size:.9rem;">An admin will review your request.</p>
                    <label class="form-label fw-semibold" style="color:var(--dark-blue);">Select New Doctor</label>
                    <select name="switch_to_dentist_id" class="form-control mb-3" required style="border-radius:12px; border:2px solid #e8edf2;">
                        <option value="">— Choose a doctor —</option>
                        @foreach ($dentists as $d)
                            @if (!$subscription || $d->id !== $subscription->dentist_id)
                                <option value="{{ $d->id }}">Dr. {{ $d->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <label class="form-label fw-semibold" style="color:var(--dark-blue);">Reason for switching</label>
                    <textarea name="reason" class="form-control" rows="3" required placeholder="Describe your reason..." style="border-radius:12px; border:2px solid #e8edf2;"></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f2f5; padding:16px 24px;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Nevermind</button>
                    <button type="submit" class="btn rounded-pill px-4 fw-bold" style="background:var(--dark-blue); color:#fff;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Rate Modal --}}
@if ($subscription && !$subscription->rating)
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#003263,#059386); border:none; padding:20px 24px;">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-star-fill me-2"></i>Rate Your Experience</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/subscriptions/{{ $subscription->id }}/rate" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patientId }}">
                <div class="modal-body text-center" style="padding:28px;">
                    <p class="text-muted mb-3">How was your experience with Dr. {{ $subscription->dentist ? $subscription->dentist->name : '' }}?</p>
                    <div class="d-flex justify-content-center gap-2 mb-4" id="starPicker">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star star-pick" data-value="{{ $i }}" style="font-size:2rem; cursor:pointer; color:#ddd; transition:color .15s, transform .15s;"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="" required>
                    <label class="form-label fw-semibold text-start d-block" style="color:var(--dark-blue);">Review (optional)</label>
                    <textarea name="review" class="form-control" rows="3" placeholder="Share your experience..." style="border-radius:12px; border:2px solid #e8edf2;"></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f2f5; padding:16px 24px;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4 fw-bold" style="background:var(--teal); color:#fff;" id="rateSubmitBtn" disabled>Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
(function () {
    var stars  = document.querySelectorAll('.star-pick');
    var input  = document.getElementById('ratingInput');
    var submit = document.getElementById('rateSubmitBtn');
    if (!stars.length) return;
    stars.forEach(function (star) {
        star.addEventListener('mouseenter', function () { highlightStars(parseInt(star.getAttribute('data-value'))); });
        star.addEventListener('mouseleave', function () { highlightStars(parseInt(input.value) || 0); });
        star.addEventListener('click', function () {
            var val = parseInt(star.getAttribute('data-value'));
            input.value = val;
            submit.disabled = false;
            highlightStars(val);
        });
    });
    function highlightStars(count) {
        stars.forEach(function (s, i) {
            if (i < count) {
                s.classList.replace('bi-star', 'bi-star-fill');
                s.style.color = '#f5a623'; s.style.transform = 'scale(1.15)';
            } else {
                s.classList.replace('bi-star-fill', 'bi-star');
                s.style.color = '#ddd'; s.style.transform = 'scale(1)';
            }
        });
    }
})();
</script>
@endpush

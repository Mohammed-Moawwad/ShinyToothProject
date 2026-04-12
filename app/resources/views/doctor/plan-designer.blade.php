@extends('doctor.layout')

@section('title', 'Treatment Plan')
@section('page-title', 'Treatment Plan Designer')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/doctor/subscriptions-view?dentist={{ $dentist->id }}" class="text-decoration-none" style="color:var(--teal);">My Patients</a></li>
    <li class="breadcrumb-item active">Plan Designer</li>
@endsection

@section('content')
<!-- ── PATIENT HEADER ─────────────────────────────────── -->
<div class="dash-card" style="background: linear-gradient(135deg, var(--dark-blue), var(--teal)); color:#fff;">
    <div class="d-flex flex-wrap align-items-center gap-3">
        <span class="patient-avatar" style="width:56px;height:56px;font-size:1.3rem;background:rgba(255,255,255,.2);border:2px solid rgba(255,255,255,.4);">
            {{ strtoupper(substr($subscription->patient->name ?? 'P', 0, 1)) }}
        </span>
        <div>
            <h5 class="fw-bold mb-0">{{ $subscription->patient->name ?? 'Patient' }}</h5>
            <small style="opacity:.8;">{{ $subscription->patient->email ?? '' }} &middot; Subscription #{{ $subscription->id }}</small>
        </div>
        <div class="ms-auto text-end">
            <span class="badge rounded-pill bg-white text-dark px-3 py-2">
                <i class="bi bi-{{ $subscription->status === 'active' ? 'check-circle-fill text-success' : 'pause-circle-fill text-warning' }}"></i>
                {{ ucfirst($subscription->status) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ── LEFT: PLAN ITEMS ───────────────────────────── -->
    <div class="col-lg-8">
        <!-- Create / update plan -->
        @if(!$subscription->plan)
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-journal-plus me-2" style="color:var(--teal);"></i>Create Treatment Plan</h6>
                </div>
                <form action="/doctor/subscriptions/{{ $subscription->id }}/plan" method="POST">
                    @csrf
                    <input type="hidden" name="dentist" value="{{ $dentist->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Complete Smile Makeover" style="border-radius:10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any notes about this plan..." style="border-radius:10px;"></textarea>
                    </div>
                    <input type="hidden" name="created_by_dentist_id" value="{{ $dentist->id }}">
                    <button type="submit" class="btn btn-teal"><i class="bi bi-plus-lg me-1"></i>Create Plan</button>
                </form>
            </div>
        @else
            @php $plan = $subscription->plan; @endphp

            <!-- Plan info card -->
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-journal-medical me-2" style="color:var(--teal);"></i>{{ $plan->title ?? 'Treatment Plan' }}</h6>
                    <span class="fw-bold" style="color:var(--teal); font-size:1.1rem;">SAR {{ number_format($plan->total_price, 2) }}</span>
                </div>

                @if($plan->notes)
                    <p class="text-muted mb-3" style="font-size:.88rem;"><i class="bi bi-info-circle me-1"></i>{{ $plan->notes }}</p>
                @endif

                @php
                    $total = $plan->items->count();
                    $done  = $plan->items->where('status','completed')->count();
                    $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="progress flex-grow-1" style="height:10px; border-radius:8px;">
                        <div class="progress-bar" style="width:{{ $pct }}%; background: linear-gradient(90deg, var(--teal), #5de8d5); border-radius:8px;"></div>
                    </div>
                    <span class="fw-bold" style="font-size:.88rem; color:var(--dark-blue);">{{ $done }}/{{ $total }}</span>
                </div>
            </div>

            <!-- Plan items list -->
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-list-check me-2" style="color:var(--dark-blue);"></i>Plan Items ({{ $total }})</h6>
                </div>

                @if($plan->items->count())
                    @foreach($plan->items->sortBy('order_index') as $item)
                        <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <!-- Order number -->
                            <div class="d-flex align-items-center justify-content-center"
                                 style="width:32px; height:32px; border-radius:50%; font-size:.82rem; font-weight:700;
                                        {{ $item->status === 'completed' ? 'background:#d4f5e4; color:#0f6b3a;' : 'background:#e9f0f5; color:var(--dark-blue);' }}">
                                @if($item->status === 'completed')
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    {{ $item->order_index }}
                                @endif
                            </div>

                            <!-- Service info -->
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $item->service->name ?? 'Unknown Service' }}</div>
                                <small class="text-muted">
                                    SAR {{ number_format($item->service->price ?? 0, 2) }}
                                    @if($item->assignedDentist)
                                        &middot; Assigned to {{ $item->assignedDentist->name }}
                                    @endif
                                </small>
                            </div>

                            <!-- Status + actions -->
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge-status badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>

                                @if($item->status !== 'completed')
                                    <form action="/doctor/plans/{{ $plan->id }}/items/{{ $item->id }}/complete" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-teal" title="Mark Complete">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="/doctor/plans/{{ $plan->id }}/items/{{ $item->id }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" style="border-radius:10px;" title="Remove">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @else
                                    <small class="text-muted">{{ $item->completed_at ? \Carbon\Carbon::parse($item->completed_at)->format('M d') : '' }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="bi bi-journal-x d-block"></i>
                        <p class="mb-0">No items in the plan yet. Add services from the right panel.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- ── RIGHT: ADD ITEM + INFO ─────────────────────── -->
    <div class="col-lg-4">
        @if($subscription->plan)
            <!-- Add item form -->
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-plus-circle me-2" style="color:var(--teal);"></i>Add Service</h6>
                </div>
                <form action="/doctor/subscriptions/{{ $subscription->id }}/plan/items" method="POST">
                    @csrf
                    <input type="hidden" name="dentist" value="{{ $dentist->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Service</label>
                        <select name="service_id" class="form-select" style="border-radius:10px;" required>
                            <option value="">Select a service...</option>
                            @php $grouped = $services->groupBy('category'); @endphp
                            @foreach($grouped as $cat => $catServices)
                                <optgroup label="{{ $cat ?? 'General' }}">
                                    @foreach($catServices as $svc)
                                        <option value="{{ $svc->id }}">{{ $svc->name }} — SAR {{ number_format($svc->price, 2) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Assign to Doctor (optional)</label>
                        <select name="assigned_dentist_id" class="form-select" style="border-radius:10px;">
                            <option value="">Self ({{ $dentist->name }})</option>
                            @foreach($dentists->where('id', '!=', $dentist->id) as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Upper left molar" style="border-radius:10px;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-teal w-100"><i class="bi bi-plus-lg me-1"></i>Add to Plan</button>
                </form>
            </div>

            <!-- Update plan info -->
            <div class="dash-card">
                <div class="card-header-custom">
                    <h6><i class="bi bi-pencil me-2" style="color:var(--dark-blue);"></i>Edit Plan Info</h6>
                </div>
                <form action="/doctor/subscriptions/{{ $subscription->id }}/plan" method="POST">
                    @csrf
                    <input type="hidden" name="dentist" value="{{ $dentist->id }}">
                    <input type="hidden" name="created_by_dentist_id" value="{{ $dentist->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $subscription->plan->title }}" style="border-radius:10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" style="border-radius:10px;">{{ $subscription->plan->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-teal w-100"><i class="bi bi-save me-1"></i>Update Plan</button>
                </form>
            </div>
        @endif

        <!-- Patient details -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-person me-2" style="color:var(--dark-blue);"></i>Patient Details</h6>
            </div>
            <table style="width:100%; font-size:.85rem;">
                <tr><td class="text-muted pe-3 py-1">Name</td><td class="fw-semibold py-1">{{ $subscription->patient->name ?? '—' }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">Email</td><td class="py-1">{{ $subscription->patient->email ?? '—' }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">Phone</td><td class="py-1">{{ $subscription->patient->phone ?? '—' }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">Gender</td><td class="py-1">{{ ucfirst($subscription->patient->gender ?? '—') }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">Blood</td><td class="py-1">{{ $subscription->patient->blood_type ?? '—' }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">DOB</td><td class="py-1">{{ $subscription->patient->date_of_birth ? $subscription->patient->date_of_birth->format('M d, Y') : '—' }}</td></tr>
                <tr><td class="text-muted pe-3 py-1">Blocked</td><td class="py-1">
                    @if($subscription->patient->booking_blocked)
                        <span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill"></i> Yes</span>
                    @else
                        <span class="text-success">No</span>
                    @endif
                </td></tr>
            </table>
        </div>
    </div>
</div>
@endsection

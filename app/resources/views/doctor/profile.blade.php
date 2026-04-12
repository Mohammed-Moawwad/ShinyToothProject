@extends('doctor.layout')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- ── PROFILE CARD ───────────────────────────────── -->
    <div class="col-lg-4">
        <div class="dash-card text-center">
            <div class="py-4 px-3 rounded-top" style="background:linear-gradient(135deg,var(--dark-blue),var(--teal)); margin:-20px -20px 20px;">
                <img src="{{ $dentist->image ? asset($dentist->image) : 'https://ui-avatars.com/api/?name=' . urlencode($dentist->name) . '&size=120&background=e6f5f3&color=059386' }}"
                     alt="{{ $dentist->name }}"
                     class="rounded-circle mb-3"
                     style="width:120px; height:120px; object-fit:cover; border:4px solid #fff;">
                <h5 class="text-white mb-1">{{ $dentist->name }}</h5>
                <p class="text-white-50 mb-0" style="font-size:.88rem;">
                    @if($dentist->specializations->count())
                        {{ $dentist->specializations->pluck('name')->join(', ') }}
                    @else
                        General Dentistry
                    @endif
                </p>
            </div>

            <div class="text-start">
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-envelope me-2"></i>Email</span>
                    <span class="fw-semibold">{{ $dentist->email }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-phone me-2"></i>Phone</span>
                    <span class="fw-semibold">{{ $dentist->phone ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-flag me-2"></i>Nationality</span>
                    <span class="fw-semibold">{{ $dentist->nationality ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>Place of Birth</span>
                    <span class="fw-semibold">{{ $dentist->place_of_birth ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-mortarboard me-2"></i>University</span>
                    <span class="fw-semibold">{{ $dentist->university ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-clock-history me-2"></i>Experience</span>
                    <span class="fw-semibold">{{ $dentist->experience_years ?? 0 }} years</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-calendar-event me-2"></i>Hire Date</span>
                    <span class="fw-semibold">{{ $dentist->hire_date ? $dentist->hire_date->format('M d, Y') : '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2" style="font-size:.88rem;">
                    <span class="text-muted"><i class="bi bi-circle-fill me-2" style="font-size:.5rem; vertical-align:middle; color:{{ $dentist->status==='active' ? '#059386' : '#dc3545' }};"></i>Status</span>
                    <span class="badge-status {{ $dentist->status==='active' ? 'badge-active' : 'badge-cancelled' }}">{{ ucfirst($dentist->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── EDITABLE FORM ──────────────────────────────── -->
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-pencil-square me-2" style="color:var(--teal);"></i>Edit Profile</h6>
            </div>

            <form action="/doctor/profile/update?dentist={{ $dentist->id }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.88rem;">Phone Number</label>
                    <input type="text"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $dentist->phone) }}"
                           placeholder="+966 5XX XXX XXXX"
                           style="border-radius:10px;">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.88rem;">Career Description</label>
                    <textarea name="career_description"
                              class="form-control @error('career_description') is-invalid @enderror"
                              rows="8"
                              placeholder="Write about your career, specializations, achievements..."
                              style="border-radius:10px;">{{ old('career_description', $dentist->career_description) }}</textarea>
                    @error('career_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This description appears on your public profile page.</small>
                </div>

                <button type="submit" class="btn btn-teal">
                    <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- ── QUICK STATS ────────────────────────────── -->
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-graph-up me-2" style="color:var(--dark-blue);"></i>Quick Stats</h6>
            </div>
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="text-center p-3 rounded" style="background:#f8f9fa;">
                        <div class="fw-bold fs-4" style="color:var(--teal);">{{ $dentist->appointments()->count() }}</div>
                        <small class="text-muted">Total Appointments</small>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-center p-3 rounded" style="background:#f8f9fa;">
                        <div class="fw-bold fs-4" style="color:var(--dark-blue);">{{ $dentist->subscriptions()->count() }}</div>
                        <small class="text-muted">Total Subscriptions</small>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-center p-3 rounded" style="background:#f8f9fa;">
                        <div class="fw-bold fs-4" style="color:#f9a825;">
                            @php $avgRating = $dentist->ratings()->avg('rating') ?? 0; @endphp
                            {{ number_format($avgRating, 1) }}
                        </div>
                        <small class="text-muted">Avg Rating</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

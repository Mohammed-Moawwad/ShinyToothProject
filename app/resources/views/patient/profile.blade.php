@extends('patient.layout')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content')
<div class="row g-4 justify-content-center">

    <!-- ── AVATAR / INFO CARD ──────────────────────────── -->
    <div class="col-lg-3">
        <div class="dash-card text-center" style="padding:32px 24px;">
            <div style="width:88px; height:88px; border-radius:50%; background:linear-gradient(135deg,var(--teal),var(--dark-blue)); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; box-shadow:0 4px 16px rgba(0,50,99,.18);">
                <i class="bi bi-person-fill" style="color:#fff; font-size:2.2rem;"></i>
            </div>
            <h5 style="font-weight:700; color:var(--dark-blue); margin-bottom:4px;">{{ $patient->name }}</h5>
            <p style="font-size:.84rem; color:#6c757d; margin-bottom:20px;">{{ $patient->email }}</p>

            <div style="background:#f6fbfa; border-radius:12px; padding:16px; text-align:left;">
                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:.84rem; color:#495057;">
                    <i class="bi bi-telephone-fill" style="color:var(--teal); width:18px;"></i>
                    <span>{{ $patient->phone ?? '—' }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:.84rem; color:#495057;">
                    <i class="bi bi-droplet-fill" style="color:var(--teal); width:18px;"></i>
                    <span>{{ $patient->blood_type ?? '—' }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:.84rem; color:#495057;">
                    <i class="bi bi-gender-ambiguous" style="color:var(--teal); width:18px;"></i>
                    <span>{{ ucfirst($patient->gender ?? '—') }}</span>
                </div>
                <div class="d-flex align-items-center gap-2" style="font-size:.84rem; color:#495057;">
                    <i class="bi bi-flag-fill" style="color:var(--teal); width:18px;"></i>
                    <span>{{ $patient->nationality ?? '—' }}</span>
                </div>
            </div>

            <div class="mt-3 pt-3 border-top" style="font-size:.78rem; color:#adb5bd;">
                <i class="bi bi-shield-lock me-1"></i> Email & password are managed separately
            </div>
        </div>
    </div>

    <!-- ── EDIT FORM ───────────────────────────────────── -->
    <div class="col-lg-9">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                 style="border-radius:12px; border:none; background:#d4f5e4; color:#0f6b3a;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('patient.profile.update') }}">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="dash-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-person-lines-fill me-2" style="color:var(--teal);"></i>Personal Information</h6>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Enter your full name" required
                               style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="e.g. 0512345678"
                               style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Date of Birth</label>
                        <input type="date" name="date_of_birth"
                               value="{{ old('date_of_birth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror"
                               style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror"
                                style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                            <option value="">Select gender</option>
                            <option value="male"   {{ old('gender', $patient->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="dash-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-heart-pulse-fill me-2" style="color:#dc3545;"></i>Medical Information</h6>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Blood Type</label>
                        <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror"
                                style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                            <option value="">Select blood type</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}" {{ old('blood_type', $patient->blood_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                        @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="dash-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-geo-alt-fill me-2" style="color:#0056b3;"></i>Location Information</h6>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Place of Birth</label>
                        <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $patient->place_of_birth) }}"
                               class="form-control @error('place_of_birth') is-invalid @enderror"
                               placeholder="e.g. Riyadh"
                               style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                        @error('place_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Nationality</label>
                        <input type="text" name="nationality" value="{{ old('nationality', $patient->nationality) }}"
                               class="form-control @error('nationality') is-invalid @enderror"
                               placeholder="e.g. Saudi"
                               style="border-radius:10px; border-color:#dee2e6; font-size:.9rem;">
                        @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem; color:var(--dark-blue);">Address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Enter your full address"
                                  style="border-radius:10px; border-color:#dee2e6; font-size:.9rem; resize:none;">{{ old('address', $patient->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-teal px-4">
                    <i class="bi bi-floppy me-2"></i>Save Changes
                </button>
                <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary" style="border-radius:10px; font-weight:600; font-size:.85rem;">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Read-only account info notice -->
        <div class="dash-card mt-4" style="border-left:4px solid var(--teal);">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-shield-lock-fill" style="color:var(--teal); font-size:1.4rem; margin-top:2px;"></i>
                <div>
                    <div style="font-weight:700; color:var(--dark-blue); margin-bottom:4px;">Account Credentials</div>
                    <div style="font-size:.86rem; color:#6c757d;">
                        Your <strong>email</strong> and <strong>password</strong> cannot be changed from this page.
                        Please contact the clinic administration to update your login credentials.
                    </div>
                    <div class="mt-2" style="font-size:.84rem; color:#495057;">
                        <i class="bi bi-envelope me-1" style="color:var(--teal);"></i> {{ $patient->email }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

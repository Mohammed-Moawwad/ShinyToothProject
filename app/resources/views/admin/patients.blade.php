@extends('admin.layout')
@section('page-title', 'Patients Management')

@section('extra-css')
<style>
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid .full { grid-column: 1 / -1; }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    {{-- Header row --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
            <i class="bi bi-plus-lg"></i> Add Patient
        </button>
    </div>

    {{-- Filters --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <form method="GET" action="{{ route('admin.patients') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Name or email..." value="{{ $search }}">
                    <input type="hidden" name="gender" value="{{ $gender }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-3">
                <label class="form-label">Gender</label>
                <form method="GET" action="{{ route('admin.patients') }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select class="form-select" name="gender" onchange="this.form.submit()">
                        <option value="all"    {{ $gender === 'all'    ? 'selected' : '' }}>All Genders</option>
                        <option value="male"   {{ $gender === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $gender === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-people-fill"></i> Patients</div>
            <span class="count-badge">{{ $patients->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Blood Type</th><th>Date of Birth</th><th>Appointments</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $pt)
                    <tr>
                        <td><strong>{{ $pt->name }}</strong></td>
                        <td>{{ $pt->email }}</td>
                        <td>{{ $pt->phone }}</td>
                        <td>
                            <span class="badge-status {{ $pt->gender === 'male' ? 'confirmed' : 'pending' }}">
                                {{ ucfirst($pt->gender) }}
                            </span>
                        </td>
                        <td>{{ $pt->blood_type ?? '�' }}</td>
                        <td>{{ $pt->date_of_birth?->format('d M Y') ?? '�' }}</td>
                        <td><span class="count-badge">{{ $pt->appointments_count }}</span></td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info me-1" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $pt->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.patients.delete', $pt->id) }}" style="display:inline;" onsubmit="return confirm('Delete patient {{ addslashes($pt->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.25);color:#f87171;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editPatientModal{{ $pt->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit {{ $pt->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.patients.update', $pt->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-grid">
                                                    <div>
                                                        <label class="form-label">Full Name *</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $pt->name }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $pt->email }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Phone *</label>
                                                        <input type="text" class="form-control" name="phone" value="{{ $pt->phone }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Date of Birth *</label>
                                                        <input type="date" class="form-control" name="date_of_birth" value="{{ $pt->date_of_birth?->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Gender *</label>
                                                        <select class="form-select" name="gender" required>
                                                            <option value="male"   {{ $pt->gender === 'male'   ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $pt->gender === 'female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Blood Type</label>
                                                        <select class="form-select" name="blood_type">
                                                            <option value="">� Select �</option>
                                                            @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bt)
                                                                <option value="{{ $bt }}" {{ $pt->blood_type === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="full">
                                                        <label class="form-label">Address *</label>
                                                        <textarea class="form-control" name="address" rows="2" required>{{ $pt->address }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Nationality</label>
                                                        <input type="text" class="form-control" name="nationality" value="{{ $pt->nationality }}">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Place of Birth</label>
                                                        <input type="text" class="form-control" name="place_of_birth" value="{{ $pt->place_of_birth }}">
                                                    </div>
                                                    <div class="full">
                                                        <label class="form-label">New Password <small style="color:var(--text-muted);">(leave blank to keep)</small></label>
                                                        <input type="password" class="form-control" name="password" autocomplete="new-password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 20px;display:flex;gap:10px;justify-content:flex-end;">
                                                <button type="button" data-bs-dismiss="modal" style="background:#f0f2f5;border:1px solid #dee2e6;color:#495057;padding:8px 18px;border-radius:10px;cursor:pointer;font-size:.85rem;">Cancel</button>
                                                <button type="submit" class="btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4" style="color:var(--text-muted);">No patients found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $patients->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

{{-- Add Patient Modal --}}
<div class="modal fade" id="addPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.patients.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div style="background:#fde8e8;border:1px solid #f5b7b7;color:#c0392b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:0.8rem;">
                            <ul style="margin:0;padding-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="form-grid">
                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required autocomplete="new-password">
                        </div>
                        <div>
                            <label class="form-label">Phone *</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Gender *</label>
                            <select class="form-select" name="gender" required>
                                <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Blood Type</label>
                            <select class="form-select" name="blood_type">
                                <option value="">� Select �</option>
                                @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bt)
                                    <option value="{{ $bt }}" {{ old('blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="nationality" value="{{ old('nationality') }}">
                        </div>
                        <div class="full">
                            <label class="form-label">Address *</label>
                            <textarea class="form-control" name="address" rows="2" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="full">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 20px;display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" data-bs-dismiss="modal" style="background:#f0f2f5;border:1px solid #dee2e6;color:#495057;padding:8px 18px;border-radius:10px;cursor:pointer;font-size:.85rem;">Cancel</button>
                    <button type="submit" class="btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addPatientModal')).show();
    });
</script>
@endif
@endsection

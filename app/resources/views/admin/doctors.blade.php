@extends('admin.layout')
@section('page-title', 'Doctors Management')

@section('extra-css')
<style>
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid .full { grid-column: 1 / -1; }
.form-err { color: #f87171; font-size: 0.73rem; margin-top: 3px; }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    {{-- Header row --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
            <i class="bi bi-plus-lg"></i> Add Doctor
        </button>
    </div>

    {{-- Filters --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <form method="GET" action="{{ route('admin.doctors') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Name or email..." value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <form method="GET" action="{{ route('admin.doctors') }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="all"      {{ $status === 'all'      ? 'selected' : '' }}>All Statuses</option>
                        <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ $status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-person-badge-fill"></i> Doctors</div>
            <span class="count-badge">{{ $doctors->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Phone</th><th>Salary</th><th>Exp.</th><th>Status</th><th>Appointments</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doc)
                    <tr>
                        <td><strong>{{ $doc->name }}</strong></td>
                        <td>{{ $doc->email }}</td>
                        <td>{{ $doc->phone }}</td>
                        <td style="color:#059386;">SAR {{ number_format($doc->salary, 0) }}</td>
                        <td>{{ $doc->experience_years ?? '�' }} yrs</td>
                        <td>
                            @php
                                $sc = ['active'=>'completed','inactive'=>'cancelled','on_leave'=>'pending'];
                            @endphp
                            <span class="badge-status {{ $sc[$doc->status] ?? 'pending' }}">{{ ucfirst(str_replace('_',' ',$doc->status)) }}</span>
                        </td>
                        <td><span class="count-badge">{{ $doc->appointments_count }}</span></td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info me-1" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doc->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.doctors.delete', $doc->id) }}" style="display:inline;" onsubmit="return confirm('Delete Dr. {{ addslashes($doc->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.25);color:#f87171;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editDoctorModal{{ $doc->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Dr. {{ $doc->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.doctors.update', $doc->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-grid">
                                                    <div>
                                                        <label class="form-label">Full Name *</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $doc->name }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $doc->email }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Phone *</label>
                                                        <input type="text" class="form-control" name="phone" value="{{ $doc->phone }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Salary *</label>
                                                        <input type="number" class="form-control" name="salary" value="{{ $doc->salary }}" min="0" step="0.01" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Hire Date *</label>
                                                        <input type="date" class="form-control" name="hire_date" value="{{ $doc->hire_date?->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Status *</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="active"   {{ $doc->status === 'active'   ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $doc->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            <option value="on_leave" {{ $doc->status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Experience (years)</label>
                                                        <input type="number" class="form-control" name="experience_years" value="{{ $doc->experience_years }}" min="0">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">University</label>
                                                        <input type="text" class="form-control" name="university" value="{{ $doc->university }}">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Nationality</label>
                                                        <input type="text" class="form-control" name="nationality" value="{{ $doc->nationality }}">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Place of Birth</label>
                                                        <input type="text" class="form-control" name="place_of_birth" value="{{ $doc->place_of_birth }}">
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
                        <tr><td colspan="8" class="text-center py-4" style="color:var(--text-muted);">No doctors found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($doctors->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $doctors->appends(['search' => $search, 'status' => $status])->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

{{-- Add Doctor Modal --}}
<div class="modal fade" id="addDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
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
                            <label class="form-label">Salary *</label>
                            <input type="number" class="form-control" name="salary" value="{{ old('salary') }}" min="0" step="0.01" required>
                        </div>
                        <div>
                            <label class="form-label">Hire Date *</label>
                            <input type="date" class="form-control" name="hire_date" value="{{ old('hire_date') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Experience (years)</label>
                            <input type="number" class="form-control" name="experience_years" value="{{ old('experience_years') }}" min="0">
                        </div>
                        <div>
                            <label class="form-label">University</label>
                            <input type="text" class="form-control" name="university" value="{{ old('university') }}">
                        </div>
                        <div>
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="nationality" value="{{ old('nationality') }}">
                        </div>
                        <div class="full">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth') }}">
                        </div>
                        <div class="full">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/webp" id="doctorImageInput">
                            <div id="doctorImagePreview" style="display:none;margin-top:10px;">
                                <img id="doctorImageThumb" src="" alt="Preview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
                            </div>
                            <small style="color:var(--text-muted);font-size:0.75rem;">JPG, PNG or WEBP · max 2 MB (optional)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 20px;display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" data-bs-dismiss="modal" style="background:#f0f2f5;border:1px solid #dee2e6;color:#495057;padding:8px 18px;border-radius:10px;cursor:pointer;font-size:.85rem;">Cancel</button>
                    <button type="submit" class="btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Doctor</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addDoctorModal')).show();
    });
</script>
@endif
<script>
    document.getElementById('doctorImageInput').addEventListener('change', function() {
        const preview = document.getElementById('doctorImagePreview');
        const img = document.getElementById('doctorImageThumb');
        if (this.files && this.files[0]) {
            img.src = URL.createObjectURL(this.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection

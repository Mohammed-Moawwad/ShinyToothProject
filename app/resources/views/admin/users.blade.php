@extends('admin.layout')
@section('page-title', 'Users Management')

@section('extra-css')
<style>
.tab-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 20px;font-size:0.85rem;font-weight:600;color:var(--text-muted);background:#f0f2f5;border:1px solid #dee2e6;border-radius:10px;text-decoration:none;transition:all .2s;}
.tab-btn:hover{background:var(--teal-light);border-color:var(--teal);color:var(--teal);}
.tab-btn.active{background:var(--teal);border-color:var(--teal);color:#fff;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-grid .full{grid-column:1/-1;}
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-people-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-people-fill"></i></div>
                <div class="stat-num">{{ $patientCount }}</div>
                <div class="stat-lbl">Total Patients</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-person-badge-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-num">{{ $dentistCount }}</div>
                <div class="stat-lbl">Total Doctors</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <i class="bi bi-person-check-fill stat-card-bg-icon"></i>
                <div class="stat-badge" style="background:#f0ecff;color:#6f42c1;"><i class="bi bi-person-check-fill"></i></div>
                <div class="stat-num">{{ $patientCount + $dentistCount }}</div>
                <div class="stat-lbl">Total Users</div>
            </div>
        </div>
    </div>

    {{-- Tab bar + search + add button --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3 flex-wrap">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users', ['type'=>'all','search'=>$search]) }}" class="tab-btn {{ $type==='all' ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> All
            </a>
            <a href="{{ route('admin.users', ['type'=>'dentists','search'=>$search]) }}" class="tab-btn {{ $type==='dentists' ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Doctors
            </a>
            <a href="{{ route('admin.users', ['type'=>'patients','search'=>$search]) }}" class="tab-btn {{ $type==='patients' ? 'active' : '' }}">
                <i class="bi bi-person-heart"></i> Patients
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width:380px;">
            <form method="GET" action="{{ route('admin.users') }}" class="d-flex gap-2 w-100">
                <input type="hidden" name="type" value="{{ $type }}">
                @if($type==='dentists') <input type="hidden" name="status" value="{{ $statusFilter }}"> @endif
                @if($type==='patients') <input type="hidden" name="gender" value="{{ $genderFilter }}"> @endif
                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by name or email...">
                <button class="btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div>
            @if($type==='dentists')
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                    <i class="bi bi-plus-lg"></i> Add Doctor
                </button>
            @elseif($type==='patients')
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="bi bi-plus-lg"></i> Add Patient
                </button>
            @endif
        </div>
    </div>

    {{-- --- ALL TAB --- --}}
    @if($type === 'all')
    <div class="row g-3">
        {{-- Doctors panel --}}
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-person-badge-fill"></i> Doctors</div>
                    <a href="{{ route('admin.users', ['type'=>'dentists']) }}" class="view-all-link">View All &rsaquo;</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Appts</th></tr></thead>
                        <tbody>
                            @forelse($doctors as $doc)
                            <tr>
                                <td><strong>{{ $doc->name }}</strong></td>
                                <td style="font-size:0.78rem;">{{ $doc->email }}</td>
                                <td>
                                    @php $sc=['active'=>'completed','inactive'=>'cancelled','on_leave'=>'pending']; @endphp
                                    <span class="badge-status {{ $sc[$doc->status] ?? 'pending' }}">{{ ucfirst(str_replace('_',' ',$doc->status)) }}</span>
                                </td>
                                <td><span class="count-badge">{{ $doc->appointments_count }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3" style="color:var(--text-muted);">No doctors yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($doctors instanceof \Illuminate\Pagination\LengthAwarePaginator && $doctors->total() > 0)
                    <div class="d-flex justify-content-center p-2" style="font-size:0.8rem;">{{ $doctors->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>
        {{-- Patients panel --}}
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title"><i class="bi bi-person-heart"></i> Patients</div>
                    <a href="{{ route('admin.users', ['type'=>'patients']) }}" class="view-all-link">View All &rsaquo;</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Gender</th><th>Appts</th></tr></thead>
                        <tbody>
                            @forelse($patients as $pt)
                            <tr>
                                <td><strong>{{ $pt->name }}</strong></td>
                                <td style="font-size:0.78rem;">{{ $pt->email }}</td>
                                <td><span class="badge-status {{ $pt->gender==='male' ? 'confirmed' : 'pending' }}">{{ ucfirst($pt->gender) }}</span></td>
                                <td><span class="count-badge">{{ $pt->appointments_count }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3" style="color:var(--text-muted);">No patients yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $patients->total() > 0)
                    <div class="d-flex justify-content-center p-2" style="font-size:0.8rem;">{{ $patients->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- --- DOCTORS TAB --- --}}
    @if($type === 'dentists')
    <div class="filter-panel mb-3">
        <form method="GET" action="{{ route('admin.users') }}" class="d-flex gap-3 align-items-end flex-wrap">
            <input type="hidden" name="type" value="dentists">
            <input type="hidden" name="search" value="{{ $search }}">
            <div>
                <label class="form-label">Status</label>
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="all"      {{ $statusFilter==='all'      ? 'selected':'' }}>All Statuses</option>
                    <option value="active"   {{ $statusFilter==='active'   ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ $statusFilter==='inactive' ? 'selected':'' }}>Inactive</option>
                    <option value="on_leave" {{ $statusFilter==='on_leave' ? 'selected':'' }}>On Leave</option>
                </select>
            </div>
        </form>
    </div>
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-person-badge-fill"></i> Doctors</div>
            @if($doctors instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <span class="count-badge">{{ $doctors->total() }} total</span>
            @endif
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Salary</th><th>Exp.</th><th>Status</th><th>Appts</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($doctors as $doc)
                    <tr>
                        <td><strong>{{ $doc->name }}</strong></td>
                        <td>{{ $doc->email }}</td>
                        <td>{{ $doc->phone }}</td>
                        <td style="color:#059386;">SAR {{ number_format($doc->salary,0) }}</td>
                        <td>{{ $doc->experience_years ?? '�' }} yrs</td>
                        <td><span class="badge-status {{ $sc[$doc->status] ?? 'pending' }}">{{ ucfirst(str_replace('_',' ',$doc->status)) }}</span></td>
                        <td><span class="count-badge">{{ $doc->appointments_count }}</span></td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info me-1" data-bs-toggle="modal" data-bs-target="#editDoc{{ $doc->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.doctors.delete',$doc->id) }}" style="display:inline;" onsubmit="return confirm('Delete Dr. {{ addslashes($doc->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.25);color:#f87171;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editDoc{{ $doc->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Dr. {{ $doc->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.doctors.update',$doc->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-grid">
                                                    <div><label class="form-label">Full Name *</label><input type="text" class="form-control" name="name" value="{{ $doc->name }}" required></div>
                                                    <div><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ $doc->email }}" required></div>
                                                    <div><label class="form-label">Phone *</label><input type="text" class="form-control" name="phone" value="{{ $doc->phone }}" required></div>
                                                    <div><label class="form-label">Salary *</label><input type="number" class="form-control" name="salary" value="{{ $doc->salary }}" min="0" step="0.01" required></div>
                                                    <div><label class="form-label">Hire Date *</label><input type="date" class="form-control" name="hire_date" value="{{ $doc->hire_date?->format('Y-m-d') }}" required></div>
                                                    <div>
                                                        <label class="form-label">Status *</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="active"   {{ $doc->status==='active'   ?'selected':'' }}>Active</option>
                                                            <option value="inactive" {{ $doc->status==='inactive' ?'selected':'' }}>Inactive</option>
                                                            <option value="on_leave" {{ $doc->status==='on_leave' ?'selected':'' }}>On Leave</option>
                                                        </select>
                                                    </div>
                                                    <div><label class="form-label">Experience (yrs)</label><input type="number" class="form-control" name="experience_years" value="{{ $doc->experience_years }}" min="0"></div>
                                                    <div><label class="form-label">University</label><input type="text" class="form-control" name="university" value="{{ $doc->university }}"></div>
                                                    <div><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="{{ $doc->nationality }}"></div>
                                                    <div><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="place_of_birth" value="{{ $doc->place_of_birth }}"></div>
                                                    <div class="full"><label class="form-label">New Password <small style="color:var(--text-muted);">(leave blank to keep)</small></label><input type="password" class="form-control" name="password" autocomplete="new-password"></div>
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
        @if($doctors instanceof \Illuminate\Pagination\LengthAwarePaginator && $doctors->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $doctors->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
    @endif

    {{-- --- PATIENTS TAB --- --}}
    @if($type === 'patients')
    <div class="filter-panel mb-3">
        <form method="GET" action="{{ route('admin.users') }}" class="d-flex gap-3 align-items-end flex-wrap">
            <input type="hidden" name="type" value="patients">
            <input type="hidden" name="search" value="{{ $search }}">
            <div>
                <label class="form-label">Gender</label>
                <select class="form-select" name="gender" onchange="this.form.submit()">
                    <option value="all"    {{ $genderFilter==='all'    ?'selected':'' }}>All Genders</option>
                    <option value="male"   {{ $genderFilter==='male'   ?'selected':'' }}>Male</option>
                    <option value="female" {{ $genderFilter==='female' ?'selected':'' }}>Female</option>
                </select>
            </div>
        </form>
    </div>
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-person-heart"></i> Patients</div>
            @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <span class="count-badge">{{ $patients->total() }} total</span>
            @endif
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Blood</th><th>DOB</th><th>Appts</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($patients as $pt)
                    <tr>
                        <td><strong>{{ $pt->name }}</strong></td>
                        <td>{{ $pt->email }}</td>
                        <td>{{ $pt->phone }}</td>
                        <td><span class="badge-status {{ $pt->gender==='male' ? 'confirmed' : 'pending' }}">{{ ucfirst($pt->gender) }}</span></td>
                        <td>{{ $pt->blood_type ?? '�' }}</td>
                        <td>{{ $pt->date_of_birth?->format('d M Y') ?? '�' }}</td>
                        <td><span class="count-badge">{{ $pt->appointments_count }}</span></td>
                        <td style="white-space:nowrap;">
                            <button class="btn-info me-1" data-bs-toggle="modal" data-bs-target="#editPat{{ $pt->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.patients.delete',$pt->id) }}" style="display:inline;" onsubmit="return confirm('Delete {{ addslashes($pt->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.25);color:#f87171;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editPat{{ $pt->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit {{ $pt->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.patients.update',$pt->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-grid">
                                                    <div><label class="form-label">Full Name *</label><input type="text" class="form-control" name="name" value="{{ $pt->name }}" required></div>
                                                    <div><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ $pt->email }}" required></div>
                                                    <div><label class="form-label">Phone *</label><input type="text" class="form-control" name="phone" value="{{ $pt->phone }}" required></div>
                                                    <div><label class="form-label">Date of Birth *</label><input type="date" class="form-control" name="date_of_birth" value="{{ $pt->date_of_birth?->format('Y-m-d') }}" required></div>
                                                    <div>
                                                        <label class="form-label">Gender *</label>
                                                        <select class="form-select" name="gender" required>
                                                            <option value="male"   {{ $pt->gender==='male'   ?'selected':'' }}>Male</option>
                                                            <option value="female" {{ $pt->gender==='female' ?'selected':'' }}>Female</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Blood Type</label>
                                                        <select class="form-select" name="blood_type">
                                                            <option value="">� Select �</option>
                                                            @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bt)
                                                                <option value="{{ $bt }}" {{ $pt->blood_type===$bt ?'selected':'' }}>{{ $bt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="full"><label class="form-label">Address *</label><textarea class="form-control" name="address" rows="2" required>{{ $pt->address }}</textarea></div>
                                                    <div><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="{{ $pt->nationality }}"></div>
                                                    <div><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="place_of_birth" value="{{ $pt->place_of_birth }}"></div>
                                                    <div class="full"><label class="form-label">New Password <small style="color:var(--text-muted);">(leave blank to keep)</small></label><input type="password" class="form-control" name="password" autocomplete="new-password"></div>
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
        @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $patients->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $patients->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Add Doctor Modal --}}
@if($type === 'dentists')
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
                        <div><label class="form-label">Full Name *</label><input type="text" class="form-control" name="name" value="{{ old('name') }}" required></div>
                        <div><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
                        <div><label class="form-label">Password *</label><input type="password" class="form-control" name="password" required autocomplete="new-password"></div>
                        <div><label class="form-label">Phone *</label><input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required></div>
                        <div><label class="form-label">Salary *</label><input type="number" class="form-control" name="salary" value="{{ old('salary') }}" min="0" step="0.01" required></div>
                        <div><label class="form-label">Hire Date *</label><input type="date" class="form-control" name="hire_date" value="{{ old('hire_date') }}" required></div>
                        <div>
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div><label class="form-label">Experience (yrs)</label><input type="number" class="form-control" name="experience_years" value="{{ old('experience_years') }}" min="0"></div>
                        <div><label class="form-label">University</label><input type="text" class="form-control" name="university" value="{{ old('university') }}"></div>
                        <div><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="{{ old('nationality') }}"></div>
                        <div class="full"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth') }}"></div>
                        <div class="full">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/webp" id="doctorImageInputUsers">
                            <div id="doctorImagePreviewUsers" style="display:none;margin-top:10px;">
                                <img id="doctorImageThumbUsers" src="" alt="Preview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
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
<script>document.addEventListener('DOMContentLoaded',function(){new bootstrap.Modal(document.getElementById('addDoctorModal')).show();});</script>
@endif
@endif

{{-- Add Patient Modal --}}
@if($type === 'patients')
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
                        <div><label class="form-label">Full Name *</label><input type="text" class="form-control" name="name" value="{{ old('name') }}" required></div>
                        <div><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
                        <div><label class="form-label">Password *</label><input type="password" class="form-control" name="password" required autocomplete="new-password"></div>
                        <div><label class="form-label">Phone *</label><input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required></div>
                        <div><label class="form-label">Date of Birth *</label><input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}" required></div>
                        <div>
                            <label class="form-label">Gender *</label>
                            <select class="form-select" name="gender" required>
                                <option value="male"   {{ old('gender')==='male'   ?'selected':'' }}>Male</option>
                                <option value="female" {{ old('gender')==='female' ?'selected':'' }}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Blood Type</label>
                            <select class="form-select" name="blood_type">
                                <option value="">� Select �</option>
                                @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bt)
                                    <option value="{{ $bt }}" {{ old('blood_type')===$bt ?'selected':'' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="{{ old('nationality') }}"></div>
                        <div class="full"><label class="form-label">Address *</label><textarea class="form-control" name="address" rows="2" required>{{ old('address') }}</textarea></div>
                        <div class="full"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth') }}"></div>
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
<script>document.addEventListener('DOMContentLoaded',function(){new bootstrap.Modal(document.getElementById('addPatientModal')).show();});</script>
@endif
@endif
<script>
    document.getElementById('doctorImageInputUsers').addEventListener('change', function() {
        const preview = document.getElementById('doctorImagePreviewUsers');
        const img = document.getElementById('doctorImageThumbUsers');
        if (this.files && this.files[0]) {
            img.src = URL.createObjectURL(this.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection

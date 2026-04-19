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

    {{-- Stat Cards — change based on active tab --}}
    <div class="row g-3 mb-4">

        @if($type === 'all')
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

        @elseif($type === 'dentists')
            {{-- Row 1: Status counts --}}
            <div class="col-6 col-sm-3">
                <div class="stat-card">
                    <i class="bi bi-person-badge-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-person-badge-fill"></i></div>
                    <div class="stat-num">{{ $stats['doctors']['total'] }}</div>
                    <div class="stat-lbl">Total Doctors</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #059386;">
                    <i class="bi bi-check-circle-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#d4f5e4;color:#0f6b3a;"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="stat-num" style="color:#059386;">{{ $stats['doctors']['active'] }}</div>
                    <div class="stat-lbl">Active</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #c0392b;">
                    <i class="bi bi-x-circle-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="stat-num" style="color:#c0392b;">{{ $stats['doctors']['inactive'] }}</div>
                    <div class="stat-lbl">Inactive</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #b86e00;">
                    <i class="bi bi-hourglass-split stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fff4e5;color:#b86e00;"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-num" style="color:#b86e00;">{{ $stats['doctors']['on_leave'] }}</div>
                    <div class="stat-lbl">On Leave</div>
                </div>
            </div>
            {{-- Row 2: Averages --}}
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #6f42c1;">
                    <i class="bi bi-mortarboard-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#f0ecff;color:#6f42c1;"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="stat-num" style="color:#6f42c1;">{{ $stats['doctors']['avg_experience'] }} <small style="font-size:.55em;">yrs</small></div>
                    <div class="stat-lbl">Avg. Experience</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #059386;">
                    <i class="bi bi-cash-stack stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-num" style="color:#059386;">SAR {{ number_format($stats['doctors']['avg_salary']) }}</div>
                    <div class="stat-lbl">Avg. Salary</div>
                </div>
            </div>
            {{-- Row 3: Breakdown panels --}}
            @if($stats['doctors']['specializations']->isNotEmpty())
            <div class="col-12 col-md-6">
                <div class="panel" style="margin-bottom:0;">
                    <div class="panel-head"><div class="panel-head-title"><i class="bi bi-diagram-3-fill"></i> Doctors by Specialization</div></div>
                    <div style="padding:12px 16px;">
                        @foreach($stats['doctors']['specializations'] as $spec)
                        @php $pct = $stats['doctors']['total'] > 0 ? round(($spec->dentists_count / $stats['doctors']['total']) * 100) : 0; @endphp
                        <div style="margin-bottom:10px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                <span>{{ $spec->name }}</span>
                                <span style="color:var(--text-muted);">{{ $spec->dentists_count }} ({{ $pct }}%)</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:4px;height:6px;">
                                <div style="background:var(--teal);width:{{ $pct }}%;height:6px;border-radius:4px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @if($stats['doctors']['nationalities']->isNotEmpty())
            <div class="col-12 col-md-6">
                <div class="panel" style="margin-bottom:0;">
                    <div class="panel-head"><div class="panel-head-title"><i class="bi bi-globe2"></i> Top Nationalities (Doctors)</div></div>
                    <div style="padding:12px 16px;">
                        @foreach($stats['doctors']['nationalities'] as $nat)
                        @php $pct = $stats['doctors']['total'] > 0 ? round(($nat->total / $stats['doctors']['total']) * 100) : 0; @endphp
                        <div style="margin-bottom:10px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                <span>{{ $nat->nationality }}</span>
                                <span style="color:var(--text-muted);">{{ $nat->total }} ({{ $pct }}%)</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:4px;height:6px;">
                                <div style="background:#0056b3;width:{{ $pct }}%;height:6px;border-radius:4px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            {{-- Leaderboards row --}}
            <div class="col-12">
                <div class="row g-3">
                    {{-- Top Rated --}}
                    <div class="col-12 col-md-4">
                        <div class="panel" style="margin-bottom:0;height:100%;">
                            <div class="panel-head"><div class="panel-head-title"><i class="bi bi-star-fill" style="color:#f5a623;"></i> Top 3 by Rating</div></div>
                            <div style="padding:12px 16px;">
                                @forelse($stats['doctors']['top_rated'] as $i => $doc)
                                <div class="d-flex align-items-center gap-3" style="padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                                    <span style="font-size:1.2rem;font-weight:800;color:{{ $i===0 ? '#f5a623' : ($i===1 ? '#9e9e9e' : '#cd7f32') }};min-width:22px;">#{{ $i+1 }}</span>
                                    @if($doc->image)
                                        <img src="{{ asset($doc->image) }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
                                    @else
                                        <div style="width:38px;height:38px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.9rem;">{{ strtoupper(substr($doc->name,0,1)) }}</div>
                                    @endif
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
                                        <div style="font-size:.75rem;color:var(--text-muted);">
                                            @for($s=1;$s<=5;$s++)<i class="bi bi-star{{ $s <= round($doc->avg_rating) ? '-fill' : '' }}" style="color:#f5a623;font-size:.65rem;"></i>@endfor
                                            {{ $doc->avg_rating }} <span style="color:#ccc;">({{ $doc->rating_count }})</span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:20px 0;">No ratings yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    {{-- Highest Salary --}}
                    <div class="col-12 col-md-4">
                        <div class="panel" style="margin-bottom:0;height:100%;">
                            <div class="panel-head"><div class="panel-head-title"><i class="bi bi-arrow-up-circle-fill" style="color:#059386;"></i> Highest Salaries</div></div>
                            <div style="padding:12px 16px;">
                                @foreach($stats['doctors']['highest_salary'] as $i => $doc)
                                <div class="d-flex align-items-center gap-3" style="padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                                    <span style="font-size:1.2rem;font-weight:800;color:#059386;min-width:22px;">#{{ $i+1 }}</span>
                                    @if($doc->image)
                                        <img src="{{ asset($doc->image) }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--teal);">
                                    @else
                                        <div style="width:38px;height:38px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.9rem;">{{ strtoupper(substr($doc->name,0,1)) }}</div>
                                    @endif
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
                                        <div style="font-size:.8rem;color:#059386;font-weight:700;">SAR {{ number_format($doc->salary) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Lowest Salary --}}
                    <div class="col-12 col-md-4">
                        <div class="panel" style="margin-bottom:0;height:100%;">
                            <div class="panel-head"><div class="panel-head-title"><i class="bi bi-arrow-down-circle-fill" style="color:#c0392b;"></i> Lowest Salaries</div></div>
                            <div style="padding:12px 16px;">
                                @foreach($stats['doctors']['lowest_salary'] as $i => $doc)
                                <div class="d-flex align-items-center gap-3" style="padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                                    <span style="font-size:1.2rem;font-weight:800;color:#c0392b;min-width:22px;">#{{ $i+1 }}</span>
                                    @if($doc->image)
                                        <img src="{{ asset($doc->image) }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #c0392b;">
                                    @else
                                        <div style="width:38px;height:38px;border-radius:50%;background:#fde8e8;display:flex;align-items:center;justify-content:center;font-weight:700;color:#c0392b;font-size:.9rem;">{{ strtoupper(substr($doc->name,0,1)) }}</div>
                                    @endif
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
                                        <div style="font-size:.8rem;color:#c0392b;font-weight:700;">SAR {{ number_format($doc->salary) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($type === 'patients')
            {{-- Row 1: Core counts --}}
            <div class="col-6 col-sm-3">
                <div class="stat-card">
                    <i class="bi bi-people-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-num">{{ $stats['patients']['total'] }}</div>
                    <div class="stat-lbl">Total Patients</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #0056b3;">
                    <i class="bi bi-gender-male stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e7f1ff;color:#0056b3;"><i class="bi bi-gender-male"></i></div>
                    <div class="stat-num" style="color:#0056b3;">{{ $stats['patients']['male'] }}</div>
                    <div class="stat-lbl">Male</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #c86b9e;">
                    <i class="bi bi-gender-female stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fce8f5;color:#c86b9e;"><i class="bi bi-gender-female"></i></div>
                    <div class="stat-num" style="color:#c86b9e;">{{ $stats['patients']['female'] }}</div>
                    <div class="stat-lbl">Female</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #c0392b;">
                    <i class="bi bi-shield-x-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#fde8e8;color:#c0392b;"><i class="bi bi-shield-x-fill"></i></div>
                    <div class="stat-num" style="color:#c0392b;">{{ $stats['patients']['blocked'] }}</div>
                    <div class="stat-lbl">Blocked</div>
                </div>
            </div>
            {{-- Row 2: Engagement --}}
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #059386;">
                    <i class="bi bi-calendar2-check-fill stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#e6f5f3;color:#059386;"><i class="bi bi-calendar2-check-fill"></i></div>
                    <div class="stat-num" style="color:#059386;">{{ $stats['patients']['with_appts'] }}</div>
                    <div class="stat-lbl">Have Appointments</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-card" style="border-left:3px solid #6f42c1;">
                    <i class="bi bi-person-slash stat-card-bg-icon"></i>
                    <div class="stat-badge" style="background:#f0ecff;color:#6f42c1;"><i class="bi bi-person-slash"></i></div>
                    <div class="stat-num" style="color:#6f42c1;">{{ $stats['patients']['total'] - $stats['patients']['with_appts'] }}</div>
                    <div class="stat-lbl">No Appointments Yet</div>
                </div>
            </div>
            {{-- Row 3: Breakdown panels --}}
            @if($stats['patients']['blood_types']->isNotEmpty())
            <div class="col-12 col-md-6">
                <div class="panel" style="margin-bottom:0;">
                    <div class="panel-head"><div class="panel-head-title"><i class="bi bi-droplet-fill" style="color:#c0392b;"></i> Patients by Blood Type</div></div>
                    <div style="padding:12px 16px;">
                        @foreach($stats['patients']['blood_types'] as $bt)
                        @php $pct = $stats['patients']['total'] > 0 ? round(($bt->total / $stats['patients']['total']) * 100) : 0; @endphp
                        <div style="margin-bottom:10px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                <span style="font-weight:600;">{{ $bt->blood_type }}</span>
                                <span style="color:var(--text-muted);">{{ $bt->total }} ({{ $pct }}%)</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:4px;height:6px;">
                                <div style="background:#c0392b;width:{{ $pct }}%;height:6px;border-radius:4px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @if($stats['patients']['nationalities']->isNotEmpty())
            <div class="col-12 col-md-6">
                <div class="panel" style="margin-bottom:0;">
                    <div class="panel-head"><div class="panel-head-title"><i class="bi bi-globe2"></i> Top Nationalities (Patients)</div></div>
                    <div style="padding:12px 16px;">
                        @foreach($stats['patients']['nationalities'] as $nat)
                        @php $pct = $stats['patients']['total'] > 0 ? round(($nat->total / $stats['patients']['total']) * 100) : 0; @endphp
                        <div style="margin-bottom:10px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                <span>{{ $nat->nationality }}</span>
                                <span style="color:var(--text-muted);">{{ $nat->total }} ({{ $pct }}%)</span>
                            </div>
                            <div style="background:#e9ecef;border-radius:4px;height:6px;">
                                <div style="background:#059386;width:{{ $pct }}%;height:6px;border-radius:4px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            {{-- Most Booking Patients --}}
            @if(isset($stats['patients']['most_booking']) && $stats['patients']['most_booking']->isNotEmpty())
            <div class="col-12 col-md-6">
                <div class="panel" style="margin-bottom:0;">
                    <div class="panel-head"><div class="panel-head-title"><i class="bi bi-trophy-fill" style="color:#f5a623;"></i> Most Active Patients (by Bookings)</div></div>
                    <div style="padding:12px 16px;">
                        @foreach($stats['patients']['most_booking'] as $i => $pat)
                        <div class="d-flex align-items-center gap-3" style="padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                            <span style="font-size:1.2rem;font-weight:800;color:{{ $i===0 ? '#f5a623' : ($i===1 ? '#9e9e9e' : '#cd7f32') }};min-width:22px;">#{{ $i+1 }}</span>
                            <div style="width:38px;height:38px;border-radius:50%;background:#e6f5f3;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--teal);font-size:.9rem;">{{ strtoupper(substr($pat->name,0,1)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $pat->name }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ $pat->email }}</div>
                            </div>
                            <span class="count-badge" style="font-size:.85rem;padding:4px 10px;">{{ $pat->appt_count }} appts</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endif

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

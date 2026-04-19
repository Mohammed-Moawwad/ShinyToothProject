@extends('admin.layout')
@section('page-title', 'Services Management')
@section('content')
<style>
/* Service card styles */
.svc-card {
    background: var(--panel-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.svc-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.35);
}
.svc-card-accent { height: 5px; width: 100%; }
.svc-card-body { padding: 1.25rem 1.25rem 0; flex: 1; }
.svc-card-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    margin-bottom: .75rem;
}
.svc-card-title { font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-bottom: .3rem; line-height: 1.3; }
.svc-card-desc  { font-size: .78rem; color: var(--text-muted); min-height: 2.4em; margin-bottom: .9rem; }
.svc-meta-row   { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: .9rem; }
.svc-meta-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .28rem .6rem; border-radius: 20px;
    font-size: .72rem; font-weight: 600; border: 1px solid;
}
.svc-stats-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: .5rem; margin-bottom: .9rem; }
.svc-stat-tile  { background: var(--sidebar-bg); border: 1px solid var(--border); border-radius: 10px; padding: .5rem .4rem; text-align: center; }
.svc-stat-tile .val { font-size: .95rem; font-weight: 700; color: var(--text-main); display: block; }
.svc-stat-tile .lbl { font-size: .63rem; color: var(--text-muted); display: block; margin-top: .1rem; }
.svc-completion-bar  { height: 5px; border-radius: 3px; background: var(--border); overflow: hidden; margin-top: .25rem; }
.svc-completion-fill { height: 100%; border-radius: 3px; transition: width .4s ease; }
.svc-card-footer {
    padding: .85rem 1.25rem;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: .5rem;
}
.accent-0 { background: linear-gradient(90deg,#0d9e8a,#07b0a0); }
.accent-1 { background: linear-gradient(90deg,#3b82f6,#1d4ed8); }
.accent-2 { background: linear-gradient(90deg,#8b5cf6,#6d28d9); }
.accent-3 { background: linear-gradient(90deg,#f59e0b,#d97706); }
.accent-4 { background: linear-gradient(90deg,#ef4444,#b91c1c); }
.accent-5 { background: linear-gradient(90deg,#10b981,#059669); }
.icon-bg-0 { background: rgba(13,158,138,.15); color:#0d9e8a; }
.icon-bg-1 { background: rgba(59,130,246,.15);  color:#3b82f6; }
.icon-bg-2 { background: rgba(139,92,246,.15);  color:#8b5cf6; }
.icon-bg-3 { background: rgba(245,158,11,.15);  color:#f59e0b; }
.icon-bg-4 { background: rgba(239,68,68,.15);   color:#ef4444; }
.icon-bg-5 { background: rgba(16,185,129,.15);  color:#10b981; }
.badge-price    { border-color:rgba(13,158,138,.4); color:#0d9e8a; background:rgba(13,158,138,.08); }
.badge-dur      { border-color:rgba(99,102,241,.4); color:#818cf8; background:rgba(99,102,241,.08); }
.badge-category { border-color:rgba(245,158,11,.4); color:#f59e0b; background:rgba(245,158,11,.08); }
</style>

<div class="container-fluid px-0">

    {{-- Summary stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(13,158,138,.15);color:#0d9e8a;"><i class="bi bi-briefcase-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalServices }}</div>
                    <div class="stat-label">Total Services</div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.15);color:#f59e0b;"><i class="bi bi-trophy-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="font-size:.85rem;">{{ $mostBookedSvc ? \Illuminate\Support\Str::limit($mostBookedSvc->name, 18) : 'N/A' }}</div>
                    <div class="stat-label">Most Booked</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search / filter bar --}}
    <div class="panel mb-4" style="padding:1rem 1.25rem;">
        <form method="GET" action="{{ route('admin.services') }}" class="d-flex gap-2 align-items-center flex-wrap">
            <i class="bi bi-search" style="color:var(--text-muted);font-size:1rem;"></i>
            <input type="text" class="form-control" name="search"
                   placeholder="Search by name or description..."
                   value="{{ $search }}"
                   style="max-width:280px;">
            <select class="form-control" name="category" style="max-width:200px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button class="btn-primary" type="submit"><i class="bi bi-search"></i> Filter</button>
            @if($search || $category)
                <a href="{{ route('admin.services') }}" class="btn-info"><i class="bi bi-x-circle"></i> Clear</a>
            @endif
            <span style="color:var(--text-muted);font-size:.82rem;">
                @if($services->total() > 0)
                    Showing {{ $services->firstItem() }}&ndash;{{ $services->lastItem() }} of {{ $services->total() }}
                @else
                    0 found
                @endif
            </span>
            <button type="button" class="btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                <i class="bi bi-plus-circle"></i> Add Service
            </button>
        </form>
    </div>

    {{-- Services card grid --}}
    @php
        $icons = ['bi-heart-pulse-fill','bi-capsule-fill','bi-bandaid-fill','bi-clipboard2-pulse-fill','bi-hospital-fill','bi-droplet-fill'];
        $openRow = false;
    @endphp

    @forelse($services as $idx => $service)
        @php
            $c     = $idx % 6;
            $rev   = $serviceRevenue[$service->id]->revenue   ?? 0;
            $done  = $serviceCompleted[$service->id]->cnt     ?? 0;
            $canc  = $serviceCancelled[$service->id]->cnt     ?? 0;
            $total = $service->appointments_count             ?? 0;
            $rate  = $total > 0 ? round($done / $total * 100) : 0;
        @endphp

        @if($idx % 3 === 0)
            @php $openRow = true; @endphp
            <div class="row g-3 mb-3">
        @endif

        <div class="col-sm-6 col-xl-4">
            <div class="svc-card">

                {{-- Top accent bar --}}
                <div class="svc-card-accent accent-{{ $c }}"></div>

                <div class="svc-card-body">
                    {{-- Icon --}}
                    <div class="svc-card-icon icon-bg-{{ $c }}">
                        <i class="bi {{ $icons[$c] }}"></i>
                    </div>

                    {{-- Title + description --}}
                    <div class="svc-card-title">{{ $service->name }}</div>
                    <div class="svc-card-desc">
                        {{ $service->description ? \Illuminate\Support\Str::limit($service->description, 80) : 'No description provided.' }}
                    </div>

                    {{-- Price, duration & category badges --}}
                    <div class="svc-meta-row">
                        <span class="svc-meta-badge badge-price">
                            <i class="bi bi-tag-fill"></i>
                            SAR {{ number_format($service->price, 2) }}
                        </span>
                        <span class="svc-meta-badge badge-dur">
                            <i class="bi bi-clock-fill"></i>
                            {{ $service->duration_minutes }} min
                        </span>
                        @if($service->category)
                        <span class="svc-meta-badge badge-category">
                            <i class="bi bi-folder-fill"></i>
                            {{ $service->category }}
                        </span>
                        @endif
                    </div>

                    {{-- Stat tiles --}}
                    <div class="svc-stats-grid">
                        <div class="svc-stat-tile">
                            <span class="val">{{ number_format($total) }}</span>
                            <span class="lbl">Bookings</span>
                        </div>
                        <div class="svc-stat-tile">
                            <span class="val">{{ $rate }}%</span>
                            <span class="lbl">Completion</span>
                            <div class="svc-completion-bar">
                                <div class="svc-completion-fill accent-{{ $c }}" style="width:{{ $rate }}%;"></div>
                            </div>
                        </div>
                        <div class="svc-stat-tile">
                            <span class="val" style="font-size:.82rem;">{{ $rev > 0 ? 'SAR '.number_format($rev,0) : 'N/A' }}</span>
                            <span class="lbl">Revenue</span>
                        </div>
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="svc-card-footer">
                    <span style="font-size:.72rem;color:var(--text-muted);">
                        <i class="bi bi-calendar3"></i>
                        Added {{ $service->created_at->format('M Y') }}
                    </span>
                    <button class="btn-info" style="font-size:.76rem;padding:.3rem .7rem;"
                            data-bs-toggle="modal" data-bs-target="#svcModal{{ $service->id }}">
                        <i class="bi bi-eye"></i> Details
                    </button>
                </div>
            </div>
        </div>

        @if($idx % 3 === 2 || $loop->last)
            @php $openRow = false; @endphp
            </div>
        @endif

        {{-- Detail modal --}}
        <div class="modal fade" id="svcModal{{ $service->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom:1px solid var(--border);">
                        <div>
                            <h5 class="modal-title mb-0">{{ $service->name }}</h5>
                            <small style="color:var(--text-muted);">Service #{{ $service->id }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="svc-stat-tile">
                                    <span class="val">SAR {{ number_format($service->price, 2) }}</span>
                                    <span class="lbl">Price</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="svc-stat-tile">
                                    <span class="val">{{ $service->duration_minutes }} min</span>
                                    <span class="lbl">Duration</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="svc-stat-tile">
                                    <span class="val">{{ number_format($total) }}</span>
                                    <span class="lbl">Total Bookings</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="svc-stat-tile">
                                    <span class="val">{{ $rev > 0 ? 'SAR '.number_format($rev,0) : 'N/A' }}</span>
                                    <span class="lbl">Revenue</span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-4">
                                <div class="svc-stat-tile">
                                    <span class="val" style="color:#10b981;">{{ number_format($done) }}</span>
                                    <span class="lbl">Completed</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="svc-stat-tile">
                                    <span class="val" style="color:#ef4444;">{{ number_format($canc) }}</span>
                                    <span class="lbl">Cancelled</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="svc-stat-tile">
                                    <span class="val">{{ $rate }}%</span>
                                    <span class="lbl">Completion Rate</span>
                                </div>
                            </div>
                        </div>
                        @if($service->description)
                        <div style="background:var(--sidebar-bg);border:1px solid var(--border);border-radius:10px;padding:1rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em;">Description</div>
                            <p style="font-size:.88rem;color:var(--text-main);margin:0;">{{ $service->description }}</p>
                        </div>
                        @endif
                        <div class="mt-3" style="font-size:.75rem;color:var(--text-muted);">
                            Created: {{ $service->created_at->format('M d, Y H:i') }} &nbsp;&middot;&nbsp;
                            Updated: {{ $service->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @empty
        <div class="panel text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-briefcase" style="font-size:2.5rem;opacity:.4;display:block;margin:0 auto .75rem;"></i>
            <p class="mb-2">No services found{{ $search ? ' for &ldquo;'.$search.'&rdquo;' : '' }}.</p>
            @if($search)
                <a href="{{ route('admin.services') }}" class="btn-primary">Clear Search</a>
            @endif
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($services->total() > 15)
        <div class="d-flex justify-content-center mt-2 mb-4">
            {{ $services->appends(['search' => $search, 'category' => $category])->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

{{-- Add Service Modal --}}
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:1px solid var(--border);">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">{{ session('success') }}</div>
                    @endif
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Service Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="e.g. Teeth Whitening">
                            @error('name')<div style="color:#ef4444;font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price (SAR) <span style="color:#ef4444;">*</span></label>
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}" required min="0" step="0.01" placeholder="0.00">
                            @error('price')<div style="color:#ef4444;font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (minutes) <span style="color:#ef4444;">*</span></label>
                            <input type="number" class="form-control" name="duration_minutes" value="{{ old('duration_minutes') }}" required min="1" placeholder="30">
                            @error('duration_minutes')<div style="color:#ef4444;font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category">
                                <option value="">— Select a category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')<div style="color:#ef4444;font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the service...">{{ old('description') }}</textarea>
                            @error('description')<div style="color:#ef4444;font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);">
                    <button type="button" class="btn-info" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary"><i class="bi bi-check-circle"></i> Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any() || session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('addServiceModal'));
        modal.show();
    });
</script>
@endif

@endsection

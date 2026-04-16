@extends('admin.layout')
@section('page-title', 'Services Management')
@section('content')
<div class="container-fluid px-0">

    {{-- Filter --}}
    <div class="filter-panel mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Search Services</label>
                <form method="GET" action="{{ route('admin.services') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Service name..." value="{{ $search }}">
                    <button class="btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title"><i class="bi bi-briefcase-fill"></i>All Services</div>
            <span class="count-badge">{{ $services->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Service Name</th><th>Description</th><th>Price</th><th>Duration</th><th>Bookings</th><th>Created</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td><strong>{{ $service->name }}</strong></td>
                            <td style="max-width:200px;">
                                <span style="font-size:0.77rem;">{{ Illuminate\Support\Str::limit($service->description, 50) }}</span>
                            </td>
                            <td>
                                @if($service->price)
                                    <strong style="color:#059386;">SAR {{ number_format($service->price, 2) }}</strong>
                                @else
                                    <span style="color:var(--text-muted);">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($service->duration_minutes)
                                    <span class="count-badge">{{ $service->duration_minutes }} min</span>
                                @else
                                    <span style="color:var(--text-muted);">�</span>
                                @endif
                            </td>
                            <td>
                                <span class="count-badge">{{ $service->appointments_count ?? 0 }}</span>
                            </td>
                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn-info" data-bs-toggle="modal" data-bs-target="#svcModal{{ $service->id }}">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                                <div class="modal fade" id="svcModal{{ $service->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $service->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Description:</strong> {{ $service->description ?? 'N/A' }}</p>
                                                <p><strong>Price:</strong> {{ $service->price ? '$' . number_format($service->price, 2) : 'N/A' }}</p>
                                                <p><strong>Duration:</strong> {{ $service->duration_minutes ? $service->duration_minutes . ' minutes' : 'N/A' }}</p>
                                                <p><strong>Total Bookings:</strong> {{ $service->appointments_count ?? 0 }}</p>
                                                <p><strong>Created:</strong> {{ $service->created_at->format('M d, Y H:i') }}</p>
                                                <p><strong>Updated:</strong> {{ $service->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">No services found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->total() > 0)
            <div class="d-flex justify-content-center p-3">{{ $services->appends(['search' => $search])->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

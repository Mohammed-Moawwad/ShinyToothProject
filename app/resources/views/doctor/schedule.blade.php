@extends('doctor.layout')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')
@section('breadcrumb')
    <li class="breadcrumb-item active">Schedule</li>
@endsection

@section('content')
<div class="dash-card">
    <div class="card-header-custom d-flex align-items-center justify-content-between">
        <h6><i class="bi bi-calendar-week me-2" style="color:var(--teal);"></i>Weekly Availability</h6>
        <span class="text-muted" style="font-size:.82rem;"><i class="bi bi-info-circle me-1"></i>Set your working hours for each day</span>
    </div>

    <form action="/doctor/schedule/update?dentist={{ $dentist->id }}" method="POST">
        @csrf

        @php
            $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
            $dayLabels = [
                'sunday'    => ['Sun', 'bi-brightness-high'],
                'monday'    => ['Mon', 'bi-calendar3'],
                'tuesday'   => ['Tue', 'bi-calendar3'],
                'wednesday' => ['Wed', 'bi-calendar3'],
                'thursday'  => ['Thu', 'bi-calendar3'],
                'friday'    => ['Fri', 'bi-moon-stars'],
                'saturday'  => ['Sat', 'bi-moon-stars'],
            ];
        @endphp

        <div class="table-responsive">
            <table class="dash-table" style="min-width:680px;">
                <thead>
                    <tr>
                        <th style="width:160px;">Day</th>
                        <th style="width:180px;">Start Time</th>
                        <th style="width:180px;">End Time</th>
                        <th style="width:120px;" class="text-center">Available</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $index => $day)
                        @php
                            $sched = $schedules[$day] ?? null;
                            $label = $dayLabels[$day];
                        @endphp
                        <tr>
                            <td>
                                <input type="hidden" name="days[{{ $index }}]" value="{{ $day }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                          style="width:36px; height:36px; font-size:.85rem; background:{{ $sched && $sched->is_available ? 'var(--teal)' : '#e9ecef' }}; color:{{ $sched && $sched->is_available ? '#fff' : '#6c757d' }};">
                                        <i class="bi {{ $label[1] }}"></i>
                                    </span>
                                    <span class="fw-semibold">{{ ucfirst($day) }}</span>
                                </div>
                            </td>
                            <td>
                                <input type="time"
                                       name="start_time[{{ $index }}]"
                                       class="form-control @error("start_time.$index") is-invalid @enderror"
                                       value="{{ old("start_time.$index", $sched->start_time ?? '09:00') }}"
                                       style="border-radius:10px; max-width:160px;">
                                @error("start_time.$index")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="time"
                                       name="end_time[{{ $index }}]"
                                       class="form-control @error("end_time.$index") is-invalid @enderror"
                                       value="{{ old("end_time.$index", $sched->end_time ?? '17:00') }}"
                                       style="border-radius:10px; max-width:160px;">
                                @error("end_time.$index")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input type="hidden" name="is_available[{{ $index }}]" value="0">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_available[{{ $index }}]"
                                           value="1"
                                           {{ old("is_available.$index", $sched->is_available ?? 0) ? 'checked' : '' }}
                                           style="width:3em; height:1.5em; cursor:pointer;">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
            <small class="text-muted"><i class="bi bi-lightbulb me-1"></i>Patients can only book appointments during your available hours.</small>
            <button type="submit" class="btn btn-teal">
                <i class="bi bi-check-lg me-1"></i> Save Schedule
            </button>
        </div>
    </form>
</div>

<!-- ── CURRENT OVERVIEW ───────────────────────────────── -->
<div class="dash-card">
    <div class="card-header-custom">
        <h6><i class="bi bi-eye me-2" style="color:var(--dark-blue);"></i>Schedule Overview</h6>
    </div>
    <div class="row g-3">
        @foreach($days as $day)
            @php $sched = $schedules[$day] ?? null; @endphp
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="p-3 rounded text-center"
                     style="background:{{ $sched && $sched->is_available ? '#e6f5f3' : '#f8f9fa' }}; border:1px solid {{ $sched && $sched->is_available ? 'rgba(5,147,134,.2)' : '#e9ecef' }};">
                    <div class="fw-bold mb-1" style="color:{{ $sched && $sched->is_available ? 'var(--teal)' : '#adb5bd' }};">
                        {{ ucfirst($day) }}
                    </div>
                    @if($sched && $sched->is_available)
                        <div style="font-size:.85rem;">
                            <i class="bi bi-clock me-1" style="color:var(--teal);"></i>
                            {{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}
                        </div>
                    @else
                        <div style="font-size:.85rem; color:#adb5bd;">
                            <i class="bi bi-x-circle me-1"></i> Off
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

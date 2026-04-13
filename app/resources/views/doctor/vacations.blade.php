@extends('doctor.layout')

@section('title', 'Vacation Requests')
@section('page-title', 'Vacation Requests')
@section('breadcrumb')
    <li class="breadcrumb-item active">Vacation Requests</li>
@endsection

@section('content')

{{-- Success / Error alerts --}}
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert"
         style="border-radius:12px; border:none; background:#d4f5e4; color:#0f6b3a; font-weight:500;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert d-flex align-items-start gap-2 mb-4" role="alert"
         style="border-radius:12px; border:none; background:#fee2e2; color:#b91c1c; font-weight:500;">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">

    {{-- ── NEW REQUEST FORM ──────────────────────────── --}}
    <div class="col-lg-5">
        <div class="dash-card h-100">
            <div class="card-header-custom">
                <h6><i class="bi bi-send-fill me-2" style="color:var(--teal);"></i>Submit Time-Off Request</h6>
            </div>

            <form action="/doctor/vacations/store?dentist={{ $dentist->id }}" method="POST" id="vacationForm">
                @csrf

                {{-- Type Toggle --}}
                <div class="mb-4">
                    <label class="form-label-dash">Request Type <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2">
                        <label class="type-toggle-btn flex-fill text-center" id="lbl-full_day">
                            <input type="radio" name="type" value="full_day" hidden
                                   {{ old('type', 'full_day') === 'full_day' ? 'checked' : '' }}>
                            <i class="bi bi-calendar2-check me-1"></i> Full Day(s)
                        </label>
                        <label class="type-toggle-btn flex-fill text-center" id="lbl-partial_day">
                            <input type="radio" name="type" value="partial_day" hidden
                                   {{ old('type') === 'partial_day' ? 'checked' : '' }}>
                            <i class="bi bi-clock me-1"></i> Partial Day (Hours)
                        </label>
                    </div>
                </div>

                {{-- Full Day fields --}}
                <div id="section-full_day">
                    <div class="mb-3">
                        <label class="form-label-dash">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="fd_start"
                               class="form-control-dash @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dash">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="fd_end"
                               class="form-control-dash @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted" style="font-size:.78rem;">For a single day off, set both dates to the same day.</small>
                    </div>
                </div>

                {{-- Partial Day fields --}}
                <div id="section-partial_day" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label-dash">Date <span class="text-danger">*</span></label>
                        <input type="date" name="partial_date" id="pd_date"
                               class="form-control-dash @error('start_date') is-invalid @enderror"
                               value="{{ old('partial_date') }}" min="{{ date('Y-m-d') }}">
                        <div id="pd_day_warning" style="color:#b45309; font-size:.78rem; margin-top:.25rem; display:none;">
                            <i class="bi bi-exclamation-triangle me-1"></i>That day is not in your working schedule.
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label-dash">From <span class="text-danger">*</span></label>
                            <select name="start_time" id="pd_from"
                                    class="form-control-dash @error('start_time') is-invalid @enderror" disabled>
                                <option value="">— pick date first —</option>
                            </select>
                            @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label-dash">To <span class="text-danger">*</span></label>
                            <select name="end_time" id="pd_to"
                                    class="form-control-dash @error('end_time') is-invalid @enderror" disabled>
                                <option value="">— pick date first —</option>
                            </select>
                            @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <small class="text-muted d-block mb-3" style="font-size:.78rem;" id="pd_hours_hint"></small>
                </div>

                <div class="mb-4">
                    <label class="form-label-dash">Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" rows="4"
                              class="form-control-dash @error('reason') is-invalid @enderror"
                              placeholder="Please provide a formal explanation (minimum 20 characters)..."
                              required>{{ old('reason') }}</textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted" style="font-size:.78rem;">Minimum 20 characters. Your request will be reviewed by the administration.</small>
                </div>

                <button type="submit" class="btn btn-teal w-100">
                    <i class="bi bi-send me-2"></i> Submit Request
                </button>
            </form>
        </div>
    </div>

    {{-- ── REQUEST HISTORY ───────────────────────────── --}}
    <div class="col-lg-7">
        <div class="dash-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-clock-history me-2" style="color:var(--dark-blue);"></i>My Requests</h6>
                <span class="text-muted" style="font-size:.82rem;">{{ $requests->count() }} total</span>
            </div>

            @if($requests->count())
                <div class="d-flex flex-column gap-3">
                    @foreach($requests as $req)
                        @php
                            $isPartial = $req->type === 'partial_day';
                            $days = $req->start_date->diffInDays($req->end_date) + 1;
                            $statusColor = match($req->status) {
                                'approved' => ['bg' => '#d4f5e4', 'text' => '#0f6b3a', 'icon' => 'bi-check-circle-fill'],
                                'rejected' => ['bg' => '#fee2e2', 'text' => '#b91c1c', 'icon' => 'bi-x-circle-fill'],
                                default    => ['bg' => '#fff9db', 'text' => '#b45309', 'icon' => 'bi-hourglass-split'],
                            };
                        @endphp
                        <div style="border:1.5px solid #e5e7eb; border-radius:12px; padding:16px 20px; background:#fafafa;">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                                <div>
                                    <div class="fw-semibold" style="color:var(--dark-blue); font-size:.95rem;">
                                        @if($isPartial)
                                            <i class="bi bi-clock me-1" style="color:var(--teal);"></i>
                                            {{ $req->start_date->format('M d, Y') }}
                                            &nbsp;·&nbsp;
                                            {{ \Carbon\Carbon::parse($req->start_time)->format('g:i A') }}
                                            &nbsp;→&nbsp;
                                            {{ \Carbon\Carbon::parse($req->end_time)->format('g:i A') }}
                                        @else
                                            <i class="bi bi-calendar-range me-1" style="color:var(--teal);"></i>
                                            {{ $req->start_date->format('M d, Y') }}
                                            @if($req->start_date != $req->end_date)
                                                &nbsp;→&nbsp; {{ $req->end_date->format('M d, Y') }}
                                            @endif
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        @if($isPartial)
                                            Partial day &nbsp;·&nbsp;
                                        @else
                                            {{ $days }} {{ Str::plural('day', $days) }} &nbsp;·&nbsp;
                                        @endif
                                        Submitted {{ $req->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; padding:4px 12px; border-radius:20px; font-size:.78rem; font-weight:700; white-space:nowrap;">
                                    <i class="bi {{ $statusColor['icon'] }} me-1"></i>{{ ucfirst($req->status) }}
                                </span>
                            </div>

                            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:10px 14px; font-size:.85rem; color:#374151; line-height:1.6;">
                                {{ $req->reason }}
                            </div>

                            @if($req->admin_note)
                                <div class="mt-2" style="background:{{ $statusColor['bg'] }}; border-radius:8px; padding:8px 14px; font-size:.82rem; color:{{ $statusColor['text'] }};">
                                    <i class="bi bi-chat-left-text me-1"></i><strong>Admin note:</strong> {{ $req->admin_note }}
                                </div>
                            @endif

                            @if($req->status === 'pending')
                                <form action="/doctor/vacations/{{ $req->id }}/cancel?dentist={{ $dentist->id }}" method="POST" class="mt-2"
                                      onsubmit="return confirm('Cancel this vacation request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;">
                                        <i class="bi bi-trash me-1"></i> Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x d-block"></i>
                    <p class="mb-0">No vacation requests submitted yet</p>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .form-label-dash {
        display: block;
        font-size: .85rem;
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: .4rem;
    }
    .form-control-dash {
        width: 100%;
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        padding: 9px 13px;
        font-size: .88rem;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }
    .form-control-dash:focus {
        outline: none;
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5,147,134,.1);
    }
    .form-control-dash.is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: .78rem;
        margin-top: .25rem;
    }
    textarea.form-control-dash { resize: vertical; }

    .type-toggle-btn {
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        padding: 9px 12px;
        font-size: .85rem;
        font-weight: 600;
        color: #6b7280;
        background: #f8fafc;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
    }
    .type-toggle-btn:has(input:checked),
    .type-toggle-btn.active {
        border-color: var(--teal);
        background: #e6f7f6;
        color: var(--teal);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Schedule data from server ──────────────────────────
    const schedules = {!! json_encode($schedulesJson) !!};
    const DAYS = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];

    function formatHour(h) {
        const ampm = h < 12 ? 'AM' : 'PM';
        const display = h % 12 === 0 ? 12 : h % 12;
        return `${String(h).padStart(2,'0')}:00`;  // value
    }
    function formatHourLabel(h) {
        const ampm = h < 12 ? 'AM' : 'PM';
        const display = h % 12 === 0 ? 12 : h % 12;
        return `${display}:00 ${ampm}`;
    }

    // ── Type toggle ────────────────────────────────────────
    const radios         = document.querySelectorAll('input[name="type"]');
    const sectionFull    = document.getElementById('section-full_day');
    const sectionPartial = document.getElementById('section-partial_day');
    const lblFull        = document.getElementById('lbl-full_day');
    const lblPartial     = document.getElementById('lbl-partial_day');

    function applyType(val) {
        const isFull = val === 'full_day';
        sectionFull.style.display    = isFull ? '' : 'none';
        sectionPartial.style.display = isFull ? 'none' : '';
        lblFull.classList.toggle('active', isFull);
        lblPartial.classList.toggle('active', !isFull);
        document.querySelectorAll('#section-full_day input').forEach(i => i.required = isFull);
        document.querySelectorAll('#section-partial_day input, #section-partial_day select').forEach(i => i.required = !isFull);
    }

    radios.forEach(r => r.addEventListener('change', () => applyType(r.value)));
    const checked = document.querySelector('input[name="type"]:checked');
    applyType(checked ? checked.value : 'full_day');

    // ── Partial day: populate hour selects on date change ──
    const pdDate    = document.getElementById('pd_date');
    const pdFrom    = document.getElementById('pd_from');
    const pdTo      = document.getElementById('pd_to');
    const pdWarning = document.getElementById('pd_day_warning');
    const pdHint    = document.getElementById('pd_hours_hint');

    function populateHours(selectEl, startH, endH, selectedVal) {
        selectEl.innerHTML = '';
        for (let h = startH; h <= endH; h++) {
            const opt = document.createElement('option');
            opt.value       = formatHour(h);
            opt.textContent = formatHourLabel(h);
            if (opt.value === selectedVal) opt.selected = true;
            selectEl.appendChild(opt);
        }
        selectEl.disabled = false;
    }

    pdDate.addEventListener('change', function () {
        const d = new Date(this.value + 'T00:00:00');
        if (isNaN(d)) return;
        const dayName = DAYS[d.getDay()];
        const sched   = schedules[dayName];

        if (!sched) {
            pdWarning.style.display = '';
            pdFrom.innerHTML = '<option value="">Not a work day</option>';
            pdTo.innerHTML   = '<option value="">Not a work day</option>';
            pdFrom.disabled  = true;
            pdTo.disabled    = true;
            pdHint.textContent = '';
        } else {
            pdWarning.style.display = 'none';
            populateHours(pdFrom, sched.start, sched.end - 1, null);
            // "To": one hour after From by default; always at least start+1
            populateHours(pdTo, sched.start + 1, sched.end, null);
            pdHint.textContent = `Working hours: ${formatHourLabel(sched.start)} – ${formatHourLabel(sched.end)}`;

            // keep To ≥ From + 1
            pdFrom.addEventListener('change', syncToMin);
            syncToMin();
        }
    });

    function syncToMin() {
        const fromH = parseInt(pdFrom.value);
        Array.from(pdTo.options).forEach(opt => {
            opt.disabled = parseInt(opt.value) <= fromH;
        });
        if (parseInt(pdTo.value) <= fromH) {
            const first = Array.from(pdTo.options).find(o => !o.disabled);
            if (first) pdTo.value = first.value;
        }
    }

    // ── On submit: copy partial_date → hidden start_date/end_date ──
    document.getElementById('vacationForm').addEventListener('submit', function () {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (type === 'partial_day') {
            const pdDateVal = pdDate.value;
            document.getElementById('fd_start').value = pdDateVal;
            document.getElementById('fd_end').value   = pdDateVal;
        }
    });
});
</script>

@endsection

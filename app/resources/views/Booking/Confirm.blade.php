<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking — ShinyTooth</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --teal:      #059386;
            --dark-blue: #003263;
            --mid-blue:  #004080;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f4f8;
            overflow-x: hidden;
        }

        /* ── NAVBAR ──────────────────────────────────────────── */
        .main-nav {
            background: linear-gradient(90deg, #003263 0%, #059386 100%);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 2px 10px rgba(0,0,0,.15);
            transition: padding .3s ease, box-shadow .3s ease;
        }
        .main-nav.scrolled { padding: 8px 0; box-shadow: 0 4px 24px rgba(0,0,0,.25); }
        .nav-link-custom {
            color: rgba(255,255,255,.88) !important;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: background .2s, color .2s;
            text-decoration: none;
        }
        .nav-link-custom:hover { background: rgba(255,255,255,.15); color: #fff !important; }
        .btn-nav-login {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,.6);
            color: #fff;
            padding: 6px 18px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-nav-login:hover { background: rgba(255,255,255,.15); color: #fff; }
        .btn-nav-signup {
            background: #fff;
            color: var(--dark-blue);
            padding: 6px 18px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-nav-signup:hover { background: #e8f0fe; transform: translateY(-1px); }

        /* ── BREADCRUMB ─────────────────────────────────────── */
        .breadcrumb-bar {
            background: var(--dark-blue);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .breadcrumb-bar .breadcrumb { margin: 0; background: transparent; padding: 0; }
        .breadcrumb-item a {
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .875rem;
            transition: color .2s;
        }
        .breadcrumb-item a:hover { color: #fff; }
        .breadcrumb-item.active { color: rgba(255,255,255,.9); font-size: .875rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.35); }

        /* ── PROGRESS BAR ────────────────────────────── */
        .progress-section {
            background: linear-gradient(135deg, #f8fafc, #f0f4f8);
            border-bottom: 1px solid #e8edf2;
            padding: 28px 0;
        }
        .progress-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .progress-label { font-size: 1rem; font-weight: 700; color: var(--dark-blue); }
        .progress-steps {
            display: flex;
            gap: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,50,99,.1);
        }
        .progress-step { flex: 1; height: 8px; background: #e8edf2; transition: background .3s ease; }
        .progress-step.completed { background: linear-gradient(90deg, var(--teal), #059386); }
        .progress-step.active    { background: linear-gradient(90deg, var(--dark-blue), #004a94); }
        .progress-step-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            font-weight: 600;
            color: #64748b;
        }
        .progress-step-label.active   { color: var(--dark-blue); }
        .progress-step-label.completed { color: var(--teal); }
        .progress-step-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e8edf2;
            font-size: .75rem;
        }
        .progress-step-label.active    .progress-step-icon { background: var(--dark-blue); color: #fff; }
        .progress-step-label.completed .progress-step-icon { background: var(--teal); color: #fff; }
        .step-labels {
            display: flex;
            gap: 4px;
            justify-content: space-between;
            margin-top: 14px;
        }
        .step-label-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
            text-align: center;
        }

        /* ── PAGE LAYOUT ─────────────────────────── */
        .page-wrapper {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px 60px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 28px;
            align-items: start;
        }

        /* ── BOOKING SUMMARY CARD ────────────────── */
        .summary-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 28px rgba(0,50,99,.09);
            overflow: hidden;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            padding: 24px 28px;
        }
        .summary-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            margin: 0 0 4px;
        }
        .summary-header p {
            margin: 0;
            font-size: .88rem;
            color: rgba(255,255,255,.65);
        }

        .summary-body { padding: 28px; }

        /* ── DETAIL SECTIONS ─────────────────────── */
        .detail-section {
            border: 1.5px solid #e8edf2;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .detail-section-title {
            background: #f8fafc;
            padding: 12px 18px;
            font-size: .78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .10em;
            border-bottom: 1.5px solid #e8edf2;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
            font-size: .9rem;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 500; }
        .detail-value { color: var(--dark-blue); font-weight: 700; text-align: right; }

        /* ── DOCTOR STRIP ────────────────────────── */
        .doctor-strip {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
        }
        .doctor-strip-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--teal);
            flex-shrink: 0;
        }
        .doctor-strip-initials {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .doctor-strip-name { font-size: 1rem; font-weight: 700; color: var(--dark-blue); }
        .doctor-strip-spec { font-size: .8rem; color: var(--teal); font-weight: 600; margin-top: 2px; }
        .doctor-strip-exp  { font-size: .78rem; color: #94a3b8; margin-top: 2px; }

        /* ── TOTAL BAR ───────────────────────────── */
        .total-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f0f9f8, #e8f4f7);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .total-bar-label { font-size: 1rem; font-weight: 700; color: var(--dark-blue); }
        .total-bar-amount { font-size: 1.8rem; font-weight: 800; color: var(--teal); }

        /* ── PATIENT FORM (right column) ─────────── */
        .patient-panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 28px rgba(0,50,99,.09);
            overflow: hidden;
            position: sticky;
            top: 100px;
        }
        .patient-panel-header {
            background: linear-gradient(135deg, var(--teal), #07a396);
            color: #fff;
            padding: 22px 24px;
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .patient-panel-body { padding: 24px; }

        .form-label-custom {
            font-size: .82rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 6px;
            display: block;
        }
        .form-control-custom {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: .9rem;
            color: #1e293b;
            transition: border-color .2s, box-shadow .2s;
            background: #fafbfc;
            width: 100%;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(5,147,134,.12);
            background: #fff;
        }
        .form-control-custom.is-invalid { border-color:#dc2626 !important; background:#fff8f8; }
        .form-control-custom.is-valid   { border-color:#059386 !important; background:#f0fdf9; }
        .cf-field-err { color:#dc2626; font-size:.78rem; margin-top:4px; }

        /* ── CONFIRM BUTTON ──────────────────────── */
        .btn-confirm-booking {
            width: 100%;
            background: linear-gradient(135deg, var(--teal), #07a396);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 15px 24px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(5,147,134,.35);
            margin-top: 22px;
        }
        .btn-confirm-booking:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(5,147,134,.45);
            background: linear-gradient(135deg, #04796e, #059386);
        }
        .btn-confirm-booking:active { transform: translateY(0); }

        .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            margin-top: 12px;
            padding: 10px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: transparent;
            color: #64748b;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }
        .back-btn:hover { border-color: var(--dark-blue); color: var(--dark-blue); background: #f8fafc; }

        .notice-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: .82rem;
            color: #92400e;
            display: flex;
            gap: 9px;
            align-items: flex-start;
            margin-top: 16px;
        }

        /* ── FOOTER ─────────────────────────────── */
        .main-footer {
            background: linear-gradient(135deg, #001e3c 0%, #003263 60%, #0a4a40 100%);
            color: #fff;
            padding: 56px 0 32px;
            margin-top: 60px;
        }
        .footer-brand { font-size: 1.3rem; font-weight: 800; color: #fff; letter-spacing: -.3px; }
        .footer-h { font-size: .82rem; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.55); font-weight: 700; margin-bottom: 14px; }
        .footer-link {
            display: block;
            color: rgba(255,255,255,.62);
            text-decoration: none;
            font-size: .87rem;
            margin-bottom: 8px;
            transition: color .2s;
        }
        .footer-link:hover { color: #fff; }
        .footer-hr { border-color: rgba(255,255,255,.1); margin: 36px 0 24px; }
        .social-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.75);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            font-size: 1rem;
            transition: background .2s, color .2s;
        }
        .social-icon:hover { background: var(--teal); color: #fff; }

        @media (max-width: 860px) {
            .page-wrapper { grid-template-columns: 1fr; }
            .patient-panel { position: static; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="main-nav" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo" height="88"
                     style="border-radius:8px; object-fit:contain;">
                <span style="color:#fff; font-size:1.3rem; font-weight:800; letter-spacing:-.3px;">ShinyTooth</span>
            </a>
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="/"         class="nav-link-custom">Home</a>
                <a href="/services" class="nav-link-custom" style="background:rgba(255,255,255,.15);">Services</a>
                <a href="/doctors"  class="nav-link-custom">Doctors</a>
                <a href="/#contact" class="nav-link-custom">Contact us</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="/login"    class="btn-nav-login">Login</a>
                <a href="/register" class="btn-nav-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/services">Services</a></li>
                <li class="breadcrumb-item"><a href="/services/{{ $service->id }}">{{ $service->name }}</a></li>
                <li class="breadcrumb-item active">Confirm Booking</li>
            </ol>
        </nav>
    </div>
</div>

{{-- PROGRESS BAR --}}
<section class="progress-section">
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">Booking Progress</span>
            <span style="font-size:.9rem; color:#94a3b8; font-weight:600;">Step <strong style="color:var(--dark-blue);">5</strong> of <strong>6</strong></span>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step active"></div>
            <div class="progress-step"></div>
        </div>
        <div class="step-labels">
            <div class="step-label-item">
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>Service</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>Doctor</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>Schedule</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>Payment</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label active">
                    <div class="progress-step-icon">5</div>
                    <span>Confirm</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label">
                    <div class="progress-step-icon">6</div>
                    <span>Done</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MAIN CONTENT --}}
<div class="page-wrapper">

    {{-- ── LEFT: BOOKING SUMMARY ─────────────────────────────────── --}}
    <div>
        <div class="summary-card">
            <div class="summary-header">
                <h2><i class="bi bi-clipboard2-check me-2"></i>Review Your Booking</h2>
                <p>Please review all details carefully before confirming your appointment.</p>
            </div>
            <div class="summary-body">

                {{-- SERVICE --}}
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-tooth"></i> Service Details
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service</span>
                        <span class="detail-value">{{ $service->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category</span>
                        <span class="detail-value">{{ $service->category }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">{{ $service->duration_minutes }} minutes</span>
                    </div>
                    @if ($service->is_special_offer)
                    <div class="detail-row">
                        <span class="detail-label">Offer</span>
                        <span class="detail-value" style="color:#f97316;">
                            <i class="bi bi-fire me-1"></i>Special Offer
                        </span>
                    </div>
                    @endif
                </div>

                {{-- DOCTOR --}}
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-person-badge"></i> Your Doctor
                    </div>
                    <div class="doctor-strip">
                        @if ($dentist->image)
                            <img src="/{{ $dentist->image }}" alt="{{ $dentist->name }}" class="doctor-strip-avatar">
                        @else
                            <div class="doctor-strip-initials">
                                {{ strtoupper(substr($dentist->name, 0, 1)) }}{{ strtoupper(substr(strrchr($dentist->name, ' '), 1, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="doctor-strip-name">{{ $dentist->name }}</div>
                            @if ($dentist->specializations->isNotEmpty())
                                <div class="doctor-strip-spec">{{ $dentist->specializations->first()->name }}</div>
                            @endif
                            <div class="doctor-strip-exp">{{ $dentist->experience_years }} years of experience</div>
                        </div>
                    </div>
                </div>

                {{-- APPOINTMENT TIME --}}
                @php
                    [$hh, $mm] = explode(':', $time);
                    $h12  = (int)$hh > 12 ? (int)$hh - 12 : ((int)$hh === 0 ? 12 : (int)$hh);
                    $ampm = (int)$hh >= 12 ? 'PM' : 'AM';
                    $formattedTime = $h12 . ':' . $mm . ' ' . $ampm;
                    $parsedDate = \Carbon\Carbon::parse($date);
                @endphp
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-calendar3"></i> Appointment Schedule
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value">
                            {{ $parsedDate->format('l') }}, {{ $parsedDate->format('F j, Y') }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time</span>
                        <span class="detail-value">{{ $formattedTime }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Estimated end</span>
                        <span class="detail-value">
                            @php
                                $endMin  = (int)$hh * 60 + (int)$mm + $service->duration_minutes;
                                $endH    = intdiv($endMin, 60);
                                $endM    = $endMin % 60;
                                $endH12  = $endH > 12 ? $endH - 12 : ($endH === 0 ? 12 : $endH);
                                $endAmpm = $endH >= 12 ? 'PM' : 'AM';
                            @endphp
                            {{ $endH12 }}:{{ str_pad($endM, 2, '0', STR_PAD_LEFT) }} {{ $endAmpm }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">ShinyTooth Dental Clinic</span>
                    </div>
                </div>

                {{-- TOTAL --}}
                <div class="total-bar">
                    <div class="total-bar-label">
                        <i class="bi bi-tag-fill me-2" style="color:var(--teal);"></i>
                        Total Amount
                    </div>
                    <div class="total-bar-amount">${{ number_format($service->price, 2) }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── RIGHT: PATIENT DETAILS + CONFIRM ─────────────────────── --}}
    <div class="patient-panel">
        <div class="patient-panel-header">
            <i class="bi bi-person-check"></i> Your Details
        </div>
        <div class="patient-panel-body">

            {{-- Confirmation form --}}
            <form method="POST" action="{{ route('booking.confirm.submit') }}" onsubmit="return cfValidateAll()">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="dentist_id" value="{{ $dentist->id }}">
                <input type="hidden" name="date"       value="{{ $date }}">
                <input type="hidden" name="time"       value="{{ $time }}">

                <div class="mb-3">
                    <label class="form-label-custom">Full Name *</label>
                    <input type="text"
                           id="cf_name"
                           name="patient_name"
                           class="form-control-custom @error('patient_name') is-invalid @enderror"
                           placeholder="Your full name"
                           autocomplete="name"
                           value="{{ old('patient_name') }}"
                           oninput="cfValidate('cf_name')"
                           required>
                    <div id="cf_name-err" class="cf-field-err" style="display:none;"></div>
                    @error('patient_name')
                        <div class="cf-field-err" style="display:block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Email *</label>
                    <input type="email"
                           id="cf_email"
                           name="patient_email"
                           class="form-control-custom @error('patient_email') is-invalid @enderror"
                           placeholder="your@email.com"
                           autocomplete="email"
                           value="{{ old('patient_email') }}"
                           oninput="cfValidate('cf_email')"
                           required>
                    <div id="cf_email-err" class="cf-field-err" style="display:none;"></div>
                    @error('patient_email')
                        <div class="cf-field-err" style="display:block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Phone *</label>
                    <input type="text"
                           id="cf_phone"
                           name="patient_phone"
                           class="form-control-custom @error('patient_phone') is-invalid @enderror"
                           placeholder="05xxxxxxxx"
                           maxlength="10"
                           inputmode="numeric"
                           autocomplete="tel"
                           value="{{ old('patient_phone') }}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,''); cfValidate('cf_phone')"
                           required>
                    <div id="cf_phone-err" class="cf-field-err" style="display:none;"></div>
                    @error('patient_phone')
                        <div class="cf-field-err" style="display:block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Notes for doctor <span style="color:#94a3b8;">(optional)</span></label>
                    <textarea name="notes"
                              class="form-control-custom"
                              rows="3"
                              placeholder="Allergies, concerns, or anything the doctor should know..."
                              style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn-confirm-booking">
                    <i class="bi bi-check2-circle"></i>
                    Confirm Appointment
                </button>
            </form>

            <a href="javascript:history.back()" class="back-btn">
                <i class="bi bi-chevron-left"></i> Go back & change time
            </a>

            <div class="notice-box">
                <i class="bi bi-info-circle-fill" style="flex-shrink:0; margin-top:1px;"></i>
                <span>Payment of <strong>${{ number_format($service->price, 2) }}</strong> will be collected in the next step. You'll receive a confirmation email once your booking is approved.</span>
            </div>

        </div>
    </div>

</div>

{{-- FOOTER --}}
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth"
                         height="80" style="border-radius:8px; object-fit:contain;">
                    <span class="footer-brand">ShinyTooth</span>
                </div>
                <p style="color:rgba(255,255,255,.58); font-size:.88rem; line-height:1.75;">
                    Your trusted dental care partner. We combine world-class expertise with
                    a warm, welcoming environment — because every smile deserves the best.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="footer-h">Quick Links</h6>
                <a href="/"         class="footer-link">Home</a>
                <a href="/services" class="footer-link">All Services</a>
                <a href="/register" class="footer-link">Book Appointment</a>
                <a href="/login"    class="footer-link">Patient Portal</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-h">Services</h6>
                <a href="#" class="footer-link">Dental Cleanings</a>
                <a href="#" class="footer-link">Oral Exams</a>
                <a href="#" class="footer-link">Fillings</a>
                <a href="#" class="footer-link">Tooth Extractions</a>
                <a href="/services" class="footer-link">All Services →</a>
            </div>
            <div class="col-lg-3">
                <h6 class="footer-h">Contact Us</h6>
                <div class="d-flex align-items-start gap-2 mb-2"
                     style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-geo-alt-fill mt-1" style="color:var(--teal); flex-shrink:0;"></i>
                    <span>123 Dental Avenue, Health City, HC 10001</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2"
                     style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-telephone-fill" style="color:var(--teal);"></i>
                    <span>+1 (800) 744-6983</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2"
                     style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-envelope-fill" style="color:var(--teal);"></i>
                    <span>hello@shinytooth.com</span>
                </div>
                <div class="d-flex align-items-center gap-2"
                     style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-clock-fill" style="color:var(--teal);"></i>
                    <span>Sun – Thu &nbsp;|&nbsp; 8:00 AM – 5:00 PM</span>
                </div>
            </div>
        </div>

        <hr class="footer-hr">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <p class="mb-0" style="color:rgba(255,255,255,.45); font-size:.82rem;">
                &copy; {{ date('Y') }} ShinyTooth. All rights reserved.
            </p>
            <div class="d-flex gap-3">
                <a href="#" class="footer-link d-inline" style="font-size:.82rem;">Privacy Policy</a>
                <a href="#" class="footer-link d-inline" style="font-size:.82rem;">Terms of Service</a>
                <a href="#" class="footer-link d-inline" style="font-size:.82rem;">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
});

// ── Confirmation form validation ───────────────────────────────────────────
const CF_RULES = {
    cf_name: v => {
        if (!v)          return 'Full name is required.';
        if (v.length < 3) return 'Name must be at least 3 characters.';
        if (!/^[\u0600-\u06FFa-zA-Z\s]+$/.test(v)) return 'Name may only contain letters and spaces.';
        return '';
    },
    cf_email: v => {
        if (!v) return 'Email is required.';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return 'Please enter a valid email (e.g. name@gmail.com).';
        return '';
    },
    cf_phone: v => {
        if (!v) return 'Phone number is required.';
        if (!/^05[0-9]{8}$/.test(v)) return 'Phone must be 10 digits and start with 05 (e.g. 0512345678).';
        return '';
    },
};

function cfValidate(id) {
    const el  = document.getElementById(id);
    const err = document.getElementById(id + '-err');
    if (!el || !err) return true;
    const msg = CF_RULES[id]?.(el.value.trim());
    if (msg) {
        err.textContent = msg;
        err.style.display = 'block';
        el.classList.add('is-invalid');
        el.classList.remove('is-valid');
        return false;
    }
    err.style.display = 'none';
    el.classList.remove('is-invalid');
    el.classList.add('is-valid');
    return true;
}

function cfValidateAll() {
    let ok = true;
    ['cf_name', 'cf_email', 'cf_phone'].forEach(id => { if (!cfValidate(id)) ok = false; });
    if (!ok) document.getElementById('cf_name').scrollIntoView({ behavior: 'smooth', block: 'center' });
    return ok;
}
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed — ShinyTooth</title>

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
        .progress-step-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            font-weight: 600;
            color: #64748b;
        }
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

        /* ── SUCCESS HERO ─────────────────────────── */
        .success-hero {
            background: linear-gradient(135deg, var(--dark-blue) 0%, #004a8c 50%, var(--teal) 100%);
            padding: 60px 20px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .success-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(5,147,134,.25) 0%, transparent 60%),
                        radial-gradient(circle at 70% 30%, rgba(255,255,255,.06) 0%, transparent 50%);
        }

        .success-icon-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            margin-bottom: 24px;
        }
        .success-icon-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.3);
            animation: ringPulse 2s ease-in-out infinite;
        }
        .success-icon-ring-2 {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.15);
            animation: ringPulse 2s ease-in-out infinite .4s;
        }
        @keyframes ringPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: .6; }
        }
        .success-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), #07c5b3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            color: #fff;
            box-shadow: 0 12px 40px rgba(5,147,134,.5);
            animation: popIn .6s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes popIn {
            from { transform: scale(.3); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }

        .success-title {
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            margin: 0 0 10px;
            position: relative;
        }
        .success-sub {
            font-size: 1.05rem;
            color: rgba(255,255,255,.72);
            margin: 0;
            position: relative;
        }

        .ref-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.12);
            border: 1.5px solid rgba(255,255,255,.25);
            border-radius: 40px;
            padding: 8px 20px;
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            margin-top: 20px;
            position: relative;
            backdrop-filter: blur(6px);
        }
        .ref-badge span { color: rgba(255,255,255,.65); font-weight: 500; }

        /* ── CONTENT AREA ──────────────────────────── */
        .content-wrapper {
            max-width: 820px;
            margin: -40px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* ── APPOINTMENT CARD ──────────────────────── */
        .appointment-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,50,99,.14);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .appointment-card-header {
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            padding: 20px 28px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .appointment-card-body { padding: 0; }

        /* Detail grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }
        .info-cell {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
        }
        .info-cell:nth-child(even) { border-right: none; }
        .info-cell:nth-last-child(-n+2) { border-bottom: none; }
        .info-cell-label {
            font-size: .75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .09em;
            margin-bottom: 5px;
        }
        .info-cell-value {
            font-size: .95rem;
            font-weight: 700;
            color: var(--dark-blue);
        }

        /* Doctor inline */
        .doctor-inline {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
        }
        .doctor-inline-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--teal);
            flex-shrink: 0;
        }
        .doctor-inline-initials {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Total row */
        .total-row-done {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            background: linear-gradient(135deg, #f0f9f8, #e8f4f7);
        }
        .total-row-done-label { font-size: .95rem; font-weight: 700; color: var(--dark-blue); }
        .total-row-done-amount { font-size: 1.6rem; font-weight: 800; color: var(--teal); }

        /* Status badge */
        .status-scheduled {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .8rem;
            font-weight: 700;
        }
        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #059669;
        }

        /* ── WHAT NOW ─────────────────────────────── */
        .what-now-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,50,99,.08);
            padding: 28px;
            margin-bottom: 20px;
        }
        .what-now-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }
        .step-item:last-child { margin-bottom: 0; }
        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            font-size: .88rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .step-text-title { font-size: .92rem; font-weight: 700; color: var(--dark-blue); margin-bottom: 2px; }
        .step-text-desc  { font-size: .83rem; color: #64748b; }

        /* ── ACTION BUTTONS ───────────────────────── */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }
        .btn-action-primary {
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .25s;
            box-shadow: 0 4px 16px rgba(0,50,99,.25);
        }
        .btn-action-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,50,99,.35); color: #fff; }
        .btn-action-secondary {
            background: #fff;
            color: var(--dark-blue);
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .25s;
        }
        .btn-action-secondary:hover { border-color: var(--dark-blue); background: #f8fafc; transform: translateY(-1px); color: var(--dark-blue); }

        /* ── FOOTER ─────────────────────────────── */
        .main-footer {
            background: linear-gradient(135deg, #001e3c 0%, #003263 60%, #0a4a40 100%);
            color: #fff;
            padding: 56px 0 32px;
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

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .info-cell { border-right: none; }
            .info-cell:nth-last-child(-n+2) { border-bottom: 1px solid #f1f5f9; }
            .info-cell:last-child { border-bottom: none; }
            .action-buttons { grid-template-columns: 1fr; }
            .success-title { font-size: 1.6rem; }
        }

        /* ── BOUNCE ANIMATION ─────────────────────── */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
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
                <a href="#doctors"  class="nav-link-custom">Doctors</a>
                <a href="#contact"  class="nav-link-custom">Contact us</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="/login"    class="btn-nav-login">Login</a>
                <a href="/register" class="btn-nav-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

{{-- PROGRESS BAR — All 6 completed --}}
<section class="progress-section">
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">Booking Progress</span>
            <span style="font-size:.9rem; color:var(--teal); font-weight:700;">
                <i class="bi bi-check-circle-fill me-1"></i> Booking Complete!
            </span>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
        </div>
        <div class="step-labels">
            @foreach(['Service','Doctor','Schedule','Payment','Confirm','Done'] as $label)
            <div class="step-label-item">
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>{{ $label }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SUCCESS HERO --}}
<div class="success-hero">
    <div class="success-icon-wrap">
        <div class="success-icon-ring-2"></div>
        <div class="success-icon-ring"></div>
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
    </div>
    <h1 class="success-title">Appointment Confirmed!</h1>
    <p class="success-sub">Your booking has been successfully placed. We'll see you soon.</p>
    <div class="ref-badge">
        <i class="bi bi-hash"></i>
        <span>Reference:</span>
        <strong>ST-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong>
    </div>
</div>

{{-- CONTENT --}}
<div class="content-wrapper">

    {{-- APPOINTMENT DETAILS CARD --}}
    <div class="appointment-card">
        <div class="appointment-card-header">
            <i class="bi bi-calendar2-check"></i>
            Appointment Details
            <span class="ms-auto status-scheduled">
                <span class="status-dot"></span> Scheduled
            </span>
        </div>
        <div class="appointment-card-body">

            {{-- Doctor strip --}}
            <div class="doctor-inline">
                @if ($appointment->dentist->image)
                    <img src="/{{ $appointment->dentist->image }}" alt="{{ $appointment->dentist->name }}"
                         class="doctor-inline-avatar">
                @else
                    <div class="doctor-inline-initials">
                        {{ strtoupper(substr($appointment->dentist->name, 3, 1)) }}{{ strtoupper(substr(strrchr($appointment->dentist->name, ' '), 1, 1)) }}
                    </div>
                @endif
                <div>
                    <div style="font-size:1rem; font-weight:700; color:var(--dark-blue);">{{ $appointment->dentist->name }}</div>
                    @if ($appointment->dentist->specializations->isNotEmpty())
                        <div style="font-size:.8rem; color:var(--teal); font-weight:600; margin-top:2px;">
                            {{ $appointment->dentist->specializations->first()->name }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info grid --}}
            <div class="info-grid">
                <div class="info-cell">
                    <div class="info-cell-label">Service</div>
                    <div class="info-cell-value">{{ $appointment->service->name }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Category</div>
                    <div class="info-cell-value">{{ $appointment->service->category }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Date</div>
                    <div class="info-cell-value">
                        {{ $appointment->appointment_date->format('D, M j, Y') }}
                    </div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Time</div>
                    <div class="info-cell-value">
                        @php
                            $t    = $appointment->appointment_time;
                            $hh   = (int)$t->format('H');
                            $mm   = $t->format('i');
                            $h12  = $hh > 12 ? $hh - 12 : ($hh === 0 ? 12 : $hh);
                            $ampm = $hh >= 12 ? 'PM' : 'AM';
                        @endphp
                        {{ $h12 }}:{{ $mm }} {{ $ampm }}
                    </div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Duration</div>
                    <div class="info-cell-value">{{ $appointment->service->duration_minutes }} minutes</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Patient</div>
                    <div class="info-cell-value">{{ $appointment->patient->name }}</div>
                </div>
            </div>

            {{-- Total --}}
            <div class="total-row-done">
                <div class="total-row-done-label">
                    <i class="bi bi-cash-coin me-2" style="color:var(--teal);"></i> Amount Due at Clinic
                </div>
                <div class="total-row-done-amount">${{ number_format($appointment->service->price, 2) }}</div>
            </div>

        </div>
    </div>

    {{-- WHAT NOW --}}
    <div class="what-now-card">
        <div class="what-now-title">
            <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i>
            What happens next?
        </div>
        <div class="step-item">
            <div class="step-num">1</div>
            <div>
                <div class="step-text-title">Check your email</div>
                <div class="step-text-desc">A booking summary has been sent to {{ $appointment->patient->email }}.</div>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div>
                <div class="step-text-title">Arrive 10 minutes early</div>
                <div class="step-text-desc">
                    Please arrive at ShinyTooth Dental Clinic at least 10 minutes before
                    your scheduled time on {{ $appointment->appointment_date->format('F j, Y') }}.
                    Bring your reference number <strong>ST-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong>.
                </div>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div>
                <div class="step-text-title">Track your appointment</div>
                <div class="step-text-desc">You'll be able to view and manage your appointments from your patient dashboard once it's ready.</div>
            </div>
        </div>
    </div>

    {{-- CELEBRATION IMAGE --}}
    <div style="text-align:center; margin:40px 0 50px; position:relative;">
        <img src="{{ asset('images/BlueToothGivingThumbsUp.png') }}" 
             alt="BlueTooth Giving Thumbs Up" 
             style="max-width:280px; height:auto; filter:drop-shadow(0 8px 24px rgba(5,147,134,.2)); animation:bounce 2s ease-in-out infinite;">
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="action-buttons">
        <a href="/services" class="btn-action-primary">
            <i class="bi bi-tooth"></i> Book Another Service
        </a>
        <a href="/" class="btn-action-secondary">
            <i class="bi bi-house"></i> Return to Home
        </a>
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
</script>
</body>
</html>

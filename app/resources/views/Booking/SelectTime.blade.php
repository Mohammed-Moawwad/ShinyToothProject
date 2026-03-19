<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment — ShinyTooth</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --teal:       #059386;
            --teal-light: #07b3a4;
            --dark-blue:  #003263;
            --mid-blue:   #004080;
            --busy:       #e74c3c;
            --busy-light: #fde8e8;
            --free:       #059386;
            --free-light: #e8f8f7;
            --slot-h:     44px;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f4f8;
            overflow-x: hidden;
        }

        /* ── NAVBAR ─────────────────────────────────────────────── */
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

        /* ── BREADCRUMB ─────────────────────────────────────────── */
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

        /* ── PROGRESS BAR (same style as ServiceDetail) ─────────── */
        .progress-section {
            background: linear-gradient(135deg, #f8fafc, #f0f4f8);
            border-bottom: 1px solid #e8edf2;
            padding: 28px 0;
        }
        .progress-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .progress-label {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark-blue);
        }
        .progress-steps {
            display: flex;
            gap: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,50,99,.1);
        }
        .progress-step {
            flex: 1;
            height: 8px;
            background: #e8edf2;
            transition: background .3s ease;
        }
        .progress-step.completed {
            background: linear-gradient(90deg, var(--teal), #059386);
        }
        .progress-step.active {
            background: linear-gradient(90deg, var(--dark-blue), #004a94);
        }
        .progress-step-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            font-weight: 600;
            color: #64748b;
        }
        .progress-step-label.active  { color: var(--dark-blue); }
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
        .progress-step-label.active   .progress-step-icon { background: var(--dark-blue); color: #fff; }
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

        /* ── PAGE LAYOUT ────────────────────────────────────────── */
        .page-wrapper {
            max-width: 1280px;
            margin: 32px auto;
            padding: 0 20px 60px;
            display: grid;
            grid-template-columns: 1fr 310px;
            gap: 28px;
            align-items: start;
        }

        /* ── SCHEDULE PANEL ─────────────────────────────────────── */
        .schedule-panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,50,99,.1);
            overflow: hidden;
        }

        /* Schedule header */
        .schedule-header {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--mid-blue) 60%, var(--teal) 100%);
            padding: 24px 28px 20px;
            position: relative;
            overflow: hidden;
        }
        .schedule-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .schedule-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: 40px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(5,147,134,.15);
        }
        .schedule-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 4px;
            position: relative;
            z-index: 1;
        }
        .schedule-sub {
            color: rgba(255,255,255,.65);
            font-size: .875rem;
            position: relative;
            z-index: 1;
        }

        /* Legend */
        .legend-bar {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 14px 28px;
            background: #f8fafb;
            border-bottom: 1px solid #e8eef3;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .8rem;
            font-weight: 600;
            color: #556;
        }
        .legend-dot {
            width: 14px; height: 14px;
            border-radius: 4px;
            flex-shrink: 0;
        }
        .legend-dot.free   { background: var(--free); }
        .legend-dot.busy   { background: var(--busy); }
        .legend-dot.selected { background: var(--dark-blue); }

        /* Week navigation */
        .week-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 28px;
            border-bottom: 1px solid #e8eef3;
        }
        .week-nav-btn {
            background: none;
            border: 1.5px solid #d0dae6;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--dark-blue);
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .week-nav-btn:hover {
            background: var(--dark-blue);
            color: #fff;
            border-color: var(--dark-blue);
        }
        .week-range-label {
            font-size: .9rem;
            font-weight: 700;
            color: var(--dark-blue);
        }

        /* Calendar grid container */
        .cal-container {
            overflow-x: auto;
            padding: 0 4px 8px;
        }
        .cal-grid {
            display: grid;
            min-width: 660px;
        }

        /* Day headers */
        .day-col-header {
            padding: 12px 6px 10px;
            text-align: center;
            border-right: 1px solid #e8eef3;
        }
        .day-col-header:last-child { border-right: none; }
        .day-col-header .day-name {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7a95;
        }
        .day-col-header .day-num {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--dark-blue);
            line-height: 1.2;
        }
        .day-col-header .day-month {
            font-size: .72rem;
            color: #9aaabb;
            font-weight: 500;
        }
        .day-col-header.is-weekend { background: #f8f4ff; }
        .day-col-header.is-weekend .day-num { color: #7c4ddb; }

        /* Time label column */
        .time-label-col { border-right: 1px solid #e8eef3; }
        .time-label {
            height: var(--slot-h);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            font-size: .72rem;
            font-weight: 600;
            color: #8899aa;
            border-top: 1px solid #eef2f6;
            white-space: nowrap;
        }
        .time-label.hour-mark {
            color: var(--dark-blue);
            font-weight: 700;
            border-top-color: #cdd9e5;
        }

        /* Slot cells */
        .slot-cell {
            height: var(--slot-h);
            border-top: 1px solid #eef2f6;
            border-right: 1px solid #e8eef3;
            padding: 3px 4px;
            position: relative;
            transition: all .18s;
        }
        .slot-cell:last-child { border-right: none; }

        .slot-btn {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
            font-size: .72rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            transition: all .18s;
            position: relative;
            overflow: hidden;
        }

        /* Free slot */
        .slot-btn.free {
            background: var(--free-light);
            color: var(--free);
            border: 1.5px solid rgba(5,147,134,.2);
        }
        .slot-btn.free::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--free);
            opacity: 0;
            border-radius: 8px;
            transition: opacity .18s;
        }
        .slot-btn.free:hover {
            background: var(--free);
            color: #fff;
            box-shadow: 0 3px 12px rgba(5,147,134,.45);
            transform: scale(1.04);
            z-index: 2;
        }
        .slot-btn.free:hover .slot-icon { color: #fff; }

        /* Busy slot */
        .slot-btn.busy {
            background: var(--busy-light);
            color: var(--busy);
            border: 1.5px solid rgba(231,76,60,.18);
            cursor: not-allowed;
            opacity: .85;
        }
        .slot-btn.busy:hover {
            background: #fcd3d0;
            transform: none;
        }

        /* Selected slot */
        .slot-btn.selected {
            background: var(--dark-blue) !important;
            color: #fff !important;
            border-color: var(--dark-blue) !important;
            box-shadow: 0 4px 16px rgba(0,50,99,.45);
            transform: scale(1.06);
            z-index: 3;
        }

        /* Pulsing ring on free slots */
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(5,147,134,.4); }
            70%  { box-shadow: 0 0 0 6px rgba(5,147,134,0); }
            100% { box-shadow: 0 0 0 0 rgba(5,147,134,0); }
        }
        .slot-btn.free:hover { animation: pulse-ring .6s ease-out; }

        /* Sticky booking summary bar */
        .summary-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 28px;
            background: linear-gradient(90deg, var(--dark-blue), var(--mid-blue));
            border-top: 3px solid var(--teal);
            position: sticky;
            bottom: 0;
        }
        .summary-details { color: rgba(255,255,255,.75); font-size: .85rem; }
        .summary-details strong { color: #fff; font-size: .95rem; }
        .selected-slot-info {
            color: rgba(255,255,255,.55);
            font-size: .82rem;
            margin-top: 3px;
        }
        #selectedSlotDisplay { color: var(--teal-light); font-weight: 700; }

        .btn-confirm {
            background: var(--teal);
            color: #fff;
            border: none;
            padding: 11px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn-confirm:hover:not(:disabled) {
            background: var(--teal-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5,147,134,.45);
        }
        .btn-confirm:disabled {
            background: rgba(255,255,255,.15);
            cursor: not-allowed;
            color: rgba(255,255,255,.4);
        }

        /* ── DOCTOR CARD (right sidebar) ────────────────────────── */
        .doctor-sidebar {
            position: sticky;
            top: 90px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .doctor-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,50,99,.1);
            overflow: hidden;
        }

        /* Photo section */
        .doctor-photo-wrap {
            position: relative;
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--teal) 100%);
            padding: 32px 20px 20px;
            text-align: center;
        }
        .doctor-photo-wrap::before {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 40px;
            background: #fff;
            border-radius: 40px 40px 0 0;
        }
        .doctor-avatar-lg {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 6px 24px rgba(0,0,0,.22);
            position: relative;
            z-index: 1;
        }
        .doctor-avatar-initials {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            border: 4px solid rgba(255,255,255,.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            box-shadow: 0 6px 24px rgba(0,0,0,.22);
        }
        .available-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(5,147,134,.15);
            border: 1px solid rgba(5,147,134,.3);
            color: var(--teal);
            font-size: .72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            position: absolute;
            top: 16px; right: 16px;
        }
        .available-badge .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--teal);
            animation: blink 1.5s ease infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: .35; }
        }

        /* Info section */
        .doctor-info-body {
            padding: 6px 20px 20px;
            text-align: center;
        }
        .doctor-name-lg {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--dark-blue);
            margin-bottom: 4px;
        }
        .spec-badge {
            display: inline-block;
            background: linear-gradient(90deg, var(--dark-blue), var(--teal));
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            letter-spacing: .04em;
            margin-bottom: 14px;
        }

        /* Stars */
        .rating-stars {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            margin-bottom: 14px;
        }
        .star { color: #f4c542; font-size: 1rem; }
        .star.empty { color: #d8dde6; }
        .rating-num { font-size: .85rem; font-weight: 700; color: var(--dark-blue); margin-left: 4px; }

        /* Stats */
        .doc-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 16px;
        }
        .doc-stat-box {
            background: #f4f8fc;
            border-radius: 12px;
            padding: 10px 8px;
            text-align: center;
        }
        .doc-stat-box .val {
            font-size: 1rem;
            font-weight: 800;
            color: var(--dark-blue);
        }
        .doc-stat-box .lbl {
            font-size: .67rem;
            color: #8899aa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-top: 2px;
        }

        .doc-detail-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .8rem;
            color: #6b7a95;
            padding: 5px 0;
            border-top: 1px solid #f0f4f8;
        }
        .doc-detail-row i { color: var(--teal); font-size: 1rem; flex-shrink: 0; }

        /* Service summary card */
        .service-mini-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,50,99,.1);
            padding: 16px 18px;
        }
        .service-mini-card h6 {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9aaabb;
            margin-bottom: 10px;
        }
        .service-mini-name {
            font-weight: 800;
            color: var(--dark-blue);
            font-size: .95rem;
            margin-bottom: 8px;
        }
        .service-mini-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .mini-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #f0f4f8;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: .75rem;
            font-weight: 600;
            color: var(--dark-blue);
        }
        .mini-badge i { color: var(--teal); }

        /* ── FOOTER ─────────────────────────────────────────────── */
        .main-footer {
            background: linear-gradient(160deg, #001f3d 0%, #002a52 50%, #003263 100%);
            padding: 60px 0 30px;
            color: #fff;
        }
        .footer-brand {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -.3px;
        }
        .footer-h {
            color: var(--teal);
            font-weight: 700;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 14px;
        }
        .footer-link {
            display: block;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .875rem;
            margin-bottom: 8px;
            transition: color .2s, padding-left .2s;
        }
        .footer-link:hover { color: var(--teal); padding-left: 4px; }
        .footer-hr { border-color: rgba(255,255,255,.08); margin: 30px 0 24px; }
        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,.08);
            color: rgba(255,255,255,.7);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s;
        }
        .social-icon:hover { background: var(--teal); color: #fff; }

        /* ── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 960px) {
            .page-wrapper {
                grid-template-columns: 1fr;
            }
            .doctor-sidebar {
                position: static;
                order: -1;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .doctor-sidebar { grid-template-columns: 1fr; }
            .page-wrapper { padding: 0 12px 40px; }
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
                <span style="color:#fff; font-size:1.3rem; font-weight:800; letter-spacing:-.3px;">
                    ShinyTooth
                </span>
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

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/services">Services</a></li>
                <li class="breadcrumb-item"><a href="/services/{{ $service->id }}">{{ $service->name }}</a></li>
                <li class="breadcrumb-item active">Book Appointment</li>
            </ol>
        </nav>
    </div>
</div>

{{-- PROGRESS BAR --}}
<section class="progress-section">
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">Booking Progress</span>
            <span style="font-size:.9rem; color:#94a3b8; font-weight:600;">Step <strong style="color:var(--dark-blue);">3</strong> of <strong>6</strong></span>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step active"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
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
                <div class="progress-step-label active">
                    <div class="progress-step-icon">3</div>
                    <span>Schedule</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label">
                    <div class="progress-step-icon">4</div>
                    <span>Payment</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label">
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

{{-- ══════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════ --}}
<div class="page-wrapper">

    {{-- ─── LEFT: SCHEDULE ─────────────────────────────── --}}
    <div class="schedule-panel">

        {{-- Header --}}
        <div class="schedule-header">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="schedule-title">
                        <i class="bi bi-calendar3 me-2"></i>Choose Your Appointment Time
                    </p>
                    <p class="schedule-sub">
                        Showing the next 14 days for <strong style="color:#fff;">{{ $dentist->name }}</strong>
                    </p>
                </div>
                <div style="text-align:right;position:relative;z-index:1;">
                    <div style="color:rgba(255,255,255,.6);font-size:.75rem;">Duration</div>
                    <div style="color:#fff;font-size:1.2rem;font-weight:800;">
                        {{ $service->duration_minutes ?? 30 }} <span style="font-size:.8rem;font-weight:500;">min</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="legend-bar">
            <div class="legend-item">
                <div class="legend-dot free"></div> Available
            </div>
            <div class="legend-item">
                <div class="legend-dot busy"></div> Booked
            </div>
            <div class="legend-item">
                <div class="legend-dot selected"></div> Selected
            </div>
            <div class="ms-auto d-flex align-items-center gap-1" style="font-size:.78rem;color:#8899aa;">
                <i class="bi bi-clock"></i> Business hours: 8:00 AM – 5:00 PM
            </div>
        </div>

        {{-- Week navigation --}}
        <div class="week-nav">
            <button class="week-nav-btn" id="prevWeekBtn" onclick="shiftWeek(-7)">
                <i class="bi bi-chevron-left"></i> Previous
            </button>
            <div class="week-range-label" id="weekRangeLabel"></div>
            <button class="week-nav-btn" id="nextWeekBtn" onclick="shiftWeek(7)">
                Next <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        {{-- Calendar grid --}}
        <div class="cal-container">
            <div id="calGrid"></div>
        </div>

        {{-- Sticky confirm bar --}}
        <div class="summary-bar">
            <div class="summary-details">
                <div><strong>{{ $service->name }}</strong></div>
                <div class="selected-slot-info">
                    Selected: <span id="selectedSlotDisplay">— None selected —</span>
                </div>
            </div>
            <button class="btn-confirm" id="confirmBtn" disabled onclick="confirmBooking()">
                <i class="bi bi-check2-circle"></i> Confirm Slot
            </button>
        </div>
    </div>

    {{-- ─── RIGHT: DOCTOR CARD ──────────────────────────── --}}
    <aside class="doctor-sidebar">

        {{-- Doctor card --}}
        <div class="doctor-card">
            <div class="doctor-photo-wrap">
                <div class="available-badge">
                    <div class="dot"></div> Available
                </div>
                @if ($dentist->image)
                    <img src="/{{ $dentist->image }}" alt="{{ $dentist->name }}" class="doctor-avatar-lg">
                @else
                    <div class="doctor-avatar-initials">
                        {{ strtoupper(substr($dentist->name, 3, 1)) }}{{ strtoupper(substr(strrchr($dentist->name, ' '), 1, 1)) }}
                    </div>
                @endif
            </div>

            <div class="doctor-info-body">
                <div class="doctor-name-lg">{{ $dentist->name }}</div>
                @foreach ($dentist->specializations as $spec)
                    <div class="spec-badge">{{ $spec->name }}</div>
                @endforeach

                {{-- Star rating --}}
                <div class="rating-stars">
                    @for ($s = 1; $s <= 5; $s++)
                        <i class="bi bi-star-fill star {{ $s <= round($avgRating) ? '' : 'empty' }}"></i>
                    @endfor
                    <span class="rating-num">{{ number_format($avgRating, 1) }}</span>
                </div>

                {{-- Stats --}}
                <div class="doc-stats">
                    <div class="doc-stat-box">
                        <div class="val">{{ $dentist->experience_years }}+</div>
                        <div class="lbl">Years Exp.</div>
                    </div>
                    <div class="doc-stat-box">
                        <div class="val">{{ $dentist->ratings->count() }}</div>
                        <div class="lbl">Reviews</div>
                    </div>
                </div>

                {{-- Details --}}
                @if ($dentist->university)
                <div class="doc-detail-row">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>{{ $dentist->university }}</span>
                </div>
                @endif
                @if ($dentist->nationality)
                <div class="doc-detail-row">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>{{ $dentist->nationality }}</span>
                </div>
                @endif
                <div class="doc-detail-row">
                    <i class="bi bi-clock-history"></i>
                    <span>Joined {{ \Carbon\Carbon::parse($dentist->hire_date)->format('M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Service mini card --}}
        <div class="service-mini-card">
            <h6><i class="bi bi-briefcase-medical me-1"></i>Booking Summary</h6>
            <div class="service-mini-name">{{ $service->name }}</div>
            <div class="service-mini-meta">
                <div class="mini-badge">
                    <i class="bi bi-tag-fill"></i>
                    SAR {{ number_format($service->price, 0) }}
                </div>
                <div class="mini-badge">
                    <i class="bi bi-clock-fill"></i>
                    {{ $service->duration_minutes ?? 30 }} min
                </div>
                <div class="mini-badge">
                    <i class="bi bi-grid-fill"></i>
                    {{ $service->category }}
                </div>
            </div>
        </div>

    </aside>
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

{{-- ══════════════════════════════════════════════════════
     JAVASCRIPT — Calendar logic
══════════════════════════════════════════════════════ --}}
<script>
// ── Data injected from PHP ───────────────────────────────────
const SCHEDULE  = @json($schedule);  // { "2026-03-18": [{time,display,is_busy}, ...], ... }
const ALL_DAYS  = @json($days);      // [{date, day_name, day_num, month, is_weekend}, ...]
const SERVICE_DURATION = {{ $service->duration_minutes ?? 30 }};
const DENTIST_ID = {{ $dentist->id }};
const SERVICE_ID = {{ $service->id }};

// ── State ────────────────────────────────────────────────────
let weekOffset    = 0;       // 0 = first 7 days, 7 = next 7
let selectedDate  = null;
let selectedTime  = null;

// ── Helpers ──────────────────────────────────────────────────
function getVisibleDays() {
    return ALL_DAYS.slice(weekOffset, weekOffset + 7);
}

function formatWeekRange(days) {
    if (!days.length) return '';
    const first = days[0];
    const last  = days[days.length - 1];
    return `${first.day_name} ${first.date_num} ${first.month} – ${last.day_name} ${last.date_num} ${last.month}`;
}

// ── Render calendar ──────────────────────────────────────────
function renderCalendar() {
    const visibleDays = getVisibleDays();
    document.getElementById('weekRangeLabel').textContent = formatWeekRange(visibleDays);

    // Disable prev button on first week
    document.getElementById('prevWeekBtn').disabled = weekOffset === 0;
    document.getElementById('prevWeekBtn').style.opacity = weekOffset === 0 ? '.4' : '1';

    // Build all unique time labels across visible days
    const allTimes = new Set();
    visibleDays.forEach(day => {
        (SCHEDULE[day.date] || []).forEach(slot => allTimes.add(slot.time));
    });
    const times = Array.from(allTimes).sort();

    // Grid columns: time-label + one per day
    const cols = 1 + visibleDays.length;
    const grid = document.getElementById('calGrid');
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = `56px repeat(${visibleDays.length}, 1fr)`;

    let html = '';

    // ── Header row ──
    html += `<div class="time-label-col" style="border-bottom:2px solid #cdd9e5;background:#f8fafb;"></div>`;
    visibleDays.forEach(day => {
        html += `
            <div class="day-col-header ${day.is_weekend ? 'is-weekend' : ''}">
                <div class="day-name">${day.day_name}</div>
                <div class="day-num">${day.date_num}</div>
                <div class="day-month">${day.month}</div>
            </div>`;
    });

    // ── Slot rows ──
    times.forEach(time => {
        const [h, m]    = time.split(':').map(Number);
        const isHour    = m === 0;
        const display12 = formatTime12(h, m);

        html += `<div class="time-label ${isHour ? 'hour-mark' : ''}">${display12}</div>`;

        visibleDays.forEach(day => {
            const slots     = SCHEDULE[day.date] || [];
            const slot      = slots.find(s => s.time === time);
            const isBusy    = slot ? slot.is_busy : false;
            const isSelected= selectedDate === day.date && selectedTime === time;

            const btnClass  = isSelected ? 'selected' : (isBusy ? 'busy' : 'free');
            const icon      = isBusy ? '&#xF623;' : '&#xF26B;';   // bi-x-circle / bi-check-circle
            const title     = isBusy ? 'Already booked' : `Select ${display12}`;
            const onclick   = isBusy ? '' : `onclick="selectSlot('${day.date}','${time}','${slot ? slot.display : display12}')"`;

            html += `
                <div class="slot-cell">
                    <button class="slot-btn ${btnClass}" ${onclick} title="${title}" ${isBusy ? 'disabled' : ''}>
                        <i class="bi ${isBusy ? 'bi-x-circle-fill' : (isSelected ? 'bi-check-circle-fill' : 'bi-circle')}"></i>
                        <span class="d-none d-xl-inline" style="font-size:.65rem;">${display12}</span>
                    </button>
                </div>`;
        });
    });

    grid.innerHTML = html;
}

// ── Select a slot ────────────────────────────────────────────
function selectSlot(date, time, display) {
    selectedDate = date;
    selectedTime = time;

    // Format nice label
    const dayObj  = ALL_DAYS.find(d => d.date === date);
    const label   = dayObj ? `${dayObj.day_name} ${dayObj.date_num} ${dayObj.month} at ${display}` : `${date} at ${display}`;
    document.getElementById('selectedSlotDisplay').textContent = label;
    document.getElementById('confirmBtn').disabled = false;

    renderCalendar();   // re-render to highlight selected
}

// ── Week navigation ──────────────────────────────────────────
function shiftWeek(delta) {
    const next = weekOffset + delta;
    if (next < 0 || next >= ALL_DAYS.length) return;
    weekOffset = Math.max(0, Math.min(next, ALL_DAYS.length - 1));
    renderCalendar();
}

// ── Confirm booking ──────────────────────────────────────────
function confirmBooking() {
    if (!selectedDate || !selectedTime) return;
    // Navigate to confirmation page (Step 5)
    const params = new URLSearchParams({
        service: SERVICE_ID,
        dentist: DENTIST_ID,
        date:    selectedDate,
        time:    selectedTime,
    });
    window.location.href = `/book/confirm?${params.toString()}`;
}

// ── Helpers ──────────────────────────────────────────────────
function formatTime12(h, m) {
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12  = h > 12 ? h - 12 : (h === 0 ? 12 : h);
    return `${h12}:${String(m).padStart(2,'0')} ${ampm}`;
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', renderCalendar);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* Sticky nav shrink on scroll */
    window.addEventListener('scroll', () => {
        document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
    });
</script>
</body>
</html>

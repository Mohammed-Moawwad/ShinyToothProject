<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment — ShinyTooth</title>

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

        /* ── PROGRESS BAR ────────────────────────────── */
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
            max-width: 1280px;
            margin: 36px auto;
            padding: 0 20px 60px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ── ORDER SUMMARY SIDEBAR ───────────────── */
        .summary-sidebar { display: flex; flex-direction: column; gap: 16px; }

        .summary-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,50,99,.08);
            overflow: hidden;
        }

        .summary-card-header {
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            padding: 18px 22px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .summary-card-body { padding: 20px 22px; }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: .88rem;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-row-label { color: #64748b; font-weight: 500; }
        .summary-row-value { color: var(--dark-blue); font-weight: 700; text-align: right; max-width: 55%; }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f0f9f8, #e8f4f7);
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 10px;
        }
        .total-label { font-size: .95rem; font-weight: 700; color: var(--dark-blue); }
        .total-amount { font-size: 1.5rem; font-weight: 800; color: var(--teal); }

        .doctor-mini {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 22px;
        }
        .doctor-mini-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--teal);
            flex-shrink: 0;
        }
        .doctor-mini-initials {
            width: 52px;
            height: 52px;
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
        .doctor-mini-name { font-size: .93rem; font-weight: 700; color: var(--dark-blue); }
        .doctor-mini-spec { font-size: .78rem; color: var(--teal); font-weight: 600; }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0faf9;
            border: 1px solid #c7ede9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: .83rem;
            color: #0d6e63;
            font-weight: 500;
        }

        /* ── PAYMENT PANEL ───────────────────────── */
        .payment-panel {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,50,99,.08);
            overflow: hidden;
        }
        .payment-panel-header {
            background: linear-gradient(135deg, var(--dark-blue), var(--mid-blue));
            color: #fff;
            padding: 22px 28px;
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .payment-panel-body { padding: 32px 28px; }

        /* ── 3D CREDIT CARD ──────────────────────── */
        .card-scene {
            width: 480px;
            height: 290px;
            perspective: 1400px;
            margin: 0 auto 36px;
        }

        .card-flip {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform .7s cubic-bezier(.4,0,.2,1);
        }
        .card-flip.is-flipped { transform: rotateY(180deg); }

        .card-face {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,.35), 0 6px 20px rgba(0,0,0,.2);
        }

        .card-front {
            background: linear-gradient(135deg, #003263 0%, #0055a5 100%);
            padding: 24px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            transition: background .5s ease;
        }

        .card-back {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, #1a2340 0%, #2d3a5a 100%);
            display: flex;
            flex-direction: column;
        }

        /* Card front elements */
        .card-top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Chip */
        .card-chip {
            width: 46px;
            height: 35px;
            border-radius: 6px;
            background: linear-gradient(135deg, #d4a843, #f0c96a, #c8922d);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.3);
        }
        .card-chip::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 1px;
            background: rgba(0,0,0,.15);
            transform: translateX(-50%);
        }
        .card-chip::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(0,0,0,.15);
            transform: translateY(-50%);
        }

        /* Network logo (top-right) */
        .card-network-logo {
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        /* Contactless + network area at top right */
        .card-top-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .contactless-icon {
            opacity: .7;
            font-size: 1.2rem;
        }

        /* Card number */
        .card-number-display {
            font-size: 1.32rem;
            letter-spacing: .12em;
            font-family: 'Courier New', monospace;
            text-shadow: 0 1px 2px rgba(0,0,0,.3);
            word-spacing: .25em;
            white-space: nowrap;
            color: #fff;
        }

        /* Card bottom row */
        .card-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .card-field-label {
            text-transform: uppercase;
            font-size: .58rem;
            letter-spacing: .12em;
            color: rgba(255,255,255,.6);
            margin-bottom: 3px;
        }
        .card-holder-display {
            font-size: .97rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #fff;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .card-expiry-display {
            font-size: .97rem;
            font-weight: 700;
            color: #fff;
            text-align: right;
        }

        /* Network logos as styled elements */
        .logo-visa {
            font-style: italic;
            font-size: 1.6rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -.02em;
            font-family: 'Times New Roman', serif;
            text-shadow: 0 1px 3px rgba(0,0,0,.3);
        }
        .logo-mastercard {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .logo-mc-left  { width: 26px; height: 26px; border-radius: 50%; background: #eb001b; opacity: .9; }
        .logo-mc-right { width: 26px; height: 26px; border-radius: 50%; background: #f79e1b; opacity: .9; margin-left: -10px; }
        .logo-amex {
            font-size: .85rem;
            font-weight: 900;
            color: #fff;
            background: rgba(255,255,255,.18);
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: .08em;
        }
        .logo-discover {
            font-size: .85rem;
            font-weight: 900;
            color: #f97316;
            background: #fff;
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: .04em;
        }
        .logo-jcb {
            font-size: .85rem;
            font-weight: 900;
            color: #1d4ed8;
            background: #fff;
            padding: 3px 8px;
            border-radius: 5px;
        }
        .logo-unionpay {
            font-size: .75rem;
            font-weight: 900;
            color: #fff;
            background: rgba(255,255,255,.2);
            padding: 3px 7px;
            border-radius: 5px;
        }
        .logo-diners {
            font-size: .75rem;
            font-weight: 900;
            color: rgba(255,255,255,.8);
            background: rgba(255,255,255,.15);
            padding: 3px 7px;
            border-radius: 5px;
        }
        .logo-default { opacity: 0; }

        /* Card back */
        .card-back-stripe {
            background: #0d1117;
            height: 46px;
            margin-top: 28px;
        }
        .card-back-sig-area {
            margin: 16px 20px 0;
            padding: 10px 14px;
            background: repeating-linear-gradient(
                -55deg,
                #e8e8e8 0px, #e8e8e8 5px,
                #f5f5f5 5px, #f5f5f5 10px
            );
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }
        .card-cvv-label {
            font-size: .65rem;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .card-cvv-value {
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            font-weight: 700;
            color: #111;
            background: #fff;
            padding: 2px 10px;
            border-radius: 4px;
            letter-spacing: .2em;
            min-width: 50px;
            text-align: center;
        }
        .card-back-network {
            display: flex;
            justify-content: flex-end;
            padding: 12px 22px 0;
        }
        .card-back-info {
            padding: 12px 20px;
            color: rgba(255,255,255,.45);
            font-size: .65rem;
            text-align: center;
        }

        /* ── FORM ELEMENTS ────────────────────────── */
        .form-section-title {
            font-size: .8rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-label-custom {
            font-size: .82rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 6px;
        }

        .form-control-custom {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 16px;
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
        .form-control-custom.is-active {
            border-color: var(--dark-blue);
            box-shadow: 0 0 0 3px rgba(0,50,99,.1);
        }

        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2364748b' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 12px;
            padding-right: 36px;
        }

        .card-number-input-wrap {
            position: relative;
        }
        .card-type-badge {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .82rem;
            font-weight: 700;
            color: #94a3b8;
            transition: all .3s;
        }

        .cvv-hint {
            font-size: .75rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .divider-section { margin: 24px 0 20px; }

        /* ── PAY BUTTON ──────────────────────────── */
        .btn-pay {
            width: 100%;
            background: linear-gradient(135deg, var(--dark-blue), #0055cc);
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
            box-shadow: 0 4px 16px rgba(0,50,99,.3);
            margin-top: 24px;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(0,50,99,.4);
            background: linear-gradient(135deg, #004080, #0066dd);
        }
        .btn-pay:active { transform: translateY(0); }
        .btn-pay:disabled { opacity: .75; cursor: not-allowed; transform: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .field-err { color:#dc2626; font-size:.78rem; margin-top:4px; }
        .form-control-custom.is-invalid { border-color:#dc2626 !important; background:#fff8f8; }
        .form-control-custom.is-valid   { border-color:#059386 !important; background:#f0fdf9; }

        .accepted-cards {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 14px;
            flex-wrap: wrap;
        }
        .accepted-card-badge {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: .73rem;
            font-weight: 700;
            color: #475569;
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

        @media (max-width: 1000px) {
            .page-wrapper { grid-template-columns: 1fr; }
            .card-scene { width: 420px; height: 255px; }
            .card-number-display { font-size: 1.15rem; }
        }
        @media (max-width: 500px) {
            .payment-panel-body { padding: 20px 16px; }
            .card-scene { width: 300px; height: 185px; }
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

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/services">Services</a></li>
                <li class="breadcrumb-item"><a href="/services/{{ $appointment->service->id }}">{{ $appointment->service->name }}</a></li>
                <li class="breadcrumb-item active">Payment</li>
            </ol>
        </nav>
    </div>
</div>

{{-- PROGRESS BAR --}}
<section class="progress-section">
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">Booking Progress</span>
            <span style="font-size:.9rem; color:#94a3b8; font-weight:600;">Step <strong style="color:var(--dark-blue);">4</strong> of <strong>6</strong></span>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step completed"></div>
            <div class="progress-step active"></div>
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
                <div class="progress-step-label completed">
                    <div class="progress-step-icon"><i class="bi bi-check-lg"></i></div>
                    <span>Schedule</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label active">
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

{{-- MAIN CONTENT --}}
<div class="page-wrapper">

    {{-- ── LEFT: ORDER SUMMARY ──────────────────────────────────────────── --}}
    <aside class="summary-sidebar">

        {{-- Doctor mini card --}}
        <div class="summary-card">
            <div class="summary-card-header">
                <i class="bi bi-person-circle"></i> Your Doctor
            </div>
            <div class="doctor-mini">
                @if ($appointment->dentist->image)
                    <img src="/{{ $appointment->dentist->image }}" alt="{{ $appointment->dentist->name }}" class="doctor-mini-avatar">
                @else
                    <div class="doctor-mini-initials">
                        {{ strtoupper(substr($appointment->dentist->name, 3, 1)) }}{{ strtoupper(substr(strrchr($appointment->dentist->name, ' '), 1, 1)) }}
                    </div>
                @endif
                <div>
                    <div class="doctor-mini-name">{{ $appointment->dentist->name }}</div>
                    @if ($appointment->dentist->specializations->isNotEmpty())
                        <div class="doctor-mini-spec">{{ $appointment->dentist->specializations->first()->name }}</div>
                    @endif
                    <div style="font-size:.78rem; color:#94a3b8; margin-top:3px;">
                        {{ $appointment->dentist->experience_years }} yrs experience
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking details --}}
        <div class="summary-card">
            <div class="summary-card-header">
                <i class="bi bi-receipt"></i> Booking Summary
            </div>
            <div class="summary-card-body">
                <div class="summary-row">
                    <span class="summary-row-label">Service</span>
                    <span class="summary-row-value">{{ $appointment->service->name }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-row-label">Category</span>
                    <span class="summary-row-value">{{ $appointment->service->category }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-row-label">Duration</span>
                    <span class="summary-row-value">{{ $appointment->service->duration_minutes }} min</span>
                </div>
                <div class="summary-row">
                    <span class="summary-row-label">Date</span>
                    <span class="summary-row-value">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M j, Y') }}
                    </span>
                </div>
                <div class="summary-row">
                    <span class="summary-row-label">Time</span>
                    <span class="summary-row-value">
                        @php
                            [$hh, $mm] = explode(':', $appointment->appointment_time);
                            $h12 = (int)$hh > 12 ? (int)$hh - 12 : ((int)$hh === 0 ? 12 : (int)$hh);
                            $ampm = (int)$hh >= 12 ? 'PM' : 'AM';
                        @endphp
                        {{ $h12 }}:{{ $mm }} {{ $ampm }}
                    </span>
                </div>

                <div class="total-row">
                    <span class="total-label">Total Amount</span>
                    <span class="total-amount">${{ number_format($appointment->service->price, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Security badge --}}
        <div class="security-badge">
            <i class="bi bi-shield-lock-fill" style="font-size:1.3rem; color:var(--teal);"></i>
            <span>Your payment is protected with 256-bit SSL encryption. We never store your card details.</span>
        </div>

    </aside>

    {{-- ── RIGHT: PAYMENT FORM ──────────────────────────────────────────── --}}
    <div class="payment-panel">
        <div class="payment-panel-header">
            <i class="bi bi-credit-card-2-front"></i> Payment Information
        </div>
        <div class="payment-panel-body">

            {{-- ═══════════════ 3D CREDIT CARD ═══════════════ --}}
            <div class="card-scene">
                <div class="card-flip" id="cardFlip">

                    {{-- FRONT --}}
                    <div class="card-face card-front" id="cardFront">
                        <div class="card-top-row">
                            <div class="card-chip"></div>
                            <div class="card-top-right">
                                <div class="contactless-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 2C9.5 4.5 8 8 8 12s1.5 7.5 4 10" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                        <path d="M12 6c-1.5 1.8-2.5 4-2.5 6s1 4.2 2.5 6" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                        <path d="M12 10c-.7.9-1.2 2-1.2 3s.5 2.1 1.2 3" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                    </svg>
                                </div>
                                {{-- Network logo --}}
                                <div class="card-network-logo" id="cardNetworkFront">
                                    <span class="logo-visa" id="logo-visa">VISA</span>
                                    <span class="logo-mastercard" id="logo-mastercard" style="display:none;">
                                        <span class="logo-mc-left"></span><span class="logo-mc-right"></span>
                                    </span>
                                    <span class="logo-amex"     id="logo-amex"     style="display:none;">AMEX</span>
                                    <span class="logo-discover" id="logo-discover" style="display:none;">DISCOVER</span>
                                    <span class="logo-jcb"      id="logo-jcb"      style="display:none;">JCB</span>
                                    <span class="logo-unionpay" id="logo-unionpay" style="display:none;">UnionPay</span>
                                    <span class="logo-diners"   id="logo-diners"   style="display:none;">Diners</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-number-display" id="cardNumberDisplay">
                            #### &nbsp;#### &nbsp;#### &nbsp;####
                        </div>

                        <div class="card-bottom-row">
                            <div>
                                <div class="card-field-label">Card Holder</div>
                                <div class="card-holder-display" id="cardHolderDisplay">FULL NAME</div>
                            </div>
                            <div style="text-align:right;">
                                <div class="card-field-label">Expires</div>
                                <div class="card-expiry-display" id="cardExpiryDisplay">MM / YY</div>
                            </div>
                        </div>
                    </div>

                    {{-- BACK --}}
                    <div class="card-face card-back">
                        <div class="card-back-stripe"></div>
                        <div class="card-back-sig-area">
                            <span class="card-cvv-label">CVV</span>
                            <span class="card-cvv-value" id="cardCvvDisplay">•••</span>
                        </div>
                        <div class="card-back-network" id="cardNetworkBack">
                            <span class="logo-visa" id="logo-visa-back">VISA</span>
                            <span id="logo-mc-back"    style="display:none;" class="logo-mastercard"><span class="logo-mc-left"></span><span class="logo-mc-right"></span></span>
                            <span id="logo-amex-back"    style="display:none;" class="logo-amex">AMEX</span>
                            <span id="logo-discover-back" style="display:none;" class="logo-discover">DISCOVER</span>
                            <span id="logo-jcb-back"      style="display:none;" class="logo-jcb">JCB</span>
                            <span id="logo-unionpay-back" style="display:none;" class="logo-unionpay">UnionPay</span>
                            <span id="logo-diners-back"   style="display:none;" class="logo-diners">Diners</span>
                        </div>
                        <div class="card-back-info">
                            This card is issued subject to the conditions of the Cardholder Agreement.
                        </div>
                    </div>

                </div>
            </div>
            {{-- ═══════════════ END CARD ═══════════════ --}}

            {{-- PAYMENT FORM --}}
            <form method="POST" action="{{ route('booking.pay') }}" id="paymentForm" onsubmit="return validatePaymentForm()">
                @csrf
                {{-- Appointment ID (patient info already confirmed) --}}
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                <div class="form-section-title"><i class="bi bi-credit-card me-2"></i>Card Details</div>

                {{-- Card Number --}}
                <div class="mb-3">
                    <label class="form-label-custom">Card Number *</label>
                    <div class="card-number-input-wrap">
                        <input type="text"
                               id="cardNumber"
                               name="card_number_display"
                               class="form-control-custom"
                               placeholder="0000 0000 0000 0000"
                               maxlength="19"
                               autocomplete="cc-number"
                               inputmode="numeric">
                        <div class="card-type-badge" id="cardTypeBadgeInInput">
                            <i class="bi bi-credit-card"></i>
                        </div>
                    </div>
                </div>

                {{-- Card Holder --}}
                <div class="mb-3">
                    <label class="form-label-custom">Card Holder Name *</label>
                    <input type="text"
                           id="cardHolder"
                           name="card_holder"
                           class="form-control-custom"
                           placeholder="Full Name on Card"
                           autocomplete="cc-name">
                </div>

                {{-- Expiry + CVV --}}
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label class="form-label-custom">Month *</label>
                        <select id="cardMonth" name="card_month" class="form-control-custom">
                            <option value="">MM</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                    {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label-custom">Year *</label>
                        <select id="cardYear" name="card_year" class="form-control-custom">
                            <option value="">YYYY</option>
                            @for ($y = date('Y'); $y <= date('Y') + 10; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label-custom">CVV *</label>
                        <input type="text"
                               id="cardCvv"
                               name="card_cvv_display"
                               class="form-control-custom"
                               placeholder="•••"
                               maxlength="4"
                               inputmode="numeric">
                        <div class="cvv-hint"><i class="bi bi-info-circle me-1"></i>3–4 digits on back</div>
                    </div>
                </div>

                {{-- Error messages --}}
                <div id="formError" style="display:none; color:#dc2626; font-size:.83rem; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; margin-bottom:12px;">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <span id="formErrorMsg"></span>
                </div>

                {{-- Pay Button --}}
                <button type="submit" class="btn-pay" id="payBtn">
                    <i class="bi bi-lock-fill"></i>
                    Pay ${{ number_format($appointment->service->price, 2) }} Securely
                </button>

                <div class="accepted-cards">
                    <span style="font-size:.73rem; color:#94a3b8; font-weight:500;">Accepted:</span>
                    <span class="accepted-card-badge">VISA</span>
                    <span class="accepted-card-badge" style="background:#fff3f3; border-color:#fecdd3; color:#dc2626;">
                        <span style="color:#dc2626;">●</span><span style="color:#f97316; margin-left:-4px;">●</span> MC
                    </span>
                    <span class="accepted-card-badge" style="background:#f0f9ff; border-color:#bae6fd; color:#0369a1;">AMEX</span>
                    <span class="accepted-card-badge" style="background:#fff7ed; border-color:#fed7aa; color:#c2410c;">DISCOVER</span>
                </div>
            </form>

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
// ── Card type detection ───────────────────────────────────────────────────
const CARD_TYPES = {
    visa:       { pattern: /^4/,                               maxLen: 16, cvvLen: 3, color: 'linear-gradient(135deg,#003263 0%,#0055a5 100%)' },
    mastercard: { pattern: /^(5[1-5]|2[2-7])/,                maxLen: 16, cvvLen: 3, color: 'linear-gradient(135deg,#1b1b2f 0%,#b91c1c 100%)' },
    amex:       { pattern: /^3[47]/,                           maxLen: 15, cvvLen: 4, color: 'linear-gradient(135deg,#007a8a 0%,#00aabb 100%)' },
    discover:   { pattern: /^(6011|65|64[4-9])/,               maxLen: 16, cvvLen: 3, color: 'linear-gradient(135deg,#92400e 0%,#f97316 100%)' },
    jcb:        { pattern: /^35(2[89]|[3-8])/,                 maxLen: 16, cvvLen: 3, color: 'linear-gradient(135deg,#1d4ed8 0%,#60a5fa 100%)' },
    unionpay:   { pattern: /^62/,                              maxLen: 19, cvvLen: 3, color: 'linear-gradient(135deg,#7f1d1d 0%,#ef4444 100%)' },
    diners:     { pattern: /^(30[0-5]|3[68])/,                 maxLen: 14, cvvLen: 3, color: 'linear-gradient(135deg,#374151 0%,#6b7280 100%)' },
};

let currentCardType = 'unknown';

function detectCardType(num) {
    const n = num.replace(/\s/g, '');
    for (const [type, cfg] of Object.entries(CARD_TYPES)) {
        if (cfg.pattern.test(n)) return type;
    }
    return 'unknown';
}

function formatCardNumber(raw, type) {
    const digits = raw.replace(/\D/g, '');
    if (type === 'amex') {
        // 4-6-5
        const p1 = digits.slice(0, 4);
        const p2 = digits.slice(4, 10);
        const p3 = digits.slice(10, 15);
        return [p1, p2, p3].filter(Boolean).join(' ');
    }
    if (type === 'diners') {
        // 4-6-4
        const p1 = digits.slice(0, 4);
        const p2 = digits.slice(4, 10);
        const p3 = digits.slice(10, 14);
        return [p1, p2, p3].filter(Boolean).join(' ');
    }
    // Standard 4-4-4-4
    return digits.match(/.{1,4}/g)?.join(' ') || '';
}

function getCardDisplayNumber(formatted, type) {
    if (type === 'amex') {
        const parts = formatted.split(' ');
        const p1 = (parts[0] || '####').padEnd(4, '#');
        const p2 = (parts[1] || '######').padEnd(6, '#');
        const p3 = (parts[2] || '#####').padEnd(5, '#');
        return `${p1} &nbsp;${p2} &nbsp;${p3}`;
    }
    if (type === 'diners') {
        const parts = formatted.split(' ');
        const p1 = (parts[0] || '####').padEnd(4, '#');
        const p2 = (parts[1] || '######').padEnd(6, '#');
        const p3 = (parts[2] || '####').padEnd(4, '#');
        return `${p1} &nbsp;${p2} &nbsp;${p3}`;
    }
    const parts = formatted.split(' ');
    return Array.from({length: 4}, (_, i) => (parts[i] || '####').padEnd(4, '#')).join(' &nbsp;');
}

// ── Show/hide network logos ───────────────────────────────────────────────
const LOGO_IDS_FRONT = { visa: 'logo-visa', mastercard: 'logo-mastercard', amex: 'logo-amex', discover: 'logo-discover', jcb: 'logo-jcb', unionpay: 'logo-unionpay', diners: 'logo-diners' };
const LOGO_IDS_BACK  = { visa: 'logo-visa-back', mastercard: 'logo-mc-back', amex: 'logo-amex-back', discover: 'logo-discover-back', jcb: 'logo-jcb-back', unionpay: 'logo-unionpay-back', diners: 'logo-diners-back' };

function setNetworkLogo(type) {
    // Front
    Object.entries(LOGO_IDS_FRONT).forEach(([t, id]) => {
        document.getElementById(id).style.display = (t === type) ? '' : 'none';
    });
    // Back
    Object.entries(LOGO_IDS_BACK).forEach(([t, id]) => {
        document.getElementById(id).style.display = (t === type) ? '' : 'none';
    });
    // Default: show Visa if unknown
    if (type === 'unknown') {
        document.getElementById('logo-visa').style.display = '';
        document.getElementById('logo-visa-back').style.display = '';
    }
    // Card gradient
    const cardFront = document.getElementById('cardFront');
    const color = (type !== 'unknown' && CARD_TYPES[type]) ? CARD_TYPES[type].color : 'linear-gradient(135deg,#003263 0%,#059386 100%)';
    cardFront.style.background = color;
    // Badge in input
    const badge = document.getElementById('cardTypeBadgeInInput');
    const icons = { visa:'<i class="bi bi-credit-card-fill" style="color:#003263;"></i> Visa', mastercard:'<span style="color:#dc2626;">●</span><span style="color:#f97316; margin-left:-3px;">●</span> MC', amex:'<i class="bi bi-credit-card-fill" style="color:#007a8a;"></i> Amex', discover:'<i class="bi bi-credit-card-fill" style="color:#f97316;"></i> Discover', jcb:'<i class="bi bi-credit-card-fill" style="color:#1d4ed8;"></i> JCB', unionpay:'<i class="bi bi-credit-card-fill" style="color:#ef4444;"></i> UP', diners:'<i class="bi bi-credit-card-fill" style="color:#6b7280;"></i> Diners' };
    badge.innerHTML = icons[type] || '<i class="bi bi-credit-card"></i>';
    badge.style.color = type !== 'unknown' ? '#1e293b' : '#94a3b8';
}

// ── Card number input ─────────────────────────────────────────────────────
const cardNumberInput = document.getElementById('cardNumber');
cardNumberInput.addEventListener('input', function () {
    let raw = this.value.replace(/\D/g, '');
    const type = detectCardType(raw);
    const maxDigits = (type !== 'unknown' && CARD_TYPES[type]) ? CARD_TYPES[type].maxLen : 16;
    raw = raw.slice(0, maxDigits);
    const formatted = formatCardNumber(raw, type);
    this.value = formatted;

    if (type !== currentCardType) {
        currentCardType = type;
        setNetworkLogo(type);
        // Update CVV max length
        const cvvLen = (CARD_TYPES[type]?.cvvLen) || 3;
        document.getElementById('cardCvv').maxLength = cvvLen;
        document.getElementById('cardCvv').placeholder = '•'.repeat(cvvLen);
    }

    document.getElementById('cardNumberDisplay').innerHTML = getCardDisplayNumber(formatted, type);
});

// ── Card holder input ──────────────────────────────────────────────────────
document.getElementById('cardHolder').addEventListener('input', function () {
    const val = this.value.trim().toUpperCase() || 'FULL NAME';
    document.getElementById('cardHolderDisplay').textContent = val.slice(0, 26);
});

// ── Expiry inputs ──────────────────────────────────────────────────────────
function updateExpiry() {
    const m = document.getElementById('cardMonth').value || 'MM';
    const y = document.getElementById('cardYear').value;
    const yy = y ? y.slice(-2) : 'YY';
    document.getElementById('cardExpiryDisplay').textContent = `${m} / ${yy}`;
}
document.getElementById('cardMonth').addEventListener('change', updateExpiry);
document.getElementById('cardYear').addEventListener('change', updateExpiry);

// ── CVV flip ──────────────────────────────────────────────────────────────
const cardFlip = document.getElementById('cardFlip');
const cvvInput = document.getElementById('cardCvv');

cvvInput.addEventListener('focus', () => { cardFlip.classList.add('is-flipped'); });
cvvInput.addEventListener('blur',  () => { cardFlip.classList.remove('is-flipped'); });

cvvInput.addEventListener('input', function () {
    const digits = this.value.replace(/\D/g, '').slice(0, this.maxLength);
    this.value = digits;
    document.getElementById('cardCvvDisplay').textContent = '•'.repeat(digits.length) || '•••';
});

// ── Form focus highlighting on card ───────────────────────────────────────
['cardNumber','cardHolder','cardMonth','cardYear'].forEach(id => {
    document.getElementById(id).addEventListener('focus', () => {
        if (cardFlip.classList.contains('is-flipped')) cardFlip.classList.remove('is-flipped');
    });
});

// ── Card payment validation ──────────────────────────────────────────────
function validatePaymentForm() {
    const numRaw  = cardNumberInput.value.replace(/\s/g, '');
    const holder  = document.getElementById('cardHolder').value.trim();
    const month   = document.getElementById('cardMonth').value;
    const year    = document.getElementById('cardYear').value;
    const cvv     = cvvInput.value.trim();

    const errBox = document.getElementById('formError');
    const errMsg = document.getElementById('formErrorMsg');

    function showError(msg) {
        errMsg.textContent = msg;
        errBox.style.display = 'block';
        errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }

    if (numRaw.length < 13) return showError('Please enter a valid card number.');
    if (!holder)            return showError('Please enter the card holder name.');
    if (!month || !year)    return showError('Please select the card expiry date.');

    const now      = new Date();
    const expDate  = new Date(parseInt(year), parseInt(month) - 1, 1);
    if (expDate < new Date(now.getFullYear(), now.getMonth(), 1))
        return showError('Your card has expired. Please use a valid card.');

    const expectedCvvLen = CARD_TYPES[currentCardType]?.cvvLen || 3;
    if (cvv.length < expectedCvvLen) return showError(`CVV must be ${expectedCvvLen} digits.`);

    errBox.style.display = 'none';
    return true;
}

// ── Navbar scroll ─────────────────────────────────────────────────────────
window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
});

// ── Init: show Visa logo by default ──────────────────────────────────────
setNetworkLogo('unknown');
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} — ShinyTooth</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --teal:      #059386;
            --dark-blue: #003263;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f4f8;
            overflow-x: hidden;
        }

        /* ── NAVBAR ───────────────────────────────────── */
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

        /* ── BREADCRUMB ───────────────────────────────── */
        .breadcrumb-bar {
            background: var(--dark-blue);
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .breadcrumb-bar .breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
        }
        .breadcrumb-item a {
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .875rem;
            transition: color .2s;
        }
        .breadcrumb-item a:hover { color: #fff; }
        .breadcrumb-item.active { color: rgba(255,255,255,.9); font-size: .875rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.35); }

        /* ── MAIN LAYOUT ─────────────────────────────── */
        .detail-wrapper {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 28px;
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px 60px;
            align-items: start;
        }

        /* ── LEFT — SERVICE INFO ──────────────────────── */
        .service-info-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .service-image-hero {
            width: 100%;
            height: 300px;
            border-radius: 16px 16px 0 0;
            object-fit: cover;
            display: block;
        }

        .service-image-placeholder {
            width: 100%;
            height: 300px;
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
        }

        .service-content-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,50,99,.08);
        }

        .service-header {
            padding: 28px 28px 0;
        }

        .category-badge-detail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .service-title {
            font-size: 1.95rem;
            font-weight: 800;
            color: var(--dark-blue);
            margin: 0 0 8px;
            line-height: 1.2;
        }

        .special-offer-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #fff8e1, #fff3cd);
            border: 1px solid #f59e0b;
            border-radius: 10px;
            padding: 10px 16px;
            margin: 12px 0;
            font-size: .9rem;
            font-weight: 600;
            color: #92400e;
        }

        /* ── QUICK STATS ROW ─────────────────────────── */
        .quick-stats {
            display: flex;
            gap: 12px;
            padding: 0 28px 20px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .stat-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f4f8;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .9rem;
        }
        .stat-chip i { color: var(--teal); font-size: 1.1rem; }
        .stat-chip .stat-label { color: #64748b; font-size: .78rem; display: block; }
        .stat-chip .stat-value { color: var(--dark-blue); font-weight: 700; font-size: 1rem; display: block; }

        .price-chip {
            background: linear-gradient(135deg, #e0faf7, #c7f5f0);
        }
        .price-chip .stat-value { color: var(--teal); font-size: 1.15rem; }

        /* ── CONTENT SECTIONS ────────────────────────── */
        .content-divider {
            border: none;
            border-top: 1px solid #e8edf2;
            margin: 0 28px;
        }

        .content-section {
            padding: 24px 28px;
        }

        .section-heading {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-heading i { color: var(--teal); font-size: 1.1rem; }

        .description-text {
            color: #475569;
            line-height: 1.8;
            font-size: .95rem;
        }

        /* ── BENEFITS GRID ───────────────────────────── */
        .benefits-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #e8edf2;
        }
        .benefit-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #059386, #047a6e);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .benefit-icon i { color: #fff; font-size: .95rem; }
        .benefit-text {
            font-size: .875rem;
            color: #374151;
            font-weight: 500;
            line-height: 1.4;
        }

        /* ── WHAT TO EXPECT STEPS ────────────────────── */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--dark-blue);
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-content .step-title {
            font-weight: 600;
            color: var(--dark-blue);
            font-size: .9rem;
            margin-bottom: 2px;
        }
        .step-content .step-desc {
            color: #64748b;
            font-size: .835rem;
            line-height: 1.5;
        }

        /* ── DOCTOR PANEL (RIGHT) ────────────────────── */
        .doctor-panel {
            position: sticky;
            top: 90px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,50,99,.1);
            overflow: hidden;
            max-height: calc(100vh - 110px);
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            background: var(--dark-blue);
            padding: 20px 22px;
            flex-shrink: 0;
        }

        .panel-header h3 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .panel-subtitle {
            color: rgba(255,255,255,.65);
            font-size: .82rem;
            margin: 0;
        }

        .available-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(5,147,134,.25);
            color: #5eead4;
            border: 1px solid rgba(5,147,134,.4);
            border-radius: 20px;
            padding: 3px 10px;
            font-size: .78rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .doctors-list {
            overflow-y: auto;
            flex: 1;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* ── DOCTOR CARD ─────────────────────────────── */
        .doctor-card {
            border: 1.5px solid #e8edf2;
            border-radius: 12px;
            padding: 14px;
            transition: border-color .2s, box-shadow .2s, transform .2s;
            cursor: pointer;
        }
        .doctor-card:hover {
            border-color: var(--teal);
            box-shadow: 0 4px 16px rgba(5,147,134,.15);
            transform: translateY(-2px);
        }

        .doctor-card-top {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .doctor-photo-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .doctor-photo {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e8edf2;
        }

        .doctor-photo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--dark-blue), var(--teal));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .doctor-photo-placeholder i { color: #fff; font-size: 1.5rem; }

        .availability-dot {
            position: absolute;
            bottom: -3px;
            right: -3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid #fff;
        }

        .doctor-name-area {
            flex: 1;
        }

        .doctor-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin: 0 0 3px;
        }

        .doctor-specializations {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 5px;
        }

        .spec-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: .72rem;
            font-weight: 600;
        }

        .doctor-stars {
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .doctor-stars i { color: #f59e0b; font-size: .75rem; }
        .doctor-stars .rating-text { color: #64748b; font-size: .78rem; margin-left: 4px; }

        .doctor-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 8px 0;
        }

        .meta-chip {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #64748b;
            font-size: .8rem;
        }
        .meta-chip i { color: var(--teal); font-size: .85rem; }

        .available-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: .78rem;
            font-weight: 600;
        }

        .btn-select-doctor {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, var(--dark-blue), #004a94);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .9rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }
        .btn-select-doctor:hover {
            background: linear-gradient(135deg, var(--teal), #047a6e);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5,147,134,.3);
        }
        .btn-select-doctor i { margin-left: 6px; }

        /* ── NO DOCTORS MESSAGE ──────────────────────── */
        .no-doctors-msg {
            padding: 40px 20px;
            text-align: center;
            color: #94a3b8;
        }
        .no-doctors-msg i { font-size: 2.5rem; margin-bottom: 10px; display: block; color: #cbd5e1; }
        .no-doctors-msg p { margin: 0; font-size: .9rem; }

        /* ── FOOTER (same as Services page) ─────────── */
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

        /* ── CATEGORY COLORS ─────────────────────────── */
        .cat-preventive  { background: linear-gradient(135deg,#0f766e,#059386); color:#fff; }
        .cat-restorative { background: linear-gradient(135deg,#1d4ed8,#2563eb); color:#fff; }
        .cat-cosmetic    { background: linear-gradient(135deg,#be185d,#db2777); color:#fff; }
        .cat-orthodontics{ background: linear-gradient(135deg,#6d28d9,#7c3aed); color:#fff; }
        .cat-periodontics{ background: linear-gradient(135deg,#b91c1c,#dc2626); color:#fff; }
        .cat-specialty   { background: linear-gradient(135deg,#b45309,#d97706); color:#fff; }
        .cat-pediatric   { background: linear-gradient(135deg,#15803d,#16a34a); color:#fff; }
        .cat-consultation{ background: linear-gradient(135deg,#0f4c75,#1a6fa0); color:#fff; }
        .cat-default     { background: linear-gradient(135deg,#475569,#64748b); color:#fff; }

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
            position: relative;
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

        .progress-step-label.active {
            color: var(--dark-blue);
        }

        .progress-step-label.completed {
            color: var(--teal);
        }

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

        .progress-step-label.active .progress-step-icon {
            background: var(--dark-blue);
            color: #fff;
        }

        .progress-step-label.completed .progress-step-icon {
            background: var(--teal);
            color: #fff;
        }

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

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 900px) {
            .detail-wrapper {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto;
            }
            .doctor-panel {
                position: static;
                max-height: none;
            }
            .doctors-list {
                max-height: 500px;
            }
        }

        @media (max-width: 576px) {
            .benefits-grid { grid-template-columns: 1fr; }
            .service-title { font-size: 1.55rem; }
            .quick-stats { gap: 8px; }
            .content-section { padding: 18px 16px; }
            .content-divider { margin: 0 16px; }
            .service-header { padding: 18px 16px 0; }
            .quick-stats { padding: 0 16px 16px; }
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
            <div class="d-flex align-items-center gap-2" id="nav-auth-area">
                @auth
                    @php
                        $u = auth()->user();
                        $isAdminPatient = $u && $u->email === config('admin.email');
                        $dashHref = $isAdminPatient ? '/admin/dashboard' : '/patient/dashboard';
                        $displayName = $u?->name ?? 'My Account';
                    @endphp
                    <a href="{{ $dashHref }}" id="nav-user-btn" style="display:inline-flex; align-items:center; gap:9px; background:#fff; border:1.5px solid #fff; border-radius:50px; padding:5px 14px 5px 5px; text-decoration:none; transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.12);" title="My Dashboard"
                       onmouseover="this.style.background='#f0f6ff'" onmouseout="this.style.background='#fff'">
                        <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#059386,#003263); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="bi bi-person-fill" style="color:#fff; font-size:.95rem;"></i>
                        </div>
                        <span id="nav-user-name" style="color:#003263; font-size:.85rem; font-weight:600; max-width:110px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ explode(' ', $displayName)[0] }}</span>
                        <i class="bi bi-grid-fill" style="color:#059386; font-size:.75rem;"></i>
                    </a>
                @else
                    <a href="/login"    class="btn-nav-login" id="nav-login-btn">Login</a>
                    <a href="/register" class="btn-nav-signup" id="nav-signup-btn">Sign Up</a>
                    <a href="/patient/dashboard" id="nav-user-btn" style="display:none; align-items:center; gap:9px; background:#fff; border:1.5px solid #fff; border-radius:50px; padding:5px 14px 5px 5px; text-decoration:none; transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.12);" title="My Dashboard"
                       onmouseover="this.style.background='#f0f6ff'" onmouseout="this.style.background='#fff'">
                        <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#059386,#003263); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="bi bi-person-fill" style="color:#fff; font-size:.95rem;"></i>
                        </div>
                        <span id="nav-user-name" style="color:#003263; font-size:.85rem; font-weight:600; max-width:110px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">My Account</span>
                        <i class="bi bi-grid-fill" style="color:#059386; font-size:.75rem;"></i>
                    </a>
                @endauth
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
                <li class="breadcrumb-item active">{{ $service->name }}</li>
            </ol>
        </nav>
    </div>
</div>

@php
/* ─── Category → CSS class ─────────────────────────────── */
$catClassMap = [
    'Preventive Dentistry'  => 'cat-preventive',
    'Restorative Dentistry' => 'cat-restorative',
    'Cosmetic Dentistry'    => 'cat-cosmetic',
    'Orthodontics'          => 'cat-orthodontics',
    'Periodontics'          => 'cat-periodontics',
    'Specialty Services'    => 'cat-specialty',
    'Pediatric Dentistry'   => 'cat-pediatric',
    'Consultation'          => 'cat-consultation',
];
$catClass = $catClassMap[$service->category] ?? 'cat-default';

/* ─── Category → icon ───────────────────────────────────── */
$catIconMap = [
    'Preventive Dentistry'  => 'bi-shield-plus',
    'Restorative Dentistry' => 'bi-bandaid',
    'Cosmetic Dentistry'    => 'bi-stars',
    'Orthodontics'          => 'bi-align-center',
    'Periodontics'          => 'bi-heart-pulse',
    'Specialty Services'    => 'bi-hospital',
    'Pediatric Dentistry'   => 'bi-emoji-smile',
    'Consultation'          => 'bi-chat-dots',
];
$catIcon = $catIconMap[$service->category] ?? 'bi-tooth';

/* ─── Per-service rich content ──────────────────────────── */
$serviceContent = [

    'General Examination' => [
        'extended_description' => 'A comprehensive General Dental Examination is the cornerstone of preventive oral healthcare. During this thorough consultation, our experienced dentist performs a complete evaluation of your teeth, gums, bite, jaw, and overall oral health. Using state-of-the-art diagnostic tools and digital imaging, we identify potential issues at their earliest — and most treatable — stage. This appointment establishes your personalized treatment plan and forms the foundation of your long-term oral health journey with ShinyTooth.',
        'benefits' => [
            ['icon' => 'bi-search',          'text' => 'Early detection of cavities, gum disease & oral cancer'],
            ['icon' => 'bi-graph-up-arrow',  'text' => 'Personalized treatment plan tailored to your needs'],
            ['icon' => 'bi-camera',          'text' => 'Digital X-rays for complete internal assessment'],
            ['icon' => 'bi-heart-pulse',     'text' => 'Full gum health evaluation & pocket depth measurement'],
            ['icon' => 'bi-clipboard2-check','text' => 'Medical history review & medication interaction check'],
            ['icon' => 'bi-shield-check',    'text' => 'Oral cancer screening for peace of mind'],
        ],
        'steps' => [
            ['title' => 'Medical & Dental History Review',   'desc' => 'We review your health history, medications, and concerns — approx. 5 minutes.'],
            ['title' => 'Visual Teeth & Gums Examination',   'desc' => 'Thorough visual check of all teeth surfaces, gums, and bite alignment — approx. 10 minutes.'],
            ['title' => 'Digital X-Rays (if needed)',        'desc' => 'Low-radiation digital X-rays to reveal issues invisible to the naked eye — approx. 10 minutes.'],
            ['title' => 'Gum Pocket Depth Measurement',      'desc' => 'A gentle probe measures gum health and detects early periodontal disease — approx. 5 minutes.'],
            ['title' => 'Oral Cancer Screening',             'desc' => 'Non-invasive check of soft tissues for any unusual changes — approx. 5 minutes.'],
            ['title' => 'Treatment Plan & Discussion',       'desc' => 'We walk you through our findings and recommend next steps — approx. 10 minutes.'],
        ],
    ],

    'Professional Cleanings' => [
        'extended_description' => 'Professional dental cleaning (prophylaxis) goes far beyond a routine brush and rinse. Our dental hygienists use specialized ultrasonic and hand instruments to remove calculus (tartar) and plaque that accumulate in hard-to-reach areas despite daily brushing. The session concludes with a high-polish finish that leaves your teeth visibly brighter and smooth, making it harder for plaque to reattach. Regular cleanings every 6 months are one of the most effective investments you can make in your long-term oral and systemic health.',
        'benefits' => [
            ['icon' => 'bi-brush',           'text' => 'Removes tartar that brushing alone cannot eliminate'],
            ['icon' => 'bi-stars',           'text' => 'Polished finish for visibly brighter, smoother teeth'],
            ['icon' => 'bi-heart-pulse',     'text' => 'Prevents gum disease and reduces systemic health risks'],
            ['icon' => 'bi-wind',            'text' => 'Significantly freshens breath'],
            ['icon' => 'bi-eye',             'text' => 'Includes a brief oral health check-up'],
            ['icon' => 'bi-calendar-heart',  'text' => 'Recommended every 6 months for optimal oral health'],
        ],
        'steps' => [
            ['title' => 'Plaque & Tartar Assessment', 'desc' => 'Identify areas of calculus build-up before cleaning begins.'],
            ['title' => 'Ultrasonic Scaling',          'desc' => 'Vibrating tip loosens and removes heavy tartar deposits.'],
            ['title' => 'Hand Scaling',                'desc' => 'Fine instruments clear remaining deposits between teeth and along the gumline.'],
            ['title' => 'Gritty Paste Polish',         'desc' => 'High-speed polisher removes surface stains and smooths enamel.'],
            ['title' => 'Flossing & Rinse',            'desc' => 'Final interdental cleaning and antibacterial rinse.'],
            ['title' => 'Fluoride Application',        'desc' => 'Optional fluoride treatment to strengthen enamel after cleaning.'],
        ],
    ],

];

/* ─── Category-level fallback content ───────────────────── */
$categoryContent = [
    'Preventive Dentistry' => [
        'extended_description' => 'Preventive dentistry focuses on maintaining your oral health and stopping problems before they start. Our preventive services are designed to keep your teeth and gums healthy, saving you both discomfort and future treatment costs. We use the latest diagnostic technology to deliver thorough, comfortable care.',
        'benefits' => [
            ['icon' => 'bi-shield-check', 'text' => 'Stop dental problems before they develop'],
            ['icon' => 'bi-cash-coin',    'text' => 'Save money by avoiding costly future treatments'],
            ['icon' => 'bi-heart-pulse',  'text' => 'Maintain lifelong oral and overall health'],
            ['icon' => 'bi-emoji-smile',  'text' => 'Keep your natural smile looking its best'],
        ],
        'steps' => [
            ['title' => 'Initial Assessment',  'desc' => 'Reviewing your dental history and current oral health status.'],
            ['title' => 'Examination',         'desc' => 'Thorough clinical evaluation using the latest diagnostic technology.'],
            ['title' => 'Treatment',           'desc' => 'Gentle, precise preventive care tailored to your needs.'],
            ['title' => 'Home Care Advice',    'desc' => 'Personalized guidance for maintaining results at home.'],
        ],
    ],
    'Restorative Dentistry' => [
        'extended_description' => 'Restorative dentistry repairs and replaces damaged or missing teeth to restore full function, comfort, and aesthetics. Our skilled restorative team uses high-quality, natural-looking materials and proven techniques to bring your smile back to its best.',
        'benefits' => [
            ['icon' => 'bi-bandaid',       'text' => 'Restore full chewing function and comfort'],
            ['icon' => 'bi-stars',         'text' => 'Natural-looking results that blend with your smile'],
            ['icon' => 'bi-shield-check',  'text' => 'Prevent further damage to surrounding teeth'],
            ['icon' => 'bi-emoji-smile',   'text' => 'Regain confidence in your smile'],
        ],
        'steps' => [
            ['title' => 'Diagnosis & X-Rays', 'desc' => 'Full assessment of the affected area using digital imaging.'],
            ['title' => 'Treatment Planning', 'desc' => 'Choosing the right restorative approach for your situation.'],
            ['title' => 'Preparation',        'desc' => 'Careful preparation of the tooth or area with local anesthesia for comfort.'],
            ['title' => 'Restoration',        'desc' => 'Placing the filling, crown, or prosthetic with precision.'],
            ['title' => 'Bite Check & Polish','desc' => 'Ensuring correct fit, bite, and finishing touches.'],
        ],
    ],
    'Cosmetic Dentistry' => [
        'extended_description' => 'Cosmetic dentistry transforms the appearance of your smile through a range of aesthetic treatments. Whether you want a brighter, straighter, or more symmetrical smile, our cosmetic team combines artistry with advanced dental science to deliver stunning, long-lasting results.',
        'benefits' => [
            ['icon' => 'bi-stars',        'text' => 'Dramatically improve the appearance of your smile'],
            ['icon' => 'bi-person-check', 'text' => 'Boost self-confidence in social and professional settings'],
            ['icon' => 'bi-palette',      'text' => 'Customized to your unique facial features'],
            ['icon' => 'bi-clock',        'text' => 'Long-lasting, durable aesthetic results'],
        ],
        'steps' => [
            ['title' => 'Smile Consultation', 'desc' => 'Discussing your goals and ideal smile outcome.'],
            ['title' => 'Digital Smile Design','desc' => 'Previewing your results using digital planning tools.'],
            ['title' => 'Preparation',         'desc' => 'Gentle preparation of the teeth for the cosmetic procedure.'],
            ['title' => 'Treatment',           'desc' => 'Precise application of the cosmetic enhancement.'],
            ['title' => 'Review & Refinement', 'desc' => 'Final evaluation and any fine adjustments needed.'],
        ],
    ],
];

/* ─── Pick the best content available ───────────────────── */
$content = $serviceContent[$service->name]
         ?? $categoryContent[$service->category]
         ?? [
                'extended_description' => $service->description,
                'benefits' => [
                    ['icon' => 'bi-check-circle', 'text' => 'Professional, high-quality dental care'],
                    ['icon' => 'bi-shield-check',  'text' => 'Safe, comfortable treatment environment'],
                    ['icon' => 'bi-person-badge',  'text' => 'Performed by experienced specialists'],
                    ['icon' => 'bi-stars',          'text' => 'Modern equipment and techniques'],
                ],
                'steps' => [
                    ['title' => 'Consultation', 'desc' => 'Initial discussion and assessment of your needs.'],
                    ['title' => 'Preparation',  'desc' => 'Preparing for the procedure safely and comfortably.'],
                    ['title' => 'Treatment',    'desc' => 'The procedure is performed with precision and care.'],
                    ['title' => 'Aftercare',    'desc' => 'Post-treatment instructions and follow-up plan.'],
                ],
           ];
@endphp

{{-- PROGRESS BAR --}}
<section class="progress-section">
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">Booking Progress</span>
            <span style="font-size:.9rem; color:#94a3b8; font-weight:600;">Step <strong style="color:var(--dark-blue);">2</strong> of <strong>6</strong></span>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed"></div>
            <div class="progress-step active"></div>
            <div class="progress-step"></div>
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
                <div class="progress-step-label active">
                    <div class="progress-step-icon">2</div>
                    <span>Doctor</span>
                </div>
            </div>
            <div class="step-label-item">
                <div class="progress-step-label">
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

{{-- MAIN CONTENT --}}
<div class="detail-wrapper">

    {{-- ═══════════════════════════════════════════════
         LEFT — SERVICE INFORMATION
    ═══════════════════════════════════════════════ --}}
    <div class="service-info-left">
        <div class="service-content-card">

            {{-- Image / Placeholder --}}
            @if ($service->image)
                <img src="{{ asset($service->image) }}"
                     alt="{{ $service->name }}"
                     class="service-image-hero">
            @else
                <div class="service-image-placeholder {{ $catClass }}">
                    <i class="bi {{ $catIcon }}"></i>
                </div>
            @endif

            {{-- Header --}}
            <div class="service-header">
                <span class="category-badge-detail {{ $catClass }}">
                    <i class="bi {{ $catIcon }}"></i>
                    {{ $service->category }}
                </span>

                <h1 class="service-title">{{ $service->name }}</h1>

                @if ($service->is_special_offer)
                <div class="special-offer-banner">
                    <i class="bi bi-fire" style="color:#f59e0b; font-size:1.1rem;"></i>
                    <span>Special Offer Available — Limited Time Discount</span>
                </div>
                @endif
            </div>

            {{-- Quick Stats --}}
            <div class="quick-stats">
                <div class="stat-chip price-chip">
                    <i class="bi bi-tag-fill"></i>
                    <div>
                        <span class="stat-label">Starting from</span>
                        <span class="stat-value">
                            {{ $service->price == 0 ? 'Free' : '$' . number_format($service->price, 0) }}
                        </span>
                    </div>
                </div>
                <div class="stat-chip">
                    <i class="bi bi-clock-fill"></i>
                    <div>
                        <span class="stat-label">Duration</span>
                        <span class="stat-value">{{ $service->duration_minutes }} min</span>
                    </div>
                </div>
                <div class="stat-chip">
                    <i class="bi bi-folder2"></i>
                    <div>
                        <span class="stat-label">Category</span>
                        <span class="stat-value" style="font-size:.88rem;">{{ $service->category }}</span>
                    </div>
                </div>
                <div class="stat-chip">
                    <i class="bi bi-calendar-check-fill"></i>
                    <div>
                        <span class="stat-label">Step</span>
                        <span class="stat-value" style="font-size:.88rem;">1 of 3</span>
                    </div>
                </div>
            </div>

            <hr class="content-divider">

            {{-- Description --}}
            <div class="content-section">
                <h5 class="section-heading">
                    <i class="bi bi-file-text-fill"></i>
                    About This Service
                </h5>
                <p class="description-text">{{ $content['extended_description'] }}</p>
            </div>

            <hr class="content-divider">

            {{-- Benefits --}}
            <div class="content-section">
                <h5 class="section-heading">
                    <i class="bi bi-check-circle-fill"></i>
                    Key Benefits
                </h5>
                <div class="benefits-grid">
                    @foreach ($content['benefits'] as $benefit)
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi {{ $benefit['icon'] }}"></i>
                        </div>
                        <span class="benefit-text">{{ $benefit['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr class="content-divider">

            {{-- What to Expect --}}
            <div class="content-section">
                <h5 class="section-heading">
                    <i class="bi bi-list-ol"></i>
                    What to Expect
                </h5>
                <div class="steps-list">
                    @foreach ($content['steps'] as $index => $step)
                    <div class="step-item">
                        <div class="step-number">{{ $index + 1 }}</div>
                        <div class="step-content">
                            <div class="step-title">{{ $step['title'] }}</div>
                            <div class="step-desc">{{ $step['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         RIGHT — DOCTOR PANEL
    ═══════════════════════════════════════════════ --}}
    <div class="doctor-panel">

        <div class="panel-header">
            <h3><i class="bi bi-person-lines-fill me-2"></i>Choose Your Dentist</h3>
            <p class="panel-subtitle">Select a specialist to continue booking</p>
            <div class="available-count-badge">
                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                {{ $dentists->count() }} specialist{{ $dentists->count() !== 1 ? 's' : '' }} available
            </div>
        </div>

        <div class="doctors-list">
            @forelse ($dentists as $dentist)
            <div class="doctor-card">
                <div class="doctor-card-top">
                    {{-- Photo --}}
                    <div class="doctor-photo-wrap">
                        @if ($dentist->image)
                            <img src="{{ asset($dentist->image) }}"
                                 alt="{{ $dentist->name }}"
                                 class="doctor-photo">
                        @else
                            <div class="doctor-photo-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                        <div class="availability-dot" title="Available Now"></div>
                    </div>

                    {{-- Info --}}
                    <div class="doctor-name-area">
                        <p class="doctor-name">{{ $dentist->name }}</p>

                        {{-- Specialization badges --}}
                        <div class="doctor-specializations">
                            @foreach (explode(', ', $dentist->specializations_list) as $spec)
                            <span class="spec-badge">{{ trim($spec) }}</span>
                            @endforeach
                        </div>

                        {{-- Stars --}}
                        <div class="doctor-stars">
                            @php $stars = round($dentist->avg_rating); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $stars ? '-fill' : '' }}"></i>
                            @endfor
                            <span class="rating-text">
                                {{ number_format($dentist->avg_rating, 1) }}
                                ({{ $dentist->rating_count }} review{{ $dentist->rating_count !== 1 ? 's' : '' }})
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="doctor-meta">
                    <span class="meta-chip">
                        <i class="bi bi-briefcase-fill"></i>
                        {{ $dentist->experience_years }} yrs experience
                    </span>
                    @if ($dentist->university)
                    <span class="meta-chip">
                        <i class="bi bi-mortarboard-fill"></i>
                        {{ $dentist->university }}
                    </span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mb-10">
                    <span class="available-badge">
                        <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                        Available Now
                    </span>
                </div>

                {{-- Select button → goes to booking step 2 --}}
                <a href="/book?service={{ $service->id }}&dentist={{ $dentist->id }}"
                   class="btn-select-doctor mt-2"
                   data-requires-auth="1">
                    Select This Doctor <i class="bi bi-arrow-right-circle-fill"></i>
                </a>
            </div>
            @empty
            <div class="no-doctors-msg">
                <i class="bi bi-calendar-x"></i>
                <p><strong>No available doctors right now</strong></p>
                <p class="mt-2" style="font-size:.82rem;">All doctors specializing in {{ $service->category }} are currently with patients. Please check back shortly.</p>
            </div>
            @endforelse
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
                    <span>Mon – Sat &nbsp;|&nbsp; 8:00 AM – 7:00 PM</span>
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
    window.SERVER_LOGGED_IN = @json(auth()->check());
    /* Sticky nav shrink on scroll */
    window.addEventListener('scroll', () => {
        document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
    });

    /* Navbar auth (client token fallback) */
    (function () {
        if (window.SERVER_LOGGED_IN) return;

        function syncClientAuth() {
            ['auth_token','user_type','user_role','user_data'].forEach((k) => {
                try {
                    const v = localStorage.getItem(k);
                    if (v !== null && sessionStorage.getItem(k) === null) {
                        sessionStorage.setItem(k, v);
                    }
                } catch (e) {}
            });
        }
        syncClientAuth();

        const token = sessionStorage.getItem('auth_token') || localStorage.getItem('auth_token');
        if (!token) return;

        const loginBtn  = document.getElementById('nav-login-btn');
        const signupBtn = document.getElementById('nav-signup-btn');
        const userBtn   = document.getElementById('nav-user-btn');
        if (loginBtn)  loginBtn.style.display  = 'none';
        if (signupBtn) signupBtn.style.display = 'none';
        if (userBtn)   userBtn.style.display = 'inline-flex';

        try {
            const raw = sessionStorage.getItem('user_data') || localStorage.getItem('user_data') || '{}';
            const userData = JSON.parse(raw);
            const firstName = (userData.name || '').split(' ')[0];
            const nameEl = document.getElementById('nav-user-name');
            if (nameEl && firstName) nameEl.textContent = firstName;
        } catch (e) {}

        const userType = sessionStorage.getItem('user_type') || sessionStorage.getItem('user_role')
            || localStorage.getItem('user_type') || localStorage.getItem('user_role');
        if (userBtn && userType === 'dentist') {
            userBtn.href = '/doctor/dashboard';
        }
    })();

    /* Block booking for guests */
    (function () {
        const requiresAuthLinks = document.querySelectorAll('a[data-requires-auth="1"]');
        if (!requiresAuthLinks.length) return;

        function isLoggedIn() {
            try {
                if (window.SERVER_LOGGED_IN) return true;
                return !!(sessionStorage.getItem('auth_token') || localStorage.getItem('auth_token'));
            } catch (e) {
                return false;
            }
        }

        const modalEl = document.getElementById('authRequiredModal');
        const authModal = modalEl ? new bootstrap.Modal(modalEl) : null;

        requiresAuthLinks.forEach((link) => {
            link.addEventListener('click', (e) => {
                if (isLoggedIn()) return;

                e.preventDefault();
                e.stopPropagation();

                // Set return URL so after login/register user can continue
                const returnUrlInput = document.getElementById('authReturnUrl');
                if (returnUrlInput) returnUrlInput.value = link.getAttribute('href') || '/';

                if (authModal) authModal.show();
                else alert('You need to create an account or log in to book an appointment.');
            });
        });
    })();
</script>

<!-- Auth Required Modal -->
<div class="modal fade" id="authRequiredModal" tabindex="-1" aria-labelledby="authRequiredModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px; overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(90deg,#003263,#059386); border:none;">
        <h5 class="modal-title text-white fw-bold" id="authRequiredModalLabel">
          Sign in required
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="padding:18px 20px;">
        <p class="mb-0" style="color:#475569;">
          You need to <strong>create an account</strong> or <strong>log in</strong> to book an appointment.
        </p>
        <input type="hidden" id="authReturnUrl" value="/" />
      </div>
      <div class="modal-footer" style="border:none; padding:14px 20px 18px;">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Not now</button>
        <a class="btn btn-outline-primary" href="/login" id="authLoginLink">Log in</a>
        <a class="btn btn-primary" href="/register" id="authRegisterLink">Sign up</a>
      </div>
    </div>
  </div>
</div>

<script>
  // Append return URL to auth links when modal opens
  (function () {
    const modalEl = document.getElementById('authRequiredModal');
    if (!modalEl) return;
    modalEl.addEventListener('show.bs.modal', () => {
      const returnUrl = document.getElementById('authReturnUrl')?.value || '/';
      const login = document.getElementById('authLoginLink');
      const reg = document.getElementById('authRegisterLink');
      if (login) login.href = '/login?return=' + encodeURIComponent(returnUrl);
      if (reg) reg.href = '/register?return=' + encodeURIComponent(returnUrl);
    });
  })();
</script>

</body>
</html>

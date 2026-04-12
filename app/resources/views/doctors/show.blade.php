<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $dentist->name }} — ShinyTooth</title>

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
            overflow-x: hidden;
            background: #f6fbfa;
        }

        /* ── NAVBAR ──────────────────────────────── */
        .main-nav {
            background: linear-gradient(90deg, #003263 0%, #059386 100%);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: padding .3s ease, box-shadow .3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,.15);
        }
        .main-nav.scrolled {
            padding: 8px 0;
            box-shadow: 0 4px 24px rgba(0,0,0,.25);
        }
        .nav-link-custom {
            color: rgba(255,255,255,.88) !important;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: background .2s, color .2s;
            text-decoration: none;
        }
        .nav-link-custom:hover {
            color: #fff !important;
            background: rgba(255,255,255,.12);
        }
        .btn-nav-login {
            border: 2px solid rgba(255,255,255,.75);
            color: #fff;
            border-radius: 25px;
            padding: 10px 28px;
            font-weight: 600;
            background: transparent;
            text-decoration: none;
            transition: all .2s;
            font-size: 1rem;
        }
        .btn-nav-login:hover {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .btn-nav-signup {
            background: #fff;
            color: var(--dark-blue) !important;
            border-radius: 25px;
            padding: 10px 28px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s;
            font-size: 1rem;
        }
        .btn-nav-signup:hover {
            background: #dff6f3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

        /* ── PROFILE HERO ────────────────────────── */
        .profile-hero {
            background: linear-gradient(135deg, #002050 0%, #003263 45%, #047a6e 80%, #059386 100%);
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
        }
        .profile-hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,.035);
            border-radius: 50%;
            pointer-events: none;
        }
        .profile-hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: 10%;
            width: 340px;
            height: 340px;
            background: rgba(255,255,255,.025);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ── PROFILE CARD ────────────────────────── */
        .profile-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(0,50,99,.12);
            margin-top: -80px;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }
        .profile-top {
            background: linear-gradient(135deg, #003263, #059386);
            padding: 40px 40px 60px;
            position: relative;
        }
        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: #f0f0f0;
            border: 5px solid #fff;
            box-shadow: 0 6px 24px rgba(0,0,0,.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: var(--teal);
            overflow: hidden;
            margin-bottom: -70px;
            position: relative;
            z-index: 12;
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }
        .profile-body {
            padding: 90px 40px 40px;
        }

        /* ── STAT BADGES ─────────────────────────── */
        .stat-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f9fafb;
            border-radius: 16px;
            padding: 16px 20px;
            transition: transform .2s;
        }
        .stat-badge:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-num { font-size: 1.35rem; font-weight: 800; color: var(--dark-blue); line-height: 1; }
        .stat-label { font-size: .78rem; color: #888; margin-top: 2px; }

        /* ── TAGS ─────────────────────────────────── */
        .specialty-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e6f5f3;
            color: var(--teal);
            font-size: .82rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
        }
        .stars { color: #f5a623; font-size: 1rem; }

        /* ── INFO LIST ────────────────────────────── */
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #e6f5f3;
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            flex-shrink: 0;
        }

        /* ── SUBSCRIBE BUTTON ────────────────────── */
        .btn-subscribe {
            background: linear-gradient(135deg, #07b89e, #059386);
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 16px 40px;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: .2px;
            transition: all .25s;
            box-shadow: 0 6px 20px rgba(5,147,134,.35);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-subscribe:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(5,147,134,.45);
            color: #fff;
        }

        /* ── REVIEW CARD ─────────────────────────── */
        .review-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,50,99,.06);
            padding: 24px;
            transition: transform .2s;
        }
        .review-card:hover { transform: translateY(-4px); }
        .reviewer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e6f5f3, #d4ede8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teal);
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ── SIDEBAR CARD ────────────────────────── */
        .sidebar-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 22px rgba(0,50,99,.08);
            padding: 28px;
        }

        /* ── POPUP (subscribe-ineligible) ────────── */
        .sub-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,20,50,.55);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }
        .sub-popup-overlay.show {
            opacity: 1;
            pointer-events: all;
        }
        .sub-popup {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            max-width: 440px;
            width: 90%;
            padding: 36px 32px;
            text-align: center;
            transform: scale(.92) translateY(20px);
            transition: transform .35s ease;
        }
        .sub-popup-overlay.show .sub-popup {
            transform: scale(1) translateY(0);
        }
        .sub-popup-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fff4e5, #ffe6c0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #f5a623;
            margin: 0 auto 18px;
        }
        .sub-popup h4 {
            color: var(--dark-blue);
            font-weight: 800;
            margin-bottom: 8px;
        }
        .sub-popup p {
            color: #666;
            font-size: .92rem;
            margin-bottom: 24px;
        }
        .btn-popup-book {
            background: linear-gradient(135deg, #07b89e, #059386);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 13px 28px;
            font-weight: 700;
            font-size: .95rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-popup-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5,147,134,.35);
            color: #fff;
        }
        .btn-popup-dismiss {
            background: none;
            border: 2px solid #e0e4ea;
            border-radius: 14px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: .95rem;
            color: #888;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-popup-dismiss:hover {
            border-color: #ccc;
            color: #555;
        }

        /* ── FOOTER ──────────────────────────────── */
        .main-footer {
            background: linear-gradient(135deg, #001a40 0%, #002a55 55%, #033d35 100%);
            color: rgba(255,255,255,.75);
            padding: 72px 0 30px;
        }
        .footer-brand { color: #fff; font-size: 1.35rem; font-weight: 800; }
        .footer-h { color: #fff; font-size: .9rem; font-weight: 700; letter-spacing: .5px; margin-bottom: 18px; }
        .footer-link {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            display: block;
            margin-bottom: 9px;
            font-size: .88rem;
            transition: color .2s, padding-left .2s;
        }
        .footer-link:hover { color: #7de8dc; padding-left: 5px; }
        .footer-hr { border-color: rgba(255,255,255,.1); margin: 32px 0; }
        .social-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: 1rem;
            transition: all .25s;
        }
        .social-icon:hover {
            background: var(--teal);
            color: #fff;
            transform: translateY(-3px);
        }

        /* ── ANIMATIONS ──────────────────────────── */
        .fade-up {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .65s ease, transform .65s ease;
        }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: .08s; }
        .delay-2 { transition-delay: .16s; }
        .delay-3 { transition-delay: .24s; }
        .delay-4 { transition-delay: .32s; }
    </style>
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="main-nav" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo" height="88"
                     style="border-radius:8px; object-fit:contain;">
                <span style="color:#fff; font-size:1.3rem; font-weight:800; letter-spacing:-.3px;">ShinyTooth</span>
            </a>
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="/services" class="nav-link-custom">Services</a>
                <a href="/doctors"  class="nav-link-custom" style="color:#fff !important; background:rgba(255,255,255,.12);">Doctors</a>
                <a href="/#who-we-are" class="nav-link-custom">Who are we</a>
                <a href="/#contact"    class="nav-link-custom">Contact us</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="/login"    class="btn-nav-login">Login</a>
                <a href="/register" class="btn-nav-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

{{-- ═══ HERO (gradient backdrop) ═══ --}}
<section class="profile-hero">
    <div class="container">
        <a href="/doctors" class="d-inline-flex align-items-center gap-1 text-decoration-none"
           style="color:rgba(255,255,255,.65); font-size:.9rem; font-weight:500;">
            <i class="bi bi-arrow-left"></i> Back to All Doctors
        </a>
    </div>
</section>

{{-- ═══ PROFILE CARD ═══ --}}
@php
    $avgRating = $dentist->ratings_avg_rating ? round($dentist->ratings_avg_rating, 1) : 0;
    $fullStars = floor($avgRating);
    $halfStar  = ($avgRating - $fullStars) >= 0.5;
@endphp

<div class="container" style="margin-bottom:80px;">
    <div class="profile-card fade-up">
        <div class="profile-top">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-end gap-4">
                    <div class="profile-avatar">
                        @if ($dentist->image)
                            <img src="{{ asset($dentist->image) }}" alt="{{ $dentist->name }}">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>
                </div>
                <div class="text-end" style="padding-bottom:12px;">
                    @if ($avgRating >= 4.5)
                        <span style="display:inline-flex; align-items:center; gap:5px; background:rgba(255,255,255,.18); backdrop-filter:blur(6px); color:#fff; font-size:.78rem; font-weight:700; padding:6px 14px; border-radius:20px;">
                            <i class="bi bi-trophy-fill"></i> Top Rated
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-body">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-0" style="color:var(--dark-blue);">{{ $dentist->name }}</h2>

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mb-3">
                        @forelse ($dentist->specializations as $spec)
                            <span class="specialty-tag"><i class="bi bi-award-fill me-1" style="font-size:.7rem;"></i>{{ $spec->name }}</span>
                        @empty
                            <span class="specialty-tag">General Dentistry</span>
                        @endforelse
                    </div>

                    <div class="d-flex align-items-center gap-1 mb-4">
                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullStars)
                                    <i class="bi bi-star-fill"></i>
                                @elseif ($halfStar && $i == $fullStars + 1)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="ms-1 fw-bold" style="color:var(--dark-blue);">{{ $avgRating > 0 ? $avgRating : 'New' }}</span>
                        <span class="text-muted" style="font-size:.88rem;">({{ $dentist->ratings_count }} {{ Str::plural('review', $dentist->ratings_count) }})</span>
                    </div>

                    {{-- ── STAT BADGES ── --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="stat-badge">
                                <div class="stat-icon" style="background:#e6f5f3; color:var(--teal);">
                                    <i class="bi bi-calendar2-check-fill"></i>
                                </div>
                                <div>
                                    <div class="stat-num">{{ $dentist->experience_years }}+</div>
                                    <div class="stat-label">Years Exp.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-badge">
                                <div class="stat-icon" style="background:#fff4e5; color:#f5a623;">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <div class="stat-num">{{ $avgRating > 0 ? $avgRating : '—' }}</div>
                                    <div class="stat-label">Avg Rating</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-badge">
                                <div class="stat-icon" style="background:#eaf0ff; color:var(--dark-blue);">
                                    <i class="bi bi-chat-square-quote-fill"></i>
                                </div>
                                <div>
                                    <div class="stat-num">{{ $dentist->ratings_count }}</div>
                                    <div class="stat-label">Reviews</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── CAREER DESCRIPTION ── --}}
                    @if ($dentist->career_description)
                        <div class="mb-4 p-4" style="background:#f9fafb; border-radius:14px; border-left:4px solid var(--teal);">
                            <h6 class="fw-bold mb-2" style="color:var(--dark-blue);">
                                <i class="bi bi-briefcase-fill me-1" style="color:var(--teal);"></i> Career Overview
                            </h6>
                            <p class="mb-0" style="color:#555; font-size:.92rem; line-height:1.75;">
                                {{ $dentist->career_description }}
                            </p>
                        </div>
                    @endif

                    {{-- ── INFO DETAILS ── --}}
                    <div class="mb-1">
                        @if ($dentist->university)
                            <div class="info-row">
                                <div class="info-icon"><i class="bi bi-mortarboard-fill"></i></div>
                                <div>
                                    <div class="text-muted" style="font-size:.78rem;">Education</div>
                                    <div class="fw-semibold" style="color:var(--dark-blue);">{{ $dentist->university }}</div>
                                </div>
                            </div>
                        @endif
                        @if ($dentist->nationality)
                            <div class="info-row">
                                <div class="info-icon"><i class="bi bi-globe-americas"></i></div>
                                <div>
                                    <div class="text-muted" style="font-size:.78rem;">Nationality</div>
                                    <div class="fw-semibold" style="color:var(--dark-blue);">{{ $dentist->nationality }}</div>
                                </div>
                            </div>
                        @endif
                        @if ($dentist->place_of_birth)
                            <div class="info-row">
                                <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <div class="text-muted" style="font-size:.78rem;">Place of Birth</div>
                                    <div class="fw-semibold" style="color:var(--dark-blue);">{{ $dentist->place_of_birth }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── SIDEBAR ── --}}
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="sidebar-card text-center">
                        <div style="font-size:.78rem; color:#888; font-weight:600; letter-spacing:.4px; margin-bottom:6px;">SUBSCRIBE TO</div>
                        <h5 class="fw-bold mb-1" style="color:var(--dark-blue);">{{ $dentist->name }}</h5>
                        <p class="text-muted mb-4" style="font-size:.85rem;">
                            Get a personalized treatment plan and dedicated care from this specialist.
                        </p>
                        <button class="btn-subscribe w-100" id="subscribeBtn"
                                data-dentist-id="{{ $dentist->id }}">
                            <i class="bi bi-heart-pulse-fill"></i> Subscribe Now
                        </button>

                        <div class="mt-3 pt-3" style="border-top:1px solid #f0f2f5;">
                            <a href="/services" class="text-decoration-none d-flex align-items-center justify-content-center gap-1"
                               style="color:var(--teal); font-size:.88rem; font-weight:600;">
                                <i class="bi bi-calendar-plus"></i> Or book an appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ REVIEWS SECTION ═══ --}}
    <section class="mt-5" id="reviews">
        <div class="d-flex align-items-center justify-content-between mb-4 fade-up">
            <h4 class="fw-bold mb-0" style="color:var(--dark-blue);">
                <i class="bi bi-chat-square-quote me-2" style="color:var(--teal);"></i>Patient Reviews
            </h4>
            @if ($dentist->ratings_count > 0)
                <span class="text-muted" style="font-size:.88rem;">{{ $dentist->ratings_count }} total</span>
            @endif
        </div>

        @forelse ($reviews as $rIndex => $review)
            <div class="review-card mb-3 fade-up delay-{{ ($rIndex % 4) + 1 }}">
                <div class="d-flex align-items-start gap-3">
                    <div class="reviewer-avatar">
                        {{ strtoupper(substr($review->patient->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="fw-bold" style="color:var(--dark-blue);">{{ $review->patient->name ?? 'Anonymous' }}</span>
                                <span class="text-muted ms-2" style="font-size:.8rem;">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="stars" style="font-size:.82rem;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if ($review->review)
                            <p class="mb-0 mt-2" style="color:#555; font-size:.92rem; line-height:1.65;">{{ $review->review }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 fade-up" style="background:#fff; border-radius:18px;">
                <i class="bi bi-chat-square-dots" style="font-size:2.5rem; color:#ddd;"></i>
                <p class="text-muted mt-2 mb-0">No reviews yet. Be the first to leave a review!</p>
            </div>
        @endforelse
    </section>
</div>

{{-- ═══ SUBSCRIBE POPUP (ineligible) ═══ --}}
<div class="sub-popup-overlay" id="subPopupOverlay">
    <div class="sub-popup">
        <div class="sub-popup-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h4>Almost There!</h4>
        <p>You need to attend at least one appointment before subscribing to a doctor.<br>Book a free 5-minute consultation to get started!</p>
        <div class="d-flex flex-column gap-2">
            <a href="/services/35" class="btn-popup-book">
                <i class="bi bi-calendar-check-fill"></i> Book Free Consultation
            </a>
            <button class="btn-popup-dismiss" onclick="closeSubPopup()">No thanks</button>
        </div>
    </div>
</div>

{{-- ═══ SUBSCRIBE FORM (hidden, for eligible patients) ═══ --}}
<form id="subscribeForm" action="/subscriptions/request" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="dentist_id" value="{{ $dentist->id }}">
    {{-- patient_id will be set via JS when auth is available --}}
    <input type="hidden" name="patient_id" id="subPatientId" value="">
</form>

{{-- ═══ FOOTER ═══ --}}
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth" height="80"
                         style="border-radius:8px; object-fit:contain;">
                    <span class="footer-brand">ShinyTooth</span>
                </div>
                <p style="color:rgba(255,255,255,.58); font-size:.88rem; line-height:1.75;">
                    Your trusted dental care partner. We combine world-class expertise with
                    a warm, welcoming environment.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="footer-h">Quick Links</h6>
                <a href="/"        class="footer-link">Home</a>
                <a href="/doctors" class="footer-link">Our Doctors</a>
                <a href="/services" class="footer-link">Services</a>
                <a href="/login"   class="footer-link">Patient Portal</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-h">Services</h6>
                <a href="/services" class="footer-link">Dental Cleanings</a>
                <a href="/services" class="footer-link">Oral Exams</a>
                <a href="/services" class="footer-link">Fillings</a>
                <a href="/services" class="footer-link">All Services &rarr;</a>
            </div>
            <div class="col-lg-3">
                <h6 class="footer-h">Contact Us</h6>
                <div class="d-flex align-items-start gap-2 mb-2" style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-geo-alt-fill mt-1" style="color:var(--teal); flex-shrink:0;"></i>
                    <span>123 Dental Avenue, Health City, HC 10001</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2" style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-telephone-fill" style="color:var(--teal);"></i>
                    <span>+1 (800) 744-6983</span>
                </div>
                <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.6); font-size:.87rem;">
                    <i class="bi bi-envelope-fill" style="color:var(--teal);"></i>
                    <span>hello@shinytooth.com</span>
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
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ── NAVBAR SCROLL ──────────────────────────────────── */
window.addEventListener('scroll', function () {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 30);
});

/* ── FADE-IN OBSERVER ───────────────────────────────── */
(function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.fade-up').forEach(function (el) {
        observer.observe(el);
    });
})();

/* ── SUBSCRIBE BUTTON LOGIC ─────────────────────────── */
(function () {
    var btn     = document.getElementById('subscribeBtn');
    var overlay = document.getElementById('subPopupOverlay');
    var form    = document.getElementById('subscribeForm');

    // Get patient_id from URL query param (?patient=...) for now; auth later
    var params    = new URLSearchParams(window.location.search);
    var patientId = params.get('patient');

    btn.addEventListener('click', function () {
        if (!patientId) {
            // Not logged in or no patient param — show ineligible popup
            overlay.classList.add('show');
            return;
        }

        // Check eligibility via a quick GET to see if patient has attended an appointment
        fetch('/api/check-eligibility?patient=' + encodeURIComponent(patientId))
            .then(function (r) { return r.json(); })
            .catch(function () { return { eligible: false }; })
            .then(function (data) {
                if (data && data.eligible) {
                    document.getElementById('subPatientId').value = patientId;
                    form.submit();
                } else {
                    overlay.classList.add('show');
                }
            });
    });

    // Close popup on overlay click (outside popup)
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeSubPopup();
    });
})();

function closeSubPopup() {
    document.getElementById('subPopupOverlay').classList.remove('show');
}
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Dentists — ShinyTooth</title>

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

        /* ── HERO BANNER ─────────────────────────── */
        .doctors-hero {
            background: linear-gradient(135deg, #002050 0%, #003263 45%, #047a6e 80%, #059386 100%);
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }
        .doctors-hero::before {
            content: '';
            position: absolute;
            top: -25%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
            pointer-events: none;
        }
        .doctors-hero h1 {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -.5px;
        }
        .doctors-hero p {
            color: rgba(255,255,255,.7);
            font-size: 1.1rem;
            max-width: 600px;
        }
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 28px;
        }
        .hero-stat-num {
            color: #07c5b3;
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
        }
        .hero-stat-label {
            color: rgba(255,255,255,.55);
            font-size: .82rem;
            margin-top: 4px;
        }

        /* ── SEARCH / FILTER BAR ─────────────────── */
        .filter-bar {
            background: #fff;
            border-radius: 16px;
            padding: 20px 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
            margin-top: -36px;
            position: relative;
            z-index: 10;
        }
        .search-input {
            border: 2px solid #e8edf2;
            border-radius: 12px;
            padding: 10px 16px 10px 44px;
            font-size: .95rem;
            width: 100%;
            transition: border-color .2s;
            background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 16px center;
        }
        .search-input:focus {
            border-color: var(--teal);
            outline: none;
            box-shadow: 0 0 0 3px rgba(5,147,134,.1);
        }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 16px;
            border: 2px solid #e8edf2;
            border-radius: 25px;
            background: #fff;
            font-size: .85rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all .2s;
        }
        .filter-chip:hover, .filter-chip.active {
            border-color: var(--teal);
            background: #e6f5f3;
            color: var(--teal);
        }

        /* ── DOCTOR CARDS ────────────────────────── */
        .doctor-card {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 4px 22px rgba(0,50,99,.09);
            transition: transform .3s ease, box-shadow .3s ease;
            position: relative;
            height: 100%;
            cursor: pointer;
        }
        .doctor-card:hover {
            transform: translateY(-9px);
            box-shadow: 0 18px 44px rgba(0,50,99,.17);
        }
        .doctor-card-header {
            background: linear-gradient(135deg, #003263, #059386);
            height: 100px;
            position: relative;
        }
        .doctor-card-badge {
            position: absolute;
            top: 12px;
            right: 14px;
            background: rgba(255,255,255,.22);
            backdrop-filter: blur(6px);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: .3px;
        }
        .doctor-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #f0f0f0;
            border: 4px solid #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: var(--teal);
            margin: -48px auto 0;
            overflow: hidden;
            position: relative;
            z-index: 10;
        }
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }
        .stars { color: #f5a623; font-size: .85rem; }
        .specialty-tag {
            display: inline-block;
            background: #e6f5f3;
            color: var(--teal);
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .btn-view-profile {
            background: linear-gradient(90deg, var(--dark-blue), var(--teal));
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 32px;
            font-size: .9rem;
            font-weight: 700;
            transition: all .2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-view-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5,147,134,.35);
            color: #fff;
        }
        .btn-subscribe-card {
            background: linear-gradient(90deg, #07b89e, var(--teal));
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 32px;
            font-size: .9rem;
            font-weight: 700;
            transition: all .2s;
            display: inline-block;
            cursor: pointer;
        }
        .btn-subscribe-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5,147,134,.35);
            color: #fff;
        }
        .btn-view-profile, .btn-subscribe-card {
            pointer-events: auto !important;
        }

        /* ── RESULTS COUNT ───────────────────────── */
        .results-count {
            color: #666;
            font-size: .9rem;
        }
        .results-count strong {
            color: var(--dark-blue);
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

        /* ── NO RESULTS ──────────────────────────── */
        .no-results {
            text-align: center;
            padding: 60px 20px;
        }
        .no-results-icon {
            font-size: 3.5rem;
            color: #ccc;
            margin-bottom: 16px;
        }
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

{{-- ═══ HERO BANNER ═══ --}}
<section class="doctors-hero">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.1); backdrop-filter:blur(6px); color:#07c5b3; font-size:.78rem; font-weight:700; padding:6px 16px; border-radius:20px; margin-bottom:18px; letter-spacing:.4px;">
                    <i class="bi bi-people-fill"></i> OUR DENTAL TEAM
                </div>
                <h1>Meet Our Expert<br>Dentists</h1>
                <p>
                    Our team of highly qualified dental professionals is dedicated to providing
                    exceptional care. Find the right specialist for your needs and book your appointment.
                </p>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-num">{{ $dentists->count() }}+</div>
                        <div class="hero-stat-label">Specialists</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">20K+</div>
                        <div class="hero-stat-label">Happy Patients</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">15+</div>
                        <div class="hero-stat-label">Specializations</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-end">
                <img src="{{ asset('images/BlueToothGivingThumbsUp.png') }}" alt="Dentist"
                     style="height:280px; filter:drop-shadow(0 16px 40px rgba(0,0,0,.3)); animation: float 3s ease-in-out infinite;">
            </div>
        </div>
    </div>
</section>

{{-- ═══ FILTER BAR ═══ --}}
<div class="container">
    <div class="filter-bar">
        <div class="row align-items-center g-3">
            <div class="col-lg-5">
                <input type="text" id="searchInput" class="search-input" placeholder="Search by name or specialization...">
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2">
                    <span class="filter-chip active" data-filter="all">
                        <i class="bi bi-grid-fill"></i> All
                    </span>
                    @php
                        $allSpecs = $dentists->flatMap(fn($d) => $d->specializations->pluck('name'))->unique()->sort();
                    @endphp
                    @foreach ($allSpecs as $spec)
                        <span class="filter-chip" data-filter="{{ $spec }}">{{ $spec }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ DOCTORS GRID ═══ --}}
<section style="padding: 40px 0 80px;">
    <div class="container">
        <div class="results-count mb-4 fade-up">
            Showing <strong id="resultCount">{{ $dentists->count() }}</strong> dentists
        </div>

        <div class="row g-4" id="doctorsGrid">
            @foreach ($dentists as $index => $dentist)
                @php
                    $avgRating = $dentist->ratings_avg_rating ? round($dentist->ratings_avg_rating, 1) : 0;
                    $fullStars = floor($avgRating);
                    $halfStar  = ($avgRating - $fullStars) >= 0.5;
                @endphp
                <div class="col-md-6 col-lg-4 fade-up delay-{{ ($index % 4) + 1 }} doctor-col"
                     data-name="{{ strtolower($dentist->name) }}"
                     data-specs="{{ $dentist->specializations->pluck('name')->map(fn($n) => strtolower($n))->join(',') }}">

                    <div class="doctor-card" onclick="window.location.href='/doctors/{{ $dentist->id }}'">
                        <div class="doctor-card-header">
                            <span class="doctor-card-badge">
                                @if ($avgRating >= 4.5)
                                    <i class="bi bi-trophy-fill me-1"></i> Top Rated
                                @else
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified
                                @endif
                            </span>
                        </div>

                        <div class="doctor-avatar">
                            @if ($dentist->image)
                                <img src="{{ asset($dentist->image) }}" alt="{{ $dentist->name }}">
                            @else
                                <i class="bi bi-person-fill"></i>
                            @endif
                        </div>

                        <div class="p-4 text-center">
                            <h5 class="fw-bold mb-0" style="color:var(--dark-blue);">{{ $dentist->name }}</h5>
                            <p class="text-muted small mb-2">{{ $dentist->experience_years }}+ Years Experience</p>

                            <div class="stars mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($halfStar && $i == $fullStars + 1)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                <span class="text-muted ms-1" style="font-size:.8rem;">
                                    {{ $avgRating > 0 ? $avgRating : 'New' }}
                                    @if ($dentist->ratings_count > 0)
                                        <span style="color:#aaa;">({{ $dentist->ratings_count }})</span>
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                                @forelse ($dentist->specializations as $spec)
                                    <span class="specialty-tag">{{ $spec->name }}</span>
                                @empty
                                    <span class="specialty-tag">General Dentistry</span>
                                @endforelse
                            </div>

                            @if ($dentist->university)
                                <p class="text-muted mb-3" style="font-size:.82rem;">
                                    <i class="bi bi-mortarboard-fill me-1"></i>{{ $dentist->university }}
                                </p>
                            @endif

                            <div class="d-flex gap-2" style="flex-wrap:wrap; justify-content:center;">
                                <a href="/doctors/{{ $dentist->id }}" class="btn-view-profile" onclick="event.stopPropagation()">
                                    <i class="bi bi-person-lines-fill me-1"></i> View Profile
                                </a>
                                <button class="btn-subscribe-card" onclick="event.stopPropagation(); handleCardSubscribe(event, {{ $dentist->id }})">
                                    <i class="bi bi-heart-pulse-fill me-1"></i> Subscribe
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- No results message --}}
        <div class="no-results" id="noResults" style="display:none;">
            <div class="no-results-icon"><i class="bi bi-emoji-frown"></i></div>
            <h5 style="color:var(--dark-blue); font-weight:700;">No dentists found</h5>
            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
            <button onclick="resetFilters()" class="btn btn-outline-secondary rounded-pill px-4 mt-2">
                <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
            </button>
        </div>
    </div>
</section>
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
    <input type="hidden" name="dentist_id" id="subDentistId" value="">
    <input type="hidden" name="patient_id" id="subPatientId" value="">
</form>

{{-- ═══ INLINE STYLES FOR POPUP ═══ --}}
<style>
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
</style>
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

/* ── HERO FLOAT ANIMATION ───────────────────────────── */
var style = document.createElement('style');
style.textContent = '@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }';
document.head.appendChild(style);

/* ── SEARCH & FILTER ────────────────────────────────── */
var searchInput = document.getElementById('searchInput');
var chips       = document.querySelectorAll('.filter-chip');
var cols        = document.querySelectorAll('.doctor-col');
var noResults   = document.getElementById('noResults');
var countEl     = document.getElementById('resultCount');
var activeFilter = 'all';

function applyFilters() {
    var query   = searchInput.value.toLowerCase().trim();
    var visible = 0;

    cols.forEach(function (col) {
        var name  = col.getAttribute('data-name');
        var specs = col.getAttribute('data-specs');

        var matchSearch = !query || name.indexOf(query) !== -1 || specs.indexOf(query) !== -1;
        var matchFilter = activeFilter === 'all' || specs.indexOf(activeFilter.toLowerCase()) !== -1;

        if (matchSearch && matchFilter) {
            col.style.display = '';
            visible++;
        } else {
            col.style.display = 'none';
        }
    });

    countEl.textContent = visible;
    noResults.style.display = visible === 0 ? '' : 'none';
}

searchInput.addEventListener('input', applyFilters);

chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.classList.remove('active'); });
        chip.classList.add('active');
        activeFilter = chip.getAttribute('data-filter');
        applyFilters();
    });
});

function resetFilters() {
    searchInput.value = '';
    activeFilter = 'all';
    chips.forEach(function (c) { c.classList.remove('active'); });
    chips[0].classList.add('active');
    applyFilters();
}

/* ── SUBSCRIBE FROM CARD ────────────────────────────── */
function handleCardSubscribe(event, dentistId) {
    event.preventDefault();
    
    // Get patient_id from URL query param (?patient=...) for now; auth later
    var params    = new URLSearchParams(window.location.search);
    var patientId = params.get('patient');

    if (!patientId) {
        // Not logged in or no patient param — show ineligible popup
        document.getElementById('subPopupOverlay').classList.add('show');
        return;
    }

    // Check eligibility via a quick GET
    fetch('/api/check-eligibility?patient=' + encodeURIComponent(patientId))
        .then(function (r) { return r.json(); })
        .catch(function () { return { eligible: false }; })
        .then(function (data) {
            if (data && data.eligible) {
                document.getElementById('subDentistId').value = dentistId;
                document.getElementById('subPatientId').value = patientId;
                document.getElementById('subscribeForm').submit();
            } else {
                document.getElementById('subPopupOverlay').classList.add('show');
            }
        });
}

function closeSubPopup() {
    document.getElementById('subPopupOverlay').classList.remove('show');
}

// Close popup on overlay click (outside popup)
document.getElementById('subPopupOverlay').addEventListener('click', function (e) {
    if (e.target === this) closeSubPopup();
});
</script>

<script>
/* ── NAVBAR AUTH (client token fallback for dentist logins) ── */
(function () {
    const serverLoggedIn = @json(auth()->check());
    if (serverLoggedIn) return;

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
</script>

</body>
</html>

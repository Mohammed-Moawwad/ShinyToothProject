<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShinyTooth — Your Smile, Our Mission</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --teal:      #059386;
            --dark-blue: #003263;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* ══════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════
           HERO
        ══════════════════════════════════════════ */
        .hero-section {
            background: linear-gradient(135deg, #002050 0%, #003263 45%, #047a6e 80%, #059386 100%);
            min-height: 88vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-circle-1 {
            position: absolute;
            top: -20%;
            right: -8%;
            width: 550px;
            height: 550px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-circle-2 {
            position: absolute;
            bottom: -25%;
            left: -6%;
            width: 420px;
            height: 420px;
            background: rgba(255,255,255,.03);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(8px);
            color: #fff;
            border-radius: 50px;
            padding: 7px 18px;
            font-size: .83rem;
            font-weight: 600;
            letter-spacing: .4px;
            margin-bottom: 22px;
            border: 1px solid rgba(255,255,255,.2);
        }
        .hero-title {
            font-size: clamp(2.2rem, 4vw, 3.6rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -.5px;
        }
        .hero-title span { color: #7de8dc; }
        .hero-subtitle {
            color: rgba(255,255,255,.78);
            font-size: 1.05rem;
            margin-bottom: 38px;
            line-height: 1.75;
            max-width: 480px;
        }
        .btn-hero-primary {
            background: #fff;
            color: var(--dark-blue);
            font-weight: 700;
            padding: 14px 34px;
            border-radius: 50px;
            font-size: 1rem;
            transition: all .25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
        }
        .btn-hero-primary:hover {
            background: #dff6f3;
            color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
        }
        .btn-hero-secondary {
            border: 2px solid rgba(255,255,255,.65);
            color: #fff;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            transition: all .25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-hero-secondary:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-color: rgba(255,255,255,.9);
        }
        .hero-stat {
            background: rgba(255,255,255,.11);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 16px;
            padding: 18px 16px;
            text-align: center;
        }
        .hero-stat .num {
            font-size: 1.9rem;
            font-weight: 800;
            color: #7de8dc;
            line-height: 1;
        }
        .hero-stat .lbl {
            font-size: .78rem;
            color: rgba(255,255,255,.72);
            margin-top: 4px;
        }
        .hero-image-wrap { position: relative; text-align: center; }
        .hero-tooth-main {
            max-height: 430px;
            width: auto;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,.3));
            animation: floatBounce 3.2s ease-in-out infinite;
        }
        .hero-tooth-advice {
            position: absolute;
            bottom: 0;
            left: -20px;
            max-height: 150px;
            width: auto;
            filter: drop-shadow(0 6px 16px rgba(0,0,0,.2));
            animation: floatBounce 4s ease-in-out infinite reverse;
        }
        @keyframes floatBounce {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-14px); }
        }

        /* ══════════════════════════════════════════
           SHARED SECTION STYLES
        ══════════════════════════════════════════ */
        .section-heading { font-size: 2rem; font-weight: 800; color: var(--dark-blue); letter-spacing: -.3px; }
        .section-sub { color: #6c757d; font-size: .98rem; max-width: 520px; margin: 0 auto; line-height: 1.7; }
        .teal-bar {
            width: 56px;
            height: 4px;
            background: linear-gradient(90deg, var(--dark-blue), var(--teal));
            border-radius: 2px;
            margin: 10px auto 16px;
        }

        /* ══════════════════════════════════════════
           DOCTORS
        ══════════════════════════════════════════ */
        .section-doctors { background: #f6fbfa; padding: 92px 0; }
        .doctor-card {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 4px 22px rgba(0,50,99,.09);
            transition: transform .3s ease, box-shadow .3s ease;
            position: relative;
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
            background: #e6f5f3;
            border: 4px solid #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: var(--teal);
            margin: -48px auto 0;
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

        /* ══════════════════════════════════════════
           SERVICES
        ══════════════════════════════════════════ */
        .section-services { padding: 92px 0; background: #fff; }
        .service-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: all .35s ease;
            height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            cursor: pointer;
            box-shadow: 0 10px 35px rgba(0,50,99,.12);
        }
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);
            transition: background .3s ease;
        }
        .service-card:hover::before {
            background: linear-gradient(180deg, rgba(5,147,134,0.2) 0%, rgba(5,147,134,0.6) 100%);
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(5,147,134,.25);
        }
        .service-content {
            position: relative;
            z-index: 2;
            padding: 40px 28px;
            text-align: center;
        }
        .service-name {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,.2);
        }
        .service-price {
            margin: 15px auto 12px;
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 2px;
            transition: all .3s ease;
        }
        .service-card:hover .service-price {
            transform: scale(1.08);
        }
        .price-currency {
            color: var(--teal);
            font-size: 1.6rem;
            font-weight: 800;
        }
        .price-amount {
            color: #fff;
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 3px 8px rgba(0,0,0,.3);
            letter-spacing: -1px;
        }
        .service-price-label {
            color: rgba(255,255,255,.85);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.1px;
            font-weight: 600;
            margin-top: 8px;
        }
        .service-card:hover .service-price {
            transform: scale(1.08);
        }

        /* ══════════════════════════════════════════
           CTA BUTTON (reused in multiple sections)
        ══════════════════════════════════════════ */
        .btn-cta {
            background: linear-gradient(135deg, var(--dark-blue), var(--teal));
            color: #fff;
            font-weight: 700;
            padding: 14px 42px;
            border-radius: 50px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all .25s;
        }
        .btn-cta:hover {
            color: #fff;
            box-shadow: 0 8px 24px rgba(5,147,134,.38);
            transform: translateY(-2px);
        }

        /* ══════════════════════════════════════════
           BEFORE & AFTER
        ══════════════════════════════════════════ */
        .section-ba { background: linear-gradient(135deg, #eef8f7, #e8eef8); padding: 92px 0; }
        .ba-character {
            max-width: 200px;
            height: auto;
            margin-top: 20px;
            filter: drop-shadow(0 8px 20px rgba(0,0,0,.12));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .comparison-wrapper {
            position: relative;
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 22px 56px rgba(0,50,99,.2);
            cursor: ew-resize;
            user-select: none;
        }
        .comp-before, .comp-after {
            width: 100%;
            height: 520px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .comp-before {
            background-image: url('/images/ba/before-and-after.avif');
        }
        .comp-after {
            position: absolute;
            top: 0; left: 0;
            background-image: url('/images/ba/before-and-after.avif');
            clip-path: inset(0 50% 0 0);
            transition: clip-path .04s linear;
        }
        .comp-divider {
            position: absolute;
            top: 0; bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            background: #fff;
            z-index: 5;
            pointer-events: none;
        }
        .comp-handle {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 46px; height: 46px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0,0,0,.22);
            color: var(--teal);
            font-size: 1.1rem;
            pointer-events: none;
        }
        .comp-range {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: ew-resize;
            z-index: 10;
            margin: 0;
        }
        .ba-tag {
            position: absolute;
            bottom: 18px;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .6px;
            z-index: 6;
        }
        .ba-tag-before { left: 16px; background: rgba(0,0,0,.4); color: #fff; }
        .ba-tag-after  { right: 16px; background: rgba(5,147,134,.85); color: #fff; }

        /* ══════════════════════════════════════════
           TESTIMONIALS
        ══════════════════════════════════════════ */
        .section-testimonials { padding: 92px 0; background: #fff; }
        .testimonial-overflow { overflow: hidden; }
        .testimonial-track {
            display: flex;
            gap: 24px;
            transition: transform .55s cubic-bezier(.25,.1,.25,1);
        }
        .testimonial-card {
            min-width: 310px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 22px;
            padding: 32px 28px 26px;
            box-shadow: 0 4px 22px rgba(0,50,99,.09);
            border-top: 4px solid var(--teal);
        }
        .t-quote {
            font-size: 3rem;
            color: var(--teal);
            line-height: .8;
            font-family: Georgia, serif;
            margin-bottom: 8px;
        }
        .t-text {
            color: #555;
            font-size: .93rem;
            line-height: 1.75;
            margin-bottom: 20px;
        }
        .t-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--dark-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .t-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #cde;
            cursor: pointer;
            transition: all .3s;
            border: none;
            padding: 0;
        }
        .t-dot.active {
            background: var(--teal);
            transform: scale(1.35);
        }

        /* ══════════════════════════════════════════
           PARTNERS
        ══════════════════════════════════════════ */
        .section-partners { background: #f6fbfa; padding: 72px 0; }
        .partner-box {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 14px;
            padding: 12px;
            box-shadow: 0 2px 12px rgba(0,50,99,.07);
            border: 1px solid #e8f0f0;
            transition: all .3s;
            overflow: hidden;
        }
        .partner-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: grayscale(0%) brightness(1);
            transition: all .3s ease;
        }
        .partner-box:hover {
            border-color: var(--teal);
            box-shadow: 0 6px 20px rgba(5,147,134,.14);
            transform: translateY(-3px);
        }
        .partner-box:hover img {
            filter: grayscale(0%) brightness(1.1);
            transform: scale(1.05);
        }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════
           SCROLL FADE-IN ANIMATION
        ══════════════════════════════════════════ */
        .fade-up {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .65s ease, transform .65s ease;
        }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: .12s; }
        .delay-2 { transition-delay: .24s; }
        .delay-3 { transition-delay: .36s; }
        .delay-4 { transition-delay: .48s; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 1 · NAVBAR
══════════════════════════════════════════════════════════════ --}}
<nav class="main-nav" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

            {{-- Logo --}}
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo" height="88"
                     style="border-radius:8px; object-fit:contain;">
                <span style="color:#fff; font-size:1.3rem; font-weight:800; letter-spacing:-.3px;">
                    ShinyTooth
                </span>
            </a>

            {{-- Nav Links --}}
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="#services"   class="nav-link-custom">Services</a>
                <a href="#doctors"    class="nav-link-custom">Doctors</a>
                <a href="#who-we-are" class="nav-link-custom">Who are we</a>
                <a href="#contact"    class="nav-link-custom">Contact us</a>
            </div>

            {{-- Auth Buttons --}}
            <div class="d-flex align-items-center gap-2">
                <a href="/login"    class="btn-nav-login">Login</a>
                <a href="/register" class="btn-nav-signup">Sign Up</a>
            </div>

        </div>
    </div>
</nav>


{{-- ══════════════════════════════════════════════════════════════
     HERO (part of "Who Are We") + TOOTH CHARACTERS
══════════════════════════════════════════════════════════════ --}}
<section class="hero-section" id="who-we-are">
    <div class="hero-circle-1"></div>
    <div class="hero-circle-2"></div>

    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5 py-5">

            {{-- Center: text --}}
            <div class="col-lg-12 text-center">
                <div class="hero-badge" style="justify-content:center;">
                    <i class="bi bi-star-fill"></i>
                    Trusted by over 20,000 happy patients
                </div>

                <h1 class="hero-title">
                    Your <span>Perfect Smile</span><br>Starts <img src="{{ asset('images/HappyTooth.png') }}" alt="Happy Tooth" style="height:1.4em; width:auto; display:inline-block; vertical-align:middle; margin:0 6px;"> Here
                </h1>

                <p class="hero-subtitle" style="margin-left:auto; margin-right:auto;">
                    At ShinyTooth, we blend world-class dental expertise with a warm,
                    comfortable environment — because you deserve a smile you're proud of every single day.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5 justify-content-center">
                    <a href="/register" class="btn-hero-primary">
                        <i class="bi bi-calendar-check-fill"></i> Book Appointment
                    </a>
                </div>

                {{-- Stats row --}}
                <div class="row g-3 justify-content-center">
                    <div class="col-4">
                        <div class="hero-stat">
                            <div class="num">20+</div>
                            <div class="lbl">Years Experience</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat">
                            <div class="num">20K+</div>
                            <div class="lbl">Happy Patients</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat">
                            <div class="num">15+</div>
                            <div class="lbl">Expert Dentists</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 2 · BEST DOCTORS
══════════════════════════════════════════════════════════════ --}}
<section class="section-doctors" id="doctors">
    <div class="container">

        <div class="text-center mb-5 fade-up">
            <h2 class="section-heading">Meet Our Expert Dentists</h2>
            <div class="teal-bar"></div>
            <p class="section-sub">
                Our team of highly qualified dental professionals is dedicated to providing
                exceptional care in a comfortable, welcoming setting.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            @if ($dentists && count($dentists) > 0)
                @foreach ($dentists as $index => $dentist)
                    <div class="col-md-6 col-lg-4 fade-up @if ($index == 0) delay-1 @elseif ($index == 1) delay-2 @else delay-3 @endif">
                        <div class="doctor-card">
                            <div class="doctor-card-header">
                                <span class="doctor-card-badge">
                                    @if ($index === 0)
                                        <i class="bi bi-trophy-fill me-1"></i> Top Rated
                                    @else
                                        <i class="bi bi-patch-check-fill me-1"></i> Verified
                                    @endif
                                </span>
                            </div>
                            <div class="doctor-avatar" style="overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f0f0f0; margin:-60px auto 0; position:relative; z-index:10;">
                                @if ($dentist->image)
                                    <img src="{{ asset($dentist->image) }}" alt="{{ $dentist->name }}" 
                                         style="width:100%; height:100%; object-fit:cover; object-position:center top;">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>
                            <div class="p-4 text-center">
                                <h5 class="fw-bold mb-0" style="color:var(--dark-blue);">{{ $dentist->name }}</h5>
                                <p class="text-muted small mb-2">{{ $dentist->experience_years }}+ Years Experience</p>
                                <div class="stars mb-2">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-muted ms-1" style="font-size:.8rem;">5.0</span>
                                </div>
                                <p class="text-muted small mb-3">
                                    Dedicated dental professional with {{ $dentist->experience_years }} years of experience in providing exceptional patient care.
                                </p>
                                <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                                    @if ($dentist->specializations && count($dentist->specializations) > 0)
                                        @foreach ($dentist->specializations as $spec)
                                            <span class="specialty-tag">{{ $spec->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="specialty-tag">{{ $dentist->university ?? 'General Dentistry' }}</span>
                                    @endif
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill px-4">View Profile</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">No active dentists available at the moment.</p>
                </div>
            @endif

        </div>

        <div class="text-center mt-5 fade-up">
            <a href="/doctors" class="btn-cta">
                <i class="bi bi-people-fill"></i> Meet All Doctors
            </a>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 3 · MAIN SERVICES
══════════════════════════════════════════════════════════════ --}}
<section class="section-services" id="services">
    <div class="container">

        <div class="text-center mb-5 fade-up">
            <h2 class="section-heading">Our Services</h2>
            <div class="teal-bar"></div>
            <p class="section-sub">
                From routine check-ups to advanced procedures — comprehensive dental care
                for every member of your family.
            </p>
        </div>

        <div class="row g-4">

            @if ($services && count($services) > 0)
                @foreach ($services as $index => $service)
                    <div class="col-md-6 col-lg-6 fade-up @if ($index == 0) delay-1 @elseif ($index == 1) delay-2 @elseif ($index == 2) delay-3 @else delay-4 @endif">
                        <div class="service-card" @if($service->image) style="background-image: url('{{ asset($service->image) }}')" @endif>
                            <div class="service-content">
                                <h5 class="service-name">{{ $service->name }}</h5>
                                <div class="service-price">
                                    <span class="price-currency">$</span>
                                    <span class="price-amount">{{ number_format($service->price, 0) }}</span>
                                </div>
                                <p class="service-price-label">Starting Price</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">No services available at the moment.</p>
                </div>
            @endif

        </div>

        <div class="text-center mt-5 fade-up">
            <a href="/services" class="btn-cta">
                <i class="bi bi-grid-3x3-gap-fill"></i> See All Services
            </a>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 4 · BEFORE & AFTER
══════════════════════════════════════════════════════════════ --}}
<section class="section-ba">
    <div class="container">
        <div class="row align-items-center g-5">

            {{-- Left text --}}
            <div class="col-lg-5 fade-up">
                <h2 class="section-heading">See the<br>Difference</h2>
                <div class="teal-bar" style="margin-left:0;"></div>
                <p class="text-muted" style="line-height:1.8; margin-bottom:24px;">
                    We have transformed thousands of lives with remarkable smile makeovers. 
                    Watch our patients' confidence shine through these incredible before and after results.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color:var(--teal);"></i>
                        <span>Whitening &amp; Bleaching</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color:var(--teal);"></i>
                        <span>Veneers &amp; Porcelain Crowns</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color:var(--teal);"></i>
                        <span>Orthodontic Corrections</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color:var(--teal);"></i>
                        <span>Full Smile Makeovers</span>
                    </li>
                </ul>
                <a href="/register" class="btn-cta">
                    <i class="bi bi-calendar-check-fill"></i> Book a Free Consultation
                </a>
                <img src="{{ asset('images/BlueToothSurprisedPointingAtTheRight.png') }}" alt="BlueTooth Character" class="ba-character">
            </div>

            {{-- Right: comparison slider --}}
            <div class="col-lg-7 fade-up delay-2">
                <div class="comparison-wrapper" id="compWrapper">

                    {{-- Before --}}
                    <div class="comp-before"></div>

                    {{-- After (clipped) --}}
                    <div class="comp-after" id="compAfter"></div>

                    {{-- Divider line + handle --}}
                    <div class="comp-divider" id="compDivider">
                        <div class="comp-handle">
                            <i class="bi bi-arrows-expand" style="transform:rotate(90deg);"></i>
                        </div>
                    </div>

                    {{-- Invisible range input for interaction --}}
                    <input type="range" class="comp-range" id="compRange"
                           min="0" max="100" value="50"
                           aria-label="Before/After comparison slider">

                    <span class="ba-tag ba-tag-before">BEFORE</span>
                    <span class="ba-tag ba-tag-after">AFTER</span>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 5 · TESTIMONIALS
══════════════════════════════════════════════════════════════ --}}
<section class="section-testimonials">
    <div class="container">

        <div class="text-center mb-5 fade-up">
            <h2 class="section-heading">What Our Patients Say</h2>
            <div class="teal-bar"></div>
            <p class="section-sub">
                Real stories from real people who trust ShinyTooth with their smiles.
            </p>
        </div>

        <div class="testimonial-overflow fade-up" id="testimonialsArea">
            <div class="testimonial-track" id="testimonialTrack">

                {{-- Card 1 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        Absolutely the best dental experience I've ever had. The staff is friendly,
                        the clinic is immaculate, and Dr. Carter's work is nothing short of extraordinary!
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">AM</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Ahmed M.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        I had severe dental anxiety, but Dr. Ramirez made me feel completely at ease.
                        The results of my treatment exceeded every expectation I had.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">SR</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Sarah R.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        My whole family goes to ShinyTooth. The kids actually look forward
                        to their appointments — that alone speaks volumes about the team here!
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">KJ</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Kevin J.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        The teeth whitening service is phenomenal — I went three shades brighter
                        in one session! Highly recommend to anyone wanting a quick confidence boost.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">LP</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Laura P.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 5 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        Booking online was seamless and the appointment reminders are a lifesaver.
                        ShinyTooth truly cares about the full patient experience from start to finish.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">MH</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Mohammed H.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 6 --}}
                <div class="testimonial-card">
                    <div class="t-quote">&ldquo;</div>
                    <p class="t-text">
                        I came in for a routine cleaning and left with a full treatment plan.
                        The thorough care and transparency here is unlike any other clinic I've visited.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="t-avatar">NA</div>
                        <div>
                            <div class="fw-bold small" style="color:var(--dark-blue);">Nadia A.</div>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Navigation dots --}}
        <div class="d-flex justify-content-center gap-2 mt-4" id="tDots">
            <button class="t-dot active" onclick="goSlide(0)" aria-label="Go to slide 1"></button>
            <button class="t-dot"        onclick="goSlide(1)" aria-label="Go to slide 2"></button>
            <button class="t-dot"        onclick="goSlide(2)" aria-label="Go to slide 3"></button>
            <button class="t-dot"        onclick="goSlide(3)" aria-label="Go to slide 4"></button>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 6 · PARTNERS
══════════════════════════════════════════════════════════════ --}}
<section class="section-partners" id="contact">
    <div class="container">

        <div class="text-center mb-4 fade-up">
            <span style="display:inline-block; color:var(--teal); font-weight:700;
                         font-size:.8rem; letter-spacing:2px; text-transform:uppercase;
                         margin-bottom:8px;">Trusted By</span>
            <h2 class="section-heading">Our Partners</h2>
            <div class="teal-bar"></div>
        </div>

        <div class="row g-3 justify-content-center fade-up">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/AlRajhiBankPartner1.png') }}" alt="Al Rajhi Bank">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/AlAhliBankPartner2.png') }}" alt="Al Ahli Bank">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/RoyalComissionPartner3.png') }}" alt="Royal Commission">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/TawuniyaPartner3.png') }}" alt="Tawuniya">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/AramcoPartner5.jpg') }}" alt="Aramco">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-box">
                    <img src="{{ asset('images/partners/YasrefPartner6.png') }}" alt="Yasref">
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 7 · FOOTER
══════════════════════════════════════════════════════════════ --}}
<footer class="main-footer fade-up">
    <div class="container">
        <div class="row g-4">

            {{-- Brand column --}}
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

            {{-- Quick Links --}}
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="footer-h">Quick Links</h6>
                <a href="#who-we-are" class="footer-link">Who Are We</a>
                <a href="#doctors"    class="footer-link">Our Doctors</a>
                <a href="#services"   class="footer-link">Services</a>
                <a href="/register"   class="footer-link">Book Appointment</a>
                <a href="/login"      class="footer-link">Patient Portal</a>
            </div>

            {{-- Services --}}
            <div class="col-6 col-lg-2">
                <h6 class="footer-h">Services</h6>
                <a href="#" class="footer-link">Dental Cleanings</a>
                <a href="#" class="footer-link">Oral Exams</a>
                <a href="#" class="footer-link">Fillings</a>
                <a href="#" class="footer-link">Tooth Extractions</a>
                <a href="/services" class="footer-link">All Services →</a>
            </div>

            {{-- Contact --}}
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


<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts

<script>
/* ── NAVBAR SHRINK ON SCROLL ──────────────────────────── */
window.addEventListener('scroll', function () {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 60);
});

/* ── BEFORE / AFTER COMPARISON SLIDER ────────────────── */
(function () {
    var range   = document.getElementById('compRange');
    var after   = document.getElementById('compAfter');
    var divider = document.getElementById('compDivider');

    function applySlider(val) {
        after.style.clipPath      = 'inset(0 ' + (100 - val) + '% 0 0)';
        divider.style.left        = val + '%';
    }

    range.addEventListener('input', function () { applySlider(this.value); });
    applySlider(50);
})();

/* ── TESTIMONIAL AUTO-CAROUSEL ────────────────────────── */
(function () {
    var track   = document.getElementById('testimonialTrack');
    var dots    = document.querySelectorAll('.t-dot');
    var current = 0;
    var timer;

    function visibleCards() {
        if (window.innerWidth >= 992) return 3;
        if (window.innerWidth >= 576) return 2;
        return 1;
    }

    function slideTo(idx) {
        var cards    = track.querySelectorAll('.testimonial-card');
        var cardW    = cards[0].offsetWidth + 24;
        var maxIdx   = Math.max(0, cards.length - visibleCards());
        current      = Math.min(Math.max(idx, 0), maxIdx);
        track.style.transform = 'translateX(-' + (current * cardW) + 'px)';
        dots.forEach(function (d, i) { d.classList.toggle('active', i === idx % dots.length); });
    }

    window.goSlide = slideTo;

    function nextSlide() {
        var cards  = track.querySelectorAll('.testimonial-card');
        var maxIdx = cards.length - visibleCards();
        slideTo(current >= maxIdx ? 0 : current + 1);
    }

    function startAuto() { timer = setInterval(nextSlide, 3800); }
    function stopAuto()  { clearInterval(timer); }

    startAuto();

    var area = document.getElementById('testimonialsArea');
    area.addEventListener('mouseenter', stopAuto);
    area.addEventListener('mouseleave', startAuto);
    window.addEventListener('resize',   function () { slideTo(current); });
})();

/* ── SCROLL FADE-IN OBSERVER ─────────────────────────── */
(function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.fade-up').forEach(function (el) {
        observer.observe(el);
    });
})();
</script>

</body>
</html>

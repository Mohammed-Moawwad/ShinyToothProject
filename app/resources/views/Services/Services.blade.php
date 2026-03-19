<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services — ShinyTooth</title>

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
           FILTER BAR
        ══════════════════════════════════════════ */
        .filter-section {
            background: var(--dark-blue);
            padding: 50px 0;
            border-bottom: none;
            margin-bottom: 50px;
        }
        .filter-header {
            margin-bottom: 32px;
        }
        .filter-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .filter-header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            margin: 0;
        }
        .search-bar-wrapper {
            margin-bottom: 40px;
        }
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            background: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .search-input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 4px 16px rgba(5,147,134,0.12);
        }
        .search-input::placeholder {
            color: #999;
        }
        .search-icon {
            position: absolute;
            left: 18px;
            color: #999;
            font-size: 1.1rem;
            pointer-events: none;
        }
        .search-input:focus ~ .search-icon {
            color: var(--teal);
        }

        .filter-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 28px;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e8f0f0;
        }

        .filter-label {
            font-weight: 700;
            color: var(--dark-blue);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label i {
            font-size: 1rem;
            color: var(--teal);
        }

        .filter-buttons {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            max-height: 120px;
            overflow-y: auto;
        }

        .filter-buttons::-webkit-scrollbar {
            width: 6px;
        }

        .filter-buttons::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 3px;
        }

        .filter-buttons::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }

        .filter-buttons::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        .filter-btn {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            text-align: left;
        }

        .filter-btn:hover {
            border-color: var(--teal);
            background: #f0f9f8;
            color: var(--teal);
        }

        .filter-btn.active {
            background: var(--teal);
            color: #fff;
            border-color: var(--teal);
            font-weight: 700;
        }

        .price-range-wrapper {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .price-inputs {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 10px;
            align-items: center;
        }

        .price-input {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            width: 100%;
            font-weight: 600;
            color: var(--dark-blue);
        }

        .price-input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(5,147,134,0.1);
        }

        .price-separator {
            color: #999;
            font-weight: 700;
        }

        .price-display {
            text-align: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--teal);
            padding: 8px;
            background: rgba(5,147,134,0.05);
            border-radius: 6px;
        }

        .price-slider {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #e0e0e0 0%, #ddd 100%);
            outline: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--teal);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(5,147,134,0.4);
            transition: all 0.2s ease;
        }

        .price-slider::-webkit-slider-thumb:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(5,147,134,0.5);
        }

        .price-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--teal);
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 8px rgba(5,147,134,0.4);
            transition: all 0.2s ease;
        }

        .price-slider::-moz-range-thumb:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(5,147,134,0.5);
        }

        .special-offers-group {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e8f0f0;
        }

        .special-offers {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .special-offers input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--teal);
            border-radius: 4px;
        }

        .special-offers label {
            cursor: pointer;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-blue);
        }

        .filter-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
        }

        .btn-filter-apply {
            background: linear-gradient(135deg, var(--teal) 0%, #047a6e 100%);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(5,147,134,0.2);
            white-space: nowrap;
        }

        .btn-filter-apply:hover {
            box-shadow: 0 6px 20px rgba(5,147,134,0.35);
            transform: translateY(-2px);
        }

        .btn-filter-reset {
            background: #fff;
            color: var(--dark-blue);
            border: 2px solid #e0e0e0;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-filter-reset:hover {
            border-color: var(--teal);
            color: var(--teal);
            background: #f0f9f8;
        }

        .filter-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--teal);
            color: #fff;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 24px;
            height: 24px;
        }

        @media (max-width: 1200px) {
            .filter-container {
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
            .filter-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }

        @media (max-width: 768px) {
            .filter-section {
                padding: 30px 0;
            }
            .filter-header h2 {
                font-size: 1.5rem;
            }
            .filter-container {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .filter-group, .special-offers-group {
                padding: 16px;
            }
            .filter-actions {
                flex-direction: column;
                gap: 12px;
            }
        }

        /* ══════════════════════════════════════════
           SERVICES GRID
        ══════════════════════════════════════════ */
        .services-section {
            padding: 48px 0 80px;
            background: #f7f9fc;
        }
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding: 14px 20px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e8ecf0;
            font-size: 0.9rem;
            color: #666;
        }
        .results-header strong { color: var(--dark-blue); font-size: 1rem; }
        .sort-label { color: #aaa; font-size: 0.85rem; }

        /* Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        /* Card base */
        .service-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #edf0f5;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.12);
        }

        /* Special offer card */
        .special-offer-card {
            border: 2px solid #f59e0b;
            box-shadow: 0 4px 20px rgba(245,158,11,0.15);
        }
        .special-offer-card:hover {
            box-shadow: 0 16px 48px rgba(245,158,11,0.25);
        }
        .offer-ribbon {
            position: absolute;
            top: 16px;
            right: 0;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 6px 14px 6px 12px;
            border-radius: 4px 0 0 4px;
            z-index: 20;
            text-transform: uppercase;
            box-shadow: -3px 3px 8px rgba(0,0,0,0.2);
        }

        /* TOP: Image/Icon area */
        .card-image-area {
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        .card-img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .service-card:hover .card-img { transform: scale(1.05); }
        .card-icon-placeholder {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.18);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(4px);
            transition: transform 0.3s ease;
        }
        .service-card:hover .card-icon-placeholder { transform: scale(1.08); }
        .card-icon-placeholder i { font-size: 2.2rem; color: #fff; }
        .category-badge {
            position: absolute;
            bottom: 10px; left: 10px;
            background: rgba(0,0,0,0.35);
            color: rgba(255,255,255,0.95);
            font-size: 0.7rem; font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            backdrop-filter: blur(6px);
            max-width: calc(100% - 80px);
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .special-tag {
            position: absolute;
            bottom: 10px; right: 10px;
            background: rgba(245,158,11,0.9);
            color: #fff;
            font-size: 0.68rem; font-weight: 700;
            padding: 3px 8px; border-radius: 20px;
        }
        /* Category gradient map */
        .cat-preventive   { background: linear-gradient(135deg, #059386 0%, #047068 100%); }
        .cat-restorative  { background: linear-gradient(135deg, #1a56db 0%, #0a3a8c 100%); }
        .cat-cosmetic     { background: linear-gradient(135deg, #e91e8c 0%, #9c0c6b 100%); }
        .cat-orthodontics { background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%); }
        .cat-periodontics { background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%); }
        .cat-specialty    { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); }
        .cat-pediatric    { background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); }
        .cat-consultation { background: linear-gradient(135deg, #003263 0%, #059386 100%); }

        /* MIDDLE: Body */
        .card-body-area {
            padding: 18px 18px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .service-name {
            font-size: 1rem; font-weight: 800;
            color: var(--dark-blue);
            margin: 0; line-height: 1.35;
        }
        .service-desc {
            font-size: 0.845rem; color: #666;
            margin: 0; line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .best-dentist-box {
            display: flex; align-items: center; gap: 10px;
            background: #f4f8f8;
            border: 1px solid #deeeed;
            border-radius: 10px;
            padding: 10px 12px;
            margin-top: auto;
        }
        .dentist-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            overflow: hidden; flex-shrink: 0;
            border: 2.5px solid var(--teal);
        }
        .dentist-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .dentist-avatar-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--teal) 0%, var(--dark-blue) 100%);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.3rem;
        }
        .dentist-info {
            display: flex; flex-direction: column; gap: 1px;
            min-width: 0; flex: 1;
        }
        .dentist-label {
            font-size: 0.67rem; font-weight: 800;
            color: var(--teal);
            text-transform: uppercase; letter-spacing: 0.6px;
        }
        .dentist-name {
            font-size: 0.88rem; font-weight: 700;
            color: var(--dark-blue);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .dentist-rating { display: flex; align-items: center; gap: 1px; }
        .dentist-rating i { font-size: 0.65rem; color: #f59e0b; }
        .rating-value { font-size: 0.72rem; color: #999; margin-left: 4px; }
        .dentist-exp {
            font-size: 0.72rem; color: #888;
            display: flex; align-items: center; gap: 3px;
        }
        .dentist-exp i { font-size: 0.65rem; color: var(--teal); }

        /* BOTTOM: Footer */
        .card-footer-area {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-top: 1px solid #f0f2f5;
            background: #fafbfc;
            flex-shrink: 0; gap: 10px;
        }
        .service-price {
            display: flex; align-items: center;
            gap: 6px; flex-wrap: wrap;
        }
        .price-current {
            font-size: 1.2rem; font-weight: 800;
            color: var(--dark-blue); line-height: 1;
        }
        .price-free { color: var(--teal) !important; }
        .price-duration {
            font-size: 0.78rem; color: #bbb;
            display: flex; align-items: center; gap: 3px;
        }
        .btn-book {
            background: linear-gradient(135deg, var(--teal) 0%, #047a6e 100%);
            color: #fff; border: none;
            padding: 9px 16px; border-radius: 8px;
            font-size: 0.85rem; font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; white-space: nowrap;
            box-shadow: 0 3px 10px rgba(5,147,134,0.25);
            flex-shrink: 0;
        }
        .btn-book:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(5,147,134,0.4);
        }
        .special-offer-card .card-footer-area {
            background: #fffbf0;
            border-top-color: #fde68a;
        }
        .special-offer-card .btn-book {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            box-shadow: 0 3px 10px rgba(245,158,11,0.3);
        }
        .special-offer-card .btn-book:hover {
            box-shadow: 0 6px 18px rgba(245,158,11,0.5);
        }

        /* No results */
        .no-results {
            flex-direction: column; align-items: center; justify-content: center;
            padding: 80px 20px; color: #bbb; gap: 16px;
        }
        .no-results i { font-size: 3rem; }
        .no-results p { font-size: 1.05rem; margin: 0; color: #aaa; }
        .no-results button {
            background: var(--teal); color: #fff; border: none;
            padding: 10px 24px; border-radius: 8px;
            font-weight: 700; cursor: pointer; font-size: 0.9rem;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.2s;
        }
        .no-results button:hover { background: #047a6e; }

        @media (max-width: 1200px) {
            .services-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
        }
        @media (max-width: 768px) {
            .services-section { padding: 32px 0 60px; }
            .services-grid { grid-template-columns: 1fr; gap: 16px; }
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
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     NAVBAR
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
     FILTER BAR
══════════════════════════════════════════════════════════════ --}}
<section class="filter-section">
    <div class="container">

        {{-- HEADER --}}
        <div class="filter-header">
            <h2>Find Your Service</h2>
            <p>Search and filter to find the perfect dental service for you</p>
        </div>

        {{-- SEARCH BAR --}}
        <div class="search-bar-wrapper">
            <div class="search-bar">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Search for services (e.g., teeth whitening, dental implants, braces...)">
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="filter-container">

            {{-- 1. CATEGORY FILTER --}}
            <div class="filter-group">
                <label class="filter-label">
                    <i class="bi bi-funnel"></i> Category
                    <span class="filter-badge" id="categoryBadge">0</span>
                </label>
                <div class="filter-buttons" id="categoryFilters">
                    <button class="filter-btn active" data-category="all">All Services</button>
                    <button class="filter-btn" data-category="Preventive Dentistry">Preventive</button>
                    <button class="filter-btn" data-category="Restorative Dentistry">Restorative</button>
                    <button class="filter-btn" data-category="Cosmetic Dentistry">Cosmetic</button>
                    <button class="filter-btn" data-category="Orthodontics">Orthodontics</button>
                    <button class="filter-btn" data-category="Periodontics">Periodontics</button>
                    <button class="filter-btn" data-category="Specialty & Additional Services">Specialty</button>
                    <button class="filter-btn" data-category="Pediatric Dentistry">Pediatric</button>
                    <button class="filter-btn" data-category="Consultation">Consultation</button>
                </div>
            </div>

            {{-- 2. PRICE RANGE FILTER --}}
            <div class="filter-group">
                <label class="filter-label">
                    <i class="bi bi-cash-coin"></i> Price Range
                </label>
                <div class="price-range-wrapper">
                    <div class="price-inputs">
                        <input type="number" class="price-input" id="priceMin" placeholder="Min" min="0" value="0">
                        <span class="price-separator">—</span>
                        <input type="number" class="price-input" id="priceMax" placeholder="Max" min="0" value="5000">
                    </div>
                    <input type="range" class="price-slider" id="priceRange" min="0" max="5000" value="5000">
                    <div class="price-display">
                        $<span id="displayMin">0</span> – $<span id="displayMax">5000</span>
                    </div>
                </div>
            </div>

            {{-- 3. SPECIAL OFFERS FILTER --}}
            <div class="special-offers-group">
                <label class="filter-label" style="margin-bottom: 8px;">
                    <i class="bi bi-star"></i> Special Offers
                </label>
                <div class="special-offers">
                    <input type="checkbox" id="specialOffers" name="special-offers">
                    <label for="specialOffers">Show only special offers</label>
                </div>
            </div>

            {{-- 4. ACTION BUTTONS --}}
            <div class="filter-actions">
                <button class="btn-filter-apply" id="applyFilters">
                    <i class="bi bi-search"></i> Apply Filters
                </button>
                <button class="btn-filter-reset" id="resetFilters">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>

        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SERVICES GRID
══════════════════════════════════════════════════════════════ --}}
@php
    $categoryClasses = [
        'Preventive Dentistry'            => 'cat-preventive',
        'Restorative Dentistry'           => 'cat-restorative',
        'Cosmetic Dentistry'              => 'cat-cosmetic',
        'Orthodontics'                    => 'cat-orthodontics',
        'Periodontics'                    => 'cat-periodontics',
        'Specialty & Additional Services' => 'cat-specialty',
        'Pediatric Dentistry'             => 'cat-pediatric',
        'Consultation'                    => 'cat-consultation',
    ];
    $categoryIcons = [
        'Preventive Dentistry'            => 'bi-shield-check',
        'Restorative Dentistry'           => 'bi-tools',
        'Cosmetic Dentistry'              => 'bi-stars',
        'Orthodontics'                    => 'bi-distribute-horizontal',
        'Periodontics'                    => 'bi-heart-pulse',
        'Specialty & Additional Services' => 'bi-hospital',
        'Pediatric Dentistry'             => 'bi-emoji-smile',
        'Consultation'                    => 'bi-chat-dots',
    ];
@endphp

<section class="services-section">
    <div class="container">

        {{-- Results header --}}
        <div class="results-header">
            <span id="resultsCount">
                <strong>{{ $services->count() }}</strong> services available
            </span>
            <span class="sort-label">
                <i class="bi bi-sort-down-alt"></i> Sorted by relevance
            </span>
        </div>

        {{-- Services grid --}}
        <div class="services-grid" id="servicesGrid">
            @foreach ($services as $service)
            @php
                $catClass = $categoryClasses[$service->category] ?? 'cat-consultation';
                $catIcon  = $categoryIcons[$service->category]  ?? 'bi-hospital';
            @endphp

            <div class="service-card {{ $service->is_special_offer ? 'special-offer-card' : '' }}"
                 data-category="{{ $service->category }}"
                 data-price="{{ $service->price }}"
                 data-special="{{ $service->is_special_offer ? 'true' : 'false' }}"
                 data-name="{{ strtolower($service->name) }}"
                 data-description="{{ strtolower($service->description ?? '') }}">

                {{-- Special offer ribbon --}}
                @if ($service->is_special_offer)
                <div class="offer-ribbon">
                    <i class="bi bi-fire"></i> Special Offer
                </div>
                @endif

                {{-- TOP: Image / Icon area --}}
                <div class="card-image-area {{ $catClass }}">
                    @if ($service->image)
                        <img src="{{ asset($service->image) }}"
                             alt="{{ $service->name }}" class="card-img">
                    @else
                        <div class="card-icon-placeholder">
                            <i class="bi {{ $catIcon }}"></i>
                        </div>
                    @endif
                    <span class="category-badge">{{ $service->category }}</span>
                    @if ($service->is_special_offer)
                    <span class="special-tag"><i class="bi bi-percent"></i> Limited Time</span>
                    @endif
                </div>

                {{-- MIDDLE: Description + Best Dentist --}}
                <div class="card-body-area">
                    <h3 class="service-name">{{ $service->name }}</h3>
                    <p class="service-desc">{{ $service->description }}</p>

                    @if ($service->best_dentist)
                    <div class="best-dentist-box">
                        <div class="dentist-avatar">
                            @if ($service->best_dentist->dentist_image)
                                <img src="{{ asset($service->best_dentist->dentist_image) }}"
                                     alt="{{ $service->best_dentist->dentist_name }}">
                            @else
                                <div class="dentist-avatar-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>
                        <div class="dentist-info">
                            <span class="dentist-label">Best Match</span>
                            <span class="dentist-name">{{ $service->best_dentist->dentist_name }}</span>
                            <div class="dentist-rating">
                                @php $stars = round($service->best_dentist->avg_rating); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $stars ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="rating-value">({{ number_format($service->best_dentist->avg_rating, 1) }})</span>
                            </div>
                            <span class="dentist-exp">
                                <i class="bi bi-briefcase-fill"></i>
                                {{ $service->best_dentist->experience_years }} yrs experience
                            </span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- BOTTOM: Price + Book button --}}
                <div class="card-footer-area">
                    <div class="service-price">
                        <span class="price-current {{ $service->price == 0 ? 'price-free' : '' }}">
                            {{ $service->price == 0 ? 'Free' : '$' . number_format($service->price, 0) }}
                        </span>
                        <span class="price-duration">
                            <i class="bi bi-clock"></i> {{ $service->duration_minutes }} min
                        </span>
                    </div>
                    <a href="/services/{{ $service->id }}" class="btn-book">
                        Book! <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        {{-- No results --}}
        <div class="no-results" id="noResults" style="display:none;">
            <i class="bi bi-search"></i>
            <p>No services match your filters</p>
            <button onclick="document.getElementById('resetFilters').click()">
                <i class="bi bi-arrow-clockwise"></i> Clear Filters
            </button>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER
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

/* ── FILTER BAR + CARD FILTERING ──────────────────────── */
(function () {
    const searchInput       = document.getElementById('searchInput');
    const categoryBtns      = document.querySelectorAll('#categoryFilters .filter-btn');
    const priceMin          = document.getElementById('priceMin');
    const priceMax          = document.getElementById('priceMax');
    const priceRange        = document.getElementById('priceRange');
    const displayMin        = document.getElementById('displayMin');
    const displayMax        = document.getElementById('displayMax');
    const specialOffersCheck= document.getElementById('specialOffers');
    const categoryBadge     = document.getElementById('categoryBadge');
    const applyBtn          = document.getElementById('applyFilters');
    const resetBtn          = document.getElementById('resetFilters');

    let searchQuery      = '';
    let selectedCategory = 'all';
    let minPrice         = 0;
    let maxPrice         = 5000;
    let showSpecialOffers = false;

    // ── Filter cards ──────────────────────────────────────
    function filterCards() {
        const cards = document.querySelectorAll('#servicesGrid .service-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardName    = card.getAttribute('data-name') || '';
            const cardDesc    = card.getAttribute('data-description') || '';
            const cardCat     = card.getAttribute('data-category') || '';
            const cardPrice   = parseFloat(card.getAttribute('data-price')) || 0;
            const cardSpecial = card.getAttribute('data-special') === 'true';

            const matchSearch   = !searchQuery || cardName.includes(searchQuery) || cardDesc.includes(searchQuery);
            const matchCategory = selectedCategory === 'all' || cardCat === selectedCategory;
            const matchPrice    = cardPrice >= minPrice && cardPrice <= maxPrice;
            const matchSpecial  = !showSpecialOffers || cardSpecial;

            if (matchSearch && matchCategory && matchPrice && matchSpecial) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const countEl   = document.getElementById('resultsCount');
        const noResults = document.getElementById('noResults');
        if (countEl)   countEl.innerHTML = '<strong>' + visibleCount + '</strong> services found';
        if (noResults) noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
    }

    // ── Search (real-time) ────────────────────────────────
    searchInput.addEventListener('input', function () {
        searchQuery = this.value.trim().toLowerCase();
    });

    // ── Category buttons (filter immediately on click) ───
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedCategory = this.getAttribute('data-category');
            updateBadge();
            filterCards();
        });
    });

    // ── Price range slider ────────────────────────────────
    priceRange.addEventListener('input', function () {
        maxPrice = parseInt(this.value);
        displayMax.textContent = maxPrice;
        priceMax.value = maxPrice;
    });
    priceMin.addEventListener('change', function () {
        minPrice = Math.max(0, parseInt(this.value) || 0);
        displayMin.textContent = minPrice;
    });
    priceMax.addEventListener('change', function () {
        maxPrice = Math.min(5000, parseInt(this.value) || 5000);
        displayMax.textContent = maxPrice;
        priceRange.value = maxPrice;
    });

    // ── Special offers ────────────────────────────────────
    specialOffersCheck.addEventListener('change', function () {
        showSpecialOffers = this.checked;
    });

    // ── Apply filters button ──────────────────────────────
    applyBtn.addEventListener('click', filterCards);

    // ── Reset ─────────────────────────────────────────────
    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        searchQuery = '';
        categoryBtns.forEach(b => b.classList.remove('active'));
        categoryBtns[0].classList.add('active');
        selectedCategory = 'all';
        minPrice = 0; maxPrice = 5000;
        priceMin.value = 0; priceMax.value = 5000;
        priceRange.value = 5000;
        displayMin.textContent = 0; displayMax.textContent = 5000;
        specialOffersCheck.checked = false;
        showSpecialOffers = false;
        updateBadge();
        filterCards();
    });

    // ── Badge counter ─────────────────────────────────────
    function updateBadge() {
        categoryBadge.textContent = selectedCategory !== 'all' ? 1 : 0;
    }
})();
</script>

</body>
</html>

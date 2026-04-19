<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Dashboard') — ShinyTooth</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --teal:       #059386;
            --teal-light: #e6f5f3;
            --dark-blue:  #003263;
            --sidebar-w:  260px;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            background: #f6fbfa;
            margin: 0;
        }

        /* ── SIDEBAR ──────────────────────────── */
        .dash-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #003263 0%, #004a8f 40%, #059386 100%);
            color: #fff;
            padding: 0;
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
        }
        .dash-sidebar .brand {
            padding: 24px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .dash-sidebar .brand h4 {
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }
        .dash-sidebar .brand small {
            opacity: .7;
            font-size: .8rem;
        }
        .dash-sidebar .patient-mini {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .dash-sidebar .patient-mini .placeholder-avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 8px;
            font-size: 1.8rem;
            border: 3px solid rgba(255,255,255,.35);
        }
        .dash-sidebar .patient-mini h6 {
            margin: 0;
            font-weight: 600;
            font-size: .95rem;
        }
        .dash-sidebar .patient-mini span {
            font-size: .78rem;
            opacity: .7;
        }

        .dash-nav {
            padding: 16px 0;
        }
        .dash-nav .nav-section {
            padding: 8px 20px 4px;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: .45;
            font-weight: 600;
        }
        .dash-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,.78);
            text-decoration: none;
            font-weight: 500;
            font-size: .9rem;
            border-left: 3px solid transparent;
            transition: all .2s;
        }
        .dash-nav a:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }
        .dash-nav a.active {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-left-color: #5de8d5;
            font-weight: 600;
        }
        .dash-nav a i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }

        /* ── MAIN CONTENT ─────────────────────── */
        .dash-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        /* Top bar */
        .dash-topbar {
            background: #fff;
            padding: 16px 32px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .dash-topbar h5 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-blue);
        }
        .dash-topbar .breadcrumb {
            margin: 0;
            font-size: .82rem;
        }

        .dash-content {
            padding: 28px 32px 48px;
        }

        /* ── CARDS & STATS ────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,50,99,.06);
            border: 1px solid #e9f0f5;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,50,99,.1);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 14px;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-blue);
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: .82rem;
            color: #6c757d;
            margin-top: 4px;
        }

        .dash-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,50,99,.06);
            border: 1px solid #e9f0f5;
            margin-bottom: 24px;
        }
        .dash-card .card-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f0f2f5;
        }
        .dash-card .card-header-custom h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-blue);
            font-size: 1rem;
        }

        /* ── TABLE STYLES ─────────────────────── */
        .dash-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .dash-table thead th {
            background: #f8fafe;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 2px solid #e9f0f5;
        }
        .dash-table tbody td {
            padding: 14px 16px;
            font-size: .88rem;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }
        .dash-table tbody tr:hover {
            background: #f8fafe;
        }

        /* ── BADGES ───────────────────────────── */
        .badge-status {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-active     { background: #d4f5e4; color: #0f6b3a; }
        .badge-pending    { background: #fff4e5; color: #b86e00; }
        .badge-completed  { background: #e6f5f3; color: #059386; }
        .badge-cancelled  { background: #fde8e8; color: #c0392b; }
        .badge-no_show    { background: #fff0f0; color: #dc3545; }
        .badge-scheduled  { background: #e7f1ff; color: #0056b3; }
        .badge-failed     { background: #fde8e8; color: #c0392b; }
        .badge-paid       { background: #d4f5e4; color: #0f6b3a; }

        /* ── BUTTONS ──────────────────────────── */
        .btn-teal {
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: .85rem;
            transition: all .2s;
        }
        .btn-teal:hover {
            background: #047a6f;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-outline-teal {
            border: 2px solid var(--teal);
            color: var(--teal);
            background: transparent;
            border-radius: 10px;
            padding: 7px 18px;
            font-weight: 600;
            font-size: .85rem;
            transition: all .2s;
        }
        .btn-outline-teal:hover {
            background: var(--teal);
            color: #fff;
        }

        /* ── STARS ────────────────────────────── */
        .stars { color: #f9a825; }
        .stars .bi-star-fill { margin-right: 1px; }

        /* ── RESPONSIVE ───────────────────────── */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 14px; left: 14px;
            z-index: 1050;
            background: var(--dark-blue);
            color: #fff;
            border: none;
            border-radius: 8px;
            width: 42px; height: 42px;
            font-size: 1.3rem;
        }
        @media (max-width: 991px) {
            .dash-sidebar { transform: translateX(-100%); }
            .dash-sidebar.open { transform: translateX(0); }
            .dash-main { margin-left: 0; }
            .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
            .dash-content { padding: 20px 16px 40px; }
            .dash-topbar { padding: 16px; }
        }

        /* ── UTILITY ──────────────────────────── */
        .patient-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--dark-blue));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: .82rem;
            flex-shrink: 0;
        }
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #adb5bd;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: .5;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile toggle -->
    <button class="sidebar-toggle" onclick="document.querySelector('.dash-sidebar').classList.toggle('open')">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <aside class="dash-sidebar">
        <div class="brand">
            <h4><i class="bi bi-heart-pulse"></i> ShinyTooth</h4>
            <small>Patient Portal</small>
        </div>

        <div class="patient-mini">
            <div class="placeholder-avatar"><i class="bi bi-person-fill"></i></div>
            <h6>{{ $patient->name }}</h6>
            <span>{{ $patient->email }}</span>
        </div>

        <nav class="dash-nav">
            <div class="nav-section">Main</div>
            <a href="/patient/dashboard" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Overview
            </a>

            <div class="nav-section">My Account</div>
            <a href="/my-subscription" class="{{ request()->is('my-subscription') ? 'active' : '' }}">
                <i class="bi bi-star-fill"></i> My Subscription
            </a>
            <a href="/patient/profile" class="{{ request()->routeIs('patient.profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> My Profile
            </a>

            <div class="nav-section" style="margin-top:12px"></div>
            <a href="/" style="opacity:.6;">
                <i class="bi bi-house"></i> Back to Website
            </a>
            <a href="#" onclick="doLogout(); return false;" style="opacity:.6; color:#ff8a8a;">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="dash-main">
        <div class="dash-topbar">
            <div>
                <h5>@yield('page-title', 'Dashboard')</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/patient/dashboard" class="text-decoration-none" style="color:var(--teal);">Dashboard</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:.82rem;">{{ now()->format('l, M d, Y') }}</span>
            </div>
        </div>

        <div class="dash-content">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px; border:none; background:#d4f5e4; color:#0f6b3a;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px; border:none;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function doLogout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_type');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_data');

        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
            }
        }).finally(() => {
            window.location.href = '/login';
        });
    }
    </script>
    @stack('scripts')
</body>
</html>

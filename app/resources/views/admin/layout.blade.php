<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShinyTooth Admin')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        :root {
            --teal:       #059386;
            --teal-light: #e6f5f3;
            --teal-dim:   #059386;
            --dark-blue:  #003263;
            --blue:       #003263;
            --sidebar-w:  260px;
            --card-bg:    #ffffff;
            --body-bg:    #f6fbfa;
            --border:     #e9f0f5;
            --text-main:  #1e293b;
            --text-muted: #6c757d;
            --text-sub:   #495057;
        }

        * { scroll-behavior: smooth; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--body-bg);
            color: var(--text-main);
            overflow-x: hidden;
            margin: 0;
        }

        /* ── SIDEBAR ──────────────────────────── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #003263 0%, #004a8f 40%, #059386 100%);
            color: #fff;
            display: flex; flex-direction: column;
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 24px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            display: flex; align-items: center; gap: 12px; flex-shrink: 0;
        }
        .sidebar-brand img { width: 36px; height: 36px; border-radius: 8px; object-fit: contain; }
        .brand-name { font-size: 1.15rem; font-weight: 700; color: #fff; line-height: 1.2; }
        .brand-sub { font-size: .72rem; color: rgba(255,255,255,.65); text-transform: uppercase; letter-spacing: 1.2px; }

        .admin-mini {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .admin-mini .admin-avatar {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 8px;
            font-size: 1.6rem;
            border: 3px solid rgba(255,255,255,.35);
            color: #fff;
        }
        .admin-mini h6 { margin: 0; font-weight: 600; font-size: .95rem; color: #fff; }
        .admin-mini span { font-size: .78rem; opacity: .7; }

        .sidebar-scroll { flex: 1; overflow-y: auto; padding: 16px 0; }
        .menu-section {
            padding: 8px 20px 4px;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: .45;
            font-weight: 600;
            color: #fff;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,.78);
            text-decoration: none;
            font-weight: 500; font-size: .9rem;
            border-left: 3px solid transparent;
            transition: all .2s;
            position: relative;
        }
        .nav-item i { font-size: 1.15rem; width: 22px; text-align: center; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-left-color: #5de8d5;
            font-weight: 600;
        }
        .nav-badge {
            margin-left: auto;
            background: #ff6b6b;
            color: #fff;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: .72rem;
            font-weight: 600;
        }

        .sidebar-foot { padding: 12px 14px; border-top: 1px solid rgba(255,255,255,.12); flex-shrink: 0; }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 10px;
            color: rgba(255,255,255,.85);
            font-size: .85rem; font-weight: 500;
            cursor: pointer; transition: all .2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,.25); color: #fca5a5; }
        .logout-btn i { font-size: 1.1rem; }

        /* ── MAIN CONTENT ─────────────────────── */
        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* Top bar */
        .topbar {
            background: #fff;
            padding: 16px 32px;
            border-bottom: 1px solid #e9ecef;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 1020; flex-shrink: 0;
        }
        .topbar-title { font-size: 1.1rem; font-weight: 700; color: var(--dark-blue); margin: 0; }
        .topbar-crumb { font-size: .82rem; color: var(--text-muted); margin-top: 2px; }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--dark-blue));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: .85rem;
            transition: all .2s; flex-shrink: 0;
        }
        .user-avatar:hover { box-shadow: 0 0 0 3px rgba(5,147,134,.25); }
        .user-name-text { font-size: .82rem; font-weight: 600; color: var(--dark-blue); }
        .user-role-text { font-size: .67rem; color: var(--teal); text-transform: uppercase; letter-spacing: .8px; }

        .page-body { padding: 28px 32px 48px; flex: 1; }

        /* ── STAT CARDS ───────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,50,99,.06);
            border: 1px solid #e9f0f5;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,50,99,.1);
        }
        .stat-card-bg-icon {
            position: absolute; right: 14px; bottom: 10px;
            font-size: 52px; opacity: .06; color: var(--dark-blue); pointer-events: none;
        }
        .stat-badge {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 14px;
        }
        .stat-num { font-size: 1.75rem; font-weight: 700; color: var(--dark-blue); line-height: 1; }
        .stat-lbl { font-size: .82rem; color: var(--text-muted); margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; font-size: .72rem; }
        .stat-hint { font-size: .75rem; color: #adb5bd; margin-top: 3px; }

        /* ── PANELS (cards) ───────────────────── */
        .panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,50,99,.06);
            border: 1px solid #e9f0f5;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .panel-head {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f2f5;
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-head-title {
            font-size: 1rem; font-weight: 700; color: var(--dark-blue);
            display: flex; align-items: center; gap: 8px;
        }
        .panel-head-title i { color: var(--teal); font-size: 1.1rem; }
        .view-all-link {
            font-size: .82rem; font-weight: 600; color: var(--teal); text-decoration: none;
            padding: 6px 16px;
            border: 2px solid var(--teal);
            border-radius: 10px;
            transition: all .2s;
        }
        .view-all-link:hover { background: var(--teal); color: #fff; }

        /* ── TABLE ────────────────────────────── */
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .data-table thead th {
            background: #f8fafe;
            font-size: .78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .5px;
            color: #6c757d;
            padding: 12px 16px;
            border-bottom: 2px solid #e9f0f5;
            white-space: nowrap;
        }
        .data-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f2f5;
            font-size: .88rem; color: var(--text-sub);
            vertical-align: middle;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #f8fafe; }
        .data-table td strong { color: var(--dark-blue); font-weight: 600; }

        /* ── BADGES ───────────────────────────── */
        .badge-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 14px; border-radius: 20px;
            font-size: .75rem; font-weight: 600;
        }
        .badge-status::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: currentColor; flex-shrink: 0;
        }
        .badge-status.completed  { background: #d4f5e4; color: #0f6b3a; }
        .badge-status.pending    { background: #fff4e5; color: #b86e00; }
        .badge-status.confirmed  { background: #e7f1ff; color: #0056b3; }
        .badge-status.cancelled  { background: #fde8e8; color: #c0392b; }
        .badge-status.failed     { background: #fde8e8; color: #c0392b; }
        .badge-status.active     { background: #d4f5e4; color: #0f6b3a; }
        .badge-status.inactive   { background: #e9ecef; color: #495057; }
        .badge-status.on_leave   { background: #fff4e5; color: #b86e00; }
        .badge-status.scheduled  { background: #e7f1ff; color: #0056b3; }
        .badge-status.no_show    { background: #fff0f0; color: #dc3545; }

        /* ── BUTTONS ──────────────────────────── */
        .btn-teal {
            background: var(--teal); color: #fff; border: none;
            border-radius: 10px; padding: 8px 20px;
            font-weight: 600; font-size: .85rem;
            transition: all .2s; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-teal:hover { background: #047a6f; color: #fff; transform: translateY(-1px); }
        .btn-outline-teal {
            border: 2px solid var(--teal); color: var(--teal);
            background: transparent; border-radius: 10px;
            padding: 7px 18px; font-weight: 600; font-size: .85rem;
            transition: all .2s; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-outline-teal:hover { background: var(--teal); color: #fff; }
        .btn-primary {
            background: var(--teal); border: none; color: white;
            padding: 8px 20px; border-radius: 10px;
            font-weight: 600; font-size: .85rem;
            cursor: pointer; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-primary:hover { background: #047a6f; color: white; transform: translateY(-1px); }

        /* ── FORM CONTROLS ────────────────────── */
        .form-control, .form-select {
            background: #fff; border: 1px solid #dee2e6;
            color: var(--text-main); border-radius: 10px;
            padding: 10px 14px; font-size: .88rem;
        }
        .form-control:focus, .form-select:focus {
            background: #fff; color: var(--text-main);
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(5,147,134,.12); outline: none;
        }
        .form-label { font-size: .82rem; font-weight: 600; color: var(--text-muted); margin-bottom: 5px; display: block; }

        /* ── FILTER PANEL ─────────────────────── */
        .filter-panel {
            background: #fff; border: 1px solid #e9f0f5;
            border-radius: 16px; padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0,50,99,.04);
        }

        /* ── PAGINATION ───────────────────────── */
        .pagination { gap: 3px; }
        .page-link {
            background: #fff; border: 1px solid #dee2e6;
            color: var(--text-muted); border-radius: 8px !important;
            font-size: .82rem; padding: 7px 13px;
        }
        .page-link:hover { background: var(--teal-light); color: var(--teal); border-color: var(--teal); }
        .page-item.active .page-link { background: var(--teal); border-color: var(--teal); color: #fff; font-weight: 700; }

        /* ── CHART ────────────────────────────── */
        .chart-box { position: relative; height: 280px; }

        /* ── MODALS ───────────────────────────── */
        .modal-content {
            background: #fff; border: none;
            border-radius: 16px; color: var(--text-main);
            box-shadow: 0 20px 60px rgba(0,50,99,.15);
        }
        .modal-header { border-bottom: 1px solid #f0f2f5; padding: 18px 24px; }
        .modal-header .modal-title { font-size: 1rem; font-weight: 700; color: var(--dark-blue); }
        .modal-body { padding: 20px 24px; font-size: .88rem; color: var(--text-sub); }
        .modal-body p { margin-bottom: 8px; }
        .modal-body strong { color: var(--dark-blue); }
        .modal-footer { border-top: 1px solid #f0f2f5; padding: 14px 24px; }

        /* ── INFO BUTTON ──────────────────────── */
        .btn-info {
            background: #e7f1ff; border: 1px solid #b8d4fe;
            color: #0056b3; font-size: .78rem; font-weight: 600;
            padding: 5px 12px; border-radius: 8px; transition: all .18s;
        }
        .btn-info:hover { background: #d0e3ff; color: #003d82; }

        /* ── COUNT BADGE ──────────────────────── */
        .count-badge {
            display: inline-block; background: var(--teal-light);
            color: var(--teal); font-size: .75rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            border: 1px solid rgba(5,147,134,.2);
        }

        /* ── UTILITY ──────────────────────────── */
        .patient-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--dark-blue));
            color: #fff; display: inline-flex;
            align-items: center; justify-content: center;
            font-weight: 600; font-size: .82rem; flex-shrink: 0;
        }
        .empty-state {
            text-align: center; padding: 48px 20px; color: #adb5bd;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; opacity: .5; }

        /* ── RESPONSIVE ───────────────────────── */
        .sidebar-toggle {
            display: none;
            position: fixed; top: 14px; left: 14px;
            z-index: 1050;
            background: var(--dark-blue); color: #fff;
            border: none; border-radius: 8px;
            width: 42px; height: 42px; font-size: 1.3rem;
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
            .page-body { padding: 20px 16px 40px; }
            .topbar { padding: 16px; }
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <!-- Mobile toggle -->
    <button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" style="text-decoration:none;">
            <i class="bi bi-heart-pulse" style="font-size:1.5rem;color:#5de8d5;"></i>
            <div>
                <div class="brand-name">ShinyTooth</div>
                <div class="brand-sub">Admin Console</div>
            </div>
        </a>

        <div class="admin-mini">
            <div class="admin-avatar">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h6>{{ auth()->user()->name ?? 'Admin' }}</h6>
            <span>Administrator</span>
        </div>

        <div class="sidebar-scroll">
            <div class="menu-section">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
            </a>

            <div class="menu-section">Manage</div>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i><span>Users</span>
            </a>
            <a href="{{ route('admin.appointments') }}" class="nav-item {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i><span>Appointments</span>
            </a>
            <a href="{{ route('admin.payments') }}" class="nav-item {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front-fill"></i><span>Payments</span>
            </a>
            <a href="{{ route('admin.services') }}" class="nav-item {{ request()->routeIs('admin.services') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i><span>Services</span>
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i><span>Subscriptions</span>
                @php
                    $pendingAdminActions = \App\Models\DoctorSubscription::where('admin_action_status', '!=', 'none')->count();
                @endphp
                @if($pendingAdminActions > 0)
                    <span class="nav-badge">{{ $pendingAdminActions }}</span>
                @endif
            </a>

            <div class="menu-section">Analytics</div>
            <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i><span>Analytics & Reports</span>
            </a>

            <div class="menu-section">Settings</div>
            <a href="{{ route('admin.vacations') }}" class="nav-item {{ request()->routeIs('admin.vacations') ? 'active' : '' }}">
                <i class="bi bi-calendar2-minus"></i><span>Vacation Requests</span>
                @php
                    $pendingVacations = \App\Models\VacationRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingVacations > 0)
                    <span class="nav-badge">{{ $pendingVacations }}</span>
                @endif
            </a>

            <div class="menu-section" style="margin-top:12px"></div>
            <a href="/" class="nav-item" style="opacity:.6;">
                <i class="bi bi-box-arrow-left"></i><span>Back to Website</span>
            </a>
        </div>

        <div class="sidebar-foot">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-power"></i><span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="main-wrap">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-crumb">
                    <a href="{{ route('admin.dashboard') }}" style="color:var(--teal);text-decoration:none;">Dashboard</a>
                    &rsaquo; @yield('page-title', 'Dashboard')
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:.82rem;">{{ now()->format('l, M d, Y') }}</span>
                <div class="text-end">
                    <div class="user-name-text">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                    <div class="user-role-text">Administrator</div>
                </div>
                <div class="user-avatar" style="border:none;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="page-body">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-dismissible fade show mb-4" role="alert" style="border-radius:12px; border:none; background:#d4f5e4; color:#0f6b3a; font-weight:500;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-dismissible fade show mb-4" role="alert" style="border-radius:12px; border:none; background:#fde8e8; color:#c0392b; font-weight:500;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-dismissible fade show mb-4" role="alert" style="border-radius:12px; border:none; background:#fde8e8; color:#c0392b;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>
</html>

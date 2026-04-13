<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShinyTooth Admin')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        :root {
            --sidebar-bg: #0d1117;
            --sidebar-w: 240px;
            --body-bg: #0f1623;
            --card-bg: #161f2e;
            --border: #1e2d42;
            --teal: #00c9b1;
            --teal-dim: #059386;
            --blue: #003263;
            --text-main: #e8edf5;
            --text-muted: #64748b;
            --text-sub: #94a3b8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: var(--body-bg); color: var(--text-main); overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0; width: var(--sidebar-w);
            background: var(--sidebar-bg); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-brand {
            padding: 18px 16px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px; flex-shrink: 0;
        }
        .sidebar-brand img { width: 36px; height: 36px; border-radius: 8px; object-fit: contain; }
        .brand-name { font-size: 0.95rem; font-weight: 700; color: var(--text-main); line-height: 1.2; }
        .brand-sub { font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.2px; }

        .sidebar-scroll { flex: 1; overflow-y: auto; padding: 10px 8px; }
        .menu-section { font-size: 0.62rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 10px 5px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            border-radius: 8px; color: var(--text-sub); text-decoration: none;
            font-size: 0.84rem; font-weight: 500; transition: all 0.18s;
            margin-bottom: 1px; position: relative;
        }
        .nav-item i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }
        .nav-item:hover { background: rgba(0,201,177,0.07); color: var(--teal); }
        .nav-item.active {
            background: rgba(0,201,177,0.12); color: var(--teal); font-weight: 600;
        }
        .nav-item.active::after {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 22px; background: var(--teal); border-radius: 0 3px 3px 0;
        }
        .sidebar-foot { padding: 12px 8px; border-top: 1px solid var(--border); flex-shrink: 0; }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; width: 100%;
            padding: 9px 12px; background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.15); border-radius: 8px;
            color: #f87171; font-size: 0.84rem; font-weight: 500;
            cursor: pointer; transition: all 0.18s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.14); color: #fca5a5; }
        .logout-btn i { font-size: 15px; }

        /* ── Main ── */
        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: var(--sidebar-bg); border-bottom: 1px solid var(--border);
            padding: 0 24px; height: 58px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 50; flex-shrink: 0;
        }
        .topbar-title { font-size: 1rem; font-weight: 700; color: var(--text-main); }
        .topbar-crumb { font-size: 0.7rem; color: var(--text-muted); margin-top: 2px; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--teal-dim));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.85rem;
            transition: opacity .18s, box-shadow .18s;
        }
        .user-avatar:hover { opacity: .82; box-shadow: 0 0 0 3px rgba(0,201,177,0.35); }
        .user-name-text { font-size: 0.82rem; font-weight: 600; color: var(--text-main); }
        .user-role-text { font-size: 0.67rem; color: var(--teal); text-transform: uppercase; letter-spacing: 0.8px; }

        .page-body { padding: 24px; flex: 1; }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 12px; padding: 20px 18px; position: relative;
            overflow: hidden; transition: border-color 0.2s, transform 0.2s;
        }
        .stat-card:hover { border-color: rgba(0,201,177,0.4); transform: translateY(-2px); }
        .stat-card-bg-icon {
            position: absolute; right: 12px; bottom: 8px;
            font-size: 52px; opacity: 0.05; color: white; pointer-events: none;
        }
        .stat-badge {
            width: 38px; height: 38px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center; font-size: 17px;
            margin-bottom: 14px;
        }
        .stat-num { font-size: 1.85rem; font-weight: 800; color: var(--text-main); line-height: 1; }
        .stat-lbl { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-top: 5px; }
        .stat-hint { font-size: 0.7rem; color: var(--text-muted); margin-top: 4px; }

        /* ── Panel (card replacement) ── */
        .panel { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .panel-head {
            padding: 13px 18px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-head-title { font-size: 0.86rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; }
        .panel-head-title i { color: var(--teal); font-size: 15px; }
        .view-all-link {
            font-size: 0.73rem; font-weight: 600; color: var(--teal); text-decoration: none;
            padding: 4px 12px; border: 1px solid rgba(0,201,177,0.25); border-radius: 20px;
            transition: all 0.18s;
        }
        .view-all-link:hover { background: rgba(0,201,177,0.08); color: var(--teal); }

        /* ── Table ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            background: rgba(0,0,0,0.25); color: var(--text-muted);
            font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; padding: 10px 16px; border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .data-table tbody td {
            padding: 11px 16px; border-bottom: 1px solid var(--border);
            font-size: 0.83rem; color: var(--text-sub); vertical-align: middle;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(255,255,255,0.018); }
        .data-table td strong { color: var(--text-main); font-weight: 600; }

        /* ── Badge ── */
        .badge-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.67rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-status::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
        .badge-status.completed { background: rgba(34,197,94,0.1); color: #4ade80; }
        .badge-status.pending   { background: rgba(234,179,8,0.1); color: #facc15; }
        .badge-status.confirmed { background: rgba(59,130,246,0.1); color: #60a5fa; }
        .badge-status.cancelled { background: rgba(239,68,68,0.1); color: #f87171; }
        .badge-status.failed    { background: rgba(239,68,68,0.1); color: #f87171; }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, var(--blue), var(--teal-dim));
            border: none; color: white; padding: 8px 18px; border-radius: 8px;
            font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-primary:hover { opacity: 0.88; color: white; transform: translateY(-1px); }

        /* ── Form controls ── */
        .form-control, .form-select {
            background: var(--body-bg); border: 1px solid var(--border);
            color: var(--text-main); border-radius: 8px; padding: 9px 14px; font-size: 0.84rem;
        }
        .form-control:focus, .form-select:focus {
            background: var(--body-bg); color: var(--text-main);
            border-color: var(--teal); box-shadow: 0 0 0 3px rgba(0,201,177,0.12); outline: none;
        }
        .form-label { font-size: 0.77rem; font-weight: 600; color: var(--text-muted); margin-bottom: 5px; display: block; }

        /* ── Filter panel ── */
        .filter-panel { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; padding: 16px 18px; margin-bottom: 20px; }

        /* ── Pagination ── */
        .pagination { gap: 3px; }
        .page-link { background: var(--card-bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 7px !important; font-size: 0.79rem; padding: 6px 12px; }
        .page-link:hover { background: rgba(0,201,177,0.08); color: var(--teal); border-color: rgba(0,201,177,0.3); }
        .page-item.active .page-link { background: var(--teal); border-color: var(--teal); color: #0d1117; font-weight: 700; }

        /* ── Chart ── */
        .chart-box { position: relative; height: 280px; }

        /* ── Dark Modals ── */
        .modal-content { background: #1a2332; border: 1px solid var(--border); color: var(--text-main); }
        .modal-header { border-bottom: 1px solid var(--border); padding: 14px 20px; }
        .modal-header .modal-title { font-size: 0.92rem; font-weight: 700; color: var(--text-main); }
        .modal-body { padding: 18px 20px; font-size: 0.84rem; color: var(--text-sub); }
        .modal-body p { margin-bottom: 8px; }
        .modal-body strong { color: var(--text-main); }
        .btn-close { filter: invert(1) brightness(0.7); }

        /* ── Info button ── */
        .btn-info { background: rgba(96,165,250,0.12); border: 1px solid rgba(96,165,250,0.25); color: #60a5fa; font-size: 0.75rem; font-weight: 600; padding: 4px 10px; border-radius: 6px; transition: all 0.18s; }
        .btn-info:hover { background: rgba(96,165,250,0.22); color: #93c5fd; }

        /* ── Inline badge ── */
        .count-badge { display: inline-block; background: rgba(0,201,177,0.12); color: #00c9b1; font-size: 0.7rem; font-weight: 700; padding: 2px 9px; border-radius: 20px; border: 1px solid rgba(0,201,177,0.2); }
    </style>
    @yield('extra-css')
</head>
<body>

<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" style="text-decoration:none;">
        <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth">
        <div>
            <div class="brand-name">ShinyTooth</div>
            <div class="brand-sub">Admin Console</div>
        </div>
    </a>
    <div class="sidebar-scroll">
        <div class="menu-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
        <div class="menu-section">Manage</div>
        <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Users</span>
        </a>
        <a href="{{ route('admin.appointments') }}" class="nav-item {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check-fill"></i><span>Appointments</span>
        </a>
        <a href="{{ route('admin.payments') }}" class="nav-item {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
            <i class="bi bi-credit-card-2-front-fill"></i><span>Payments</span>
        </a>
        <a href="{{ route('admin.services') }}" class="nav-item {{ request()->routeIs('admin.services') ? 'active' : '' }}">
            <i class="bi bi-briefcase-fill"></i><span>Services</span>
        </a>
        <div class="menu-section">Reports</div>
        <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i><span>Analytics</span>
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

<div class="main-wrap">
    <header class="topbar">
        <div>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-crumb">ShinyTooth &rsaquo; @yield('page-title', 'Dashboard')</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="user-name-text">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div class="user-role-text">Administrator</div>
            </div>
            <div class="dropdown">
                <button class="user-avatar" data-bs-toggle="dropdown" aria-expanded="false" style="border:none;cursor:pointer;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="background:#1a2332;border:1px solid var(--border);min-width:160px;padding:6px;">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color:#f87171;background:transparent;border:none;width:100%;text-align:left;padding:8px 14px;border-radius:6px;font-size:0.83rem;font-weight:600;cursor:pointer;">
                                <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="page-body">
        @yield('content')
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@yield('extra-js')
</body>
</html>

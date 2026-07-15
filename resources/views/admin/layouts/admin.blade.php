<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | IBA Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        :root{ --iba-navy:#0f2a4a; --iba-teal:#0fb98a; }
        .brand-link{ background:var(--iba-navy); border-bottom:1px solid rgba(255,255,255,.1); }
        .brand-text{ color:#fff; font-weight:800; letter-spacing:.5px; }
        .main-sidebar{ background:var(--iba-navy); }
        .nav-sidebar .nav-link{ color:#c7d2e0; border-radius:8px; margin-bottom:2px; }
        .nav-sidebar .nav-link.active{ background:var(--iba-teal); color:#fff; font-weight:600; }
        .nav-sidebar .nav-link:hover{ color:#fff; }
        .card{ border-radius:14px; border:1px solid #eef0f2; box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .card-header{ border-radius:14px 14px 0 0 !important; background:#fff; }
        .admin-pill{ background:#eaf6ff; color:var(--iba-navy); font-size:.7rem; padding:2px 10px; border-radius:20px; font-weight:700; letter-spacing:.5px; }
        .badge-pending{ background:#fff3cd; color:#8a6d00; }
        .badge-approved{ background:#d7f7ea; color:#0a7a52; }
        .badge-changes{ background:#ffe3d9; color:#b34700; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
            <li class="nav-item d-none d-sm-inline-block"><span class="admin-pill">INTERNAL ADMIN PANEL</span></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle mr-1"></i> {{ auth()->user()->name ?? 'Staff' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form method="POST" action="{{ url('/admin/logout') }}">@csrf
                        <button class="dropdown-item">Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-1">
        <a href="{{ url('/admin/dashboard') }}" class="brand-link">
            <span class="brand-text">IBA <small class="d-block text-white-50" style="font-size:.6rem;letter-spacing:2px;">ADMIN PANEL</small></span>
        </a>
        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i><p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/companies') }}" class="nav-link {{ request()->is('admin/companies*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i><p>Client Companies</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/samples') }}" class="nav-link {{ request()->is('admin/samples*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tshirt"></i><p>Samples</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/orders') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i><p>Orders</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/shipments') }}" class="nav-link {{ request()->is('admin/shipments*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i><p>Shipments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/finance') }}" class="nav-link {{ request()->is('admin/finance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dollar-sign"></i><p>Finance / Ledger</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i><p>Staff & Client Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/audit-logs') }}" class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i><p>Audit Logs</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper" style="background:#f7f8fa;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">@yield('title')</h1></div></div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer"><strong>&copy; {{ date('Y') }} IBA Just In Time Garments.</strong> Internal Admin Panel</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>

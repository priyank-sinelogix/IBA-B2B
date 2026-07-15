<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | IBA Partner Portal</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.1/css/OverlayScrollbars.min.css">

    <style>
        :root{
            --iba-navy:#0f2a4a;
            --iba-teal:#0fb98a;
        }
        .brand-link{ background:#fff; border-bottom:1px solid #eef0f2; }
        .brand-text{ color:var(--iba-navy); font-weight:800; letter-spacing:.5px; }
        .main-sidebar{ background:#fff; }
        .nav-sidebar .nav-link.active{ background:#eaf6ff; color:var(--iba-navy); border-radius:8px; font-weight:600; }
        .nav-sidebar .nav-link{ color:#4a5568; border-radius:8px; margin-bottom:2px; }
        .small-box{ border-radius:14px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
        .card{ border-radius:14px; border:1px solid #eef0f2; box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .card-header{ border-radius:14px 14px 0 0 !important; background:#fff; }
        .badge-pending{ background:#fff3cd; color:#8a6d00; }
        .badge-approved{ background:#d7f7ea; color:#0a7a52; }
        .badge-changes{ background:#ffe3d9; color:#b34700; }
        .btn-iba{ background:var(--iba-navy); color:#fff; }
        .btn-iba:hover{ background:#0b1f38; color:#fff; }
        .navbar-white{ border-bottom:1px solid #eef0f2; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Topbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link"><i class="fas fa-building mr-1"></i> {{ auth()->user()->company->name ?? 'Oceanic Apparel Ltd.' }}</span>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="#"><i class="far fa-bell"></i></a></li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle mr-1"></i> {{ auth()->user()->name ?? 'Alex Kumar' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">Profile</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ url('/logout') }}">@csrf
                        <button class="dropdown-item">Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-light-primary elevation-1">
        <a href="{{ url('/dashboard') }}" class="brand-link">
            <span class="brand-text">IBA <small class="d-block text-muted" style="font-size:.6rem;letter-spacing:2px;">JUST IN TIME GARMENTS</small></span>
        </a>
        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i><p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/samples') }}" class="nav-link {{ request()->is('samples*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tshirt"></i><p>Sampling</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/samples?status=pending') }}" class="nav-link">
                            <i class="nav-icon fas fa-check-circle"></i><p>Approvals</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/orders') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i><p>Orders</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/shipments') }}" class="nav-link {{ request()->is('shipments*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i><p>Shipments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/finance') }}" class="nav-link {{ request()->is('finance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dollar-sign"></i><p>Finance</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/messages') }}" class="nav-link {{ request()->is('messages*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comment-dots"></i><p>Messages</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/documents') }}" class="nav-link {{ request()->is('documents*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-folder"></i><p>Documents</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper" style="background:#f7f8fa;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">@yield('title')</h1></div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} IBA Just In Time Garments.</strong> Partner Portal
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>

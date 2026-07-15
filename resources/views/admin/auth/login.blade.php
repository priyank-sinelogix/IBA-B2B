<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Login | IBA Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body{ background:#0f2a4a; height:100vh; display:flex; align-items:center; justify-content:center; }
        .login-card{ width:420px; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.25); padding:40px 36px; background:#fff; }
        .brand{ font-weight:800; font-size:2rem; color:#0f2a4a; letter-spacing:1px; }
        .brand small{ display:block; font-size:.6rem; letter-spacing:3px; color:#7a8699; font-weight:700; }
        .form-control{ border-radius:10px; padding:22px 14px; }
        .btn-login{ background:#0fb98a; color:#fff; border-radius:10px; padding:12px; font-weight:600; width:100%; }
        .btn-login:hover{ background:#0d9c75; color:#fff; }
        .staff-badge{ background:#eaf6ff; color:#0f2a4a; font-size:.75rem; padding:4px 10px; border-radius:20px; font-weight:600; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-3">
            <div class="brand">IBA<small>JUST IN TIME GARMENTS</small></div>
        </div>
        <div class="text-center mb-4">
            <span class="staff-badge"><i class="fas fa-user-shield mr-1"></i> Internal Team Access</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@ibacrafts.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••••" required>
            </div>
            <button type="submit" class="btn btn-login mt-3">Sign In to Admin Panel</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ url('/login') }}" class="small text-muted">Are you a customer? Go to Customer Login →</a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Login | IBA Partner Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body{ background:#eef1f3; height:100vh; display:flex; align-items:center; justify-content:center; }
        .login-card{ width:420px; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.08); padding:40px 36px; background:#fff; }
        .brand{ font-weight:800; font-size:2rem; color:#0f2a4a; letter-spacing:1px; }
        .brand small{ display:block; font-size:.6rem; letter-spacing:3px; color:#7a8699; font-weight:700; }
        .form-control{ border-radius:10px; padding:22px 14px; }
        .btn-login{ background:#0f2a4a; color:#fff; border-radius:10px; padding:12px; font-weight:600; width:100%; }
        .btn-login:hover{ background:#0b1f38; color:#fff; }
        .secure-note{ font-size:.8rem; color:#0fb98a; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="brand">IBA<small>JUST IN TIME GARMENTS</small></div>
        </div>
        <h4 class="text-center mb-4" style="font-weight:700;">Customer Login</h4>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••••" required>
                <a href="{{ url('/password/reset') }}" class="float-right mt-1" style="font-size:.85rem;">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-login mt-3">Sign In</button>
        </form>

        <div class="text-center mt-4 secure-note">
            <i class="fas fa-shield-alt"></i> Secure access for approved B2B clients
        </div>
    </div>
</body>
</html>

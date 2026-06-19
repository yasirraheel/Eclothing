@php
    $setting = \App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ $setting->site_name ?? config('app.name') }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <style>
        .auth-page-wrapper {
            min-height: calc(100vh - 60px);
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .auth-card {
            background: var(--white);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .auth-card h1 {
            font-size: 22px;
            font-weight: 500;
            color: var(--daraz-dark);
            margin-bottom: 25px;
        }
        .auth-card .form-group {
            margin-bottom: 18px;
        }
        .auth-card label {
            display: block;
            font-size: 13px;
            color: var(--daraz-dark);
            margin-bottom: 6px;
            font-weight: 500;
        }
        .auth-card input[type="email"],
        .auth-card input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 2px;
            font-size: 14px;
            color: var(--daraz-dark);
            background: #fff;
            outline: none;
            transition: border-color 0.2s;
        }
        .auth-card input:focus {
            border-color: var(--daraz-orange);
        }
        .auth-card .text-danger {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }
        .auth-card .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--daraz-gray);
        }
        .auth-card .remember-row a {
            color: var(--daraz-orange);
            text-decoration: none;
        }
        .auth-card .btn-submit {
            width: 100%;
            background: var(--daraz-orange);
            color: #fff;
            border: none;
            padding: 13px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 2px;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: background 0.2s;
        }
        .auth-card .btn-submit:hover {
            background: var(--daraz-orange-hover);
        }
        .auth-card .auth-alt {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--daraz-gray);
        }
        .auth-card .auth-alt a {
            color: var(--daraz-orange);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-wrapper">
        <div class="container header">
            <a href="{{ route('home') }}" class="logo">
                @if($setting && $setting->logo)
                    <img src="{{ Storage::url($setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}">
                @else
                    {{ $setting->site_name ?? 'Eclothing' }}
                @endif
            </a>

            <form action="{{ route('home') }}" method="GET" class="search-box">
                <input type="text" name="q" placeholder="Search {{ $setting->site_name ?? 'Eclothing' }}">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="auth-links">
                <a href="{{ route('register') }}">Create Account</a>
            </div>

            <a href="{{ route('cart.index') }}" class="cart-icon">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-label">Cart</span>
            </a>
        </div>
    </div>

    <!-- Nav Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-bar-links">
                <a href="{{ route('home') }}">All</a>
                <a href="#">Today's Deals</a>
                <a href="#">Customer Service</a>
            </div>
        </div>
    </div>

    <!-- Auth Form -->
    <div class="auth-page-wrapper">
        <div class="auth-card">
            <h1>Welcome to {{ $setting->site_name ?? config('app.name') }}! Please login.</h1>

            @if(session('status'))
                <div style="background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:2px; font-size:13px; margin-bottom:15px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-row">
                    <label style="font-weight:400; cursor:pointer;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">LOGIN</button>
            </form>

            <div class="auth-alt">
                New member? <a href="{{ route('register') }}">Register here.</a>
            </div>
        </div>
    </div>

</body>
</html>

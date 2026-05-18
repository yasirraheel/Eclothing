@php
    $setting = \App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - {{ $setting->site_name ?? config('app.name') }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <style>
        .auth-page-wrapper {
            min-height: calc(100vh - 60px);
            background-color: var(--daraz-light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .auth-card {
            background: var(--white);
            width: 100%;
            max-width: 460px;
            padding: 40px 35px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .auth-card .otp-icon {
            font-size: 48px;
            color: var(--daraz-orange);
            margin-bottom: 15px;
        }
        .auth-card h1 {
            font-size: 22px;
            font-weight: 500;
            color: var(--daraz-dark);
            margin-bottom: 10px;
        }
        .auth-card p {
            font-size: 13px;
            color: var(--daraz-gray);
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .auth-card .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        .auth-card label {
            display: block;
            font-size: 13px;
            color: var(--daraz-dark);
            margin-bottom: 6px;
            font-weight: 500;
        }
        .auth-card input[type="text"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #ddd;
            border-radius: 2px;
            font-size: 20px;
            color: var(--daraz-dark);
            background: #fff;
            outline: none;
            letter-spacing: 8px;
            text-align: center;
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
            text-align: center;
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
        .auth-card .resend-link {
            margin-top: 18px;
            font-size: 13px;
            color: var(--daraz-gray);
        }
        .auth-card .resend-link button {
            background: none;
            border: none;
            color: var(--daraz-orange);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
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
                <a href="{{ route('login') }}">Sign in</a>
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

    <!-- OTP Form -->
    <div class="auth-page-wrapper">
        <div class="auth-card">
            <div class="otp-icon"><i class="fa-regular fa-envelope"></i></div>
            <h1>Verify Your Email</h1>
            <p>
                We sent a 6-digit OTP to <strong>{{ auth()->user()->email }}</strong>.<br>
                Please enter the code below to verify your account.
            </p>

            @if(session('status'))
                <div style="background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:2px; font-size:13px; margin-bottom:15px;">
                    {{ session('status') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#ffebee; color:#c62828; padding:10px 14px; border-radius:2px; font-size:13px; margin-bottom:15px;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify.otp') }}">
                @csrf
                <div class="form-group">
                    <label>Enter OTP Code</label>
                    <input type="text" name="otp" maxlength="6" placeholder="——————" required autofocus>
                    @error('otp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn-submit">VERIFY OTP</button>
            </form>

            <div class="resend-link">
                Didn't receive the code?
                <form method="POST" action="{{ route('verification.send') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Resend OTP</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

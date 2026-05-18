@php
    $setting = \App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ $setting->site_name ?? config('app.name') }}</title>
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
        .auth-card input[type="text"],
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
            margin-top: 5px;
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

    <!-- Auth Form -->
    <div class="auth-page-wrapper">
        <div class="auth-card">
            <h1>Create your {{ $setting->site_name ?? config('app.name') }} Account</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Create a password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn-submit">SIGN UP</button>
            </form>

            <div class="auth-alt">
                Already have an account? <a href="{{ route('login') }}">Login here.</a>
            </div>
        </div>
    </div>

</body>
</html>

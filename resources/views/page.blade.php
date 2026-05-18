<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $page->title }} - {{ $setting->site_name ?? config('app.name') }}</title>
    
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    
    <style>
        .page-content {
            background: var(--white);
            padding: 40px;
            margin: 40px auto;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            min-height: 50vh;
        }
        .page-content h1 {
            color: var(--daraz-dark);
            margin-bottom: 20px;
            font-size: 28px;
            border-bottom: 1px solid var(--daraz-border);
            padding-bottom: 10px;
        }
        .page-content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .page-content {
            overflow: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .page-content img,
        .page-content table,
        .page-content iframe,
        .page-content video,
        .page-content embed {
            max-width: 100%;
            height: auto;
        }
        .page-content table {
            display: block;
            overflow-x: auto;
        }
        .page-content pre {
            overflow-x: auto;
            max-width: 100%;
        }
        @media (max-width: 576px) {
            .page-content {
                padding: 20px;
                margin: 20px auto;
            }
            .page-content h1 {
                font-size: 22px;
            }
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
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search {{ $setting->site_name ?? 'Eclothing' }}">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="auth-links">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @else
                        <a href="{{ route('dashboard') }}">Account</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Sign in</a>
                @endauth
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
                <a href="#">Gift Cards</a>
                <a href="#">Sell</a>
            </div>
            <div class="top-bar-auth">
                @auth
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                @endauth
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-content">
            <h1>{{ $page->title }}</h1>
            <div>
                {!! $page->content !!}
            </div>
        </div>
    </div>

    <div class="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})">Back to top</div>
    <div class="footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <h4>Get to Know Us</h4>
                <ul>
                    @foreach($customerCarePages as $fPage)
                        <li><a href="{{ route('frontend.page', $fPage->slug) }}">{{ $fPage->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>{{ $setting->site_name ?? 'Eclothing' }}</h4>
                <ul>
                    @foreach($darazPages as $fPage)
                        <li><a href="{{ route('frontend.page', $fPage->slug) }}">{{ $fPage->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul>
                    @if($setting && $setting->site_email)
                        <li><a href="mailto:{{ $setting->site_email }}">{{ $setting->site_email }}</a></li>
                    @endif
                    @if($setting && $setting->site_phone)
                        <li><a href="tel:{{ $setting->site_phone }}">{{ $setting->site_phone }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="footer-col">
                <h4>Connect With Us</h4>
                <div class="footer-social">
                    @if(isset($setting->social_facebook) && $setting->social_facebook)
                        <a href="{{ $setting->social_facebook }}" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    @endif
                    @if(isset($setting->social_twitter) && $setting->social_twitter)
                        <a href="{{ $setting->social_twitter }}" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    @endif
                    @if(isset($setting->social_instagram) && $setting->social_instagram)
                        <a href="{{ $setting->social_instagram }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">&copy; {{ date('Y') }} {{ $setting->site_name ?? 'Eclothing' }}. All rights reserved.</div>

</body>
</html>

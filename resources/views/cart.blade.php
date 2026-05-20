<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart - {{ $setting->site_name ?? config('app.name') }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <style>
        .cart-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            margin: 30px auto 60px auto;
        }
        .cart-items {
            background: var(--white);
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .cart-summary {
            background: var(--white);
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            height: fit-content;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 80px 1fr 120px 100px 50px;
            gap: 15px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--daraz-border);
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--daraz-border);
        }
        .qty-controls {
            display: flex;
            align-items: center;
        }
        .qty-controls form {
            display: flex;
            align-items: center;
        }
        .qty-controls button {
            width: 25px;
            height: 25px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .qty-controls input {
            width: 40px;
            height: 25px;
            text-align: center;
            border: 1px solid #ccc;
            border-left: none;
            border-right: none;
        }
        .btn-checkout {
            background: var(--daraz-orange);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 2px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 15px;
        }
        .btn-checkout:hover {
            background: var(--daraz-orange-hover);
        }
        @media (max-width: 768px) {
            .cart-container { grid-template-columns: 1fr; }
            .cart-item {
                grid-template-columns: 60px 1fr;
                gap: 10px;
            }
            .cart-item img {
                width: 60px;
                height: 60px;
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

    <div class="container cart-container">
        <div class="cart-items">
            <h2 style="font-size:18px; font-weight:500; border-bottom:1px solid var(--daraz-border); padding-bottom:15px; margin-bottom:10px;">Shopping Cart ({{ count($cart) }} Items)</h2>
            
            @if(session('success'))
                <div style="padding:10px; background:#e8f5e9; color:#2e7d32; margin-bottom:15px; border-radius:2px;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="padding:10px; background:#ffebee; color:#c62828; margin-bottom:15px; border-radius:2px;">{{ session('error') }}</div>
            @endif

            @if(count($cart) > 0)
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div class="cart-item">
                        <div>
                            @if($details['image'])
                                <img src="{{ Storage::url($details['image']) }}" alt="{{ $details['name'] }}">
                            @else
                                <div style="width:80px;height:80px;background:#eee;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-image" style="color:#ccc;"></i></div>
                            @endif
                        </div>
                        <div style="font-size:14px; color:var(--daraz-dark);">{{ $details['name'] }}</div>
                        <div style="font-size:16px; color:var(--daraz-orange); font-weight:500;">Rs. {{ number_format($details['price'], 0) }}</div>
                        <div class="qty-controls">
                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" style="width:50px; padding:5px; border:1px solid var(--daraz-border);">
                                <button type="submit" style="padding:5px 10px; background:white; border:1px solid var(--daraz-border); color:var(--daraz-orange); margin-left:5px; cursor:pointer;"><i class="fa-solid fa-rotate"></i></button>
                            </form>
                        </div>
                        <div>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" style="background:none; border:none; color:var(--daraz-gray); cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align:center; padding:40px;">
                    <i class="fa-solid fa-cart-shopping" style="font-size:40px; color:#ccc; margin-bottom:15px;"></i>
                    <p>There are no items in this cart</p>
                    <a href="/" style="display:inline-block; margin-top:15px; padding:10px 20px; border:1px solid var(--daraz-orange); color:var(--daraz-orange); text-decoration:none;">CONTINUE SHOPPING</a>
                </div>
            @endif
        </div>

        <div class="cart-summary">
            <h2 style="font-size:16px; font-weight:500; margin-bottom:15px;">Order Summary</h2>
            @if(count($cart) > 0)
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; color:var(--daraz-gray);">
                    <span>Subtotal ({{ count($cart) }} items)</span>
                    <span style="color:var(--daraz-dark);">Rs. {{ number_format($total, 0) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:14px; color:var(--daraz-gray);">
                    <span>Shipping Fee</span>
                    <span style="color:var(--daraz-dark);">Calculated at Checkout</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:500; padding-top:15px; border-top:1px solid var(--daraz-border);">
                    <span>Total</span>
                    <span style="color:var(--daraz-orange);">Rs. {{ number_format($total, 0) }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" style="text-decoration:none;">
                    <button class="btn-checkout">PROCEED TO CHECKOUT</button>
                </a>
            @else
                <div style="color:var(--daraz-gray); font-size:13px; text-align:center;">Your cart is empty.</div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fa-solid fa-arrow-up"></i>
    </div>

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
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">&copy; {{ date('Y') }} {{ $setting->site_name ?? 'Eclothing' }}. All rights reserved.</div>

</body>
</html>

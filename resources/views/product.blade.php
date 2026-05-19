<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $product->name }} - {{ $setting->site_name ?? config('app.name') }}</title>
    
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    
    <style>
        .breadcrumb {
            padding: 15px 0;
            font-size: 13px;
            color: var(--daraz-gray);
        }
        .breadcrumb a {
            color: var(--daraz-orange);
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .product-main-card {
            background: var(--white);
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: minmax(0, 400px) minmax(0, 1fr);
            gap: 40px;
            overflow: hidden;
        }
        
        .product-gallery {
            position: relative;
        }

        .product-gallery,
        .product-info {
            min-width: 0;
        }
        .product-gallery img {
            width: 100%;
            border-radius: 4px;
            border: 1px solid var(--daraz-border);
        }
        
        .product-info h1 {
            font-size: 22px;
            font-weight: 500;
            color: var(--daraz-dark);
            margin-bottom: 10px;
            line-height: 1.3;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .product-ratings {
            font-size: 13px;
            color: #f5a623;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--daraz-border);
            padding-bottom: 15px;
        }
        
        .product-price-section {
            padding: 15px 0;
            border-bottom: 1px solid var(--daraz-border);
            margin-bottom: 20px;
        }
        
        .price-main {
            font-size: 30px;
            color: var(--daraz-orange);
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .price-old {
            color: var(--daraz-gray);
            text-decoration: line-through;
            font-size: 14px;
        }
        
        .price-discount {
            color: var(--daraz-dark);
            font-size: 14px;
            margin-left: 10px;
            font-weight: 500;
        }
        
        .emi-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 15px;
            max-width: 100%;
            flex-wrap: wrap;
        }
        
        .qty-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .qty-selector span {
            color: var(--daraz-gray);
            font-size: 14px;
        }
        
        .qty-btns {
            display: flex;
            align-items: center;
        }
        
        .qty-btns button {
            width: 32px;
            height: 32px;
            border: 1px solid var(--daraz-border);
            background: #fafafa;
            color: var(--daraz-dark);
            cursor: pointer;
            font-size: 16px;
        }
        
        .qty-btns input {
            width: 50px;
            height: 32px;
            border: 1px solid var(--daraz-border);
            border-left: none;
            border-right: none;
            text-align: center;
            font-size: 14px;
            outline: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn-buy, .btn-cart {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 2px;
            cursor: pointer;
            border: none;
            text-align: center;
            transition: opacity 0.2s;
        }
        
        .btn-buy {
            background: #25a5d8;
            color: white;
        }
        
        .btn-cart {
            background: var(--daraz-orange);
            color: white;
        }
        
        .btn-buy:hover, .btn-cart:hover {
            opacity: 0.9;
        }
        
        .product-description-card {
            background: var(--white);
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 40px;
            overflow: hidden;
        }
        
        .product-description-card h2 {
            font-size: 18px;
            font-weight: 500;
            color: var(--daraz-dark);
            background: #fafafa;
            padding: 15px 20px;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid var(--daraz-border);
        }
        
        .ck-content {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            overflow-x: auto;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .ck-content img,
        .ck-content table,
        .ck-content iframe,
        .ck-content video,
        .ck-content embed {
            max-width: 100%;
            height: auto;
        }

        .ck-content table {
            display: block;
            overflow-x: auto;
        }

        .ck-content pre {
            overflow-x: auto;
            max-width: 100%;
        }
        .ck-content p {
            margin-bottom: 15px;
        }
        
        @media (max-width: 992px) {
            .product-main-card {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .product-gallery img {
                max-width: 400px;
                margin: 0 auto;
                display: block;
            }
        }

        @media (max-width: 576px) {
            .product-main-card {
                padding: 15px;
                gap: 15px;
            }
            .product-info h1 {
                font-size: 18px;
            }
            .price-main {
                font-size: 24px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .emi-badge {
                font-size: 12px;
                word-break: break-word;
            }
            .product-description-card h2 {
                font-size: 16px;
                padding: 12px 15px;
                margin: -15px -15px 15px -15px;
            }
            .product-description-card {
                padding: 15px;
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
        <div class="breadcrumb">
            <a href="/">Home</a> > <a href="/">Products</a> > {{ $product->name }}
        </div>

        <div class="product-main-card">
            <div class="product-gallery">
                @if($product->discount_percentage > 0)
                    <div style="position:absolute; top:10px; right:10px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; padding:4px 12px; font-size:12px; font-weight:700; border-radius:4px; z-index:10;">-{{ round($product->discount_percentage) }}% OFF</div>
                @endif
                @if($product->image)
                    <img src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                @else
                    <div style="width:100%; aspect-ratio:1/1; display:flex; align-items:center; justify-content:center; background:#f8fafc; color:#cbd5e1; font-size:4rem; border:1px solid var(--daraz-border); border-radius:4px;">
                        <i class="fa-solid fa-image"></i>
                    </div>
                @endif
            </div>

            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                
                <div class="product-ratings">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star-half-stroke"></i>
                    <span style="color:#212121; margin-left:5px;">(15 Ratings)</span>
                </div>
                
                <div class="product-price-section">
                    <div class="price-main">Rs. {{ number_format($product->discounted_price, 0) }}</div>
                    @if($product->discount_percentage > 0)
                        <div>
                            <span class="price-old">Rs. {{ number_format($product->cash_sale_price, 0) }}</span>
                            <span class="price-discount">-{{ round($product->discount_percentage) }}%</span>
                        </div>
                    @endif
                    
                    <div class="emi-badge">
                        <i class="fa-solid fa-credit-card"></i> Installment available: Rs. {{ number_format($product->emi_sale_price, 0) }} via EMI
                    </div>
                </div>
                
                <div class="qty-selector">
                    <span>Quantity</span>
                    <div class="qty-btns">
                        <button type="button" onclick="document.getElementById('qty').value = Math.max(1, parseInt(document.getElementById('qty').value) - 1); updateFormQty();">-</button>
                        <input type="text" id="qty" value="1" onchange="updateFormQty()" readonly>
                        <button type="button" onclick="document.getElementById('qty').value = parseInt(document.getElementById('qty').value) + 1; updateFormQty();">+</button>
                    </div>
                    @if($product->stock > 0)
                        <span style="color: #2e7d32; font-weight:500;">In Stock ({{ $product->stock }})</span>
                    @else
                        <span style="color: #d32f2f; font-weight:500;">Out of Stock</span>
                    @endif
                </div>
                
                <div class="action-buttons">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="quantity" id="buy-qty" value="1">
                        <input type="hidden" name="checkout" value="true">
                        <button type="submit" class="btn-buy" style="width:100%;" {{ $product->stock <= 0 ? 'disabled style="background:#ccc;cursor:not-allowed;"' : '' }}>Buy Now</button>
                    </form>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="quantity" id="cart-qty" value="1">
                        <button type="submit" class="btn-cart" style="width:100%;" {{ $product->stock <= 0 ? 'disabled style="background:#ccc;cursor:not-allowed;"' : '' }}>Add to Cart</button>
                    </form>
                </div>
                
                <script>
                    function updateFormQty() {
                        let q = document.getElementById('qty').value;
                        document.getElementById('buy-qty').value = q;
                        document.getElementById('cart-qty').value = q;
                    }
                </script>
            </div>
        </div>

        <div class="product-description-card">
            <h2>Product details of {{ $product->name }}</h2>
            <div class="ck-content">
                @if($product->description)
                    {!! $product->description !!}
                @else
                    <p style="color: var(--daraz-gray);">No detailed description available for this product.</p>
                @endif
            </div>
        </div>
    </div>

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

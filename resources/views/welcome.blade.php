<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- SEO Settings -->
    <title>{{ $setting->seo_title ?? ($setting->site_name ?? config('app.name')) }}</title>
    <meta name="description" content="{{ $setting->seo_description ?? '' }}">
    <meta name="keywords" content="{{ $setting->seo_keywords ?? '' }}">
    
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Frontend CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
        <script src="{{ asset('js/cart.js') }}" defer></script>
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

    <!-- Hero Banner -->
    <div class="hero-slider-container">
        <div class="hero-slider">
            <div class="hero-slide active" style="background: linear-gradient(135deg, #fef9ef 0%, #fdf2e9 50%, #f8e8d4 100%);">
                <div class="hero-slide-inner">
                    <div class="hero-slide-content">
                        <span class="hero-badge-new">New Arrivals</span>
                        <h2 class="slide-title">Summer Fashion Collection</h2>
                        <p class="slide-subtitle">Discover the latest trends in women's fashion. Free shipping on orders over Rs. 2,000.</p>
                        <div class="hero-actions">
                            <a href="#products" class="hero-primary-btn">Shop now</a>
                            <a href="#products" class="hero-secondary-btn">See all deals</a>
                        </div>
                    </div>
                    <div class="hero-slide-image">
                        <img src="{{ asset('images/hero_female.png') }}" alt="Summer Collection">
                    </div>
                </div>
            </div>

            <div class="hero-slide" style="background: linear-gradient(135deg, #eef7f0 0%, #e3f2e8 50%, #d4edda 100%);">
                <div class="hero-slide-inner">
                    <div class="hero-slide-content">
                        <span class="hero-badge-new">Limited Time</span>
                        <h2 class="slide-title">Easy EMI Plans Available</h2>
                        <p class="slide-subtitle">Shop now, pay later. Flexible 3 to 12 month installment plans with 0% markup.</p>
                        <div class="hero-actions">
                            <a href="#products" class="hero-primary-btn">Learn more</a>
                        </div>
                    </div>
                    <div class="hero-slide-image">
                        <img src="{{ asset('images/hero_female.png') }}" alt="EMI Plans">
                    </div>
                </div>
            </div>

            @if(isset($heroProduct))
            <div class="hero-slide" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8edfa 50%, #dce3f5 100%);">
                <div class="hero-slide-inner">
                    <div class="hero-slide-content">
                        <span class="hero-badge-new">Featured</span>
                        <h2 class="slide-title">{{ $heroProduct->name }}</h2>
                        <p class="slide-subtitle">
                            @if($heroProduct->discount_percentage > 0)
                                Now Rs. {{ number_format($heroProduct->discounted_price, 0) }} — Save {{ round($heroProduct->discount_percentage) }}%
                            @else
                                Starting at Rs. {{ number_format($heroProduct->cash_sale_price, 0) }}
                            @endif
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('frontend.product', $heroProduct->id) }}" class="hero-primary-btn">Shop now</a>
                        </div>
                    </div>
                    <div class="hero-slide-image">
                        @if($heroProduct->image)
                            <img src="{{ asset(Storage::url($heroProduct->image)) }}" alt="{{ $heroProduct->name }}">
                        @else
                            <img src="{{ asset('images/hero_female.png') }}" alt="{{ $heroProduct->name }}">
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <button class="slider-btn prev-btn" onclick="moveSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="slider-btn next-btn" onclick="moveSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <!-- Main Content -->
    <div class="container">

        <!-- Feature Cards Row -->
        <div class="hero-features">
            <a href="#products" class="hero-feature">
                <div class="hero-feature-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <div class="hero-feature-text">
                    <h4>Free Delivery</h4>
                    <p>On orders over Rs. 2,000</p>
                </div>
            </a>
            <a href="#products" class="hero-feature">
                <div class="hero-feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="hero-feature-text">
                    <h4>Secure Payment</h4>
                    <p>100% protected</p>
                </div>
            </a>
            <a href="#products" class="hero-feature">
                <div class="hero-feature-icon"><i class="fa-solid fa-rotate-left"></i></div>
                <div class="hero-feature-text">
                    <h4>Easy Returns</h4>
                    <p>7-day return policy</p>
                </div>
            </a>
            <a href="#products" class="hero-feature">
                <div class="hero-feature-icon"><i class="fa-solid fa-headset"></i></div>
                <div class="hero-feature-text">
                    <h4>24/7 Support</h4>
                    <p>Customer care team</p>
                </div>
            </a>
        </div>

        <!-- Products -->
        @if(request()->filled('q'))
            <h3 class="section-title">Results for "{{ request('q') }}"</h3>
        @else
            <h3 class="section-title" id="products">Popular products</h3>
        @endif

        <div class="product-grid">
            @forelse($products as $product)
                <div class="product-card">
                    <a href="{{ route('frontend.product', $product->id) }}" class="product-img-link">
                        <div class="product-img">
                            @if($product->image)
                                <img src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                            @else
                                <div class="product-img-placeholder"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                    </a>
                    <div class="product-details">
                        <a href="{{ route('frontend.product', $product->id) }}" class="product-name-link">
                            <div class="product-name">{{ $product->name }}</div>
                        </a>

                        <div class="product-price-row">
                            @if($product->discount_percentage > 0)
                                <span class="product-price">Rs. {{ number_format($product->discounted_price, 0) }}</span>
                                <span class="old-price">Rs. {{ number_format($product->cash_sale_price, 0) }}</span>
                                <span class="discount-badge">-{{ round($product->discount_percentage) }}%</span>
                            @else
                                <span class="product-price">Rs. {{ number_format($product->cash_sale_price, 0) }}</span>
                            @endif
                        </div>

                        <div class="product-emi">EMI from Rs. {{ number_format($product->emi_sale_price, 0) }}/mo</div>

                        <div class="product-delivery"><i class="fa-solid fa-truck"></i> FREE Delivery</div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No products found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Back to Top -->
    <div class="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fa-solid fa-arrow-up"></i>
    </div>

    <!-- Footer -->
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
                    @if($setting && $setting->site_address)
                        <li><span>{{ $setting->site_address }}</span></li>
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

    <!-- Copyright -->
    <div class="footer-bottom">
        &copy; {{ date('Y') }} {{ $setting->site_name ?? 'Eclothing' }}. All rights reserved.
    </div>
    <!-- Hero Slider Script -->
    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.dot');
        let autoSlideTimer = null;

        function showSlide(index) {
            if (slides.length === 0) return;
            if (index >= slides.length) currentSlideIndex = 0;
            if (index < 0) currentSlideIndex = slides.length - 1;
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[currentSlideIndex].classList.add('active');
            if (dots[currentSlideIndex]) {
                dots[currentSlideIndex].classList.add('active');
            }
        }

        function moveSlide(step) {
            currentSlideIndex += step;
            showSlide(currentSlideIndex);
            resetAutoSlide();
        }

        function currentSlide(index) {
            currentSlideIndex = index;
            showSlide(currentSlideIndex);
            resetAutoSlide();
        }

        function startAutoSlide() {
            autoSlideTimer = setInterval(() => {
                currentSlideIndex++;
                showSlide(currentSlideIndex);
            }, 6000); // 6 seconds auto-slide interval
        }

        function resetAutoSlide() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
            }
            startAutoSlide();
        }

        // Initialize Slider
        document.addEventListener('DOMContentLoaded', () => {
            showSlide(currentSlideIndex);
            startAutoSlide();
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>

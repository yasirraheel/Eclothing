@php
    $setting = $setting ?? \App\Models\Setting::first();
@endphp
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
            @php
                $cart = session('cart', []);
                $cartCount = array_sum(array_column($cart, 'quantity'));
            @endphp
            @if($cartCount > 0)
                <span class="cart-count">{{ $cartCount }}</span>
            @endif
        </a>
    </div>
</div>

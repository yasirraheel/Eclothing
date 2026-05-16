@php
    $setting = $setting ?? \App\Models\Setting::first();
    $siteName = $setting->site_name ?? 'Eclothing';
@endphp
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

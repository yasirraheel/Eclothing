<nav x-data="{ open: false }" class="frontend-header">
    <style>
        .frontend-header .top-bar { background-color:#f5f5f5; font-size:12px; display:flex; justify-content:flex-end; padding:6px 0; }
        .frontend-header .top-bar a { color:#212121; text-decoration:none; margin-left:18px; text-transform:uppercase; font-weight:600; }
        .frontend-header .header-wrapper { background:#fff; padding:14px 0; box-shadow:0 1px 3px rgba(0,0,0,0.05); position:sticky; top:0; z-index:1000; }
        .frontend-header .header { display:flex; align-items:center; gap:20px; }
        .frontend-header .logo { font-size:26px; font-weight:800; color:#f85606; text-decoration:none; letter-spacing:-1px; }
        .frontend-header .search-box { flex-grow:1; display:flex; background:#f5f5f5; border-radius:2px; overflow:hidden; }
        .frontend-header .search-box input { flex-grow:1; padding:12px 14px; border:none; background:transparent; outline:none; font-size:14px; }
        .frontend-header .search-box button { background:#f85606; border:none; color:#fff; padding:0 22px; cursor:pointer; font-size:16px; }
        .frontend-header .nav-links { display:flex; align-items:center; gap:14px; }
        .frontend-header .nav-links a { text-decoration:none; color:#212121; font-size:14px; }
        .frontend-header .nav-links a.primary { background:#f85606; color:#fff; padding:8px 14px; border-radius:18px; }
        .frontend-header .mobile-toggle { display:none; }
        .frontend-header .mobile-menu { display:none; }
        @media (max-width: 768px) {
            .frontend-header .top-bar { display:none; }
            .frontend-header .header { flex-wrap:wrap; }
            .frontend-header .search-box { order:3; width:100%; }
            .frontend-header .nav-links { display:none; }
            .frontend-header .mobile-toggle { display:flex; }
            .frontend-header .mobile-menu { display:block; padding:10px 0; }
            .frontend-header .mobile-menu a { display:block; padding:8px 0; color:#212121; text-decoration:none; }
        }
    </style>

    <div class="top-bar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="display:flex; justify-content:flex-end;">
            <a href="#">Save More on App</a>
            <a href="#">Sell on Eclothing</a>
            <a href="#">Customer Care</a>
            <a href="#">Track My Order</a>
        </div>
    </div>

    <div class="header-wrapper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 header">
            <a href="/" class="logo">Eclothing</a>

            <form action="{{ route('home') }}" method="GET" class="search-box" style="margin:0;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search in Eclothing">
                <button type="submit">Search</button>
            </form>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('orders.index') }}">My Orders</a>
                <a href="{{ route('profile.edit') }}">Settings</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="primary" style="border:none; background:#f85606; color:#fff; padding:8px 14px; border-radius:18px; cursor:pointer;">Logout</button>
                </form>
            </div>

            <div class="mobile-toggle -me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}" class="mobile-menu max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('orders.index') }}">My Orders</a>
            <a href="{{ route('profile.edit') }}">Settings</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" style="border:none; background:#f85606; color:#fff; padding:8px 14px; border-radius:18px; cursor:pointer;">Logout</button>
            </form>
        </div>
    </div>
</nav>

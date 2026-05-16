@php
    $setting = \App\Models\Setting::first();
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
    $totalOrders   = \App\Models\Order::where('user_id', auth()->id())->count();
    $pendingOrders = \App\Models\Order::where('user_id', auth()->id())->where('status', 'pending')->count();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Dashboard - {{ $setting->site_name ?? 'Eclothing' }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 20px;
            margin: 30px auto 60px auto;
        }
        .sidebar-menu {
            background: var(--white);
            border-radius: 8px;
            padding: 12px 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 1px solid var(--daraz-border);
        }
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: var(--daraz-dark);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #fff3ec;
            color: var(--daraz-orange);
            font-weight: 500;
        }
        .sidebar-menu a i {
            margin-right: 10px;
            width: 16px;
            text-align: center;
        }
        .dashboard-content {
            background: var(--white);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 1px solid var(--daraz-border);
        }
        .dashboard-content h2 {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 20px;
            color: var(--daraz-dark);
            padding-bottom: 15px;
            border-bottom: 1px solid var(--daraz-border);
        }
        
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff7f2;
            border: 1px solid var(--daraz-border);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card .num {
            font-size: 28px;
            font-weight: 700;
            color: var(--daraz-orange);
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            font-size: 13px;
            color: var(--daraz-gray);
        }
        
        .profile-panels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .profile-panel {
            border: 1px solid var(--daraz-border);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .profile-panel-header {
            padding: 15px;
            border-bottom: 1px solid var(--daraz-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-panel-body {
            padding: 15px;
            font-size: 14px;
            color: var(--daraz-gray);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            .stat-cards {
                grid-template-columns: 1fr;
            }
            .profile-panels {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    @include('components.header')
    @include('components.top-bar')

    <div class="container dashboard-container">
        @include('components.sidebar')
        
        <!-- Main Content -->
        <div class="dashboard-content">
            <h2>Manage My Account</h2>
            
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="num">{{ $totalOrders }}</div>
                    <div class="label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="num">{{ $pendingOrders }}</div>
                    <div class="label">Pending Orders</div>
                </div>
                <div class="stat-card">
                    <div class="num">0</div>
                    <div class="label">Wishlist Items</div>
                </div>
            </div>
            
            <div class="profile-panels">
                <div class="profile-panel">
                    <div class="profile-panel-header">
                        <span style="font-weight:500; font-size:14px;">Personal Profile</span>
                        <a href="{{ route('profile.edit') }}" style="color:var(--daraz-orange); font-size:13px; text-decoration:none;">EDIT</a>
                    </div>
                    <div class="profile-panel-body">
                        <div style="color:var(--daraz-dark);">{{ auth()->user()->name }}</div>
                        <div>{{ auth()->user()->email }}</div>
                        <div>{{ auth()->user()->whatsapp_number ?? 'No phone number added' }}</div>
                    </div>
                </div>
                
                <div class="profile-panel">
                    <div class="profile-panel-header">
                        <span style="font-weight:500; font-size:14px;">Address Book</span>
                        <a href="#" style="color:var(--daraz-orange); font-size:13px; text-decoration:none;">EDIT</a>
                    </div>
                    <div class="profile-panel-body">
                        <div style="color:var(--daraz-dark);">Default Shipping Address</div>
                        <div style="margin-top:5px;">{{ auth()->user()->address ?? 'No address saved.' }}</div>
                    </div>
                </div>
            </div>
        </div>
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

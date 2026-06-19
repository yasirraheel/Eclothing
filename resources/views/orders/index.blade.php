@php
    $setting = $setting ?? \App\Models\Setting::first();
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = $customerCarePages ?? \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = $darazPages ?? \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders - {{ $setting->site_name ?? 'Eclothing' }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <style>
        .dashboard-container { display:grid; grid-template-columns:250px 1fr; gap:20px; margin:30px auto 60px auto; }
        .sidebar-menu { background:var(--white); border-radius:8px; padding:12px 0; box-shadow:0 8px 20px rgba(0,0,0,0.06); border:1px solid var(--daraz-border); }
        .sidebar-menu a { display:block; padding:12px 20px; color:var(--daraz-dark); text-decoration:none; font-size:14px; transition:background 0.2s; }
        .sidebar-menu a:hover,.sidebar-menu a.active { background:#fff3ec; color:var(--daraz-orange); font-weight:500; }
        .sidebar-menu a i { margin-right:10px; width:16px; text-align:center; }
        .dashboard-content { background:var(--white); border-radius:8px; padding:30px; box-shadow:0 8px 20px rgba(0,0,0,0.06); border:1px solid var(--daraz-border); }
        .order-card { border:1px solid var(--daraz-border); border-radius:8px; margin-bottom:15px; overflow:hidden; }
        .order-card-header { background:#fff7f2; padding:14px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; border-bottom:1px solid var(--daraz-border); }
        .order-badge { display:inline-block; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-pending { background:#fff3cd; color:#856404; }
        .badge-processing { background:#cfe2ff; color:#0a58ca; }
        .badge-shipped { background:#e8d5ff; color:#6f3dc5; }
        .badge-delivered { background:#d1f5e0; color:#1a7a4a; }
        .badge-cancelled { background:#fde8e8; color:#c0392b; }
        .order-card-body { padding:15px 20px; }
        .order-item-row { display:flex; align-items:center; gap:12px; padding:8px 0; border-bottom:1px solid #f0f0f0; }
        .order-item-row:last-child { border-bottom:none; }
        @media(max-width:768px){ .dashboard-container{grid-template-columns:1fr;} }
    </style>
</head>
<body>
    @include('components.header')
    @include('components.top-bar')

    <div class="container dashboard-container">
        @include('components.sidebar')

        <!-- Content -->
        <div class="dashboard-content">
            <h2 style="font-size:20px;font-weight:500;margin-bottom:20px;color:var(--daraz-dark);padding-bottom:15px;border-bottom:1px solid var(--daraz-border);">
                My Orders ({{ $orders->total() }})
            </h2>

            @if(session('success'))
                <div style="background:#d4edda;color:#155724;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:14px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @forelse($orders as $order)
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--daraz-dark);">{{ $order->order_number }}</div>
                        <div style="font-size:12px;color:var(--daraz-gray);margin-top:2px;">
                            Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <span class="order-badge badge-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span style="font-weight:700;color:var(--daraz-orange);font-size:15px;">
                            Rs. {{ number_format($order->total_amount, 0) }}
                        </span>
                        <a href="{{ route('orders.show', $order->order_number) }}" style="color:var(--daraz-orange);font-size:13px;text-decoration:none;font-weight:500;border:1px solid var(--daraz-orange);padding:5px 12px;border-radius:2px;">
                            View Details
                        </a>
                    </div>
                </div>
                <div class="order-card-body">
                    @foreach($order->items->take(3) as $item)
                    <div class="order-item-row">
                        @if($item->product && $item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                        @else
                            <div style="width:50px;height:50px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-tshirt" style="color:#ccc;font-size:20px;"></i>
                            </div>
                        @endif
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:500;color:var(--daraz-dark);">{{ $item->product_name }}</div>
                            <div style="font-size:12px;color:var(--daraz-gray);">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price, 0) }}</div>
                        </div>
                        <div style="font-size:14px;font-weight:500;color:var(--daraz-dark);">
                            Rs. {{ number_format($item->subtotal, 0) }}
                        </div>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                        <div style="font-size:12px;color:var(--daraz-gray);padding-top:8px;text-align:center;">
                            +{{ $order->items->count() - 3 }} more item(s)
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px 20px;color:var(--daraz-gray);">
                <i class="fa-solid fa-box-open" style="font-size:3rem;margin-bottom:15px;display:block;color:#ddd;"></i>
                <div style="font-size:16px;font-weight:500;margin-bottom:8px;">No orders yet</div>
                <div style="font-size:13px;margin-bottom:20px;">Start shopping to see your orders here!</div>
                <a href="/" style="background:var(--daraz-orange);color:white;padding:10px 25px;border-radius:2px;text-decoration:none;font-weight:500;">
                    Shop Now
                </a>
            </div>
            @endforelse

            @if($orders->hasPages())
            <div style="margin-top:20px;">{{ $orders->links() }}</div>
            @endif
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
                    @if($setting && $setting->social_facebook)
                        <a href="{{ $setting->social_facebook }}" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    @endif
                    @if($setting && $setting->social_instagram)
                        <a href="{{ $setting->social_instagram }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">&copy; {{ date('Y') }} {{ $setting->site_name ?? 'Eclothing' }}. All rights reserved.</div>
</body>
</html>

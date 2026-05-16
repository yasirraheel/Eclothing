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
    <title>Order {{ $order->order_number }} - {{ $setting->site_name ?? 'Eclothing' }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .dashboard-container { display:grid; grid-template-columns:250px 1fr; gap:20px; margin:30px auto 60px auto; }
        .sidebar-menu { background:var(--white); border-radius:8px; padding:12px 0; box-shadow:0 8px 20px rgba(0,0,0,0.06); border:1px solid var(--daraz-border); }
        .sidebar-menu a { display:block; padding:12px 20px; color:var(--daraz-dark); text-decoration:none; font-size:14px; transition:background 0.2s; }
        .sidebar-menu a:hover,.sidebar-menu a.active { background:#fff3ec; color:var(--daraz-orange); font-weight:500; }
        .sidebar-menu a i { margin-right:10px; width:16px; text-align:center; }
        .dashboard-content { background:var(--white); border-radius:8px; padding:30px; box-shadow:0 8px 20px rgba(0,0,0,0.06); border:1px solid var(--daraz-border); }

        /* Order Status Stepper */
        .order-stepper { display:flex; align-items:center; justify-content:center; margin:20px 0 30px; flex-wrap:wrap; gap:5px; }
        .step { display:flex; flex-direction:column; align-items:center; position:relative; flex:1; min-width:80px; }
        .step-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; background:#e5e7eb; color:#9ca3af; font-weight:600; transition:all 0.3s; }
        .step-circle.active { background:var(--daraz-orange); color:white; }
        .step-circle.done { background:#10b981; color:white; }
        .step-label { font-size:11px; color:#9ca3af; margin-top:6px; text-align:center; }
        .step-label.active,.step-label.done { color:var(--daraz-dark); font-weight:500; }
        .step-line { flex:1; height:2px; background:#e5e7eb; margin:0 5px; align-self:flex-start; margin-top:18px; }
        .step-line.done { background:#10b981; }

        /* Tables & Cards */
        .info-section { border:1px solid var(--daraz-border); border-radius:8px; margin-bottom:20px; overflow:hidden; }
        .info-section-header { background:#fff7f2; padding:12px 18px; font-weight:500; font-size:14px; border-bottom:1px solid var(--daraz-border); color:var(--daraz-dark); }
        .info-section-body { padding:18px; }
        .order-items-table { width:100%; border-collapse:collapse; }
        .order-items-table th { background:#f8fafc; padding:10px 14px; font-size:13px; color:var(--daraz-gray); text-align:left; border-bottom:1px solid var(--daraz-border); }
        .order-items-table td { padding:12px 14px; font-size:13px; border-bottom:1px solid #f5f5f5; }
        .order-items-table tr:last-child td { border-bottom:none; }

        .badge-pending { background:#fff3cd; color:#856404; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-processing { background:#cfe2ff; color:#0a58ca; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-shipped { background:#e8d5ff; color:#6f3dc5; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-delivered { background:#d1f5e0; color:#1a7a4a; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-cancelled { background:#fde8e8; color:#c0392b; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-paid { background:#d1f5e0; color:#1a7a4a; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }

        .detail-two-col { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        @media(max-width:768px){ 
            .dashboard-container{grid-template-columns:1fr;} 
            .detail-two-col { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
    @include('components.header')
    @include('components.top-bar')

    <div class="container dashboard-container">
        @include('components.sidebar')

        <!-- Content -->
        <div class="dashboard-content">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
                <a href="{{ route('orders.index') }}" style="color:var(--daraz-gray);font-size:13px;text-decoration:none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
                <div style="font-size:20px;font-weight:500;color:var(--daraz-dark);">
                    Order: {{ $order->order_number }}
                </div>
                <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>

            <!-- Status Stepper -->
            @php
                $steps = ['pending','processing','shipped','delivered'];
                $currentStep = array_search($order->status, $steps);
                if ($order->status === 'cancelled') $currentStep = -1;
            @endphp

            @if($order->status !== 'cancelled')
            <div class="order-stepper">
                @foreach($steps as $i => $step)
                    <div class="step">
                        <div class="step-circle {{ $i < $currentStep ? 'done' : ($i == $currentStep ? 'active' : '') }}">
                            @if($step == 'pending') <i class="fa-solid fa-clock"></i>
                            @elseif($step == 'processing') <i class="fa-solid fa-gear"></i>
                            @elseif($step == 'shipped') <i class="fa-solid fa-truck"></i>
                            @else <i class="fa-solid fa-circle-check"></i>
                            @endif
                        </div>
                        <div class="step-label {{ $i <= $currentStep ? 'active' : '' }}">{{ ucfirst($step) }}</div>
                    </div>
                    @if(!$loop->last)
                        <div class="step-line {{ $i < $currentStep ? 'done' : '' }}"></div>
                    @endif
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:15px;background:#fde8e8;border-radius:4px;margin-bottom:20px;color:#c0392b;font-weight:500;">
                <i class="fa-solid fa-xmark"></i> This order has been cancelled.
            </div>
            @endif

            <!-- Order Items -->
            <div class="info-section">
                <div class="info-section-header"><i class="fa-solid fa-box" style="margin-right:8px;"></i>Order Items</div>
                <div class="info-section-body" style="padding:0; overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
                    <table class="order-items-table" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="display:flex;align-items:center;gap:12px;">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" style="width:55px;height:55px;object-fit:cover;border-radius:4px;border:1px solid var(--daraz-border);">
                                    @else
                                        <div style="width:55px;height:55px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-tshirt" style="color:#ccc;font-size:20px;"></i></div>
                                    @endif
                                    <div>
                                        <div style="font-weight:500;color:var(--daraz-dark);">{{ $item->product_name }}</div>
                                    </div>
                                </td>
                                <td>Rs. {{ number_format($item->price, 0) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>Rs. {{ number_format($item->subtotal, 0) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8fafc;">
                                <td colspan="3" style="text-align:right;font-weight:600;padding:14px;">Shipping Fee</td>
                                <td style="padding:14px;color:#27ae60;font-weight:500;">Free</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:700;font-size:15px;padding:14px;">Total</td>
                                <td style="padding:14px;"><strong style="color:var(--daraz-orange);font-size:16px;">Rs. {{ number_format($order->total_amount, 0) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="detail-two-col">
                <!-- Shipping Info -->
                <div class="info-section">
                    <div class="info-section-header"><i class="fa-solid fa-location-dot" style="margin-right:8px;"></i>Shipping Address</div>
                    <div class="info-section-body" style="font-size:14px;line-height:1.8;color:var(--daraz-dark);">
                        <div style="font-weight:600;">{{ auth()->user()->name }}</div>
                        <div>{{ $order->shipping_phone }}</div>
                        <div>{{ $order->shipping_city }}</div>
                        <div>{{ $order->shipping_address }}</div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="info-section">
                    <div class="info-section-header"><i class="fa-solid fa-credit-card" style="margin-right:8px;"></i>Payment Info</div>
                    <div class="info-section-body" style="font-size:14px;line-height:2;color:var(--daraz-dark);">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--daraz-gray);">Payment Method</span>
                            <strong>
                                @if($order->payment_method == 'cod') <i class="fa-solid fa-money-bill-wave" style="color:#27ae60;"></i> Cash on Delivery
                                @elseif($order->payment_method == 'bank') <i class="fa-solid fa-building-columns" style="color:#2980b9;"></i> Bank Transfer
                                @elseif($order->payment_method == 'jazzcash') <i class="fa-solid fa-mobile-screen" style="color:#e91e63;"></i> JazzCash
                                @elseif($order->payment_method == 'easypaisa') <i class="fa-solid fa-wallet" style="color:#4CAF50;"></i> EasyPaisa
                                @endif
                            </strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--daraz-gray);">Payment Status</span>
                            <span class="badge-{{ $order->payment_status == 'paid' ? 'paid' : ($order->payment_status == 'pending' ? 'pending' : 'cancelled') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--daraz-gray);">Order Date</span>
                            <strong>{{ $order->created_at->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->order_notes)
            <div style="margin-top:5px;padding:14px 18px;background:#fffbeb;border-radius:4px;border-left:3px solid #f59e0b;font-size:14px;">
                <strong style="color:#92400e;"><i class="fa-solid fa-note-sticky" style="margin-right:8px;"></i>Order Notes:</strong>
                <p style="color:#78350f;margin-top:5px;">{{ $order->order_notes }}</p>
            </div>
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

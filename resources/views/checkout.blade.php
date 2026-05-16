<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - {{ $setting->site_name ?? config('app.name') }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ Storage::url($setting->favicon) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
            margin: 30px auto 60px auto;
        }
        .checkout-box {
            background: var(--white);
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .checkout-box h2 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--daraz-border);
            padding-bottom: 10px;
            color: var(--daraz-dark);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: var(--daraz-dark);
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--daraz-border);
            border-radius: 2px;
            font-size: 14px;
            outline: none;
        }
        .payment-methods label {
            display: block;
            padding: 15px;
            border: 1px solid var(--daraz-border);
            border-radius: 2px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .payment-methods input {
            margin-right: 10px;
        }
        .btn-place-order {
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
        .btn-place-order:hover {
            background: var(--daraz-orange-hover);
        }
        @media (max-width: 768px) {
            .checkout-container { grid-template-columns: 1fr; }
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
                <input type="text" name="q" placeholder="Search {{ $setting->site_name ?? 'Eclothing' }}">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="auth-links">
                @auth
                    <a href="{{ route('dashboard') }}">Account</a>
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
            </div>
            <div class="top-bar-auth">
                @auth
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-checkout').submit();">Sign Out</a>
                    <form id="logout-form-checkout" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                @endauth
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="container checkout-container">
            <div class="left-side">
                <div class="checkout-box">
                    <h2>Shipping Information</h2>
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly style="background:#f5f5f5;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="shipping_phone" class="form-control" value="{{ old('shipping_phone', auth()->user()->whatsapp_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Detailed Address</label>
                        <textarea name="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                    </div>
                </div>

                <div class="checkout-box payment-methods">
                    <h2>Payment Method</h2>
                    
                    @php $anyPayment = false; @endphp

                    @if($setting && $setting->pm_cod)
                        @php $anyPayment = true; @endphp
                        <label style="display:flex; align-items:flex-start; gap:10px; padding:15px; border:1px solid var(--daraz-border); border-radius:2px; margin-bottom:10px; cursor:pointer;">
                            <input type="radio" name="payment_method" value="cod" checked style="margin-top:3px;">
                            <div>
                                <div style="font-weight:500;"><i class="fa-solid fa-money-bill-wave" style="color:#27ae60;margin-right:6px;"></i> Cash on Delivery (COD)</div>
                                <div style="font-size:12px; color:var(--daraz-gray); margin-top:3px;">Pay in cash when your order arrives.</div>
                            </div>
                        </label>
                    @endif

                    @if($setting && $setting->pm_bank && $setting->pm_bank_details)
                        @php $anyPayment = true; @endphp
                        <label style="display:flex; align-items:flex-start; gap:10px; padding:15px; border:1px solid var(--daraz-border); border-radius:2px; margin-bottom:10px; cursor:pointer;" onclick="showDetails('bank-info')">
                            <input type="radio" name="payment_method" value="bank" style="margin-top:3px;">
                            <div style="width:100%;">
                                <div style="font-weight:500;"><i class="fa-solid fa-building-columns" style="color:#2980b9;margin-right:6px;"></i> Bank Transfer</div>
                                <div id="bank-info" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border-radius:2px; font-size:13px; white-space:pre-line; color:var(--daraz-dark);">{{ $setting->pm_bank_details }}</div>
                            </div>
                        </label>
                    @endif

                    @if($setting && $setting->pm_jazzcash && $setting->pm_jazzcash_details)
                        @php $anyPayment = true; @endphp
                        <label style="display:flex; align-items:flex-start; gap:10px; padding:15px; border:1px solid var(--daraz-border); border-radius:2px; margin-bottom:10px; cursor:pointer;" onclick="showDetails('jazzcash-info')">
                            <input type="radio" name="payment_method" value="jazzcash" style="margin-top:3px;">
                            <div style="width:100%;">
                                <div style="font-weight:500;"><i class="fa-solid fa-mobile-screen" style="color:#e91e63;margin-right:6px;"></i> JazzCash</div>
                                <div id="jazzcash-info" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border-radius:2px; font-size:13px; white-space:pre-line; color:var(--daraz-dark);">{{ $setting->pm_jazzcash_details }}</div>
                            </div>
                        </label>
                    @endif

                    @if($setting && $setting->pm_easypaisa && $setting->pm_easypaisa_details)
                        @php $anyPayment = true; @endphp
                        <label style="display:flex; align-items:flex-start; gap:10px; padding:15px; border:1px solid var(--daraz-border); border-radius:2px; margin-bottom:10px; cursor:pointer;" onclick="showDetails('easypaisa-info')">
                            <input type="radio" name="payment_method" value="easypaisa" style="margin-top:3px;">
                            <div style="width:100%;">
                                <div style="font-weight:500;"><i class="fa-solid fa-wallet" style="color:#4CAF50;margin-right:6px;"></i> EasyPaisa</div>
                                <div id="easypaisa-info" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border-radius:2px; font-size:13px; white-space:pre-line; color:var(--daraz-dark);">{{ $setting->pm_easypaisa_details }}</div>
                            </div>
                        </label>
                    @endif

                    @if(!$anyPayment)
                        <p style="color:var(--daraz-gray); font-size:13px;">No payment methods configured. Please contact the store admin.</p>
                    @endif

                    <script>
                        function showDetails(id) {
                            document.querySelectorAll('[id$="-info"]').forEach(el => el.style.display = 'none');
                            const el = document.getElementById(id);
                            if (el) el.style.display = 'block';
                        }
                    </script>
                </div>
            </div>

            <div class="right-side">
                <div class="checkout-box">
                    <h2>Order Summary</h2>
                    
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <div style="display:flex; align-items:center; margin-bottom:15px;">
                            <div style="flex:1; font-size:13px; color:var(--daraz-dark);">
                                {{ $details['name'] }}<br>
                                <span style="color:var(--daraz-gray);">Qty: {{ $details['quantity'] }}</span>
                            </div>
                            <div style="font-size:14px; color:var(--daraz-dark); font-weight:500;">
                                Rs. {{ number_format($details['price'] * $details['quantity'], 0) }}
                            </div>
                        </div>
                    @endforeach
                    
                    <div style="border-top:1px solid var(--daraz-border); padding-top:15px; margin-top:15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; color:var(--daraz-gray);">
                            <span>Subtotal ({{ count($cart) }} items)</span>
                            <span style="color:var(--daraz-dark);">Rs. {{ number_format($total, 0) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:14px; color:var(--daraz-gray);">
                            <span>Shipping Fee</span>
                            <span style="color:var(--daraz-dark);">Free</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:500; padding-top:15px; border-top:1px solid var(--daraz-border);">
                            <span>Total</span>
                            <span style="color:var(--daraz-orange);">Rs. {{ number_format($total, 0) }}</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-place-order">Place Order</button>
                </div>
            </div>
        </div>
    </form>

</body>
</html>

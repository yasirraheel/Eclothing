@php
    $setting = \App\Models\Setting::first();
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Settings - {{ $setting->site_name ?? 'Eclothing' }}</title>
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
        .dashboard-content h2 { font-size:20px; font-weight:500; margin-bottom:20px; color:var(--daraz-dark); padding-bottom:15px; border-bottom:1px solid var(--daraz-border); }
        .profile-section { border:1px solid var(--daraz-border); border-radius:8px; padding:20px; margin-bottom:20px; background:#fff; }
        .profile-section h3 { font-size:16px; font-weight:700; color:var(--daraz-dark); margin-bottom:12px; }
        .profile-section p { color:var(--daraz-gray); font-size:13px; margin-bottom:15px; }
        .dashboard-content input[type="text"], .dashboard-content input[type="email"], .dashboard-content input[type="password"], .dashboard-content textarea { border:1px solid #e6e6e6 !important; border-radius:8px !important; padding:10px 12px !important; font-family:inherit; font-size:14px; }
        .dashboard-content input[type="text"]:focus, .dashboard-content input[type="email"]:focus, .dashboard-content input[type="password"]:focus, .dashboard-content textarea:focus { border-color:#f85606 !important; box-shadow:0 0 0 2px rgba(248,86,6,0.15) !important; outline:none; }
        .dashboard-content button[type="submit"] { background:#f85606 !important; color:#fff !important; border:none !important; border-radius:22px !important; padding:10px 24px !important; font-weight:700; cursor:pointer; box-shadow:0 8px 18px rgba(248,86,6,0.14); }
        .dashboard-content button[type="submit"]:hover { background:#d04604 !important; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-weight:600; color:var(--daraz-dark); margin-bottom:6px; font-size:14px; }
        .form-error { color:#dc2626; font-size:12px; margin-top:4px; }
        .success-message { background:#d1f5e0; color:#1a7a4a; padding:12px 16px; border-radius:6px; margin-bottom:20px; }
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
            <h2>Account Settings</h2>

            @if(session('status') === 'profile-updated')
                <div class="success-message">
                    <i class="fa-solid fa-circle-check"></i> Profile updated successfully!
                </div>
            @endif

            <!-- Profile Information -->
            <div class="profile-section">
                <h3><i class="fa-solid fa-user-circle" style="margin-right:8px; color:#f85606;"></i>Profile Information</h3>
                <p>Update your account's profile information and email address.</p>
                
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="whatsapp_number">WhatsApp Number</label>
                        <input id="whatsapp_number" name="whatsapp_number" type="text" value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="profile-section">
                <h3><i class="fa-solid fa-lock" style="margin-right:8px; color:#f85606;"></i>Change Password</h3>
                <p>Ensure your account is using a long, random password to stay secure.</p>
                
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input id="current_password" name="current_password" type="password" required>
                        @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password *</label>
                        <input id="password" name="password" type="password" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password *</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required>
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit"><i class="fa-solid fa-shield"></i> Update Password</button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="profile-section" style="border-color:#fde8e8; background:#fffbf5;">
                <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:8px;"></i>Delete Account</h3>
                <p>Once your account is deleted, there is no going back. Please be certain.</p>
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="form-group">
                        <label for="password_delete">Confirm Password *</label>
                        <input id="password_delete" name="password" type="password" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" style="background:#c0392b !important;">
                        <i class="fa-solid fa-trash"></i> Delete Account Permanently
                    </button>
                </form>
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

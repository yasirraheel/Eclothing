@extends('layouts.admin')

@section('content')
    <h1 class="page-title">System Settings</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <style>
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .tab:hover {
            color: var(--primary);
        }
        .tab.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="card">
        <div class="card-header">
            <div class="tabs">
                <div class="tab active" onclick="openTab('general', this)">General</div>
                <div class="tab" onclick="openTab('smtp', this)">SMTP Mail</div>
                <div class="tab" onclick="openTab('seo', this)">SEO metadata</div>
                <div class="tab" onclick="openTab('social', this)">Social Links</div>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- GENERAL TAB -->
                <div id="general" class="tab-content active">
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">General Store Information</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $setting->site_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="site_email" class="form-label">Site Email</label>
                            <input type="email" id="site_email" name="site_email" class="form-control" value="{{ old('site_email', $setting->site_email ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="site_phone" class="form-label">Site Phone</label>
                            <input type="text" id="site_phone" name="site_phone" class="form-control" value="{{ old('site_phone', $setting->site_phone ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="site_address" class="form-label">Site Address</label>
                            <input type="text" id="site_address" name="site_address" class="form-control" value="{{ old('site_address', $setting->site_address ?? '') }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="logo" class="form-label">Site Logo</label>
                            @if(isset($setting->logo) && $setting->logo)
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ Storage::url($setting->logo) }}" alt="Logo" style="height: 50px;">
                                </div>
                            @endif
                            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="favicon" class="form-label">Favicon</label>
                            @if(isset($setting->favicon) && $setting->favicon)
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ Storage::url($setting->favicon) }}" alt="Favicon" style="height: 32px;">
                                </div>
                            @endif
                            <input type="file" id="favicon" name="favicon" class="form-control" accept="image/x-icon,image/png">
                        </div>
                    </div>
                </div>

                <!-- SMTP TAB -->
                <div id="smtp" class="tab-content">
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">SMTP Email Configuration</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="smtp_host" class="form-label">SMTP Host</label>
                            <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="{{ old('smtp_host', $setting->smtp_host ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="smtp_port" class="form-label">SMTP Port</label>
                            <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="{{ old('smtp_port', $setting->smtp_port ?? '') }}">
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="smtp_username" class="form-label">SMTP Username</label>
                            <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="{{ old('smtp_username', $setting->smtp_username ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="smtp_password" class="form-label">SMTP Password</label>
                            <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="{{ old('smtp_password', $setting->smtp_password ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- SEO TAB -->
                <div id="seo" class="tab-content">
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">SEO Settings</h3>
                    <div class="form-group">
                        <label for="seo_title" class="form-label">SEO Title</label>
                        <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title', $setting->seo_title ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="seo_keywords" class="form-label">SEO Keywords (Comma separated)</label>
                        <input type="text" id="seo_keywords" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $setting->seo_keywords ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="seo_description" class="form-label">SEO Description</label>
                        <textarea id="seo_description" name="seo_description" class="form-control" rows="4">{{ old('seo_description', $setting->seo_description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- SOCIAL TAB -->
                <div id="social" class="tab-content">
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">Follow Us Links</h3>
                    <div class="form-group">
                        <label for="social_facebook" class="form-label"><i class="fa-brands fa-facebook" style="color: #1877F2;"></i> Facebook URL</label>
                        <input type="url" id="social_facebook" name="social_facebook" class="form-control" placeholder="https://facebook.com/yourpage" value="{{ old('social_facebook', $setting->social_facebook ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="social_twitter" class="form-label"><i class="fa-brands fa-twitter" style="color: #1DA1F2;"></i> Twitter (X) URL</label>
                        <input type="url" id="social_twitter" name="social_twitter" class="form-control" placeholder="https://twitter.com/yourhandle" value="{{ old('social_twitter', $setting->social_twitter ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="social_instagram" class="form-label"><i class="fa-brands fa-instagram" style="color: #E4405F;"></i> Instagram URL</label>
                        <input type="url" id="social_instagram" name="social_instagram" class="form-control" placeholder="https://instagram.com/yourhandle" value="{{ old('social_instagram', $setting->social_instagram ?? '') }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 2rem; margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa-solid fa-save"></i> Save All Settings</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTab(tabId, element) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            // Show selected tab and mark active
            document.getElementById(tabId).classList.add('active');
            element.classList.add('active');
        }
    </script>
@endsection

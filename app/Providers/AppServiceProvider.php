<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrap();
        
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $setting = \App\Models\Setting::first();
                if ($setting && $setting->smtp_host) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $setting->smtp_host,
                        'mail.mailers.smtp.port' => $setting->smtp_port,
                        'mail.mailers.smtp.username' => $setting->smtp_username,
                        'mail.mailers.smtp.password' => $setting->smtp_password,
                        'mail.mailers.smtp.encryption' => 'tls',
                        'mail.from.address' => $setting->smtp_from_address ?: ($setting->smtp_username ?: ($setting->site_email ?: env('MAIL_FROM_ADDRESS', 'hello@example.com'))),
                        'mail.from.name' => $setting->smtp_from_name ?: ($setting->site_name ?: env('MAIL_FROM_NAME', 'Example')),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore during migrations or before setup
        }
    }
}

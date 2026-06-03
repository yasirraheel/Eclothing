<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = \App\Models\Setting::first() ?? new \App\Models\Setting();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Site Details
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:255',
            'site_address' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,ico,svg,webp|max:1024',
            
            // SMTP Settings
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:255',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|max:255',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            
            // SEO Settings
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',

            // Payment Methods
            'pm_cod' => 'nullable|boolean',
            'pm_bank' => 'nullable|boolean',
            'pm_jazzcash' => 'nullable|boolean',
            'pm_easypaisa' => 'nullable|boolean',
            'pm_bank_details' => 'nullable|string',
            'pm_jazzcash_details' => 'nullable|string',
            'pm_easypaisa_details' => 'nullable|string',
        ]);

        $setting = \App\Models\Setting::first() ?? new \App\Models\Setting();

        // Checkboxes are not submitted when unchecked — force to 0/1
        $validated['pm_cod']       = $request->has('pm_cod') ? 1 : 0;
        $validated['pm_bank']      = $request->has('pm_bank') ? 1 : 0;
        $validated['pm_jazzcash']  = $request->has('pm_jazzcash') ? 1 : 0;
        $validated['pm_easypaisa'] = $request->has('pm_easypaisa') ? 1 : 0;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            Storage::disk('public')->makeDirectory('settings');
            $path = $request->file('logo')->store('settings', 'public');
            if ($path) {
                $validated['logo'] = $path;
            }
        } else {
            // Keep existing logo if no new file uploaded
            unset($validated['logo']);
        }

        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            Storage::disk('public')->makeDirectory('settings');
            $path = $request->file('favicon')->store('settings', 'public');
            if ($path) {
                $validated['favicon'] = $path;
            }
        } else {
            // Keep existing favicon if no new file uploaded
            unset($validated['favicon']);
        }

        $setting->fill($validated);
        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}

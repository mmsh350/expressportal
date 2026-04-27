<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
     public function edit()
    {
        $settings = SiteSetting::first();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        // $data = $request->validate([
        //     // 'site_name' => 'required|string|max:255',
        //     // 'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        //     // 'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        //     // 'login_background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        //     // 'registration_background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        //     'home_enabled' => 'boolean',
        //     'login_enabled' => 'boolean',
        //     'register_enabled' => 'boolean',
        // ]);

        // $settings = SiteSetting::firstOrNew();

        // foreach (['logo', 'favicon', 'login_background_image', 'registration_background_image'] as $image) {
        //     if ($request->hasFile($image)) {
        //         $path = $request->file($image)->store('public/settings');
        //         $data[$image] = str_replace('public/', '', $path);
        //     }
        // }

        // $data['home_enabled'] = $request->has('home_enabled');
        // $data['login_enabled'] = $request->has('login_enabled');
        // $data['register_enabled'] = $request->has('register_enabled');

        $data = $request->validate([
            'home_enabled' => 'nullable|boolean',
            'login_enabled' => 'nullable|boolean',
            'register_enabled' => 'nullable|boolean',
            'delink_notice' => 'nullable|string',
            'ipe_notice' => 'nullable|string',
        ]);

        // For each checkbox, set 1 if present, 0 if missing
        $data['home_enabled'] = $request->has('home_enabled') ? 1 : 0;
        $data['login_enabled'] = $request->has('login_enabled') ? 1 : 0;
        $data['register_enabled'] = $request->has('register_enabled') ? 1 : 0;

        $settings = SiteSetting::firstOrNew();
        $settings->fill($data);
        $settings->save();

        Cache::forget('site-settings');

        return back()->with('success', 'Settings updated successfully');
    }
}

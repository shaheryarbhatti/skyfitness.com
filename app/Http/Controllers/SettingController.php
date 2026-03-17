<?php
namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.settings');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'footer_text'    => 'nullable|string',
            'login_logo'     => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'admin_logo'     => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'login_bg_image' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'favicon'        => 'nullable|mimes:png,ico|max:1024',
            'login_heading' => 'nullable|string|max:120',
            'login_description' => 'nullable|string|max:500',
            'login_bullet_1' => 'nullable|string|max:120',
            'login_bullet_2' => 'nullable|string|max:120',
            'theme_primary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_primary_text' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_secondary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_secondary_text' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_accent' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_accent_text' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ]);

        $themePrimary = $request->input('theme_primary_text') ?: $request->input('theme_primary');
        $themeSecondary = $request->input('theme_secondary_text') ?: $request->input('theme_secondary');
        $themeAccent = $request->input('theme_accent_text') ?: $request->input('theme_accent');

        unset($data['theme_primary_text'], $data['theme_secondary_text'], $data['theme_accent_text']);

        if ($themePrimary) {
            $data['theme_primary'] = $themePrimary;
        }
        if ($themeSecondary) {
            $data['theme_secondary'] = $themeSecondary;
        }
        if ($themeAccent) {
            $data['theme_accent'] = $themeAccent;
        }

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                // Delete old file if exists
                $oldPath = Setting::get($key);
                if ($oldPath) {@unlink(public_path($oldPath));}

                // Store new file
                $file     = $request->file($key);
                $fileName = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $fileName);
                $value = 'uploads/settings/' . $fileName;
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', __('Settings updated successfully!'));
    }
}

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
        ]);

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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                if ($request->hasFile($key)) {
                    // Handle file upload (Logo/Favicon)
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('settings', 'public');
                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            } else {
                // Create if not exists (optional, depends on your seeders)
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => 'general'
                ]);
            }
        }

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}

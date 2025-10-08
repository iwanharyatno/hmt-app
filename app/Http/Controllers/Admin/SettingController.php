<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            Setting::HMT_DESCRIPTION => Setting::getValue(Setting::HMT_DESCRIPTION, ''),
            Setting::HMT_DURATION => Setting::getValue(Setting::HMT_DURATION, ''),
            Setting::HMT_PRIVACY => Setting::getValue(Setting::HMT_PRIVACY, ''),
            Setting::LS_DESCRIPTION => Setting::getValue(Setting::LS_DESCRIPTION, ''),
            Setting::LS_PRIVACY => Setting::getValue(Setting::LS_PRIVACY, ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        foreach (Setting::allKeys() as $key) {
            Setting::setValue($key, $data[$key]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}


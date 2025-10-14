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
            Setting::HMT_SOAL_FIRST => Setting::getValue(Setting::HMT_SOAL_FIRST, ''),
            Setting::LS_DESCRIPTION => Setting::getValue(Setting::LS_DESCRIPTION, ''),
            Setting::LS_PRIVACY => Setting::getValue(Setting::LS_PRIVACY, ''),
            Setting::WEB_FEEDBACK_FORM_URL => Setting::getValue(Setting::WEB_FEEDBACK_FORM_URL, ''),
            Setting::WEB_ALLOW_LS => Setting::getValue(Setting::WEB_ALLOW_LS, ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        foreach (Setting::allKeys() as $key) {
            if ($key === Setting::HMT_SOAL_FIRST) {
                Setting::setValue($key, isset($data[$key]));
                continue;
            }
            if ($key === Setting::WEB_ALLOW_LS) {
                Setting::setValue($key, isset($data[$key]));
                continue;
            }
            Setting::setValue($key, $data[$key]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}


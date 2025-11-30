<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Fetch all settings as an associative array for easy access in view
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate inputs
        $data = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'required|string|max:255',
            'store_phone' => 'nullable|string|max:50',
            'store_tin' => 'nullable|string|max:50',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'ptu_number' => 'nullable|string',
            'accreditation_no' => 'nullable|string',
        ]);

        // Save each setting
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}
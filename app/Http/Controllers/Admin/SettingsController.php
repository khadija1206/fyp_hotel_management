<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsUpdateRequest;
use App\Models\Setting;
use App\Services\AuditLogger;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'hotel_name' => Setting::get('hotel_name', 'My Hotel'),
            'hotel_address' => Setting::get('hotel_address', ''),
            'hotel_phone' => Setting::get('hotel_phone', ''),
            'hotel_email' => Setting::get('hotel_email', ''),
            'tax_rate' => Setting::get('tax_rate', 13),
            'default_check_in_time' => Setting::get('default_check_in_time', '14:00'),
            'default_check_out_time' => Setting::get('default_check_out_time', '12:00'),
            'currency_symbol' => Setting::get('currency_symbol', 'PKR'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(SettingsUpdateRequest $request)
    {
        foreach ($request->validated() as $key => $value) {
            Setting::set($key, $value);
        }

        AuditLogger::log('settings.updated', null, 'System settings updated');

        return back()->with('success', 'Settings updated successfully.');
    }
}

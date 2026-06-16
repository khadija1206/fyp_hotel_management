<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'hotel_name', 'value' => 'UCP Grand Hotel', 'type' => 'string', 'group' => 'general', 'label' => 'Hotel Name'],
            ['key' => 'hotel_address', 'value' => '1-Khayaban-e-Jinnah, Johar Town, Lahore', 'type' => 'string', 'group' => 'general', 'label' => 'Hotel Address'],
            ['key' => 'hotel_phone', 'value' => '+92-42-35880007', 'type' => 'string', 'group' => 'contact', 'label' => 'Phone'],
            ['key' => 'hotel_email', 'value' => 'info@ucpgrand.example.com', 'type' => 'string', 'group' => 'contact', 'label' => 'Email'],
            ['key' => 'tax_rate', 'value' => '13', 'type' => 'number', 'group' => 'billing', 'label' => 'Tax Rate (%)'],
            ['key' => 'default_check_in_time', 'value' => '14:00', 'type' => 'string', 'group' => 'operations', 'label' => 'Default Check-In Time'],
            ['key' => 'default_check_out_time', 'value' => '12:00', 'type' => 'string', 'group' => 'operations', 'label' => 'Default Check-Out Time'],
            ['key' => 'currency_symbol', 'value' => 'PKR', 'type' => 'string', 'group' => 'billing', 'label' => 'Currency'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}

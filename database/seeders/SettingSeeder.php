<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            'store_name' => 'Kape Ni Asero',
            'store_address' => '123 Coffee Street, Manila',
            'store_phone' => '(02) 8123-4567',
            'store_tin' => '000-000-000-000 VAT',
            'tax_rate' => '12', // percentage
            'ptu_number' => 'FP00000-000-0000000-00000',
            'accreditation_no' => '000-0000000000-000000',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
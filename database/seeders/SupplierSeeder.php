<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Highland Coffee Traders',
                'contact_person' => 'Mr. Mateo Ibalio',
                'email' => 'orders@highlandtraders.ph',
                'phone' => '0917-555-0101',
            ],
            [
                'name' => 'Batangas Barako Direct',
                'contact_person' => 'Aling Elena Reyes',
                'email' => 'sales@barakodirect.com',
                'phone' => '0918-555-0202',
            ],
            [
                'name' => 'Dairy Best Philippines',
                'contact_person' => 'John Cruz',
                'email' => 'support@dairybest.com.ph',
                'phone' => '(02) 8123-4567',
            ],
            [
                'name' => 'Sweet Sips Syrups',
                'contact_person' => 'Sarah Lim',
                'email' => 'hello@sweetsips.ph',
                'phone' => '0922-555-0303',
            ],
            [
                'name' => 'Manila Baking Essentials',
                'contact_person' => 'Peter Tan',
                'email' => 'peter@manilabaking.com',
                'phone' => '(02) 8987-6543',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['email' => $supplier['email']], // Check for duplicates by email
                [
                    'name' => $supplier['name'],
                    'contact_person' => $supplier['contact_person'],
                    'phone' => $supplier['phone'],
                ]
            );
        }
    }
}
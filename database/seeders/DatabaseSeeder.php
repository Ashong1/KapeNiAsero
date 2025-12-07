<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin Account (Safely)
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Check if email exists
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'must_change_password' => true,
            ]
        );

        // 2. Create Staff Account (Safely)
        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'must_change_password' => true, 
            ]
        );

        // 3. Run other seeders
        $this->call([
            SettingSeeder::class,
            ProductSeeder::class,
            RecipeSeeder::class,   
            SupplierSeeder::class, 
        ]);
    }
}
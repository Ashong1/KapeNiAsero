<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Categories Safely (Checks if slug exists first)
        $categories = [
            'Hot Coffee' => Category::firstOrCreate(
                ['slug' => Str::slug('Hot Coffee')],
                ['name' => 'Hot Coffee']
            )->id,

            'Iced Coffee' => Category::firstOrCreate(
                ['slug' => Str::slug('Iced Coffee')],
                ['name' => 'Iced Coffee']
            )->id,

            'Pastries' => Category::firstOrCreate(
                ['slug' => Str::slug('Pastries')],
                ['name' => 'Pastries']
            )->id,

            'Meals' => Category::firstOrCreate(
                ['slug' => Str::slug('Meals')],
                ['name' => 'Meals']
            )->id,
        ];

        // 2. Define the Menu Items
        $menuItems = [
            // Hot Coffee
            ['name' => 'Kapeng Barako', 'price' => 85.00, 'category' => 'Hot Coffee'],
            ['name' => 'Espresso', 'price' => 90.00, 'category' => 'Hot Coffee'],
            ['name' => 'Americano', 'price' => 100.00, 'category' => 'Hot Coffee'],
            ['name' => 'Cappuccino', 'price' => 120.00, 'category' => 'Hot Coffee'],
            ['name' => 'Cafe Latte', 'price' => 130.00, 'category' => 'Hot Coffee'],
            ['name' => 'Cafe Mocha', 'price' => 140.00, 'category' => 'Hot Coffee'],
            ['name' => 'Caramel Macchiato', 'price' => 150.00, 'category' => 'Hot Coffee'],
            ['name' => 'Flat White', 'price' => 130.00, 'category' => 'Hot Coffee'],
            ['name' => 'White Chocolate Mocha', 'price' => 150.00, 'category' => 'Hot Coffee'],

            // Iced Coffee
            ['name' => 'Iced Americano', 'price' => 110.00, 'category' => 'Iced Coffee'],
            ['name' => 'Iced Latte', 'price' => 140.00, 'category' => 'Iced Coffee'],
            ['name' => 'Iced Spanish Latte', 'price' => 150.00, 'category' => 'Iced Coffee'],
            ['name' => 'Iced Caramel Macchiato', 'price' => 160.00, 'category' => 'Iced Coffee'],
            ['name' => 'Iced Mocha', 'price' => 150.00, 'category' => 'Iced Coffee'],
            ['name' => 'Iced White Choco Mocha', 'price' => 160.00, 'category' => 'Iced Coffee'],
            ['name' => 'Cold Brew', 'price' => 130.00, 'category' => 'Iced Coffee'],
            ['name' => 'Kape Asero Signature', 'price' => 170.00, 'category' => 'Iced Coffee'],

            // Pastries
            ['name' => 'Classic Ensaymada', 'price' => 65.00, 'category' => 'Pastries'],
            ['name' => 'Ube Cheese Pandesal (3pcs)', 'price' => 75.00, 'category' => 'Pastries'],
            ['name' => 'Banana Walnut Bread', 'price' => 85.00, 'category' => 'Pastries'],
            ['name' => 'Blueberry Cheesecake', 'price' => 180.00, 'category' => 'Pastries'],
            ['name' => 'Choco Chip Cookie', 'price' => 55.00, 'category' => 'Pastries'],
            ['name' => 'Butter Croissant', 'price' => 90.00, 'category' => 'Pastries'],
            ['name' => 'Revel Bar', 'price' => 60.00, 'category' => 'Pastries'],
            ['name' => 'Calamansi Muffin', 'price' => 70.00, 'category' => 'Pastries'],

            // Meals
            ['name' => 'Tapsilog', 'price' => 185.00, 'category' => 'Meals'],
            ['name' => 'Tocilog', 'price' => 175.00, 'category' => 'Meals'],
            ['name' => 'Longsilog', 'price' => 170.00, 'category' => 'Meals'],
            ['name' => 'Bangus Silog', 'price' => 195.00, 'category' => 'Meals'],
            ['name' => 'Creamy Carbonara', 'price' => 220.00, 'category' => 'Meals'],
            ['name' => 'Chicken Pesto Pasta', 'price' => 230.00, 'category' => 'Meals'],
            ['name' => 'Grilled Cheese Sandwich', 'price' => 150.00, 'category' => 'Meals'],
            ['name' => 'Clubhouse Sandwich', 'price' => 190.00, 'category' => 'Meals'],
        ];

        // 3. Loop through items and create them in the database
        // Uses firstOrCreate to prevent duplicates if run multiple times
        foreach ($menuItems as $item) {
            Product::firstOrCreate(
                ['name' => $item['name']], // Check by name
                [
                    'price' => $item['price'],
                    'category_id' => $categories[$item['category']],
                    'stock' => 100,
                    'image_path' => null,
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Supplier;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        // 1. Fetch Existing Suppliers
        $highland = Supplier::where('name', 'Highland Coffee Traders')->first();
        $batangas = Supplier::where('name', 'Batangas Barako Direct')->first();
        $dairy    = Supplier::where('name', 'Dairy Best Philippines')->first();
        $syrups   = Supplier::where('name', 'Sweet Sips Syrups')->first();
        $baking   = Supplier::where('name', 'Manila Baking Essentials')->first();

        // 2. Create a "Fresh Market" Supplier for Meats/Produce if missing
        $freshMarket = Supplier::firstOrCreate(
            ['email' => 'orders@freshmarket.ph'],
            [
                'name' => 'Local Fresh Market',
                'contact_person' => 'Mang Boy',
                'phone' => '0999-888-7777'
            ]
        );

        // ==========================================
        // 3. Create ALL Ingredients (Coffee + Food)
        // ==========================================
        $ingredients = [
            // --- COFFEE ---
            'Coffee Beans (Arabica)' => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $highland?->id],
            'Barako Beans'           => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $batangas?->id],
            'Fresh Milk'             => ['unit' => 'ml', 'stock' => 10000, 'reorder' => 2000, 'supplier_id' => $dairy?->id],
            'Chocolate Syrup'        => ['unit' => 'ml', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $syrups?->id],
            'Caramel Syrup'          => ['unit' => 'ml', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $syrups?->id],
            'Vanilla Syrup'          => ['unit' => 'ml', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $syrups?->id],
            'White Choco Sauce'      => ['unit' => 'ml', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $syrups?->id],
            'Condensed Milk'         => ['unit' => 'ml', 'stock' => 3000, 'reorder' => 500, 'supplier_id' => $dairy?->id],
            'Muscovado Syrup'        => ['unit' => 'ml', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $syrups?->id],
            'Water'                  => ['unit' => 'ml', 'stock' => 20000, 'reorder' => 0, 'supplier_id' => null],

            // --- PASTRIES (Simplified for Inventory) ---
            'All-Purpose Flour'      => ['unit' => 'g', 'stock' => 10000, 'reorder' => 2000, 'supplier_id' => $baking?->id],
            'White Sugar'            => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $baking?->id],
            'Butter'                 => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $dairy?->id],
            'Eggs'                   => ['unit' => 'pcs', 'stock' => 100, 'reorder' => 20, 'supplier_id' => $freshMarket?->id],
            'Cheese Block'           => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $dairy?->id],
            'Ube Halaya'             => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $baking?->id],
            'Bananas'                => ['unit' => 'pcs', 'stock' => 50, 'reorder' => 10, 'supplier_id' => $freshMarket?->id],
            'Walnuts'                => ['unit' => 'g', 'stock' => 1000, 'reorder' => 200, 'supplier_id' => $baking?->id],
            'Cream Cheese'           => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $dairy?->id],
            'Blueberry Filling'      => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $baking?->id],
            'Chocolate Chips'        => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $baking?->id],
            'Oatmeal'                => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $baking?->id],
            'Calamansi Extract'      => ['unit' => 'ml', 'stock' => 1000, 'reorder' => 200, 'supplier_id' => $freshMarket?->id],

            // --- MEALS ---
            'Rice'                   => ['unit' => 'g', 'stock' => 25000, 'reorder' => 5000, 'supplier_id' => $freshMarket?->id],
            'Beef Tapa'              => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $freshMarket?->id],
            'Pork Tocino'            => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $freshMarket?->id],
            'Longganisa'             => ['unit' => 'pcs', 'stock' => 100, 'reorder' => 20, 'supplier_id' => $freshMarket?->id],
            'Daing na Bangus'        => ['unit' => 'pcs', 'stock' => 50, 'reorder' => 10, 'supplier_id' => $freshMarket?->id],
            'Pasta Noodles'          => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $baking?->id],
            'Carbonara Sauce'        => ['unit' => 'ml', 'stock' => 3000, 'reorder' => 500, 'supplier_id' => $dairy?->id],
            'Pesto Sauce'            => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $baking?->id],
            'Chicken Breast'         => ['unit' => 'g', 'stock' => 5000, 'reorder' => 1000, 'supplier_id' => $freshMarket?->id],
            'Bread Slices'           => ['unit' => 'pcs', 'stock' => 100, 'reorder' => 20, 'supplier_id' => $baking?->id],
            'Ham'                    => ['unit' => 'pcs', 'stock' => 100, 'reorder' => 20, 'supplier_id' => $freshMarket?->id],
            'Bacon'                  => ['unit' => 'g', 'stock' => 2000, 'reorder' => 500, 'supplier_id' => $freshMarket?->id],
        ];

        $ingModels = [];
        foreach ($ingredients as $name => $data) {
            $ingModels[$name] = Ingredient::updateOrCreate(
                ['name' => $name],
                [
                    'unit' => $data['unit'],
                    'stock' => $data['stock'],
                    'reorder_level' => $data['reorder'],
                    'supplier_id' => $data['supplier_id']
                ]
            );
        }

        // ==========================================
        // 4. Define Recipes (Product Name -> Ingredients)
        // ==========================================
        $recipes = [
            // [HOT COFFEE]
            'Kapeng Barako' => ['Barako Beans' => 15, 'Water' => 250],
            'Espresso' => ['Coffee Beans (Arabica)' => 18],
            'Americano' => ['Coffee Beans (Arabica)' => 18, 'Water' => 200],
            'Cappuccino' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150],
            'Cafe Latte' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 200],
            'Cafe Mocha' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Chocolate Syrup' => 30],
            'Caramel Macchiato' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Vanilla Syrup' => 15, 'Caramel Syrup' => 15],
            'Flat White' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 120],
            'White Chocolate Mocha' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'White Choco Sauce' => 30],

            // [ICED COFFEE]
            'Iced Americano' => ['Coffee Beans (Arabica)' => 18, 'Water' => 150],
            'Iced Latte' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 180],
            'Iced Spanish Latte' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Condensed Milk' => 30],
            'Iced Caramel Macchiato' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Vanilla Syrup' => 15, 'Caramel Syrup' => 15],
            'Iced Mocha' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Chocolate Syrup' => 30],
            'Iced White Choco Mocha' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'White Choco Sauce' => 30],
            'Cold Brew' => ['Coffee Beans (Arabica)' => 30, 'Water' => 200],
            'Kape Asero Signature' => ['Coffee Beans (Arabica)' => 18, 'Fresh Milk' => 150, 'Muscovado Syrup' => 25],

            // [PASTRIES]
            // Note: Recipes are estimates per serving
            'Classic Ensaymada' => ['All-Purpose Flour' => 50, 'Butter' => 10, 'Cheese Block' => 10, 'White Sugar' => 10, 'Eggs' => 0.5],
            'Ube Cheese Pandesal (3pcs)' => ['All-Purpose Flour' => 100, 'Ube Halaya' => 30, 'Cheese Block' => 15],
            'Banana Walnut Bread' => ['All-Purpose Flour' => 60, 'Bananas' => 0.5, 'Walnuts' => 10, 'White Sugar' => 20],
            'Blueberry Cheesecake' => ['Cream Cheese' => 80, 'Blueberry Filling' => 30, 'White Sugar' => 20, 'Eggs' => 0.5],
            'Choco Chip Cookie' => ['All-Purpose Flour' => 40, 'Chocolate Chips' => 20, 'Butter' => 15, 'White Sugar' => 15],
            'Butter Croissant' => ['All-Purpose Flour' => 60, 'Butter' => 30],
            'Revel Bar' => ['Oatmeal' => 30, 'Chocolate Chips' => 20, 'Butter' => 20, 'Condensed Milk' => 10],
            'Calamansi Muffin' => ['All-Purpose Flour' => 50, 'Calamansi Extract' => 10, 'White Sugar' => 20, 'Eggs' => 0.5],

            // [MEALS]
            'Tapsilog' => ['Beef Tapa' => 150, 'Rice' => 200, 'Eggs' => 1],
            'Tocilog' => ['Pork Tocino' => 150, 'Rice' => 200, 'Eggs' => 1],
            'Longsilog' => ['Longganisa' => 2, 'Rice' => 200, 'Eggs' => 1],
            'Bangus Silog' => ['Daing na Bangus' => 1, 'Rice' => 200, 'Eggs' => 1],
            'Creamy Carbonara' => ['Pasta Noodles' => 150, 'Carbonara Sauce' => 100, 'Bacon' => 30],
            'Chicken Pesto Pasta' => ['Pasta Noodles' => 150, 'Pesto Sauce' => 50, 'Chicken Breast' => 100],
            'Grilled Cheese Sandwich' => ['Bread Slices' => 2, 'Cheese Block' => 30, 'Butter' => 10],
            'Clubhouse Sandwich' => ['Bread Slices' => 3, 'Ham' => 1, 'Bacon' => 20, 'Cheese Block' => 15, 'Eggs' => 1],
        ];

        // ==========================================
        // 5. Attach Ingredients to Products
        // ==========================================
        foreach ($recipes as $productName => $productIngredients) {
            $product = Product::where('name', $productName)->first();

            if ($product) {
                // Clear existing ingredients to ensure clean update
                $product->ingredients()->detach();

                foreach ($productIngredients as $ingredientName => $quantity) {
                    if (isset($ingModels[$ingredientName])) {
                        $product->ingredients()->attach($ingModels[$ingredientName]->id, [
                            'quantity_needed' => $quantity
                        ]);
                    }
                }
            }
        }
    }
}
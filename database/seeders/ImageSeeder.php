<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    public function run()
    {
        // Get all products
        $products = Product::all();

        // Get all files from the storage/app/public/products directory
        // Ensure you have run: php artisan storage:link
        $files = Storage::disk('public')->files('products');

        $updatedCount = 0;

        foreach ($products as $product) {
            // Generate possible filenames for this product (e.g., "Kapeng Barako" -> "kapeng-barako", "kapeng_barako")
            $slugDash = Str::slug($product->name); // kapeng-barako
            $slugUnderscore = str_replace('-', '_', $slugDash); // kapeng_barako
            
            // Look for a matching file in the directory
            $matchedFile = null;
            foreach ($files as $file) {
                // Check if filename contains the product slug (case insensitive)
                if (str_contains(strtolower($file), $slugDash) || str_contains(strtolower($file), $slugUnderscore)) {
                    $matchedFile = $file;
                    break;
                }
            }

            // Update the product if a match is found
            if ($matchedFile) {
                $product->image_path = $matchedFile;
                $product->save();
                $updatedCount++;
            }
        }

        $this->command->info("Successfully linked images to {$updatedCount} products.");
    }
}
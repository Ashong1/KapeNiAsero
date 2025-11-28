<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_add_category_id_to_products.php
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the new column (nullable for now to prevent errors with existing data)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            // Optional: Drop the old string column if you don't need it anymore
            // $table->dropColumn('category'); 
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            // $table->string('category'); // Restore if needed
        });
    }
};

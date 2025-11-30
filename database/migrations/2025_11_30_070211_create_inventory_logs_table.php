<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Who made the change
            $table->string('type'); // 'restock', 'used_in_order', 'wastage', 'manual_adjustment', 'void_return'
            $table->decimal('quantity_change', 10, 2); // e.g., +1000 or -50
            $table->decimal('running_balance', 10, 2); // Stock level AFTER this change
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit (only for restocks)
            $table->string('remarks')->nullable(); // Invoice #, Reason for wastage, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
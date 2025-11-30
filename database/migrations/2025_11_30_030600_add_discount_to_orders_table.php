<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->after('user_id')->default(0); // Original amount before discount
            $table->string('discount_name')->nullable()->after('subtotal'); // e.g., "Senior Citizen"
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_name'); // Actual deducted amount
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_name', 'discount_amount']);
        });
    }
};
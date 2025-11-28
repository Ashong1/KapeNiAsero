<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g., "Benguet Coffee Co."
            $table->string('contact_person')->nullable(); // e.g., "Mr. Juan"
            $table->string('email')->unique();   // e.g., "orders@benguetcoffee.com"
            $table->string('phone')->nullable(); // e.g., "0917-123-4567"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

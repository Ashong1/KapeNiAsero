<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('start_cash', 10, 2);
            $table->decimal('end_cash', 10, 2)->nullable(); // Actual cash counted by user
            $table->decimal('expected_cash', 10, 2)->nullable(); // System calculated cash
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
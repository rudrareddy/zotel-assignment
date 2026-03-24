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
        Schema::create('room_type_rate_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('rate_plan_id')->constrained()->onDelete('cascade');
            $table->decimal('base_price_multiplier', 5, 2)->default(1.00);
            $table->decimal('meal_price_per_person', 10, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['room_type_id', 'rate_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_rate_plans');
    }
};

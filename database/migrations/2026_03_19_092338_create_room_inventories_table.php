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
        Schema::create('room_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('available_rooms')->default(5);
            $table->decimal('base_price', 10, 2);
            $table->decimal('extra_guest_price', 10, 2)->default(0);
            $table->integer('base_guest_count')->default(2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['room_type_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_inventories');
    }
};

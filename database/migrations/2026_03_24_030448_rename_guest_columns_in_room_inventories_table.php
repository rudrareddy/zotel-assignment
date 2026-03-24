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
        Schema::table('room_inventories', function (Blueprint $table) {
            $table->renameColumn('extra_guest_price', 'extra_adult_price');
            $table->renameColumn('base_guest_count', 'base_occupancy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_inventories', function (Blueprint $table) {
            $table->renameColumn('extra_adult_price', 'extra_guest_price');
            $table->renameColumn('base_occupancy', 'base_guest_count');
        });
    }
};

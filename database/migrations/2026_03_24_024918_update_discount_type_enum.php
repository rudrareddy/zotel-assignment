<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify ENUM
        DB::statement("ALTER TABLE discounts MODIFY type ENUM('long_stay', 'last_minute', 'early_bird')");

       
        Schema::table('discounts', function (Blueprint $table) {
            $table->json('applicable_rate_plan_ids')
                  ->after('discount_percentage')
                  ->nullable();

            $table->json('applicable_room_type_ids')
                  ->after('applicable_rate_plan_ids')
                  ->nullable();

            $table->integer('priority')
                  ->after('is_active')
                  ->default(0);
        });
    }

    public function down(): void
    {
        //Revert ENUM
        DB::statement("ALTER TABLE discounts MODIFY type ENUM('long_stay', 'last_minute')");

        // Drop added columns
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'applicable_rate_plan_ids',
                'applicable_room_type_ids',
                'priority'
            ]);
        });
    }
};
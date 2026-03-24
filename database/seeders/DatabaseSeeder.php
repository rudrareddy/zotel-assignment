<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\RoomInventory;
use App\Models\RatePlan;
use App\Models\Discount;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Rate Plans
        $ep = RatePlan::create([
            'name' => 'European Plan',
            'code' => 'EP',
            'description' => 'Room only, no meals included',
            'meal_type' => 'EP',
            'is_active' => true
        ]);

        $cp = RatePlan::create([
            'name' => 'Continental Plan',
            'code' => 'CP',
            'description' => 'Breakfast included',
            'meal_type' => 'CP',
            'is_active' => true
        ]);

        $map = RatePlan::create([
            'name' => 'Modified American Plan',
            'code' => 'MAP',
            'description' => 'Breakfast and dinner included',
            'meal_type' => 'MAP',
            'is_active' => true
        ]);

        // Create Room Types
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'slug' => 'standard',
            'max_adults' => 3,
            'total_rooms' => 5,
            'description' => 'Comfortable standard room with essential amenities. Max 3 adults.'
        ]);

        $deluxe = RoomType::create([
            'name' => 'Deluxe Room',
            'slug' => 'deluxe',
            'max_adults' => 4,
            'total_rooms' => 5,
            'description' => 'Spacious deluxe room with premium amenities. Max 4 adults.'
        ]);

        // Associate Rate Plans with Room Types
        // Standard Room: EP & CP only
        $standard->ratePlans()->attach($ep->id, [
            'base_price_multiplier' => 1.00,
            'meal_price_per_person' => 0,
            'is_available' => true
        ]);
        
        $standard->ratePlans()->attach($cp->id, [
            'base_price_multiplier' => 1.10, // 10% premium for breakfast
            'meal_price_per_person' => 15, // $15 per person for breakfast
            'is_available' => true
        ]);
        
        // Deluxe Room: CP & MAP only
        $deluxe->ratePlans()->attach($cp->id, [
            'base_price_multiplier' => 1.00,
            'meal_price_per_person' => 20, // $20 per person for breakfast
            'is_available' => true
        ]);
        
        $deluxe->ratePlans()->attach($map->id, [
            'base_price_multiplier' => 1.20, // 20% premium for MAP
            'meal_price_per_person' => 35, // $35 per person for meals
            'is_available' => true
        ]);

        // Create Inventory for next 30 days
        $startDate = Carbon::today();
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $isWeekend = $date->isWeekend();
            
            // Standard Room inventory
            RoomInventory::create([
                'room_type_id' => $standard->id,
                'date' => $date,
                'available_rooms' => rand(1, 5),
                'base_price' => $isWeekend ? 2500 : 2000,
                'extra_adult_price' => 500,
                'base_occupancy' => 1,
                'is_available' => true
            ]);
            
            // Deluxe Room inventory
            RoomInventory::create([
                'room_type_id' => $deluxe->id,
                'date' => $date,
                'available_rooms' => rand(1, 5),
                'base_price' => $isWeekend ? 3000 : 3500,
                'extra_adult_price' => 500,
                'base_occupancy' => 1,
                'is_available' => true
            ]);
        }

        // Create Discount Rules
        // EP rate plan: 5% early bird discount (7+ days in advance)
        Discount::create([
            'name' => 'EP Early Bird 5%',
            'type' => 'early_bird',
            'discount_percentage' => 5,
            'days_before_checkin' => 7,
            'applicable_rate_plan_ids' => [$ep->id],
            'applicable_room_type_ids' => null, // All room types
            'is_active' => true,
            'priority' => 10
        ]);

        // CP and MAP rate plans: 10% early bird discount (7+ days in advance)
        Discount::create([
            'name' => 'CP/MAP Early Bird 10%',
            'type' => 'early_bird',
            'discount_percentage' => 10,
            'days_before_checkin' => 7,
            'applicable_rate_plan_ids' => [$cp->id, $map->id],
            'applicable_room_type_ids' => null,
            'is_active' => true,
            'priority' => 10
        ]);

        // Long stay discounts (all rate plans)
        Discount::create([
            'name' => 'Long Stay 10%',
            'type' => 'long_stay',
            'discount_percentage' => 10,
            'min_nights' => 3,
            'applicable_rate_plan_ids' => null, // All rate plans
            'applicable_room_type_ids' => null,
            'is_active' => true,
            'priority' => 5
        ]);

        Discount::create([
            'name' => 'Long Stay 20%',
            'type' => 'long_stay',
            'discount_percentage' => 20,
            'min_nights' => 6,
            'applicable_rate_plan_ids' => null,
            'applicable_room_type_ids' => null,
            'is_active' => true,
            'priority' => 5
        ]);

        // Last minute discount (all rate plans)
        Discount::create([
            'name' => 'Last Minute 5%',
            'type' => 'last_minute',
            'discount_percentage' => 5,
            'days_before_checkin' => 3,
            'applicable_rate_plan_ids' => null,
            'applicable_room_type_ids' => null,
            'is_active' => true,
            'priority' => 8
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\RoomInventory;
use App\Models\MealPlan;
use App\Models\Discount;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Meal Plans
        $roomOnly = MealPlan::create([
            'name' => 'Room Only',
            'code' => 'RO',
            'price_per_night' => 0
        ]);

        $breakfast = MealPlan::create([
            'name' => 'Breakfast Included',
            'code' => 'BB',
            'price_per_night' => 400
        ]);

        // Create Room Types
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'slug' => 'standard',
            'max_adults' => 3,
            'total_rooms' => 5,
            'description' => 'Comfortable standard room with essential amenities',
            'amenities'   => [
                'Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV',
                'Mini Fridge', 'In-room Safe', 'Rainfall Shower',
                'City View', 'Daily Housekeeping',
            ],
        ]);

        $deluxe = RoomType::create([
            'name' => 'Deluxe Room',
            'slug' => 'deluxe',
            'max_adults' => 3,
            'total_rooms' => 5,
            'description' => 'Spacious deluxe room with premium amenities and city view',
            'amenities'   => [
                'Free Wi-Fi', 'Air Conditioning', '55" Smart TV',
                'Mini Bar', 'In-room Safe', 'Bathtub + Rainfall Shower',
                'Pool/Garden View', 'Nespresso Machine', 'Daily Housekeeping',
                'Pillow Menu', 'Bathrobes & Slippers',
            ],
        ]);

        // Associate meal plans with room types
        $standard->mealPlans()->attach($roomOnly->id, ['price_per_person' => 0]);
        $standard->mealPlans()->attach($breakfast->id, ['price_per_person' => 400]);
        
        $deluxe->mealPlans()->attach($roomOnly->id, ['price_per_person' => 0]);
        $deluxe->mealPlans()->attach($breakfast->id, ['price_per_person' => 400]);

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
                'base_price' => $isWeekend ? 2400 : 2000,
                'extra_guest_price' => 500,
                'base_guest_count' => 1,
                'is_available' => true
            ]);
            
            // Deluxe Room inventory
            RoomInventory::create([
                'room_type_id' => $deluxe->id,
                'date' => $date,
                'available_rooms' => rand(1, 5),
                'base_price' => $isWeekend ? 3000: 2500,
                'extra_guest_price' => 500,
                'base_guest_count' => 1,
                'is_available' => true
            ]);
        }

        // Create Discounts
        Discount::create([
            'name' => 'Long Stay 10%',
            'type' => 'long_stay',
            'min_nights' => 3,
            'discount_percentage' => 10,
            'is_active' => true
        ]);

        Discount::create([
            'name' => 'Long Stay 20%',
            'type' => 'long_stay',
            'min_nights' => 6,
            'discount_percentage' => 20,
            'is_active' => true
        ]);

        Discount::create([
            'name' => 'Last Minute 5%',
            'type' => 'last_minute',
            'days_before_checkin' => 3,
            'discount_percentage' => 5,
            'is_active' => true
        ]);
    }
}
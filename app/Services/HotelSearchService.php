<?php

namespace App\Services;

use App\Models\RoomType;
use App\Models\RatePlan;
use App\Models\Discount;
use Carbon\Carbon;

class HotelSearchService
{
    public function search($request)
    {
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $guests = (int) $request->guests;
        $ratePlanCode = $request->rate_plan ?? null;
        
        $totalNights = $checkIn->diffInDays($checkOut);
        
        if ($totalNights <= 0) {
            return ['error' => 'Invalid date range'];
        }

        // Get all active room types
        $roomTypes = RoomType::with(['inventory' => function($query) use ($checkIn, $checkOut) {
            $query->whereBetween('date', [$checkIn, $checkOut->copy()->subDay()])
                  ->orderBy('date');
        }, 'availableRatePlans'])->get();

        $availableRooms = [];

        foreach ($roomTypes as $roomType) {
            // Check if room can accommodate guests
            if (!$roomType->canAccommodate($guests)) {
                continue;
            }

            // Check inventory exists for all nights
            $inventoryRecords = $roomType->inventory;
            if (count($inventoryRecords) < $totalNights) {
                continue;
            }

            // Check availability for all nights
            $isAvailable = true;
            foreach ($inventoryRecords as $inventory) {
                if (!$inventory->is_available || $inventory->available_rooms <= 0) {
                    $isAvailable = false;
                    break;
                }
            }

            if (!$isAvailable) {
                continue;
            }

            // Get available rate plans for this room type
            $availableRatePlans = $roomType->availableRatePlans;
            
            // If specific rate plan requested, filter
            if ($ratePlanCode) {
                $availableRatePlans = $availableRatePlans->filter(function($plan) use ($ratePlanCode) {
                    return $plan->code === $ratePlanCode;
                });
            }

            foreach ($availableRatePlans as $ratePlan) {
                // Calculate price with this rate plan
                $priceData = $roomType->getPriceForDates($checkIn, $checkOut, $guests, $ratePlan);
                
                if (!$priceData) {
                    continue;
                }

                // Apply discounts
                $discountCalculation = Discount::calculateDiscounts(
                    $checkIn, 
                    $checkOut, 
                    $totalNights, 
                    $priceData['total_price'],
                    $ratePlan->id,
                    $roomType->id
                );

                $availableRooms[] = [
                    'room_type' => [
                        'id' => $roomType->id,
                        'name' => $roomType->name,
                        'max_adults' => $roomType->max_adults,
                        'description' => $roomType->description
                    ],
                    'rate_plan' => [
                        'id' => $ratePlan->id,
                        'name' => $ratePlan->name,
                        'code' => $ratePlan->code,
                        'meal_type' => $ratePlan->meal_type,
                        'description' => $ratePlan->description,
                        'base_price_multiplier' => $ratePlan->pivot->base_price_multiplier,
                        'meal_price_per_person' => $ratePlan->pivot->meal_price_per_person
                    ],
                    'total_nights' => $totalNights,
                    'guest_count' => $guests,
                    'daily_breakdown' => $priceData['daily_breakdown'],
                    'pricing' => [
                        'base_room_price' => collect($priceData['daily_breakdown'])->sum('base_price'),
                        'rate_plan_adjustment' => collect($priceData['daily_breakdown'])->sum(function($day) {
                            return $day['base_price_with_multiplier'] - $day['base_price'];
                        }),
                        'extra_adult_charges' => collect($priceData['daily_breakdown'])->sum('extra_adult_charge'),
                        'meal_charges' => collect($priceData['daily_breakdown'])->sum('meal_charge'),
                        'subtotal' => $priceData['total_price'],
                        'discounts' => $discountCalculation['applied_discounts'],
                        'total_discount' => $discountCalculation['total_discount'],
                        'final_price' => $discountCalculation['final_price']
                    ],
                    'status' => 'available'
                ];
            }
        }

        return [
            'search_criteria' => [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'guests' => $guests,
                'nights' => $totalNights
            ],
            'available_rooms' => $availableRooms,
            'total_options' => count($availableRooms)
        ];
    }
}
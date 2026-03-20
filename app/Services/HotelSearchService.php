<?php

namespace App\Services;

use App\Models\RoomType;
use App\Models\Discount;
use Carbon\Carbon;

class HotelSearchService
{
    public function search($request)
    {
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $guests = (int) $request->guests;
        $mealPlanCode = $request->meal_plan;
        
        $totalNights = $checkIn->diffInDays($checkOut);
        
        if ($totalNights <= 0) {
            return ['error' => 'Invalid date range'];
        }
        $roomTypes = RoomType::with(['inventory' => function($query) use ($checkIn, $checkOut) {
            $query->whereBetween('date', [$checkIn, $checkOut->copy()->subDay()])
                  ->orderBy('date');
        }])->get();
       // return $roomTypes;

        $availableRooms = [];

        foreach ($roomTypes as $roomType) {
            if (!$roomType->canAccommodate($guests)) {
                continue;
            }

            $inventoryRecords = $roomType->inventory;
            //return $inventoryRecords;
            if (count($inventoryRecords) < $totalNights) {
                continue;
            }

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

            $priceData = $roomType->getPriceForDates($checkIn, $checkOut, $guests, $mealPlanCode);
            
            if (!$priceData) {
                continue;
            }

            $discountCalculation = Discount::calculateDiscounts(
                $checkIn, 
                $checkOut, 
                $totalNights, 
                $priceData['total_price']
            );

            $mealPlanDetails = null;
            if ($mealPlanCode) {
                $mealPlan = $roomType->mealPlans()->where('code', $mealPlanCode)->first();
                if ($mealPlan) {
                    $mealPlanDetails = [
                        'code' => $mealPlan->code,
                        'name' => $mealPlan->name,
                        'price_per_person' => $mealPlan->pivot->price_per_person,
                        'total_price' => $mealPlan->pivot->price_per_person * $guests * $totalNights
                    ];
                }
            }

            $roomCharges = collect($priceData['daily_breakdown'])->sum('base_price');
            $extraGuestCharges = collect($priceData['daily_breakdown'])->sum(function($day) {
                return $day['nightly_total'] - $day['base_price'];
            });

            $availableRooms[] = [
                'room_type' => [
                    'id' => $roomType->id,
                    'name' => $roomType->name,
                    'max_adults' => $roomType->max_adults,
                    'description' => $roomType->description
                ],
                'total_nights' => $totalNights,
                'guest_count' => $guests,
                'daily_breakdown' => $priceData['daily_breakdown'],
                'meal_plan' => $mealPlanDetails,
                'pricing' => [
                    'base_price' => $priceData['total_price'],
                    'price_breakdown' => [
                        'room_charges' => $roomCharges,
                        'room_price'=>$roomCharges+$extraGuestCharges,
                        'extra_guest_charges' => $extraGuestCharges,
                        'meal_plan_charges' => $mealPlanDetails['total_price'] ?? 0
                    ],
                    'discounts' => $discountCalculation['applied_discounts'],
                    'total_discount' => $discountCalculation['total_discount'],
                    'final_price' => $discountCalculation['final_price']
                ],
                'status' => 'available'
            ];
        }

        return [
            'search_criteria' => [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'guests' => $guests,
                'meal_plan' => $mealPlanCode,
                'nights' => $totalNights
            ],
            'available_rooms' => $availableRooms,
            'total_options' => count($availableRooms)
        ];
    }
}
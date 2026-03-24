<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomType extends Model
{
    protected $fillable = ['name', 'slug', 'max_adults', 'total_rooms', 'description','amenities','image_url'];

    protected $casts = [
        'amenities' => 'array',
        'max_adults' => 'integer',
        'total_rooms' => 'integer',
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(RoomInventory::class);
    }

    public function mealPlans(): BelongsToMany
    {
        return $this->belongsToMany(MealPlan::class, 'room_type_meal_plans')
                    ->withPivot('price_per_person')
                    ->withTimestamps();
    }

    public function ratePlans(): BelongsToMany
    {
        return $this->belongsToMany(RatePlan::class, 'room_type_rate_plans')
                    ->withPivot('base_price_multiplier', 'meal_price_per_person', 'is_available')
                    ->withTimestamps();
    }

    public function availableRatePlans()
    {
        return $this->ratePlans()->wherePivot('is_available', true);
    }

    public function canAccommodate(int $guestCount): bool
    {
        return $guestCount <= $this->max_adults;
    }

        public function getPriceForDates($checkIn, $checkOut, int $guestCount, RatePlan $ratePlan)
    {
        $inventory = $this->inventory()
            ->whereBetween('date', [$checkIn, $checkOut->copy()->subDay()])
            ->orderBy('date')
            ->get();

        if ($inventory->count() < $checkIn->diffInDays($checkOut)) {
            return null;
        }

        $totalPrice = 0;
        $dailyBreakdown = [];

        foreach ($inventory as $item) {
            // Calculate base price with rate plan multiplier
            $nightlyBasePrice = $item->base_price * $ratePlan->pivot->base_price_multiplier;
            
            // Calculate extra adult charges
            $extraAdults = max(0, $guestCount - $item->base_occupancy);
            $nightlyExtraAdultPrice = $extraAdults * $item->extra_adult_price;
            
            // Calculate meal charges
            $nightlyMealPrice = $ratePlan->pivot->meal_price_per_person * $guestCount;
            
            $nightlyTotal = $nightlyBasePrice + $nightlyExtraAdultPrice + $nightlyMealPrice;

            $dailyBreakdown[] = [
                'date' => $item->date->format('Y-m-d'),
                'base_price' => $item->base_price,
                'rate_plan_multiplier' => $ratePlan->pivot->base_price_multiplier,
                'base_price_with_multiplier' => $nightlyBasePrice,
                'extra_adults' => $extraAdults,
                'extra_adult_charge' => $nightlyExtraAdultPrice,
                'meal_charge' => $nightlyMealPrice,
                'nightly_total' => $nightlyTotal,
                'available_rooms' => $item->available_rooms
            ];

            $totalPrice += $nightlyTotal;
        }

        return [
            'total_price' => $totalPrice,
            'daily_breakdown' => $dailyBreakdown
        ];
    }
}
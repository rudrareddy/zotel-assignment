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

    public function canAccommodate(int $guestCount): bool
    {
        return $guestCount <= $this->max_adults;
    }

    public function getPriceForDates($checkIn, $checkOut, int $guestCount, ?string $mealPlanCode = null)
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
            $nightlyPrice = $item->calculatePriceForGuests($guestCount);
            
            if ($mealPlanCode) {
                $mealPlan = $this->mealPlans()->where('code', $mealPlanCode)->first();
                if ($mealPlan) {
                    $nightlyPrice += ($mealPlan->pivot->price_per_person * $guestCount);
                }
            }

            $dailyBreakdown[] = [
                'date' => $item->date->format('Y-m-d'),
                'base_price' => $item->base_price,
                'extra_guest_price' => $item->extra_guest_price,
                'guest_count' => $guestCount,
                'nightly_total' => $nightlyPrice,
                'available_rooms' => $item->available_rooms
            ];

            $totalPrice += $nightlyPrice;
        }

        return [
            'total_price' => $totalPrice,
            'daily_breakdown' => $dailyBreakdown
        ];
    }
}
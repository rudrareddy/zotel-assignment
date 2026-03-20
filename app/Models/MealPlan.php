<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealPlan extends Model
{
    protected $fillable = ['name', 'code', 'price_per_night'];

    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'room_type_meal_plans')
                    ->withPivot('price_per_person')
                    ->withTimestamps();
    }
}
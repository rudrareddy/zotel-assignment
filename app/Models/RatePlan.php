<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RatePlan extends Model
{
    protected $fillable = ['name', 'code', 'description', 'meal_type', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'room_type_rate_plans')
                    ->withPivot('base_price_multiplier', 'meal_price_per_person', 'is_available')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
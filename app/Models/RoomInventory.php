<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomInventory extends Model
{
    
    protected $fillable = [
        'room_type_id', 'date', 'available_rooms', 'base_price', 
        'extra_guest_price', 'base_guest_count', 'is_available','extra_adult_price','base_occupancy'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean'
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function calculatePriceForGuests(int $guestCount): float
    {
        if ($guestCount <= $this->base_guest_count) {
            return $this->base_price;
        }
        
        $extraGuests = $guestCount - $this->base_guest_count;
        return $this->base_price + ($extraGuests * $this->extra_guest_price);
    }
}
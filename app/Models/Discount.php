<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    
    protected $fillable = [
        'name', 'type', 'discount_percentage', 'min_nights', 
        'days_before_checkin', 'applicable_rate_plan_ids', 
        'applicable_room_type_ids', 'is_active', 'priority'
    ];

    protected $casts = [
        'applicable_rate_plan_ids' => 'array',
        'applicable_room_type_ids' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isApplicableToRatePlan($ratePlanId)
    {
        if (empty($this->applicable_rate_plan_ids)) {
            return true; // Apply to all rate plans
        }
        return in_array($ratePlanId, $this->applicable_rate_plan_ids);
    }

    public function isApplicableToRoomType($roomTypeId)
    {
        if (empty($this->applicable_room_type_ids)) {
            return true; // Apply to all room types
        }
        return in_array($roomTypeId, $this->applicable_room_type_ids);
    }

    public static function calculateDiscounts($checkIn, $checkOut, $totalNights, $totalPrice, $ratePlanId, $roomTypeId)
    {
        $rules = self::active()->orderBy('priority', 'desc')->get();
        $appliedDiscounts = [];
        $finalPrice = $totalPrice;
        
        foreach ($rules as $rule) {
            // Check if rule applies to this rate plan and room type
            if (!$rule->isApplicableToRatePlan($ratePlanId) || !$rule->isApplicableToRoomType($roomTypeId)) {
                continue;
            }
            
            $applicable = false;
            $discountAmount = 0;
            
            switch ($rule->type) {
                case 'early_bird':
                    $daysUntilCheckin = now()->diffInDays($checkIn, false);
                    if ($daysUntilCheckin >= ($rule->days_before_checkin ?? 0) && $daysUntilCheckin >= 0) {
                        $applicable = true;
                    }
                    break;
                    
                case 'long_stay':
                    if ($totalNights >= ($rule->min_nights ?? 0)) {
                        $applicable = true;
                    }
                    break;
                    
                case 'last_minute':
                    $daysUntilCheckin = now()->diffInDays($checkIn, false);
                    if ($daysUntilCheckin <= ($rule->days_before_checkin ?? 0) && $daysUntilCheckin >= 0) {
                        $applicable = true;
                    }
                    break;
            }
            
            if ($applicable) {
                $discountAmount = ($finalPrice * $rule->discount_percentage) / 100;
                $finalPrice -= $discountAmount;
                $appliedDiscounts[] = [
                    'name' => $rule->name,
                    'type' => $rule->type,
                    'percentage' => $rule->discount_percentage,
                    'amount' => round($discountAmount, 2)
                ];
            }
        }
        
        return [
            'original_price' => $totalPrice,
            'final_price' => round($finalPrice, 2),
            'total_discount' => round($totalPrice - $finalPrice, 2),
            'applied_discounts' => $appliedDiscounts
        ];
    }
}
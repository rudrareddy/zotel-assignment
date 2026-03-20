<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'type', 'min_nights', 
        'days_before_checkin', 'discount_percentage', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function calculateDiscounts($checkIn, $checkOut, $totalNights, $totalPrice)
    {
        $discounts = self::active()->get();
        $appliedDiscounts = [];
        $finalPrice = $totalPrice;
        
        $longStayDiscounts = $discounts->where('type', 'long_stay')->sortByDesc('min_nights');
        $lastMinuteDiscounts = $discounts->where('type', 'last_minute');
        
        $bestLongStayDiscount = null;
        foreach ($longStayDiscounts as $discount) {
            if ($totalNights >= $discount->min_nights) {
                $bestLongStayDiscount = $discount;
                break;
            }
        }
        
        if ($bestLongStayDiscount) {
            $discountAmount = ($finalPrice * $bestLongStayDiscount->discount_percentage) / 100;
            $finalPrice -= $discountAmount;
            $appliedDiscounts[] = [
                'name' => $bestLongStayDiscount->name,
                'type' => 'long_stay',
                'percentage' => $bestLongStayDiscount->discount_percentage,
                'amount' => round($discountAmount, 2)
            ];
        }
        
        foreach ($lastMinuteDiscounts as $discount) {
            $daysUntilCheckin = now()->diffInDays($checkIn, false);
            if ($daysUntilCheckin <= $discount->days_before_checkin && $daysUntilCheckin >= 0) {
                $discountAmount = ($finalPrice * $discount->discount_percentage) / 100;
                $finalPrice -= $discountAmount;
                $appliedDiscounts[] = [
                    'name' => $discount->name,
                    'type' => 'last_minute',
                    'percentage' => $discount->discount_percentage,
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
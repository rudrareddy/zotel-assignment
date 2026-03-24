<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required',
            'type'=>'required|in:early_bird,last_minute,long_stay',
            'discount_percentage'=>'required|integer',
            'min_nights'=>'nullable|integer',
            'days_before_checkin'=>'nullable|integer',
            'is_active'=>'required|boolean',
            'applicable_rate_plan_ids' => 'nullable|array',
            'applicable_rate_plan_ids.*' => 'exists:rate_plans,id',

            'applicable_room_type_ids' => 'nullable|array',
            'applicable_room_type_ids.*' => 'exists:room_types,id',
        ];
    }
}

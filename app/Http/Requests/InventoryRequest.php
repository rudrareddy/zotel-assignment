<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'room_type_id'=>'required|integer|exists:room_types,id',
            'inventory_date_count'=>'required|integer',
            'available_rooms'=>'required|integer',
            'base_price'=>'required|integer',
            'extra_adult_price'=>'required|integer',
            'base_occupancy'=>'required|integer|min:1|max:2'
        ];
    }
}

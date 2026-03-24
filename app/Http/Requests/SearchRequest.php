<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class SearchRequest extends FormRequest
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
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:3',
            'rate_plan' => 'required|in:EP,CP,MAP'
        ];
    }

    public function messages()
    {
        return [
            'check_in.after_or_equal' => 'Check-in date cannot be in the past',
            'check_out.after' => 'Check-out date must be after check-in date',
            'guests.max' => 'Maximum 4 guests allowed per room',
            'rate_plan.in' => 'Invalid rate plan selection'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ],400));
    }
}

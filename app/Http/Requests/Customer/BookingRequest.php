<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => [
                'required',
                Rule::exists('services', 'id'),
            ],
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'The service field is required.',
            'service_id.exists' => 'The selected service is invalid.',

            'booking_date.required' => 'The booking date is required.',
            'booking_date.date' => 'The booking date must be a valid date.',
            'booking_date.after_or_equal' => 'The booking date cannot be in the past. Please select today or a future date.',
        ];
    }
}

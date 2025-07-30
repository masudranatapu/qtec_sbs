<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            "name" => [
                "required",
                "min:2",
                "max:50",
                "string",
                Rule::unique('services')->ignore($this->id),
            ],
            "description" => [
                "required",
                "min:2",
                "string",
                "max:20000",
            ],
            "price" => [
                "required",
                "numeric",
                "gt:0", // Greater than 0
            ],
            "status" => [
                "required",
                Rule::in(["Active", "Inactive"]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'name.min' => 'The service name must be at least 2 characters.',
            'name.max' => 'The service name must not exceed 50 characters.',
            'name.string' => 'The service name must be a string.',
            'name.unique' => 'The service name has already been used.',

            'description.required' => 'The description is required.',
            'description.min' => 'The description must be at least 2 characters.',
            'description.max' => 'The description must not exceed 20000 characters.',
            'description.string' => 'The description must be a valid string.',

            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.gt' => 'The price must be greater than 0.',

            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status must be either Active or Inactive.',
        ];
    }
}

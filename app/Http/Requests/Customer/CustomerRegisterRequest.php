<?php

namespace App\Http\Requests\Customer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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
            "name" => ["required", "string", "min:2", "max:30"],
            "email" => [
                "required",
                "email",
                "max:40",
                Rule::unique('users')
            ],
            "password" => [
                "required",
                "min:4",
                "max:25",
                "confirmed"
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'Name must be an string.',
            'name.min' => 'The name must not minimum 2 characters.',
            'name.max' => 'The name must not exceed 40 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email must not exceed 40 characters.',
            'email.unique' => 'This email is already has an account. Please choose a different email.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 4 characters.',
            'password.max' => 'The password may not be greater than 20 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}

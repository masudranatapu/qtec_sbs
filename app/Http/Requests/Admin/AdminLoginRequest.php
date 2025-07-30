<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            "email" => ["required", "email", "max:40", "exists:admins,email"],
            "password" => ["required", "min:4", "max:25"],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email must not exceed 40 characters.',
            'email.exists' => 'The provided email does not match in our records.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 4 characters long.',
            'password.max' => 'The password must not exceed 25 characters.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'user_role' => 'sometimes',
            'password_attempt' => 'sometimes',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutlestStoreRequest extends FormRequest
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
            'product_id'=>'required|integer',
            'product_description'=>'required|string|max:255',
            'quantity'=>'required|integer',
            'total_price'=>'required|double'
        ];
    }
}

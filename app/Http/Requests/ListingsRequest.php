<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingsRequest extends FormRequest
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
            'property_status' => ['sometimes', 'required', 'string'],
            'location' => ['sometimes', 'required', 'string'],
            'property_type' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'string']
        ];
    }
}

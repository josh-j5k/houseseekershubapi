<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ListingUpdateRequest extends FormRequest
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
            "title" => ['required', 'string'],
            'description' => ['required', 'string'],
            'property_status' => ['required', 'string'],
            'property_type' => ['required', 'string'],
            'location' => ['required', 'string'],
            'price' => ['required', 'int'],
            'inputFiles' => ['sometimes', 'array'],
            'inputFiles.*' => [File::types(['jpg', 'webp', 'png'])],
            'deletedImages' => ['sometimes', 'array'],
            'deletedImages.*' => ['string']
        ];
    }
}

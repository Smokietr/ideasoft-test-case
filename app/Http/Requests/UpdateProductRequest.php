<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => [
                'present', 'min:3', 'max:255', 'string',
            ],
            'description' => [
                'present', 'min:3', 'max:255', 'string',
            ],
            'price' => [
                'present', 'numeric', 'min:0,9999.99',
            ],
            'stock' => [
                'present', 'numeric', 'min:0',
            ],
            'category' => [
                'present', 'between:1,100', 'numeric',
            ],
        ];
    }
}

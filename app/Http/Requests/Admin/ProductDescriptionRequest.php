<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductDescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required','exists:products,id'],
            'description' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.'
        ];
    }
}

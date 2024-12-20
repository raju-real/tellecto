<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SubcategoryBelongsToCategory;

class BulkCategoryRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => [
                'required',
                'exists:sub_categories,id',
                new SubcategoryBelongsToCategory($this->input('category_id'))
            ],
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The Category field is required.',
            'category_id.exists' => 'The selected category value is invalid.',
            'subcategory_id.required' => 'The Subcategory field is required.',
            'subcategory_id.exists' => 'The selected subcategory value is invalid.',
        ];
    }
}

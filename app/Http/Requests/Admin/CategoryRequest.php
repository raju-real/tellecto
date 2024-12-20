<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                'nullable',
                'sometimes',
                'string',
                'max:100',
                Rule::unique('categories')->ignore($this->route('category'))->whereNull('deleted_at')
            ],
            'slug'=>'nullable',
            'thumbnail' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'original' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'is_mega' => 'required|in:0,1',
            'active_status' => 'required|in:0,1',
            'vat_type' => 'required|in:VAT_FREE,VAT,PARTIAL_VAT',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The Category Name field is required.',
            'name.string' => 'The Category Name must be a string.',
            'name.unique' => $this->route('category') ? 'The Category Name already exists.' : 'The Category Name must be unique.',
            'name.max' => 'The Category Name must not be greater than 100 characters.',
            'icon.image' => 'The Icon should be a valid image with type of png,jpg or jpeg',
            'icon.mimes' => 'The Icon should be type of png,jpg or jpeg.',
            'icon.max' => 'The Icon size may not be greater then 1MB.',
        ];
    }
}

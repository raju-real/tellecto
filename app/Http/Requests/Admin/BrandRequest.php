<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'name' => 'nullable',
            'slug' => 'nullable',
            'thumbnail' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'original' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'active_status' => 'required|in:0,1'
        ];
    }
}

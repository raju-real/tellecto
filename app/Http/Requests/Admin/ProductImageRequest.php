<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
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
            'id' => ['required','exists:products,id'],
            'thumbnail' => ['nullable','sometimes','image','mimes:jpg,jpeg,png','max:5120'],
            'images' => ['nullable','sometimes','array'],
            'images.*' => ['required','image','mimes:jpg,jpeg,png','max:5120']
        ];
    }
}

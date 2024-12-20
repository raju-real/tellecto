<?php

namespace App\Http\Requests\Admin;

use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;

class SingleProductCategoryRequest extends FormRequest
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
            'id' => ['required', 'exists:products,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:sub_categories,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $categoryId = $data['category_id'] ?? null;
            $subcategoryId = $data['subcategory_id'] ?? null;
            if ($categoryId && !$this->subcategoryBelongsToCategory($subcategoryId, $categoryId)) {
                $validator->errors()->add("subcategory_id", "The selected subcategory does not belong to the specified category.");
            }
        });
    }

    protected function subcategoryBelongsToCategory($subcategoryId, $categoryId)
    {
        return SubCategory::where('id', $subcategoryId)->where('category_id',$categoryId)->exists();
    }
}

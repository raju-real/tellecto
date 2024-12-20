<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManualProductCategoryRequest extends FormRequest
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
            '*.id' => [
                'required',
                'integer',
                Rule::exists('products', 'id'),
            ],
            '*.category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            '*.subcategory_id' => [
                'required',
                'integer',
                Rule::exists('sub_categories', 'id'),
            ],

        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            foreach ($data as $index => $item) {
                if (!isset($item['id']) || !isset($item['category_id']) || !isset($item['subcategory_id'])) {
                    $validator->errors()->add("data.{$index}", "The object at index {$index} must contain id, category, and subcategory fields.");
                }
                $categoryId = $item['category_id'] ?? null;
                $subcategoryId = $item['subcategory_id'] ?? null;
                $productNumber = $item['id'];
                if ($categoryId && !$this->subcategoryBelongsToCategory($subcategoryId, $categoryId)) {
                    $validator->errors()->add("subcategories.$index", "On product numbers $productNumber the selected subcategory does not belong to the specified category.");
                }
            }
        });
    }

    /**
     * Check if the subcategory belongs to the specified category.
     *
     * @param int $subcategoryId
     * @param int $categoryId
     * @return bool
     */
    protected function subcategoryBelongsToCategory($subcategoryId, $categoryId)
    {
        return SubCategory::where('id', $subcategoryId)->where('category_id',$categoryId)->exists();
    }
}

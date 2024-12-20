<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use App\Models\SubCategory;
use App\Rules\ProfitRatioRule;
use Illuminate\Foundation\Http\FormRequest;

class SingleProductUpdateRequest extends FormRequest
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
        // Retrieve the product ID from the route
        $productId = $this->route('id');
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:sub_categories,id'],
            'profit_type' => ['required','in:FLAT,PERCENTAGE'],
//            'profit' => ['required', new ProfitRatioRule()],
            'description' => ['nullable', 'string','max:20000'],
            'specification' => ['nullable', 'string','max:20000'],
            'colors' => ['nullable','sometimes', 'array'],
            'colors.*' => ['required','exists:colors,id'],
            'sizes' => ['nullable','sometimes', 'array'],
            'sizes.*' => ['required','exists:sizes,id'],
            'images' => ['nullable','sometimes', 'array', 'max:10'],
            'images.*image' => 'nullable|mimes:jpeg,png,jpg|max:2040',
            'images.*.is_thumbnail' => ['required', 'boolean'],
            'active_status' => ['required','in:0,1'],
            'is_new_arrival' => ['nullable','sometimes','in:0,1'],
            'is_best_selling' => ['nullable','sometimes','in:0,1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            // Validate the product ID
            $productId = $this->route('id');
            if (!Product::where('id',$productId)->exists()) {
                $validator->errors()->add('id', 'The specified product id does not exist.');
            }

            $categoryId = $data['category_id'] ?? null;
            $subcategoryId = $data['subcategory_id'] ?? null;
            if ($categoryId && !$this->subcategoryBelongsToCategory($subcategoryId, $categoryId)) {
                $validator->errors()->add("subcategory_id", "The selected subcategory does not belong to the specified category.");
            }
        });
    }

    protected function subcategoryBelongsToCategory($subcategoryId, $categoryId)
    {
        return SubCategory::where('id', $subcategoryId)->where('category_id', $categoryId)->exists();
    }
}

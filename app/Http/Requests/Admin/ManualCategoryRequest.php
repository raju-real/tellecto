<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Rules\ArrayLengthsMatch;
use Illuminate\Foundation\Http\FormRequest;

class ManualCategoryRequest extends FormRequest
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
            'product_numbers' => ['required', 'array'],
            'product_numbers.*' => ['required', 'exists:products,product_number'],
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'exists:categories,id'],
            'subcategories' => ['required', 'array'],
            'subcategories.*' => ['required', 'exists:sub_categories,id']
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
        // After basic validation rules, add custom rules
        $validator->after(function ($validator) {
            $data = $this->all();

            // Check if the main fields are present and are arrays
            if ($this->hasRequiredFields($data)) {
                // Add ArrayLengthsMatch rule
                $validator->addRules([
                    'product_numbers' => [new ArrayLengthsMatch(['categories', 'subcategories'])],
                    'categories' => [new ArrayLengthsMatch(['product_numbers', 'subcategories'])],
                    'subcategories' => [new ArrayLengthsMatch(['product_numbers', 'categories'])],
                ]);

                // Validate subcategory belongs to category
                $this->validateSubcategoryBelongsToCategory($validator, $data);
            }
        });
    }

    /**
     * Check if required fields are present and are arrays.
     *
     * @param array $data
     * @return bool
     */
    protected function hasRequiredFields(array $data)
    {
        return isset($data['product_numbers'], $data['categories'], $data['subcategories']) &&
               is_array($data['product_numbers']) &&
               is_array($data['categories']) &&
               is_array($data['subcategories']);
    }

    /**
     * Validate that each subcategory belongs to the corresponding category.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param array $data
     * @return void
     */
    protected function validateSubcategoryBelongsToCategory($validator, $data)
    {
        foreach ($data['subcategories'] as $index => $subcategoryId) {
            $categoryId = $data['categories'][$index] ?? null;
            if ($categoryId && !$this->subcategoryBelongsToCategory($subcategoryId, $categoryId)) {
                $validator->errors()->add("subcategories.$index", 'The selected subcategory does not belong to the specified category.');
            }
        }
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
        return Category::where('id', $categoryId)->whereHas('subcategories', function ($query) use ($subcategoryId) {
            $query->where('id', $subcategoryId);
        })->exists();
    }

    public function messages()
    {
        return [
            'product_numbers.required' => 'The Product Numbers field is required.',
            'product_numbers.array' => 'The Product Numbers must be an array.',
            'product_numbers.*.required' => 'Each product number is required.',
            'product_numbers.*.exists' => 'The selected product number is invalid.',
            'categories.required' => 'The Categories field is required.',
            'categories.array' => 'The Categories must be an array.',
            'categories.*.required' => 'Each category is required.',
            'categories.*.exists' => 'The selected category value is invalid.',
            'subcategories.required' => 'The Subcategories field is required.',
            'subcategories.array' => 'The Subcategories must be an array.',
            'subcategories.*.required' => 'Each subcategory is required.',
            'subcategories.*.exists' => 'The selected subcategory value is invalid.',
            'subcategories.*' => 'The selected subcategory does not belong to the specified category.'
        ];
    }
}

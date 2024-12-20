<?php

namespace App\Rules;

use App\Models\SubCategory;
use Illuminate\Contracts\Validation\Rule;

class SubcategoryBelongsToCategory implements Rule
{
    protected $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function passes($attribute, $value)
    {
        return SubCategory::where('id', $value)
            ->where('category_id', $this->categoryId)
            ->exists();
    }

    public function message()
    {
        return 'The selected subcategory does not belong to the specified category.';
    }
}

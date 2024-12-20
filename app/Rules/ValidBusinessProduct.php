<?php
namespace App\Rules;

use App\Models\BusinessProductPrice;
use Illuminate\Contracts\Validation\Rule;

class ValidBusinessProduct implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $businessId = auth()->user()->id;

        // Check if the product exists with the given product_number and business_id
        return BusinessProductPrice::where('product_id', $value)
            ->where('business_id', $businessId)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected product is invalid or does not belong to your business.';
    }
}

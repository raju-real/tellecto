<?php

namespace App\Rules;

use App\Models\BusinessProductPrice;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BusinessProductRule implements ValidationRule
{
    protected $missing_product = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $check_product = [
            'product_id' => $value,
            'business_id' => authAgentInfo()['business_id']
        ];

        if (!BusinessProductPrice::where($check_product)->exists()) {
            $this->missing_product[] = Product::productNumberByID($value);
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The products ' . implode(' and ', $this->missing_product) . ' do not belong to your business.';
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfitRatioRule implements ValidationRule
{
    /**
     * Validate the attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validate if value is numeric (integer or float)
        if (!is_numeric($value) || !$this->isValidFormat($value)) {
            $fail('The :attribute should be numeric and before decimal 10 and after decimal 2.');
        }
    }

    /**
     * Validate the format.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isValidFormat($value): bool
    {
        // Validate format: up to 8 digits before decimal and up to 2 digits after decimal
        return preg_match('/^\d{1,10}(\.\d{1,2})?$/', $value) === 1;
    }
}

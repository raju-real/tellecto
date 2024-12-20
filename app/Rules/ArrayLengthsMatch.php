<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayLengthsMatch implements Rule
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function passes($attribute, $value)
    {
        $length = count($value);
        foreach ($this->fields as $field) {
            if (count(request($field)) !== $length) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'The :attribute and ' . implode(', ', $this->fields) . ' must have the same number of elements.';
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayLengthCheck implements Rule
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function passes($attribute, $value)
    {
        $data = request()->all();
        $length = count($data[$this->fields[0]]);

        foreach ($this->fields as $field) {
            if (!isset($data[$field]) || count($data[$field]) !== $length) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute and ' . implode(' and ', $this->fields) . ' must have the same number of items.';
    }
}

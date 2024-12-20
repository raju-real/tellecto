<?php

namespace App\Rules;

use App\Models\Address;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgentAddressRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $check_address = [
            'id' => $value,
            'agent_id' => authAgentInfo()['agent_id']
        ];

        if (!Address::where($check_address)->exists()) {
            $fail($this->message());
        }

        $address = Address::where($check_address)->first();
        if ($address->address == null) {
            $fail("The address should not be empty!");
        }

        if ($address->zip_code == null) {
            $fail("The zip code should not be empty!");
        }

        if ($address->city == null) {
            $fail("The city should not be empty!");
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The address is not associated with authenticate agent';
    }
}

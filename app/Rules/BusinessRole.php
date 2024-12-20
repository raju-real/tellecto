<?php
namespace App\Rules;

use App\Models\RolePermission\Role;
use Illuminate\Contracts\Validation\Rule;

class BusinessRole implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return Role::where('id', $value)->where('type', 'BUSINESS')->exists();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The selected role is not an business role.';
    }
}

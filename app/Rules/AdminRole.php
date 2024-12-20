<?php
namespace App\Rules;

use App\Models\RolePermission\Role;
use Illuminate\Contracts\Validation\Rule;

class AdminRole implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return Role::where('id', $value)->where('type', 'ADMIN')->exists();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The selected role is not an admin role.';
    }
}

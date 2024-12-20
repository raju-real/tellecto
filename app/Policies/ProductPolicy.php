<?php

namespace App\Policies;

use App\Models\BusinessProductPrice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given product can be viewed by the user.
     */
    public function view(User $user, BusinessProductPrice $product)
    {
        return $user->id === $product->business_id;
    }
}


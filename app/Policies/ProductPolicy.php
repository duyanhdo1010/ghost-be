<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }

    public function create(User $user)
    {
        return $user->isAdministrator();
    }

    public function update(User $user)
    {
        return $user->isAdministrator();
    }

    public function delete(User $user)
    {
        return $user->isAdministrator();
    }
}

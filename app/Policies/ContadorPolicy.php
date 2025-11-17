<?php

namespace App\Policies;

use App\Models\Contador;
use App\Models\User;

class ContadorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contador $contador): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }

        if ($user->role === 'CONTADOR') {
            return $user->contador->contador_id === $contador->contador_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contador $contador): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contador $contador): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contador $contador): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contador $contador): bool
    {
        return $user->role === 'ADMIN';
    }
}

<?php

namespace App\Policies;

use App\Models\Contabilidade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContabilidadePolicy
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
    public function view(User $user, Contabilidade $contabilidade): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }

        if ($user->role === 'CONTADOR') {
            return $user->contador->contabilidade_id === $contabilidade->contabilidade_id;
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
    public function update(User $user, Contabilidade $contabilidade): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contabilidade $contabilidade): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contabilidade $contabilidade): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contabilidade $contabilidade): bool
    {
        return $user->role === 'ADMIN';
    }
}

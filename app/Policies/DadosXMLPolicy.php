<?php

namespace App\Policies;

use App\Models\DadosXML;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DadosXMLPolicy
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
    public function view(User $user, DadosXML $dadosXML): bool
    {
        // ADMIN vê tudo
        if ($user->role === 'ADMIN') {
            return true;
        }

        // CONTADOR vê XMLs das empresas de sua contabilidade
        if ($user->role === 'CONTADOR') {
            return $user->contador?->contabilidade?->empresas
                ->contains('empresa_id', $dadosXML->empresa_id);
        }

        // CLIENTE vê apenas XMLs de sua empresa
        if ($user->role === 'CLIENTE') {
            return $user->cliente?->empresa_id === $dadosXML->empresa_id;
        }

        return false;
    }

    /**
     * Usuário pode fazer download de DadosXML
     */
    public function download(User $user, DadosXML $dadosXML): bool
    {
        return $this->view($user, $dadosXML);
    }

    /**
     * Usuário pode consultar XMLs de uma empresa
     */
    public function consultarEmpresa(User $user, int $empresaId): bool
    {
        // ADMIN pode consultar qualquer empresa
        if ($user->role === 'ADMIN') {
            return true;
        }

        // CONTADOR pode consultar empresas de sua contabilidade
        if ($user->role === 'CONTADOR') {
            return $user->contador?->contabilidade?->empresas
                ->contains('empresa_id', $empresaId);
        }

        // CLIENTE pode consultar apenas sua empresa
        if ($user->role === 'CLIENTE') {
            return $user->cliente?->empresa_id === $empresaId;
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
    public function update(User $user, DadosXML $dadosXML): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DadosXML $dadosXML): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DadosXML $dadosXML): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DadosXML $dadosXML): bool
    {
        return $user->role === 'ADMIN';
    }
}

<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\User;
use App\Repositories\Interface\IUsuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UsuarioRepository implements IUsuario
{
    public function paginacaoUsuarios(string $role, int $perPage, ?string $consulta): LengthAwarePaginator
    {
        return User::query()
            ->where('role', $role)
            ->where('name', 'like', "%$consulta%")
            ->where('email', 'like', "%$consulta%")
            ->paginate($perPage, [
                'id',
                'name',
                'email',
            ]);
    }

    public function consultaUsuario(int $usuario_id): ?Model
    {
        return User::query()
            ->where('id', $usuario_id)
            ->first([
                'id',
                'name',
                'email',
            ]);
    }

    public function consultaUsuarioPorEmail(string $email): ?Model
    {
        return User::query()
            ->where('email', $email)
            ->first([
                'id',
                'name',
                'email',
            ]);
    }

    public function cadastraUsuario(array $dados): Model
    {
        return User::query()
            ->create($dados);
    }

    public function editaUsuario(array $dados): int
    {
        return User::query()
            ->where('id', $dados['id'])
            ->update($dados);
    }
}

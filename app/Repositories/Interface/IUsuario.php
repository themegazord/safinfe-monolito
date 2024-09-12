<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUsuario {
  public function paginacaoUsuarios(string $role, int $perPage = 10, ?string $consulta): LengthAwarePaginator;
  public function cadastraUsuario(array $dados): Model;
  public function consultaUsuario(int $usuario_id): ?Model;
  public function consultaUsuarioPorEmail(string $email): ?Model;
  public function editaUsuario(array $dados): int;
}

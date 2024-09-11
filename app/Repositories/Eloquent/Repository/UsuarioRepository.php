<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\User;
use App\Repositories\Interface\IUsuario;
use Illuminate\Database\Eloquent\Model;

class UsuarioRepository implements IUsuario {
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

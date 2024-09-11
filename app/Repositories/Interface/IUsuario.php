<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

interface IUsuario {
  public function cadastraUsuario(array $dados): Model;
  public function editaUsuario(array $dados): int;
}

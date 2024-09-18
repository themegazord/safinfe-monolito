<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

interface IEmpCont {
  public function cadastrar(array $dados): ?Model;
  public function removeRelacionamentoContabilidade(int $contabilidade_id): mixed;
}

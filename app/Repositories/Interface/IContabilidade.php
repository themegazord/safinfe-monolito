<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IContabilidade {
  public function paginacaoContabilidades(int $perPage, string $consulta): LengthAwarePaginator;
  public function cadastroContabilidade(array $dados): Model;
  public function consultaContabilidade(int $contabilidade_id): ?Model;
  public function editaContabilidade(array $dados): int;
  public function removeContabilidade(int $contabilidade_id): mixed;
}

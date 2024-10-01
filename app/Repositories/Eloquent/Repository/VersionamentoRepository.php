<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Versionamento;
use App\Repositories\Interface\IVersionamento;
use Illuminate\Database\Eloquent\Model;

class VersionamentoRepository implements IVersionamento {
  public function cadastro(array $dados): Model
  {
    return Versionamento::query()
      ->create($dados);
  }

  public function consultaVersaoPorPatch(string $patch): ?Model
  {
    return Versionamento::query()
      ->where('patch', $patch)
      ->first();
  }

  public function consultaVersaoPorId(int $versionamento_id): ?Model {
    return Versionamento::query()
      ->where('versionamento_id', $versionamento_id)
      ->first();
  }
}

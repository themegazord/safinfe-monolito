<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Contador;
use App\Repositories\Interface\IContador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ContadorRepository implements IContador {
  public function paginacaoContadores(int $perPage, ?string $pesquisa, bool $status = true): LengthAwarePaginator
  {
    $consulta = Contador::select([
      'contador_id',
      'usuario_id',
      'contadores.contabilidade_id',
      'nome',
      'email',
      'cpf',
      'contabilidades.social as social'
    ])
      ->join('contabilidades', 'contabilidades.contabilidade_id', '=', 'contadores.contabilidade_id')
      ->where(function ($query) use ($pesquisa) {
        $query->where('nome', 'like', "%$pesquisa%")
          ->orWhere('email', 'like', "%$pesquisa%")
          ->orWhere('cpf', 'like', "%pesquisa%")
          ->orWhere('contabilidades.social', 'like', "%$pesquisa%");
      });

    if (!$status) {
      return $consulta->onlyTrashed()->paginate($perPage);
    }

    return $consulta->whereNull('contadores.deleted_at')->paginate($perPage);
  }

  public function cadastroContador(array $dados): Model
  {
    return Contador::query()
      ->create($dados);
  }

  public function consultaContador(int $contador_id): ?Model
  {
    return Contador::query()
      ->where('contador_id', $contador_id)
      ->first([
        'contador_id',
        'usuario_id',
        'contabilidade_id',
        'nome',
        'email',
        'cpf',
      ]);
  }

  public function consultaContadorPorEmail(string $email): ?Model
  {
    return Contador::query()
      ->where('email', $email)
      ->first([
        'contador_id',
        'usuario_id',
        'contabilidade_id',
        'nome',
        'email',
        'cpf',
      ]);
  }

  public function consultaContadorPorCPF(string $cpf): ?Model
  {
    return Contador::query()
      ->where('cpf', $cpf)
      ->first([
        'contador_id',
        'usuario_id',
        'contabilidade_id',
        'nome',
        'email',
        'cpf',
      ]);
  }

  public function editaContador(array $dados): int
  {
    return Contador::query()
      ->where('contador_id', $dados['contador_id'])
      ->update($dados);
  }
}

<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Cliente;
use App\Repositories\Interface\ICliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ClienteRepository implements ICliente
{
  public function paginacaoClientes(int $perPage, ?string $pesquisa = null, bool $status = true): LengthAwarePaginator
  {
    $consulta = Cliente::select([
      'cliente_id',
      'usuario_id',
      'clientes.empresa_id',
      'nome',
      'email',
      'empresas.fantasia as fantasia'
    ])
      ->join('empresas', 'empresas.empresa_id', '=', 'clientes.empresa_id')
      ->where(function ($query) use ($pesquisa) {
        $query->where('nome', 'like', "%$pesquisa%")
          ->orWhere('email', 'like', "%$pesquisa%")
          ->orWhere('empresas.fantasia', 'like', "%$pesquisa%");
      });

    if (!$status) {
      return $consulta->onlyTrashed()->paginate($perPage);
    }

    return $consulta->whereNull('clientes.deleted_at')->paginate($perPage);
  }

  public function cadastroCliente(array $dados): Model
  {
    return Cliente::query()
      ->create($dados);
  }

  public function consultaClientePorEmail(string $email): ?Model
  {
    return Cliente::query()
      ->where('email', $email)
      ->first(['cliente_id', 'email']);
  }

  public function consultaCliente(int $cliente_id): ?Model
  {
    return Cliente::query()
      ->where('cliente_id', $cliente_id)
      ->first([
        'cliente_id',
        'usuario_id',
        'empresa_id',
        'nome',
        'email',
      ]);
  }

  public function editaCliente(array $dados): int
  {
    return Cliente::query()
      ->where('cliente_id', $dados['cliente_id'])
      ->update($dados);
  }

  public function removeCliente(int $cliente_id): mixed
  {
    return Cliente::query()
      ->where('cliente_id', $cliente_id)
      ->delete();
  }
}

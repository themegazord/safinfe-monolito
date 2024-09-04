<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Empresa;
use App\Repositories\Interface\IEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EmpresaRepository implements IEmpresa
{
  public function paginacaoEmpresas(int $perPage = 10, string $consulta): LengthAwarePaginator
  {
    return Empresa::query()
      ->orWhere('fantasia', 'like', '%' . $consulta . '%')
      ->orWhere('social', 'like', '%' . $consulta . '%')
      ->orWhere('cnpj', 'like', '%' . $consulta . '%')
      ->orWhere('ie', 'like', '%' . $consulta . '%')
      ->orWhere('email_contato', 'like', '%' . $consulta . '%')
      ->paginate(10, [
        'empresa_id',
        'fantasia',
        'social',
        'cnpj',
        'ie',
        'email_contato'
      ]);
  }

  public function cadastroEmpresa(array $dados): Model
  {
    return Empresa::query()
      ->create($dados);
  }

  public function consultaEmpresa(int $empresa_id): ?Model
  {
    return Empresa::query()
      ->where('empresa_id', $empresa_id)
      ->first([
        'empresa_id',
        'endereco_id',
        'fantasia',
        'social',
        'cnpj',
        'ie',
        'email_contato',
        'telefone_contato',
        'telefone_reserva',
      ]);
  }

  public function editaEmpresa(array $dados): int
  {
    return Empresa::query()
      ->where('empresa_id', $dados['empresa_id'])
      ->update($dados);
  }

  public function removeEmpresa(int $empresa_id): mixed
  {
    return Empresa::query()
      ->where('empresa_id', $empresa_id)
      ->delete();
  }
}

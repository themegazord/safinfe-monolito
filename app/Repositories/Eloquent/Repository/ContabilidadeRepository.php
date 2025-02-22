<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Contabilidade;
use App\Repositories\Interface\IContabilidade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ContabilidadeRepository implements IContabilidade
{
  public function paginacaoContabilidades(int $perPage = 10, ?string $consulta): LengthAwarePaginator
  {
    return Contabilidade::query()
      ->orWhere('social', 'like', '%' . $consulta . '%')
      ->orWhere('cnpj', 'like', '%' . $consulta . '%')
      ->orWhere('telefone_corporativo', 'like', '%' . $consulta . '%')
      ->orWhere('email_corporativo', 'like', '%' . $consulta . '%')
      ->paginate(10, [
        'contabilidade_id',
        'social',
        'cnpj',
        'telefone_corporativo',
        'email_corporativo',
      ]);
  }

  public function listagemContabilidades(): Collection {
    return Contabilidade::query()
      ->get([
        'contabilidade_id',
        'social',
        'cnpj',
        'telefone_corporativo',
        'email_corporativo',
      ]);
  }

  public function cadastroContabilidade(array $dados): Model
  {
    return Contabilidade::query()
      ->create($dados);
  }

  public function consultaContabilidade(int $Contabilidade_id): ?Model
  {
    return Contabilidade::query()
      ->where('contabilidade_id', $Contabilidade_id)
      ->first([
        'contabilidade_id',
        'endereco_id',
        'social',
        'cnpj',
        'telefone_corporativo',
        'email_corporativo',
        'email_contato',
        'telefone_contato',
        'telefone_reserva',
      ]);
  }

  public function consultaContabilidadePorEmailCorporativo(string $email): ?Model
  {
    return Contabilidade::query()
      ->where('email_corporativo', $email)
      ->first([
        'contabilidade_id',
        'endereco_id',
        'social',
        'cnpj',
        'telefone_corporativo',
        'email_corporativo',
        'email_contato',
        'telefone_contato',
        'telefone_reserva',
      ]);
  }

  public function consultaContabilidadePorCNPJ(string $cnpj): ?Model
  {
    return Contabilidade::query()
      ->where('cnpj', $cnpj)
      ->first([
        'contabilidade_id',
        'endereco_id',
        'social',
        'cnpj',
        'telefone_corporativo',
        'email_corporativo',
        'email_contato',
        'telefone_contato',
        'telefone_reserva',
      ]);
  }

  public function editaContabilidade(array $dados): int
  {
    return Contabilidade::query()
      ->where('contabilidade_id', $dados['contabilidade_id'])
      ->update($dados);
  }

  public function removeContabilidade(int $Contabilidade_id): mixed
  {
    return Contabilidade::query()
      ->where('contabilidade_id', $Contabilidade_id)
      ->delete();
  }
}

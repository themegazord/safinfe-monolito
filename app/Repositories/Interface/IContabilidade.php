<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IContabilidade
{
    public function paginacaoContabilidades(int $perPage, ?string $consulta): LengthAwarePaginator;

    public function listagemContabilidades(): Collection;

    public function cadastroContabilidade(array $dados): Model;

    public function consultaContabilidade(int $contabilidade_id): ?Model;

    public function consultaContabilidadePorEmailCorporativo(string $email): ?Model;

    public function consultaContabilidadePorCNPJ(string $cnpj): ?Model;

    public function editaContabilidade(array $dados): int;

    public function removeContabilidade(int $contabilidade_id): mixed;
}

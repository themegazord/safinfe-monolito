<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IEmpresa
{
    public function paginacaoEmpresas(int $perPage, string $consulta): LengthAwarePaginator;

    public function listagemEmpresas(): Collection;

    public function cadastroEmpresa(array $dados): Model;

    public function consultaEmpresa(int $empresa_id): ?Model;

    public function consultaEmpresaPorCNPJ(string $cnpj): ?Model;

    public function editaEmpresa(array $dados): int;

    public function removeEmpresa(int $empresa_id): mixed;
}

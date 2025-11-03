<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICliente
{
    public function paginacaoClientes(int $perPage, ?string $pesquisa, bool $status = true): LengthAwarePaginator;

    // public function listagemClientes(): Collection;
    public function cadastroCliente(array $dados): Model;

    public function consultaClientePorEmail(string $email): ?Model;

    public function consultaCliente(int $cliente_id): ?Model;

    public function editaCliente(array $dados): int;

    public function removeCliente(int $cliente_id): mixed;
}

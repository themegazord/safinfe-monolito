<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IContador {
  public function paginacaoContadores(int $perPage, ?string $pesquisa, bool $status = true): LengthAwarePaginator;
  // public function listagemContadors(): Collection;
  public function cadastroContador(array $dados): Model;
  public function consultaContadorPorEmail(string $email): ?Model;
  public function consultaContadorPorCPF(string $cpf): ?Model;
  public function consultaContador(int $contador_id): ?Model;
  public function editaContador(array $dados): int;
}

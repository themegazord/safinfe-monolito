<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

interface IEndereco
{
    public function consultaEndereco(int $endereco_id): ?Model;

    public function cadastraEndereco(array $dados): Model;

    public function editaEndereco(array $dados): int;
}

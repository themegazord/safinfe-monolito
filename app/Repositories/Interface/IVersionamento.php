<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

interface IVersionamento
{
    public function cadastro(array $dados): Model;

    public function consultaVersaoPorPatch(string $patch): ?Model;

    public function consultaVersaoPorId(int $versionamento_id): ?Model;
}

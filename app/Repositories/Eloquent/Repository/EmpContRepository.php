<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\EmpCont;
use App\Repositories\Interface\IEmpCont;
use Illuminate\Database\Eloquent\Model;

class EmpContRepository implements IEmpCont
{
    public function cadastrar(array $dados): ?Model
    {
        return EmpCont::query()
            ->create($dados);
    }

    public function removeRelacionamentoContabilidade(int $contabilidade_id): mixed
    {
        return EmpCont::query()
            ->where('contabilidade_id', $contabilidade_id)
            ->delete();
    }
}

<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\Endereco;
use App\Repositories\Interface\IEndereco;
use Illuminate\Database\Eloquent\Model;

class EnderecoRepository implements IEndereco
{
    public function consultaEndereco(int $endereco_id): ?Model
    {
        return Endereco::query()
            ->where('endereco_id', $endereco_id)
            ->first([
                'endereco_id',
                'rua',
                'numero',
                'cep',
                'bairro',
                'complemento',
                'cidade',
                'estado',
            ]);
    }

    public function cadastraEndereco(array $dados): Model
    {
        return Endereco::query()
            ->create($dados);
    }

    public function editaEndereco(array $dados): int
    {
        return Endereco::query()
            ->where('endereco_id', $dados['endereco_id'])
            ->update($dados);
    }
}

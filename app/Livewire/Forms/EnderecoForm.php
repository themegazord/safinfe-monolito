<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'rua' => 'required|max:155',
    'numero' => 'required|integer',
    'cep' => 'required|max:8',
    'bairro' => 'required|max:155',
    'complemento' => 'max:255',
    'cidade' => 'required|max:155',
    'estado' => 'required|max:2',
], message: [
    'required' => 'O campo é obrigatório.',
    'rua.max' => 'A rua deve conter no máximo 155 caracteres',
    'cep.max' => 'O CEP deve conter no máximo 8 caracteres',
    'bairro.max' => 'O bairro deve conter no máximo 155 caracteres',
    'complemento.max' => 'O complemento deve conter no máximo 255 caracteres',
    'cidade.max' => 'A cidade deve conter no máximo 155 caracteres',
    'estado.max' => 'A sigla do estado deve conter no máximo 2 caracteres',
])]
class EnderecoForm extends Form
{
    public string $rua = '';

    public ?int $numero = null;

    public string $cep = '';

    public string $bairro = '';

    public ?string $complemento = null;

    public string $cidade = '';

    public string $estado = '';

    public function tratarCamposSujos(): void
    {
        $this->cep = str_replace(['-', '.', '/'], '', $this->cep);
    }
}

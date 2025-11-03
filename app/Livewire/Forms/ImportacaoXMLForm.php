<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'cnpj' => 'required',
    'arquivo' => 'required',
], message: [
    'required' => 'Campo obrigatorio.',
])]
class ImportacaoXMLForm extends Form
{
    public ?string $cnpj = null;

    public $arquivo;
}

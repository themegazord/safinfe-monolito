<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'arquivo' => 'required',
], message: [
    'required' => 'Campo obrigatorio',
])]
class ImportacaoContabilidadeForm extends Form
{
    public $arquivo;
}

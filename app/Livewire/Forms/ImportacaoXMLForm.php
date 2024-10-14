<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'empresa_id' => 'required|exists:empresas',
  'arquivo' => 'required'
], message: [
  'required' => 'Campo obrigatorio.',
  'empresa_id.exists' => 'A empresa nao existe.'
])]
class ImportacaoXMLForm extends Form
{
  public ?string $empresa_id = null;
  public $arquivo;
}

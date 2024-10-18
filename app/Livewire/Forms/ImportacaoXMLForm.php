<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'empresa_id' => 'required',
  'arquivo' => 'required'
], message: [
  'required' => 'Campo obrigatorio.',
])]
class ImportacaoXMLForm extends Form
{
  public ?string $empresa_id = null;
  public $arquivo;
}

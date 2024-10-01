<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'patch' => 'required',
  'detalhe' => 'required'
], message: [
  'required' => 'Campo obrigatório',
])]
class VersaoForm extends Form
{
  public string $patch = '';
  public string $detalhe = '';
}

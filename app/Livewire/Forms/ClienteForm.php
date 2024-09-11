<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'nome' => 'required|max:255',
  'email' => 'required|email|max:255'
], message: [
  'required' => 'O campo é obrigatório.',
  'nome.max' => 'O nome deve conter no máximo 255 caracteres.',
  'email.email' => 'O email é inválido.',
  'email.max' => 'O email deve conter no máximo 255 caracteres.'
])]
class ClienteForm extends Form
{
  public ?int $empresa_id = null;
  public ?int $usuario_id = null;
  public string $nome = '';
  public string $email = '';
}

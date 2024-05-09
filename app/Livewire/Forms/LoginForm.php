<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
  #[Validate(rule: [
    'email' => 'required|email|string|max:155',
  ],
  message: [
    'email.required' => 'O campo de email é obrigatório',
    'email.email' => 'Esse email não é um email válido',
    'email.max:155' => 'O email deve conter no máximo 155 caracteres.',
    'email.string' => 'O email obrigatóriamente deve ser uma string'
  ])]
  public string $email = '';
  #[Validate(rule: [
    'senha' => 'required|max:155',
  ],
  message: [
    'senha.required' => 'O campo de senha é obrigatório',
    'senha.max:155' => 'O senha deve conter no máximo 155 caracteres.'
  ])]
  public string $senha = '';
}

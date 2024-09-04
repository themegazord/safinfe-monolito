<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'email' => 'required|email|string|max:155|exists:users,email',
  'password' => 'required|max:155',
],
message: [
  'email.required' => 'O campo de email é obrigatório',
  'email.email' => 'Esse email não é um email válido',
  'email.max:155' => 'O email deve conter no máximo 155 caracteres.',
  'email.string' => 'O email obrigatóriamente deve ser uma string',
  'email.exists' => 'Não existe esse email no banco de dados',
  'password.required' => 'O campo de senha é obrigatório',
  'password.max:155' => 'O senha deve conter no máximo 155 caracteres.'
])]
class LoginForm extends Form
{
  public string $email = '';
  public string $password = '';

  public function efetuarLogin(): void {
    if (Auth::attempt($this->all())) {
      Auth::login(Auth::user());
    }
  }
}

<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'password' => 'required'
], message: [
  'required' => 'O campo é obrigatório.'
])]
class UsuarioForm extends Form
{
  public string $role = '';
  public string $name = '';
  public string $email = '';
  public string $password = '';

  public function encriptaSenha(): void {
    $this->password = Hash::make($this->password);
  }
}

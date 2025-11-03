<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'name' => 'required|max:255',
    'email' => 'required|email|max:255',
    'password' => 'required',
], message: [
    'required' => 'O campo é obrigatório.',
    'name.max' => 'O nome deve conter no máximo 255 caracteres.',
    'email.email' => 'O email é inválido.',
    'email.max' => 'O email deve conter no máximo 255 caracteres.',
])]
class UsuarioForm extends Form
{
    public string $role = '';

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function encriptaSenha(): void
    {
        $this->password = Hash::make($this->password);
    }
}

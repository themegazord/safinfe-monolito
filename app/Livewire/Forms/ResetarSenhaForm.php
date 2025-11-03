<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'email' => 'required|max:255|email',
], message: [
    'email.email' => 'O email é inválido',
    'required' => 'O campo é obrigatório.',
    'email.max' => 'O email deve conter no máximo 255 caracteres.',
])]
class ResetarSenhaForm extends Form
{
    public ?string $email = null;
}

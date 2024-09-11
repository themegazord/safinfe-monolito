<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'nome' => 'required|max:255',
  'email' => 'required|email|max:255',
  'cpf' => 'required|size:11',
  'contabilidade_id' => 'required'
], message: [
  'required' => 'O campo é obrigatório.',
  'nome.max' => 'O nome deve conter no máximo 255 caracteres.',
  'email.email' => 'O email é inválido.',
  'email.max' => 'O email deve conter no máximo 255 caracteres.',
  'cpf.size' => 'O CPF deve conter 11 caracteres'
])]
class ContadorForm extends Form
{
  public ?int $usuario_id = null;
  public ?int $contabilidade_id = null;
  public string $nome = '';
  public string $email = '';
  public string $cpf = '';

  public function limpaCampos(): void {
    $this->cpf = str_replace(['-', '.', '/', '_'], '', $this->cpf);
  }
}

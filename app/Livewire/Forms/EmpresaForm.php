<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'fantasia' => 'required|max:255',
  'social' => 'required|max:255',
  'cnpj' => 'required|max:14',
  'ie' => 'max:20',
  'email_contato' => 'required|email|max:255',
  'telefone_contato' => 'required|max:20',
  'telefone_reserva' => 'max:20'
], message: [
  'required' => 'O campo é obrigatório.',
  'fantasia.max' => 'O nome fantasia da empresa deve conter no máximo 255 caracteres.',
  'social.max' => 'A razão social da empresa deve conter no máximo 255 caracteres.',
  'cnpj.max' => 'O CNPJ deve conter no máximo 14 caracteres.',
  'cnpj.exists' => 'O CNPJ já está cadastrado no sistema.',
  'ie.max' => 'A IE deve conter no máximo 20 caracteres.',
  'ie.exists' => 'A IE já está cadastrada no sistema.',
  'email_contato.email' => 'O email de contato informado é inválido.',
  'email_contato.max' => 'O email de contato deve conter no máximo 255 caracteres.',
  'telefone_contato.max' => 'O telefone de contato deve conter no máximo 20 caracteres.',
  'telefone_reserva.max' => 'O telefone de reserva deve conter no máximo 20 caracteres.'
])]
class EmpresaForm extends Form
{
  public ?int $endereco_id = null;
  public string $fantasia = '';
  public string $social = '';
  public string $cnpj = '';
  public ?string $ie = null;
  public string $email_contato = '';
  public string $telefone_contato = '';
  public ?string $telefone_reserva = null;

  public function tratarCamposSujos(): void {
    $this->cnpj = str_replace(['-', '.', '/'], '', $this->cnpj);
    $this->ie = str_replace(['-', '.', '/'], '', $this->ie);
    $this->telefone_contato = trim(str_replace(['-', '.', '/', '(', ')'], '', $this->telefone_contato));
    $this->telefone_reserva = trim(str_replace(['-', '.', '/', '(', ')'], '', $this->telefone_reserva));
    $this->email_contato = strtolower(trim($this->email_contato));
  }
}

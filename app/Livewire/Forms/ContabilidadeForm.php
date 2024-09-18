<?php

namespace App\Livewire\Forms;

use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'social' => 'required|max:255',
  'cnpj' => 'required|max:14',
  'telefone_corporativo' => 'required|max:20',
  'email_corporativo' => 'required|max:255',
  'email_contato' => 'required|max:255',
  'telefone_contato' => 'required|max:20',
  'telefone_reserva' => 'max:20'
], message: [
  'required' => 'O campo é obrigatório.',
  'social.max' => 'A razão social da contabilidade deve conter no máximo 255 caracteres.',
  'cnpj.max' => 'O CNPJ deve conter no máximo 14 caracteres.',
  'telefone_corporativo.max' => 'O telefone corporativo deve conter no máximo 20 caracteres.',
  'email_corporativo.max' => 'O email corporativo deve conter no máximo 255 caracteres.',
  'email_contato.max' => 'O email corporativo deve conter no máximo 255 caracteres.',
  'telefone_contato.max' => 'O telefone corporativo deve conter no máximo 20 caracteres.',
  'telefone_reserva.max' => 'O telefone corporativo deve conter no máximo 20 caracteres.',
])]
class ContabilidadeForm extends Form
{
  public ?int $endereco_id = null;
  public string $social = '';
  public string $cnpj = '';
  public string $telefone_corporativo = '';
  public string $email_corporativo = '';
  public string $email_contato = '';
  public string $telefone_contato = '';
  public ?string $telefone_reserva = null;
  public ?array $empresas = [];

  public function tratarCamposSujos(): void
  {
    $this->cnpj = str_replace(['-', '.', '/'], '', $this->cnpj);
    $this->telefone_corporativo = trim(str_replace(['-', '.', '/', '(', ')'], '', $this->telefone_corporativo));
    $this->telefone_contato = trim(str_replace(['-', '.', '/', '(', ')'], '', $this->telefone_contato));
    $this->telefone_reserva = trim(str_replace(['-', '.', '/', '(', ')'], '', $this->telefone_reserva));
    $this->email_contato = strtolower(trim($this->email_contato));
    $this->email_corporativo = strtolower(trim($this->email_corporativo));
  }

  public function filtraEmpresas(): void {
    $this->empresas = array_filter($this->empresas, fn ($empresa) => $empresa);
  }
}

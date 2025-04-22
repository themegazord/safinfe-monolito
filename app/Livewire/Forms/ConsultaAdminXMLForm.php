<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'data_inicio_fim' => 'required_without:numeroInicial,numeroFinal',
  'empresa_id' => 'required'
], message: [
  'required' => 'Campo obrigatório',
  'required_without' => 'Campo obrigatório.'
])]
class ConsultaAdminXMLForm extends Form
{
  public ?string $data_inicio_fim = '2024-06-01 00:00 até 2024-06-30 00:00';
  public ?string $data_inicio = null;
  public ?string $data_fim = null;
  public string $status = "TODAS";
  public ?string $serie = null;
  public string $modelo = "TODAS";
  public ?int $numeroInicial = null;
  public ?int $numeroFinal = null;
  public int|string|null $empresa_id = 3;
}

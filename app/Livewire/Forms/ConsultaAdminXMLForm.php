<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
  'data_inicio' => 'required_without:numeroInicial,numeroFinal',
  'data_fim' => 'required_without:numeroInicial,numeroFinal',
  'empresa_id' => 'required'
], message: [
  'required' => 'Campo obrigatório',
  'required_without' => 'Campo obrigatório.'
])]
class ConsultaAdminXMLForm extends Form
{
  public ?string $data_inicio = null;
  public ?string $data_fim = null;
  public string $status = "TODAS";
  public ?string $serie = null;
  public string $modelo = "TODAS";
  public ?int $numeroInicial = null;
  public ?int $numeroFinal = null;
  public ?int $empresa_id = null;
}

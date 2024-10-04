<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class Totalnotaspordiames extends Component
{
  public array $dadosAtual = [];

  public function mount(array $dados): void {
    foreach($dados as $key => $dado) {
      array_push($this->dadosAtual, array('y' => date('d/m/Y', strtotime($key)), 'value' => $dado));
    }
  }
  public function render()
  {
    return view('livewire.componentes.relatorios.dashboard.totalnotaspordiames');
  }
}

<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class Topprodutosvendidos extends Component
{
  public array $dadosTopProdutosVendidos = [];

  public function mount(array $dados): void {
    foreach($dados as $dado) {
      array_push($this->dadosTopProdutosVendidos, array('y' => $dado['Nome Produto'], 'value' => $dado['Quantidade']));
    }
  }

  public function render()
  {
    return view('livewire.componentes.relatorios.dashboard.topprodutosvendidos');
  }
}

<?php

namespace App\Livewire\Views\Clientes;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Listagem extends Component
{
  #[Title('SAFI NFE - Listagem de Clientes')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.clientes.listagem');
  }
}

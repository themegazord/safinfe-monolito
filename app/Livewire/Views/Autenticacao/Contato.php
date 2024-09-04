<?php

namespace App\Livewire\Views\Autenticacao;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Contato extends Component
{
  #[Title('SAFI NFE - Contato')]
  #[Layout('components.layouts.autenticacao')]
  public function render()
  {
    return view('livewire.views.autenticacao.contato');
  }
}

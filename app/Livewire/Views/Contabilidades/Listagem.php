<?php

namespace App\Livewire\Views\Contabilidades;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Listagem extends Component
{
  #[Title("SAFI NFE - Listagem de Contabilidades")]
  #[Layout("components.layouts.main")]
  public function render()
  {
    return view('livewire.views.contabilidades.listagem');
  }

  public function irCadastrar(): void {
    redirect("/contabilidades/cadastro");
  }
}

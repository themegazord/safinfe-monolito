<?php

namespace App\Livewire\Views\Importacao;

use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ListagemErros extends Component
{
  public array $erros = [];

  public function mount(): void {
    $this->erros = json_decode(base64_decode(cache()->get('erros')), true);
  }

  #[Title('SAFI NFE - Listagem de Erros da importação de XML')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.importacao.listagem-erros');
  }

  public function voltar(): void {
    redirect('/importacao');
  }
}

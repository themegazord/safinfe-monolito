<?php

namespace App\Livewire\Views\Contadores;

use App\Models\Contador;
use App\Repositories\Eloquent\Repository\ContadorRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public ?string $pesquisa = null;
  public bool $estaAtivo = true;

  #[Title('SAFI NFE - Listagem de Contadores')]
  #[Layout('components.layouts.main')]
  public function render(ContadorRepository $contadorRepository)
  {
    $contadores = $contadorRepository->paginacaoContadores(10, $this->pesquisa, $this->estaAtivo);
    return view('livewire.views.contadores.listagem', [
      'listagem' => compact('contadores')
    ]);
  }

  public function irEdicaoContador(int $contador_id): void {
    redirect("/contadores/edicao/$contador_id");
  }

  #[On('inativar-contador')]
  public function alteraStatusContador(int $contador_id): void {
    $contador = new Contador();
    if ($contador->onlyTrashed()->where('contador_id', $contador_id)->first()) {
      $contador->onlyTrashed()->where('contador_id', $contador_id)->first()->restore();
      Session::flash('sucesso', 'Contador ativado com sucesso');
    } else {
      $contador->where('contador_id', $contador_id)->first()->delete();
      Session::flash('sucesso', 'Contador inativado com sucesso');
    }
    redirect('contadores/');
  }

  public function irCadastrar(): void {
    redirect('/contadores/cadastro');
  }
}

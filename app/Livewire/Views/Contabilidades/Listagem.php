<?php

namespace App\Livewire\Views\Contabilidades;

use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Listagem extends Component
{

  public ?string $consulta = null;

  #[Title("SAFI NFE - Listagem de Contabilidades")]
  #[Layout("components.layouts.main")]
  public function render(ContabilidadeRepository $contabilidadeRepository)
  {
    $contabilidades = $contabilidadeRepository->paginacaoContabilidades(10, $this->consulta);

    return view('livewire.views.contabilidades.listagem', [
      'listagem' => compact('contabilidades')
    ]);
  }

  public function irCadastrar(): void {
    redirect("/contabilidades/cadastro");
  }

  public function irEdicaoContabilidade(int $contabilidade_id): void {
    redirect("/contabilidades/edicao/{$contabilidade_id}");
  }

  #[On('excluir-contabilidade')]
  public function excluirContabilidade(int $contabilidade_id, ContabilidadeRepository $contabilidadeRepository): Redirector|RedirectResponse {
    $contabilidade = $contabilidadeRepository->consultaContabilidade($contabilidade_id);
    if (is_null($contabilidade)) {
      Session::flash('erro', 'Contabilidade inexistente.');
      return redirect('/contabilidades');
    }
    $contabilidade->delete();
    $contabilidade->endereco()->first()->delete();
    Session::flash('sucesso', 'Contabilidade removida com sucesso.');
    return redirect('/contabilidades');
  }
}

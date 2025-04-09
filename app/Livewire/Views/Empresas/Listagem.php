<?php

namespace App\Livewire\Views\Empresas;

use App\Repositories\Eloquent\Repository\EmpresaRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination, WithoutUrlPagination;

  public LengthAwarePaginator $empresas;
  public string $consulta = '';
  public int $porPagina = 10;

  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Listagem de Empresas')]
  public function render()
  {
    return view('livewire.views.empresas.listagem');
  }

  public function irCadastrar(): void {
    redirect('/empresas/cadastro');
  }

  #[On('excluir-empresa')]
  public function excluirEmpresa(int $empresa_id, EmpresaRepository $empresaRepository): void {
    $empresa = $empresaRepository->consultaEmpresa($empresa_id);
    $empresa->endereco()->first()->delete();
    $empresa->delete();

    Session::flash('sucesso', 'Empresa removida com sucesso');
    redirect('/empresas');
  }

  public function irEdicaoEmpresa(int $empresa_id): Redirector|RedirectResponse {
    return redirect("/empresas/edicao/{$empresa_id}");
  }
}

<?php

namespace App\Livewire\Views\Versionamento;

use App\Models\User;
use App\Models\Versionamento;
use App\Repositories\Eloquent\Repository\VersionamentoRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public string $pesquisa = '';
  public ?Versionamento $versaoAtual = null;
  public User|Authenticatable $usuario;
  public int $perPage = 10;
  public bool $modalVisualizarVersao = false;

  public function mount(): void {
    $this->usuario = Auth::user();
  }

  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Versionamento')]
  public function render()
  {
    return view('livewire.views.versionamento.listagem');
  }

  public function irCadastrar(): void {
    redirect('/versionamento/cadastro');
  }

  public function selecionaVersaoAtual(int $versionamento_id, VersionamentoRepository $versionamentoRepository): void {
    $this->versaoAtual = $versionamentoRepository->consultaVersaoPorId($versionamento_id);
    $this->modalVisualizarVersao = !$this->modalVisualizarVersao;
    $this->dispatch('recebe-detalhe', ['detalhe' => $this->versaoAtual->detalhe]);
  }

  #[On("limpa-versao-selecionado")]
  public function limpaVersaoSelecionada() {
    $this->versaoAtual = null;
  }
}

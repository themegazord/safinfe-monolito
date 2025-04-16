<?php

namespace App\Livewire\Views\Versionamento;

use App\Livewire\Forms\VersaoForm;
use App\Repositories\Eloquent\Repository\VersionamentoRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cadastro extends Component
{
  public VersaoForm $versao;

  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Versionamento')]
  public function render()
  {
    return view('livewire.views.versionamento.cadastro');
  }

  public function cadastrar(VersionamentoRepository $versionamentoRepository) {
    $this->versao->validate();
    if (!is_null($versionamentoRepository->consultaVersaoPorPatch($this->versao->patch))) return $this->addError('versao.patch', 'A versão já existe');
    $versionamentoRepository->cadastro($this->versao->all());

    Session::flash('sucesso', 'Versão cadastrada com sucesso.');
    return redirect('versionamento');
  }

  public function voltar(): void {
    redirect('versionamento');
  }

  public function mostrarPreview(): void {
    $this->versao->detalhe = $this->versao->detalhe;
  }
}

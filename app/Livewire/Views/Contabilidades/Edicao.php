<?php

namespace App\Livewire\Views\Contabilidades;

use App\Livewire\Forms\ContabilidadeForm;
use App\Livewire\Forms\EnderecoForm;
use App\Models\Contabilidade;
use App\Models\Endereco;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\EmpContRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edicao extends Component
{
  public ContabilidadeForm $contabilidade;
  public EnderecoForm $endereco;
  public Endereco $enderecoAtual;
  public Contabilidade $contabilidadeAtual;
  public Collection $empresas;

  public function mount(
    int $contabilidade_id,
    ContabilidadeRepository $contabilidadeRepository,
    EnderecoRepository $enderecoRepository,
    EmpresaRepository $empresaRepository
  ): void {
    $this->contabilidadeAtual = $contabilidadeRepository->consultaContabilidade($contabilidade_id);
    $this->enderecoAtual = $enderecoRepository->consultaEndereco($this->contabilidadeAtual->getAttribute('endereco_id'));
    $this->empresas = $empresaRepository->listagemEmpresas()->sortBy('fantasia');
    foreach($this->contabilidadeAtual->empresas()->get() as $empresa) {
      $this->contabilidade->empresas[$empresa->empresa_id] = true;
    }
  }

  #[Title("SAFI NFE - Edição de Contabilidades")]
  #[Layout("components.layouts.main")]
  public function render()
  {
    return view('livewire.views.contabilidades.edicao');
  }

  public function editar(
    ContabilidadeRepository $contabilidadeRepository,
    EnderecoRepository $enderecoRepository,
    EmpContRepository $empContRepository
  ) {
    $this->contabilidade->filtraEmpresas();
    $this->contabilidade->tratarCamposSujos();
    $this->contabilidade->validate();

    if (!is_null(DB::table('contabilidades')->where('cnpj', $this->contabilidade->cnpj)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) return $this->addError('contabilidade.cnpj', 'CNPJ já existente.');
    if (!is_null(DB::table('contabilidades')->where('email_corporativo', $this->contabilidade->email_corporativo)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) return $this->addError('contabilidade.email_corporativo', 'Email corporativo já existente.');
    if (!is_null(DB::table('contabilidades')->where('email_contato', $this->contabilidade->email_contato)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) return $this->addError('contabilidade.email_contato', 'Email de contato já existente.');

    $this->endereco->tratarCamposSujos();
    $this->contabilidade->validate();

    $empresas = $this->contabilidade->empresas;

    $dadosContabilidade = $this->contabilidade->all();

    unset($dadosContabilidade['empresas']);

    $empContRepository->removeRelacionamentoContabilidade($this->contabilidadeAtual->getAttribute('contabilidade_id'));

    foreach($empresas as $key => $empresa) {
      $empContRepository->cadastrar([
        'empresa_id' => $key,
        'contabilidade_id' => $this->contabilidadeAtual->getAttribute('contabilidade_id')
      ]);
    }

    $enderecoParaAtualizacao = array_diff($this->endereco->all(), $this->enderecoAtual->toArray());
    $contabilidadeParaAtualizacao = array_diff($dadosContabilidade, $this->contabilidadeAtual->toArray());

    $enderecoParaAtualizacao['endereco_id'] = $this->enderecoAtual->getAttribute('endereco_id');

    $contabilidadeParaAtualizacao['endereco_id'] = $enderecoParaAtualizacao['endereco_id'];
    $contabilidadeParaAtualizacao['contabilidade_id'] = $this->contabilidadeAtual->getAttribute('contabilidade_id');

    $enderecoRepository->editaEndereco($enderecoParaAtualizacao);
    $contabilidadeRepository->editaContabilidade($contabilidadeParaAtualizacao);

    Session::flash('sucesso', 'Contabilidade editada com sucesso');
    redirect('/contabilidades');
  }

  public function voltar(): void {
    redirect('/contabilidades');
  }
}

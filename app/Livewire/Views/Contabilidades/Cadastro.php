<?php

namespace App\Livewire\Views\Contabilidades;

use App\Livewire\Forms\ContabilidadeForm;
use App\Livewire\Forms\EnderecoForm;
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

class Cadastro extends Component
{
  public ContabilidadeForm $contabilidade;
  public EnderecoForm $endereco;
  public Collection $empresas;

  public function mount(EmpresaRepository $empresaRepository): void {
    $this->empresas = $empresaRepository->listagemEmpresas()->sortBy('fantasia');
  }

  #[Title("SAFI NFE - Cadastro de Contabilidades")]
  #[Layout("components.layouts.main")]
  public function render()
  {
    return view('livewire.views.contabilidades.cadastro');
  }

  public function cadastrar(EnderecoRepository $enderecoRepository, ContabilidadeRepository $contabilidadeRepository, EmpContRepository $empContRepository) {
    $this->contabilidade->filtraEmpresas();
    $this->contabilidade->tratarCamposSujos();
    $this->contabilidade->validate();

    if (!is_null(DB::table('contabilidades')->where('cnpj', $this->contabilidade->documento)->first())) return $this->addError('contabilidade.documento', 'Documento já existente.');
    if (!is_null(DB::table('contabilidades')->where('email_corporativo', $this->contabilidade->email_corporativo)->first())) return $this->addError('contabilidade.email_corporativo', 'Email corporativo já existente.');
    if (!is_null(DB::table('contabilidades')->where('email_contato', $this->contabilidade->email_contato)->first())) return $this->addError('contabilidade.email_contato', 'Email de contato já existente.');

    $this->endereco->tratarCamposSujos();
    $this->endereco->validate();

    $endereco = $enderecoRepository->cadastraEndereco($this->endereco->all());

    $this->contabilidade->endereco_id = $endereco->getAttribute('endereco_id');

    $empresas = $this->contabilidade->empresas;

    unset($this->contabilidade->empresas);

    $contabilidade = $contabilidadeRepository->cadastroContabilidade([
      'cnpj' => $this->contabilidade->documento,
      ...$this->contabilidade->all()
    ]);

    foreach($empresas as $key => $empresa) {
      $empContRepository->cadastrar([
        'empresa_id' => $key,
        'contabilidade_id' => $contabilidade->getAttribute('contabilidade_id')
      ]);
    }

    Session::flash('sucesso', 'Contabilidade cadastrada com sucesso.');
    redirect('/contabilidades');
  }

  public function voltar(): void {
    redirect('/contabilidades');
  }
}

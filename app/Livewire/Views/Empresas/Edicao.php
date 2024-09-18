<?php

namespace App\Livewire\Views\Empresas;

use App\Livewire\Forms\EmpresaForm;
use App\Livewire\Forms\EnderecoForm;
use App\Models\Empresa;
use App\Models\Endereco;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edicao extends Component
{
  public ?Empresa $empresaAtual;
  public ?Endereco $enderecoAtual;
  public EmpresaForm $empresa;
  public EnderecoForm $endereco;

  public function mount(int $empresa_id, EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository): void {
    $this->empresaAtual = $empresaRepository->consultaEmpresa($empresa_id);
    $this->enderecoAtual = $enderecoRepository->consultaEndereco($this->empresaAtual->endereco_id);
  }

  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Edicao de Empresas')]
  public function render()
  {
    return view('livewire.views.empresas.edicao');
  }

  public function editar(EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository) {
    $this->empresa->validate();
    $this->empresa->tratarCamposSujos();
    $this->endereco->validate();
    $this->endereco->tratarCamposSujos();

    if (!is_null(DB::table('empresas')->where('cnpj', $this->empresa->cnpj)->whereNot('empresa_id', $this->empresaAtual->getAttribute('empresa_id'))->first())) return $this->addError('empresa.cnpj', 'CNPJ já existente.');
    if (!is_null(DB::table('empresas')->where('ie', $this->empresa->ie)->whereNot('empresa_id', $this->empresaAtual->getAttribute('empresa_id'))->first())) return $this->addError('empresa.ie', 'IE já existente.');


    $enderecoAtualizado = array_diff($this->endereco->all(), $this->enderecoAtual->toArray());
    $empresaAtualizado = array_diff($this->empresa->all(), $this->empresaAtual->toArray());

    $enderecoAtualizado['endereco_id'] = $this->enderecoAtual->getAttribute('endereco_id');
    $empresaAtualizado['endereco_id'] = $this->enderecoAtual->getAttribute('endereco_id');
    $empresaAtualizado['empresa_id'] = $this->empresaAtual->getAttribute('empresa_id');

    $empresaRepository->editaEmpresa($empresaAtualizado);
    $enderecoRepository->editaEndereco($enderecoAtualizado);

    Session::flash('sucesso', 'Empresa editada com sucesso');
    redirect('/empresas');
  }

  public function voltar(): void {
    redirect('/empresas');
  }
}

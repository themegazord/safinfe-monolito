<?php

namespace App\Livewire\Views\Empresas;

use App\Livewire\Forms\EmpresaForm;
use App\Livewire\Forms\EnderecoForm;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cadastro extends Component
{
  public EnderecoForm $endereco;
  public EmpresaForm $empresa;

  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Cadastro de Empresas')]
  public function render()
  {
    return view('livewire.views.empresas.cadastro');
  }

  public function voltar(): void {
    redirect('/empresas');
  }

  public function cadastrar(EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository): void {
    $this->endereco->tratarCamposSujos();
    $this->endereco->validate();

    $endereco_cadastrado = $enderecoRepository->cadastraEndereco($this->endereco->all());

    $this->empresa->tratarCamposSujos();
    $this->empresa->validate();

    $this->empresa->endereco_id = $endereco_cadastrado->getAttribute('endereco_id');

    $empresaRepository->cadastroEmpresa($this->empresa->all());

    Session::flash('sucesso', 'Empresa cadastrada com sucesso.');
    redirect('/empresas');
  }
}

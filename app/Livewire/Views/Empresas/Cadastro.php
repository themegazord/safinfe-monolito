<?php

namespace App\Livewire\Views\Empresas;

use App\Livewire\Forms\EmpresaForm;
use App\Livewire\Forms\EnderecoForm;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Support\Facades\DB;
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

  public function cadastrar(EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository) {
    $this->endereco->tratarCamposSujos();

    if (!is_null(DB::table('empresas')->where('cnpj', $this->empresa->cnpj)->first())) return $this->addError('empresa.cnpj', 'CNPJ já existente.');
    if (!is_null(DB::table('empresas')->where('ie', $this->empresa->ie)->first())) return $this->addError('empresa.ie', 'IE já existente.');
    if (!is_null(DB::table('empresas')->where('email_contato', $this->empresa->email_contato)->first())) return $this->addError('empresa.email_contato', 'Email de contato já existente.');

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

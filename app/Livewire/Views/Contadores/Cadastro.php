<?php

namespace App\Livewire\Views\Contadores;

use App\Livewire\Forms\ContadorForm;
use App\Livewire\Forms\UsuarioForm;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\ContadorRepository;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cadastro extends Component
{
  public ContadorForm $contador;
  public UsuarioForm $usuario;
  public Collection $contabilidades;

  public function mount(ContabilidadeRepository $contabilidadeRepository): void {
    $this->contabilidades = collect($contabilidadeRepository->listagemContabilidades());
  }

  #[Title('SAFI NFE - Cadastro de contadors')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.contadores.cadastro');
  }

  public function cadastrar(UsuarioRepository $usuarioRepository, ContadorRepository $contadorRepository, ContabilidadeRepository $contabilidadeRepository) {
    $this->contador->limpaCampos();
    $this->contador->validate();

    if (is_null($contabilidadeRepository->consultaContabilidade($this->contador->contabilidade_id))) return $this->addError('contador.contabilidade_id', 'A contabilidade deve ser informada.');
    if (!is_null($contadorRepository->consultaContadorPorEmail($this->contador->email))) return $this->addError('contador.email', 'O email já existe no sistema');
    if (!is_null($contadorRepository->consultaContadorPorCPF($this->contador->cpf))) return $this->addError('contador.cpf', 'O CPF já existe no sistema');

    $this->usuario->validate();
    $this->usuario->encriptaSenha();

    $this->usuario->name = $this->contador->nome;
    $this->usuario->email = $this->contador->email;
    $this->usuario->role = 'CONTADOR';

    $usuario = $usuarioRepository->cadastraUsuario($this->usuario->all());

    $this->contador->usuario_id = $usuario->getAttribute('id');

    $contadorRepository->cadastrocontador($this->contador->all());

    Session::flash('sucesso', 'Contador cadastrado com sucesso.');
    redirect('contadores/');
  }
}

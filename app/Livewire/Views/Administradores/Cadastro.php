<?php

namespace App\Livewire\Views\Administradores;

use App\Livewire\Forms\UsuarioForm;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cadastro extends Component
{
  public UsuarioForm $administrador;

  #[Title('SAFI NFE - Cadastro de Administradores')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.administradores.cadastro');
  }

  public function cadastrar(UsuarioRepository $usuarioRepository)
  {
    $this->administrador->validate();
    $this->administrador->encriptaSenha();
    $this->administrador->role = 'ADMIN';

    if (!is_null($usuarioRepository->consultaUsuarioPorEmail($this->administrador->email))) return $this->addError('administrador.email', 'O email já está sendo usado.');

    $usuarioRepository->cadastraUsuario($this->administrador->all());

    Session::flash('sucesso', 'Administrador cadastrado com sucesso.');
    redirect('administradores/');
  }

  public function voltar(): void
  {
    redirect('/administradores');
  }
}

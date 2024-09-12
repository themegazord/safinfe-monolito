<?php

namespace App\Livewire\Views\Administradores;

use App\Livewire\Forms\UsuarioForm;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edicao extends Component
{
  public array $administradorAtual = [];
  public UsuarioForm $administrador;

  public function mount(
    int $administrador_id,
    UsuarioRepository $usuarioRepository
  ): void {
    $this->administradorAtual = $usuarioRepository->consultaUsuario($administrador_id)->toArray();
  }

  #[Title('SAFI NFE - Edição de Administradores')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.administradores.edicao');
  }

  public function editar(UsuarioRepository $usuarioRepository) {
    $this->administrador->validateOnly('name');
    $this->administrador->validateOnly('email');
    $this->administrador->role = 'ADMIN';

    $administradorAtualizado = array_diff($this->administrador->all(), $this->administradorAtual);
    unset($administradorAtualizado['password']);
    // Valida se existe email cadastrado em outro usuario.
    $administradorValidadoEmail = $usuarioRepository->consultaUsuarioPorEmail($this->administrador->email);
    if (!is_null($administradorValidadoEmail) && $this->administradorAtual['id'] !== $administradorValidadoEmail->getAttribute('id')) return $this->addError('administrador.email', 'O email já está sendo usado por outro usuario, escolha outro.');

    $administradorAtualizado['id'] = $this->administradorAtual['id'];

    $usuarioRepository->editaUsuario($administradorAtualizado);

    Session::flash('sucesso', 'Administrador editado com sucesso.');
    redirect('administradores/');
  }

  public function voltar(): void {
    redirect('administradores/');
  }
}

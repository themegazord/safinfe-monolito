<?php

namespace App\Livewire\Views\Administradores;

use App\Livewire\Forms\UsuarioForm;
use App\Models\User;
use App\Traits\EnviaEmailResetSenhaTrait;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Edicao extends Component
{
  use Toast, EnviaEmailResetSenhaTrait;
  public ?User $administradorAtual;
  public UsuarioForm $administrador;

  public function mount(
    int $administrador_id
  ): void {
    $this->administradorAtual = User::find($administrador_id);
  }

  #[Title('SAFI NFE - Edição de Administradores')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.administradores.edicao');
  }

  public function editar() {
    $this->administrador->validateOnly('name');
    $this->administrador->validateOnly('email');
    $this->administrador->role = 'ADMIN';

    $administradorAtualizado = array_diff($this->administrador->all(), $this->administradorAtual->toArray());
    unset($administradorAtualizado['password']);
    // Valida se existe email cadastrado em outro usuario.
    $administradorValidadoEmail = User::whereEmail($this->administrador->email)->first();
    if (!is_null($administradorValidadoEmail) && $this->administradorAtual['id'] !== $administradorValidadoEmail->getAttribute('id')) return $this->addError('administrador.email', 'O email já está sendo usado por outro usuario, escolha outro.');

    $administradorAtualizado['id'] = $this->administradorAtual['id'];

    User::where('id', $administradorAtualizado['id'])->update($administradorAtualizado);

    $this->success('Administrador editado com sucesso.', route('administradores'));
  }

  public function voltar(): void {
    redirect('administradores/');
  }

  public function enviarEmailResetSenha(): void {
    $this->enviaEmail($this->administradorAtual->email);

    $this->success('Email enviado com sucesso');
  }
}

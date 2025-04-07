<?php

namespace App\Livewire\Views\Autenticacao;

use Livewire\Component;
use App\Livewire\Forms\ResetarSenhaForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;

class ResetarSenha extends Component
{
  use Toast;
  public ResetarSenhaForm $resetSenha;

  #[Layout('components.layouts.autenticacao')]
  #[Title('SAFI NFE - Resetar senha')]
  public function render()
  {
    return view('livewire.views.autenticacao.resetar-senha');
  }

  public function alterarSenha(): void {
    $this->resetSenha->validate();

    $usuario = User::whereEmail($this->resetSenha->email)->first();

    if (is_null($usuario) || !Hash::check($this->resetSenha->oldPassword, $usuario->password)) {
      $this->success("Dados atualizados.", redirectTo: route('login'));
      return;
    }

    $usuario->forceFill([
      'password' => Hash::make($this->resetSenha->newPassword)
    ])->save();

    $this->success("Dados atualizados.", redirectTo: route('login'));
    return;
  }
}

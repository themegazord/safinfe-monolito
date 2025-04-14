<?php

namespace App\Livewire\Views\Autenticacao;

use App\Notifications\SolicitacaoResetSenhaNotification;
use Livewire\Component;
use App\Livewire\Forms\ResetarSenhaForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

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

    if ($usuario !== null) {
      $token = Str::uuid();

      DB::table('password_reset_tokens')->insert([
        'token' => $token,
        'email' => $this->resetSenha->email
      ]);

      $usuario->notify(new SolicitacaoResetSenhaNotification($token, $this->resetSenha->email));
    }

    $this->success(title: 'Email encaminhado com sucesso', redirectTo: route('login'));
  }
}

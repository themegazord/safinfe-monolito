<?php

namespace App\Livewire\Views\Autenticacao;

use App\Notifications\SolicitacaoResetSenhaNotification;
use Livewire\Component;
use App\Livewire\Forms\ResetarSenhaForm;
use App\Models\User;
use App\Traits\EnviaEmailResetSenhaTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class ResetarSenha extends Component
{
  use Toast, EnviaEmailResetSenhaTrait;
  public ResetarSenhaForm $resetSenha;

  #[Layout('components.layouts.autenticacao')]
  #[Title('SAFI NFE - Resetar senha')]
  public function render()
  {
    return view('livewire.views.autenticacao.resetar-senha');
  }

  public function alterarSenha(): void {
    $this->resetSenha->validate();

    $this->enviaEmail($this->resetSenha->email);

    $this->success(title: 'Email encaminhado com sucesso', redirectTo: route('login'));
  }
}

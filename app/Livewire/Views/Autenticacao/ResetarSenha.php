<?php

namespace App\Livewire\Views\Autenticacao;

use App\Livewire\Forms\ResetarSenhaForm;
use App\Trait\EnviaEmailResetSenhaTrait;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class ResetarSenha extends Component
{
    use EnviaEmailResetSenhaTrait, Toast;

    public ResetarSenhaForm $resetSenha;

    #[Layout('components.layouts.autenticacao')]
    #[Title('SAFI NFE - Resetar senha')]
    public function render()
    {
        return view('livewire.views.autenticacao.resetar-senha');
    }

    public function alterarSenha(): void
    {
        $this->resetSenha->validate();

        $this->enviaEmail($this->resetSenha->email);

        $this->success(title: 'Email encaminhado com sucesso', redirectTo: route('login'));
    }
}

<?php

namespace App\Livewire\Views\Autenticacao;

use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
  public LoginForm $login;
  public bool $lembraSenha = false;

  #[Layout('components.layouts.autenticacao')]
  public function render()
  {
    return view('livewire.views.autenticacao.login');
  }

  public function logar() {
    $this->validate();
  }
}

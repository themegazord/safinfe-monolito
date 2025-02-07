<?php

namespace App\Livewire\Views\Autenticacao;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
  public LoginForm $login;
  public bool $lembraSenha = false;

  #[Layout('components.layouts.autenticacao')]
  #[Title('SAFI NFE - Login')]
  public function render()
  {
    return view('livewire.views.autenticacao.login');
  }

  public function logar(): void {
    $this->login->validate();
    if (Auth::attempt($this->login->all())) {
      Auth::login(Auth::user());
      redirect('/dashboard');
    } else {
      $this->addError('login.email', 'Email ou senha inválidos');
    }
  }
}

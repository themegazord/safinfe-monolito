<?php

namespace App\Livewire\Views\Autenticacao;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
  public function mount(): void {
    Auth::logout();
    $this->redirect(route('login'));
  }
  public function render()
  {
    return <<<'HTML'
        <div>
            {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
        </div>
        HTML;
  }
}

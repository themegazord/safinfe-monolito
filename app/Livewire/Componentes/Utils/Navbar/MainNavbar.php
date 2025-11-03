<?php

namespace App\Livewire\Componentes\Utils\Navbar;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MainNavbar extends Component
{
    public string $iniciaisUsuarioLogo = '';

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        $this->defineIniciaisDoUsuario();
    }

    public function render()
    {
        return view('livewire.componentes.utils.navbar.main-navbar');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }

    protected function defineIniciaisDoUsuario(): void
    {
        $nomes = explode(' ', $this->usuario->name);

        if (count($nomes) >= 2) {
            $this->iniciaisUsuarioLogo = strtoupper(substr($nomes[0], 0, 1).substr($nomes[1], 0, 1));
        }

        if (count($nomes) == 1 && strlen($nomes[0]) > 1) {
            $this->iniciaisUsuarioLogo = strtoupper(substr($nomes[0], 0, 2));
        }
    }
}

<?php

namespace App\Livewire\Views\Administradores;

use App\Livewire\Forms\UsuarioForm;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
    use Toast;

    public UsuarioForm $administrador;

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('create', \App\Models\User::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Cadastro de Administradores')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.administradores.cadastro');
    }

    public function cadastrar(): void
    {
        $this->administrador->validate();
        $this->administrador->encriptaSenha();
        $this->administrador->role = 'ADMIN';

        if (! is_null(User::whereEmail($this->administrador->email)->first())) {
            $this->addError('administrador.email', 'O email já está sendo usado.');

            return;
        }

        User::create($this->administrador->all());

        $this->success('Administrador cadastrado com sucesso', redirectTo: route('administradores'));
    }

    public function voltar(): void
    {
        redirect('/administradores');
    }
}

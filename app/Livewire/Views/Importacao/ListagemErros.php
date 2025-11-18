<?php

namespace App\Livewire\Views\Importacao;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ListagemErros extends Component
{
    public array $erros = [];

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\User::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
        $this->erros = json_decode(base64_decode(cache()->get('erros')), true);
    }

    #[Title('SAFI NFE - Listagem de Erros da importação de XML')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.importacao.listagem-erros');
    }

    public function voltar(): void
    {
        redirect('/importacao');
    }
}

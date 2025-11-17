<?php

namespace App\Livewire\Views\Administradores;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Listagem extends Component
{
    use Toast, WithoutUrlPagination, WithPagination;

    public ?string $pesquisa = null;

    public ?int $porPagina = 10;

    public ?User $administradorAtual;

    public bool $modalConfirmandoRemocaoAdministrador = false;

    public User|Authenticatable $usuario;

    public function mount(): void {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\User::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Listagem de Usuarios')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.administradores.listagem');
    }

    public function irCadastrar(): void
    {
        redirect('/administradores/cadastro');
    }

    public function irEdicaoAdministrador(int $administrador_id): void
    {
        redirect("/administradores/edicao/$administrador_id");
    }

    public function removerAdministrador(): void
    {
        if (is_null($this->administradorAtual)) {
            $this->warning('O administrador não existe.');
        }

        $this->administradorAtual->delete();

        $this->modalConfirmandoRemocaoAdministrador = ! $this->modalConfirmandoRemocaoAdministrador;
        $this->success('O administrador foi removido com sucesso');
    }

    public function setRemoverAdministrador(int $administrador_id): void
    {
        $this->administradorAtual = User::find($administrador_id);
        $this->modalConfirmandoRemocaoAdministrador = ! $this->modalConfirmandoRemocaoAdministrador;
    }
}

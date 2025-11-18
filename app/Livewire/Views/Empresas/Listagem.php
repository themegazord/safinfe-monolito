<?php

namespace App\Livewire\Views\Empresas;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Listagem extends Component
{
    use Toast, WithoutUrlPagination, WithPagination;

    public LengthAwarePaginator $empresas;

    public ?Empresa $empresaAtual = null;

    public string $consulta = '';

    public int $porPagina = 10;

    public bool $modalConfirmandoRemocaoEmpresa = false;

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\Empresa::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Layout('components.layouts.main')]
    #[Title('SAFI NFE - Listagem de Empresas')]
    public function render()
    {
        return view('livewire.views.empresas.listagem');
    }

    public function irCadastrar(): void
    {
        if ($this->usuario->cannot('create', \App\Models\Empresa::class)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect('/empresas/cadastro');
    }

    public function excluirEmpresa(): void
    {
        if ($this->usuario->cannot('delete', $this->empresaAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        $this->empresaAtual->endereco()->first()->delete();
        $this->empresaAtual->delete();

        $this->success('Empresa removida com sucesso.');
        $this->modalConfirmandoRemocaoEmpresa = false;
    }

    public function setRemocaoEmpresa(?int $empresa_id): void
    {
        $this->empresaAtual = Empresa::find($empresa_id);
        if ($this->usuario->cannot('delete', $this->empresaAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        $this->modalConfirmandoRemocaoEmpresa = true;
    }

    public function irEdicaoEmpresa(int $empresa_id): void
    {
        $this->empresaAtual = Empresa::find($empresa_id);
        if ($this->usuario->cannot('update', $this->empresaAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect("/empresas/edicao/{$empresa_id}");
    }
}

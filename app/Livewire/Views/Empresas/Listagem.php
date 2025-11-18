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
        redirect('/empresas/cadastro');
    }

    public function excluirEmpresa(): void
    {
        $this->empresaAtual->endereco()->first()->delete();
        $this->empresaAtual->delete();

        $this->success('Empresa removida com sucesso.');
        $this->modalConfirmandoRemocaoEmpresa = false;
    }

    public function setRemocaoEmpresa(?int $empresa_id): void
    {
        $this->empresaAtual = Empresa::find($empresa_id);
        $this->modalConfirmandoRemocaoEmpresa = true;
    }

    public function irEdicaoEmpresa(int $empresa_id): Redirector|RedirectResponse
    {
        return redirect("/empresas/edicao/{$empresa_id}");
    }
}

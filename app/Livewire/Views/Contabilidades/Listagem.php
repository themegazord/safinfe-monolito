<?php

namespace App\Livewire\Views\Contabilidades;

use App\Models\Contabilidade;
use App\Models\User;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
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

    public ?string $consulta = null;

    public int $porPagina = 10;

    public ?Contabilidade $contabilidadeAtual;

    public bool $modalConfirmandoRemocaoContabilidade = false;

    public User|Authenticatable $usuario;

    public function mount(): void {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\Contabilidade::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Listagem de Contabilidades')]
    #[Layout('components.layouts.main')]
    public function render(ContabilidadeRepository $contabilidadeRepository)
    {
        return view('livewire.views.contabilidades.listagem');
    }

    public function irCadastrar(): void
    {
        redirect('/contabilidades/cadastro');
    }

    public function irEdicaoContabilidade(int $contabilidade_id): void
    {
        redirect("/contabilidades/edicao/{$contabilidade_id}");
    }

    public function setRemocaoContabilidade(int $contabilidade_id): void
    {
        $this->contabilidadeAtual = Contabilidade::find($contabilidade_id);
        $this->modalConfirmandoRemocaoContabilidade = true;
    }

    public function excluirContabilidade(): void
    {
        if (is_null($this->contabilidadeAtual)) {
            $this->error('Contabilidade inexistente.');
        }
        $this->contabilidadeAtual->delete();
        $this->contabilidadeAtual->endereco()->first()->delete();
        $this->modalConfirmandoRemocaoContabilidade = false;
        $this->success('Contabilidade removida com sucesso');
    }
}

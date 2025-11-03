<?php

namespace App\Livewire\Views\Versionamento;

use App\Models\User;
use App\Models\Versionamento;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Listagem extends Component
{
    use Toast, WithPagination;

    public string $pesquisa = '';

    public ?Versionamento $versaoAtual = null;

    public User|Authenticatable $usuario;

    public int $perPage = 10;

    public bool $modalVisualizarVersao = false;

    public function mount(): void
    {
        $this->usuario = Auth::user();
    }

    #[Layout('components.layouts.main')]
    #[Title('SAFI NFE - Versionamento')]
    public function render()
    {
        return view('livewire.views.versionamento.listagem');
    }

    public function irCadastrar(): void
    {
        redirect('/versionamento/cadastro');
    }

    public function selecionaVersaoAtual(int $versionamento_id): void
    {
        $this->versaoAtual = Versionamento::find($versionamento_id);
        $this->modalVisualizarVersao = ! $this->modalVisualizarVersao;
    }
}

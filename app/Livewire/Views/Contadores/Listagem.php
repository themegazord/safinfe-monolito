<?php

namespace App\Livewire\Views\Contadores;

use App\Models\Contador;
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

    public bool $estaAtivo = true;

    public int $porPagina = 10;

    public ?Contador $contadorAtual;

    public bool $modalConfirmandoInativacaoContador = false;

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\Contador::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Listagem de Contadores')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.contadores.listagem');
    }

    public function irEdicaoContador(int $contador_id): void
    {
        $this->contadorAtual = Contador::withTrashed()->find($contador_id);
        if ($this->usuario->cannot('update', $this->contadorAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect("/contadores/edicao/$contador_id");
    }

    public function irCadastrar(): void
    {
        if ($this->usuario->cannot('create', \App\Models\Contador::class)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect('/contadores/cadastro');
    }

    public function setInativacaoContador(int $contador_id): void
    {
        $this->contadorAtual = Contador::withTrashed()->find($contador_id);

        $acao = $this->contadorAtual->trashed() ? 'restore' : 'delete';

        if ($this->usuario->cannot($acao, $this->contadorAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }

        $this->modalConfirmandoInativacaoContador = ! $this->modalConfirmandoInativacaoContador;
    }

    public function inativarContador(): void
    {
        $estaInativo = $this->contadorAtual->trashed();

        $acao = $this->contadorAtual->trashed() ? 'restore' : 'delete';

        if ($this->usuario->cannot($acao, $this->contadorAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }

        $estaInativo ? $this->contadorAtual->restore() : $this->contadorAtual->delete();
        $mensagem = $estaInativo ? 'Contador ativado com sucesso' : 'Contador inativado com sucesso';
        $this->success($mensagem);

        $this->modalConfirmandoInativacaoContador = ! $this->modalConfirmandoInativacaoContador;
        $this->estaAtivo = true;
    }
}

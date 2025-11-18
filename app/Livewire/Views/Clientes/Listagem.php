<?php

namespace App\Livewire\Views\Clientes;

use App\Models\Cliente;
use App\Models\User;
use App\Repositories\Eloquent\Repository\ClienteRepository;
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

    public int $porPagina = 10;

    public bool $estaAtivo = true;

    public bool $modalConfirmandoInativacaoCliente = false;

    public ?Cliente $clienteAtual;

    public User|Authenticatable $usuario;

    public function mount(): void
    {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\Cliente::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Listagem de Clientes')]
    #[Layout('components.layouts.main')]
    public function render(ClienteRepository $clienteRepository)
    {
        return view('livewire.views.clientes.listagem');
    }

    public function irCadastrar(): void
    {
        if ($this->usuario->cannot('create', \App\Models\Cliente::class)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect('/clientes/cadastro');
    }

    public function irEdicaoCliente(int $cliente_id): void
    {
        $this->clienteAtual = Cliente::withTrashed()->find($cliente_id);
        if ($this->usuario->cannot('update', $this->clienteAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        redirect("/clientes/edicao/$cliente_id");
    }

    public function inativarCliente(): void
    {
        $estaInativo = $this->clienteAtual->trashed();
        $acao = $estaInativo ? 'restore' : 'delete';

        if ($this->usuario->cannot($acao, $this->clienteAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }

        $estaInativo ? $this->clienteAtual->restore() : $this->clienteAtual->delete();

        $mensagem = $estaInativo ? 'Cliente ativado com sucesso' : 'Cliente inativado com sucesso';
        $this->success($mensagem);

        $this->modalConfirmandoInativacaoCliente = false;
        $this->estaAtivo = true;
    }

    public function setInativacaoCliente(int $cliente_id): void
    {
        $this->clienteAtual = Cliente::withTrashed()->find($cliente_id);

        $acao = $this->clienteAtual->trashed() ? 'restore' : 'delete';

        if ($this->usuario->cannot($acao, $this->clienteAtual)) {
            $this->error('Você não tem permissão para fazer isso.');
            return;
        }
        $this->modalConfirmandoInativacaoCliente = ! $this->modalConfirmandoInativacaoCliente;
    }
}

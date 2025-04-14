<?php

namespace App\Livewire\Views\Clientes;

use App\Models\Cliente;
use App\Repositories\Eloquent\Repository\ClienteRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Listagem extends Component
{
  use WithPagination, WithoutUrlPagination, Toast;

  public ?string $pesquisa = null;
  public int $porPagina = 10;
  public bool $estaAtivo = true;
  public bool $modalConfirmandoInativacaoCliente = false;
  public ?Cliente $clienteAtual;

  #[Title('SAFI NFE - Listagem de Clientes')]
  #[Layout('components.layouts.main')]
  public function render(ClienteRepository $clienteRepository)
  {
    $clientes = $clienteRepository->paginacaoClientes(10, $this->pesquisa, $this->estaAtivo);
    return view('livewire.views.clientes.listagem', [
      'listagem' => compact('clientes')
    ]);
  }

  public function irCadastrar(): void {
    redirect('/clientes/cadastro');
  }

  public function irEdicaoCliente(int $cliente_id): void {
    redirect("/clientes/edicao/$cliente_id");
  }

  public function inativarCliente(): void {
    if ($this->clienteAtual->trashed()) {
      $this->clienteAtual->restore();
      $this->success('Cliente ativado com sucesso');
    } else {
      $this->clienteAtual->delete();
      $this->success('Cliente inativado com sucesso');
    }
    $this->modalConfirmandoInativacaoCliente = !$this->modalConfirmandoInativacaoCliente;
    $this->estaAtivo = true;
  }

  public function setInativacaoCliente(int $cliente_id): void {
    $this->clienteAtual = Cliente::withTrashed()->find($cliente_id);
    $this->modalConfirmandoInativacaoCliente = !$this->modalConfirmandoInativacaoCliente;
  }
}

<?php

namespace App\Livewire\Views\Clientes;

use App\Models\Cliente;
use App\Repositories\Eloquent\Repository\ClienteRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public ?string $pesquisa = null;
  public int $porPagina = 10;
  public bool $estaAtivo = true;

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

  #[On('inativar-cliente')]
  public function inativarCliente(int $cliente_id, ClienteRepository $clienteRepository): void {
    $cliente = new Cliente;
    if ($cliente->onlyTrashed()->where('cliente_id', $cliente_id)->first()) {
      $cliente->onlyTrashed()->where('cliente_id', $cliente_id)->first()->restore();
      Session::flash('sucesso', 'Cliente ativado com sucesso');
    } else {
      $cliente->where('cliente_id', $cliente_id)->first()->delete();
      Session::flash('sucesso', 'Cliente inativado com sucesso');
    }
    redirect('clientes/');
  }
}

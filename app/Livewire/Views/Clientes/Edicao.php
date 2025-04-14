<?php

namespace App\Livewire\Views\Clientes;

use App\Livewire\Forms\ClienteForm;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Edicao extends Component
{
  use Toast;

  public ?Cliente $clienteAtual;
  public Collection $empresas;
  public ClienteForm $cliente;

  public function mount(
    int $cliente_id,
  ): void {
    $this->empresas = Empresa::all();
    $this->clienteAtual = Cliente::find($cliente_id);
    $this->cliente->empresa_id = $this->clienteAtual->empresa->empresa_id;
  }

  #[Title('SAFI NFE - Edição de Clientes')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.clientes.edicao');
  }

  public function editar(): void {
    $this->cliente->validate();

    $this->cliente->usuario_id = $this->clienteAtual['usuario_id'];
    $clienteAtualizado = array_diff($this->cliente->all(), $this->clienteAtual->toArray());

    // Valida se existe email cadastrado em outro usuario.
    $clienteValidadoEmail = Cliente::whereEmail($this->cliente->email)->first();
    if (!is_null($clienteValidadoEmail) && $this->clienteAtual['cliente_id'] !== $clienteValidadoEmail->getAttribute('cliente_id')) {
      $this->addError('cliente.email', 'O email já está sendo usado por outro usuario, escolha outro.');
      return;
    };

    // Pega somente as informações alteradas na edição do cliente para ser alterado no cadastro de usuários.
    $usuarioAtualizado = [];
    if (isset($clienteAtualizado['nome'])) $usuarioAtualizado['name'] = $clienteAtualizado['nome'];
    if (isset($clienteAtualizado['email'])) $usuarioAtualizado['email'] = $clienteAtualizado['email'];

    $usuarioAtualizado['id'] = $this->clienteAtual['usuario_id'];
    $clienteAtualizado['cliente_id'] = $this->clienteAtual['cliente_id'];

    User::where('id', $usuarioAtualizado['id'])->update($usuarioAtualizado);
    Cliente::where('cliente_id', $clienteAtualizado['cliente_id'])->update($clienteAtualizado);

    $this->success('Cliente editado com sucesso.', redirectTo: route('clientes'));
  }

  public function voltar(): void {
    redirect('clientes/');
  }
}

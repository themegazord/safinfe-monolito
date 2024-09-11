<?php

namespace App\Livewire\Views\Clientes;

use App\Livewire\Forms\ClienteForm;
use App\Repositories\Eloquent\Repository\ClienteRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edicao extends Component
{
  public array $clienteAtual = [];
  public Collection $empresas;
  public ClienteForm $cliente;

  public function mount(
    int $cliente_id,
    ClienteRepository $clienteRepository,
    EmpresaRepository $empresaRepository
  ): void {
    $this->empresas = $empresaRepository->listagemEmpresas();
    $this->clienteAtual = $clienteRepository->consultaCliente($cliente_id)->toArray();
  }

  #[Title('SAFI NFE - Edição de Clientes')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.clientes.edicao');
  }

  public function editar(ClienteRepository $clienteRepository, UsuarioRepository $usuarioRepository) {
    $this->cliente->validate();

    $this->cliente->usuario_id = $this->clienteAtual['usuario_id'];
    $clienteAtualizado = array_diff($this->cliente->all(), $this->clienteAtual);

    // Valida se existe email cadastrado em outro usuario.
    $clienteValidadoEmail = $clienteRepository->consultaClientePorEmail($this->cliente->email);
    if (!is_null($clienteValidadoEmail) && $this->clienteAtual['cliente_id'] !== $clienteValidadoEmail->getAttribute('cliente_id')) return $this->addError('cliente.email', 'O email já está sendo usado por outro usuario, escolha outro.');

    // Pega somente as informações alteradas na edição do cliente para ser alterado no cadastro de usuários.
    $usuarioAtualizado = [];
    if (isset($clienteAtualizado['nome'])) $usuarioAtualizado['name'] = $clienteAtualizado['nome'];
    if (isset($clienteAtualizado['email'])) $usuarioAtualizado['email'] = $clienteAtualizado['email'];

    $usuarioAtualizado['id'] = $this->clienteAtual['usuario_id'];
    $clienteAtualizado['cliente_id'] = $this->clienteAtual['cliente_id'];

    $usuarioRepository->editaUsuario($usuarioAtualizado);
    $clienteRepository->editaCliente($clienteAtualizado);

    Session::flash('sucesso', 'Cliente editado com sucesso.');
    redirect('clientes/');
  }

  public function voltar(): void {
    redirect('clientes/');
  }
}

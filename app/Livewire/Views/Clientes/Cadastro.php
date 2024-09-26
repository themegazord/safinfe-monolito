<?php

namespace App\Livewire\Views\Clientes;

use App\Livewire\Forms\ClienteForm;
use App\Livewire\Forms\UsuarioForm;
use App\Repositories\Eloquent\Repository\ClienteRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cadastro extends Component
{
  public ClienteForm $cliente;
  public UsuarioForm $usuario;
  public Collection $empresas;

  public function mount(EmpresaRepository $empresaRepository): void {
    $this->empresas = collect($empresaRepository->listagemEmpresas());
  }

  #[Title('SAFI NFE - Cadastro de Clientes')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.clientes.cadastro');
  }

  public function cadastrar(UsuarioRepository $usuarioRepository, ClienteRepository $clienteRepository) {
    $this->cliente->validate();

    if (!is_null($clienteRepository->consultaClientePorEmail($this->cliente->email))) return $this->addError('cliente.email', 'O email já existe no sistema');

    $this->usuario->validateOnly('password');
    $this->usuario->encriptaSenha();

    $this->usuario->name = $this->cliente->nome;
    $this->usuario->email = $this->cliente->email;
    $this->usuario->role = 'CLIENTE';

    $usuario = $usuarioRepository->cadastraUsuario($this->usuario->all());

    $this->cliente->usuario_id = $usuario->getAttribute('id');

    $clienteRepository->cadastroCliente($this->cliente->all());

    Session::flash('sucesso', 'Cliente cadastrado com sucesso.');
    redirect('clientes/');
  }

  public function voltar(): void
  {
    redirect('contadores/');
  }
}

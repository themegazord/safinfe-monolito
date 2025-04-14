<?php

namespace App\Livewire\Views\Clientes;

use App\Livewire\Forms\ClienteForm;
use App\Livewire\Forms\UsuarioForm;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
  use Toast;

  public ClienteForm $cliente;
  public UsuarioForm $usuario;
  public Collection $empresas;

  public function mount(): void {
    $this->search();
  }

  #[Title('SAFI NFE - Cadastro de Clientes')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.clientes.cadastro');
  }

  public function cadastrar(): void {
    $this->cliente->validate();

    if (!is_null(Cliente::whereEmail($this->cliente->email))) {
      $this->addError('cliente.email', 'O email já existe no sistema');
      return;
    }

    $this->usuario->validateOnly('password');
    $this->usuario->encriptaSenha();

    $this->usuario->name = $this->cliente->nome;
    $this->usuario->email = $this->cliente->email;
    $this->usuario->role = 'CLIENTE';

    $usuario = User::create($this->usuario->all());

    $this->cliente->usuario_id = $usuario->getAttribute('id');

    Cliente::create($this->cliente->all());

    $this->success('Cliente cadastrado com sucesso', redirectTo: route('clientes'));
  }

  public function voltar(): void
  {
    redirect('contadores/');
  }

  public function search(?string $valor = null): void {
    $this->empresas = Empresa::query()->where('fantasia', 'like', "%$valor%")->orderBy('fantasia')->get();
  }
}

<?php

namespace App\Livewire\Views\Contadores;

use App\Livewire\Forms\ContadorForm;
use App\Livewire\Forms\UsuarioForm;
use App\Models\Contabilidade;
use App\Models\Contador;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
  use Toast;

  public ContadorForm $contador;
  public UsuarioForm $usuario;
  public Collection $contabilidades;

  public function mount(): void {
    $this->search();
  }

  #[Title('SAFI NFE - Cadastro de contadors')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.contadores.cadastro');
  }

  public function cadastrar() {
    $this->contador->limpaCampos();
    $this->contador->validate();

    if (is_null(Contabilidade::query()->find($this->contador->contabilidade_id))) return $this->addError('contador.contabilidade_id', 'A contabilidade deve ser informada.');
    if (!is_null(Contador::whereEmail($this->contador->email)->first())) return $this->addError('contador.email', 'O email já existe no sistema');
    if (!is_null(Contador::whereCpf($this->contador->cpf)->first())) return $this->addError('contador.cpf', 'O CPF já existe no sistema');

    $this->usuario->validateOnly('password');
    $this->usuario->encriptaSenha();

    $this->usuario->name = $this->contador->nome;
    $this->usuario->email = $this->contador->email;
    $this->usuario->role = 'CONTADOR';

    $usuario = User::create($this->usuario->all());

    $this->contador->usuario_id = $usuario->getAttribute('id');

    Contador::create($this->contador->all());

    $this->success('Contador cadastrado com sucesso', redirectTo: route('contadores'));
  }

  public function voltar(): void
  {
    redirect('contadores/');
  }

  public function search(?string $valor = null): void {
    $this->contabilidades = Contabilidade::query()->where('social', 'like', "%$valor%")->orderBy('social')->get();
  }
}

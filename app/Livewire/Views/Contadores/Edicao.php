<?php

namespace App\Livewire\Views\Contadores;

use App\Livewire\Forms\ContadorForm;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\ContadorRepository;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edicao extends Component
{
  public array $contadorAtual = [];
  public Collection $contabilidades;
  public ContadorForm $contador;

  public function mount(
    int $contador_id,
    ContadorRepository $contadorRepository,
    ContabilidadeRepository $contabilidadeRepository
  ): void {
    $this->contabilidades = $contabilidadeRepository->listagemContabilidades();
    $this->contadorAtual = $contadorRepository->consultaContador($contador_id)->toArray();
  }

  #[Title('SAFI NFE - Edição de Contadors')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.contadores.edicao');
  }

  public function editar(
    ContadorRepository $contadorRepository,
    UsuarioRepository $usuarioRepository
  ) {
    $this->contador->limpaCampos();
    $this->contador->validate();

    $this->contador->usuario_id = $this->contadorAtual['usuario_id'];
    $contadorAtualizado = array_diff($this->contador->all(), $this->contadorAtual);

    // Valida se existe email cadastrado em outro usuario.
    $contadorValidadoEmail = $contadorRepository->consultaContadorPorEmail($this->contador->email);
    $contadorValidadeCPF = $contadorRepository->consultaContadorPorCPF($this->contador->cpf);
    if (!is_null($contadorValidadoEmail) && $this->contadorAtual['contador_id'] !== $contadorValidadoEmail->getAttribute('contador_id')) return $this->addError('contador.email', 'O email já está sendo usado por outro usuario, escolha outro.');
    if (!is_null($contadorValidadeCPF) && $this->contadorAtual['contador_id'] !== $contadorValidadeCPF->getAttribute('contador_id')) return $this->addError('contador.cpf', 'O CPF já está sendo usado por outro usuario, escolha outro.');

    // Pega somente as informações alteradas na edição do contador para ser alterado no cadastro de usuários.
    $usuarioAtualizado = [];
    if (isset($contadorAtualizado['nome'])) $usuarioAtualizado['name'] = $contadorAtualizado['nome'];
    if (isset($contadorAtualizado['email'])) $usuarioAtualizado['email'] = $contadorAtualizado['email'];

    $usuarioAtualizado['id'] = $this->contadorAtual['usuario_id'];
    $contadorAtualizado['contador_id'] = $this->contadorAtual['contador_id'];

    $usuarioRepository->editaUsuario($usuarioAtualizado);
    $contadorRepository->editacontador($contadorAtualizado);

    Session::flash('sucesso', 'contador editado com sucesso.');
    redirect('contadores/');
  }

  public function voltar(): void
  {
    redirect('contadores/');
  }
}

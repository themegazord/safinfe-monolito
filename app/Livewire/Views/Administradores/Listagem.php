<?php

namespace App\Livewire\Views\Administradores;

use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public ?string $pesquisa = null;

  #[Title('SAFI NFE - Listagem de Usuarios')]
  #[Layout('components.layouts.main')]
  public function render(UsuarioRepository $usuarioRepository)
  {
    $administradores = $usuarioRepository->paginacaoUsuarios('ADMIN', 10, $this->pesquisa);
    return view('livewire.views.administradores.listagem', [
      'listagem' => compact('administradores')
    ]);
  }

  public function irCadastrar(): void {
    redirect('/administradores/cadastro');
  }

  public function irEdicaoAdministrador(int $administrador_id): void {
    redirect("/administradores/edicao/$administrador_id");
  }

  public function removerAdministrador(int $administrador_id, UsuarioRepository $usuarioRepository): void {
    $administrador = $usuarioRepository->consultaUsuario($administrador_id);
    if (is_null($administrador)) {
      Session::flash('alerta', 'O administrador não existe.');
      redirect('administradores/');
    }

    $administrador->delete();

    Session::flash('sucesso', 'O administrador foi removido com sucesso.');
    redirect('administradores/');
  }
}

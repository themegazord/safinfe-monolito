<?php

namespace App\Livewire\Views\Contadores;

use App\Models\Contador;
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

    public bool $estaAtivo = true;

    public int $porPagina = 10;

    public ?Contador $contadorAtual;

    public bool $modalConfirmandoInativacaoContador = false;

    #[Title('SAFI NFE - Listagem de Contadores')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.contadores.listagem');
    }

    public function irEdicaoContador(int $contador_id): void
    {
        redirect("/contadores/edicao/$contador_id");
    }

    public function irCadastrar(): void
    {
        redirect('/contadores/cadastro');
    }

    public function setInativacaoContador(int $contador_id): void
    {
        $this->contadorAtual = Contador::withTrashed()->find($contador_id);
        $this->modalConfirmandoInativacaoContador = ! $this->modalConfirmandoInativacaoContador;
    }

    public function inativarContador(): void
    {
        if ($this->contadorAtual->trashed()) {
            $this->contadorAtual->restore();
            $this->success('Contador ativado com sucesso');
        } else {
            $this->contadorAtual->delete();
            $this->success('Contador inativado com sucesso');
        }
        $this->modalConfirmandoInativacaoContador = ! $this->modalConfirmandoInativacaoContador;
        $this->estaAtivo = true;
    }
}

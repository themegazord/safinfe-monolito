<?php

namespace App\Livewire\Views\Versionamento;

use App\Livewire\Forms\VersaoForm;
use App\Models\Versionamento;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
    use Toast;

    public VersaoForm $versao;

    #[Layout('components.layouts.main')]
    #[Title('SAFI NFE - Versionamento')]
    public function render()
    {
        return view('livewire.views.versionamento.cadastro');
    }

    public function cadastrar()
    {
        $this->versao->validate();
        if (! is_null(Versionamento::wherePatch($this->versao->patch)->first())) {
            return $this->addError('versao.patch', 'A versão já existe');
        }
        Versionamento::create($this->versao->all());

        $this->success('Versão cadastrada com sucesso.', redirectTo: route('versionamento'));
    }

    public function voltar(): void
    {
        redirect('versionamento');
    }

    public function mostrarPreview(): void
    {
        $this->versao->detalhe = $this->versao->detalhe;
    }
}

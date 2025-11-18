<?php

namespace App\Livewire\Views\Contadores;

use App\Livewire\Forms\ContadorForm;
use App\Models\Contabilidade;
use App\Models\Contador;
use App\Models\User;
use App\Trait\EnviaEmailResetSenhaTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Edicao extends Component
{
    use EnviaEmailResetSenhaTrait, Toast;

    public ?Contador $contadorAtual;

    public ?string $novaSenha = null;

    public Collection $contabilidades;

    public ContadorForm $contador;

    public User|Authenticatable $usuarioAutenticado;

    public function mount(
        int $contador_id
    ): void {
        $this->usuarioAutenticado = Auth::user();
        if ($this->usuarioAutenticado->cannot('update', \App\Models\Contador::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
        $this->contabilidades = Contabilidade::all();
        $this->contadorAtual = Contador::find($contador_id);
        $this->contador->contabilidade_id = $this->contadorAtual->contabilidade_id;
    }

    #[Title('SAFI NFE - Edição de Contadors')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.contadores.edicao');
    }

    public function editar(): void
    {
        $this->contador->limpaCampos();
        $this->contador->validate();

        $this->contador->usuario_id = $this->contadorAtual['usuario_id'];
        $contadorAtualizado = array_diff($this->contador->all(), $this->contadorAtual->toArray());

        // Valida se existe email cadastrado em outro usuario.
        $contadorValidadoEmail = Contador::whereEmail($this->contador->email)->first();
        $contadorValidadeCPF = Contador::whereCpf($this->contador->cpf)->first();
        if (! is_null($contadorValidadoEmail) && $this->contadorAtual['contador_id'] !== $contadorValidadoEmail->getAttribute('contador_id')) {
            $this->addError('contador.email', 'O email já está sendo usado por outro usuario, escolha outro.');

            return;
        }
        if (! is_null($contadorValidadeCPF) && $this->contadorAtual['contador_id'] !== $contadorValidadeCPF->getAttribute('contador_id')) {
            $this->addError('contador.cpf', 'O CPF já está sendo usado por outro usuario, escolha outro.');

            return;
        }

        // Pega somente as informações alteradas na edição do contador para ser alterado no cadastro de usuários.
        $usuarioAtualizado = [];
        if (isset($contadorAtualizado['nome'])) {
            $usuarioAtualizado['name'] = $contadorAtualizado['nome'];
        }
        if (isset($contadorAtualizado['email'])) {
            $usuarioAtualizado['email'] = $contadorAtualizado['email'];
        }

        $usuarioAtualizado['id'] = $this->contadorAtual['usuario_id'];
        $contadorAtualizado['contador_id'] = $this->contadorAtual['contador_id'];

        User::where('id', $usuarioAtualizado['id'])->update($usuarioAtualizado);
        Contador::where('contador_id', $contadorAtualizado['contador_id'])->update($contadorAtualizado);

        $this->success('Contador editado com sucesso', redirectTo: route('contadores'));
    }

    public function voltar(): void
    {
        redirect('contadores/');
    }

    public function enviaEmailTrocaSenha(): void
    {
        $this->enviaEmail($this->contadorAtual->email);

        $this->success('Email enviado com sucesso');
    }
}

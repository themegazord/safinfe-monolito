<?php

namespace App\Livewire\Views\Contabilidades;

use App\Livewire\Forms\ContabilidadeForm;
use App\Livewire\Forms\EnderecoForm;
use App\Models\Contabilidade;
use App\Models\Empresa;
use App\Models\Endereco;
use App\Models\User;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\EmpContRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Edicao extends Component
{
    use Toast;

    public ContabilidadeForm $contabilidade;

    public EnderecoForm $endereco;

    public Endereco $enderecoAtual;

    public Contabilidade $contabilidadeAtual;

    public Collection $empresas;

    public User|Authenticatable $usuario;

    public function mount(
        int $contabilidade_id,
    ): void {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('update', \App\Models\Contabilidade::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
        $this->contabilidadeAtual = Contabilidade::find($contabilidade_id);
        $this->enderecoAtual = Endereco::find($this->contabilidadeAtual->endereco_id);
        $this->search();
        foreach ($this->contabilidadeAtual->empresas()->get() as $empresa) {
            array_push($this->contabilidade->empresas, $empresa->empresa_id);
        }
    }

    #[Title('SAFI NFE - Edição de Contabilidades')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.contabilidades.edicao');
    }

    public function editar(
        ContabilidadeRepository $contabilidadeRepository,
        EnderecoRepository $enderecoRepository,
        EmpContRepository $empContRepository
    ) {
        $this->contabilidade->filtraEmpresas();
        $this->contabilidade->tratarCamposSujos();
        $this->contabilidade->validate();

        if (! is_null(DB::table('contabilidades')->where('cnpj', $this->contabilidade->documento)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) {
            return $this->addError('contabilidade.documento', 'Documento já existente.');
        }
        if (! is_null(DB::table('contabilidades')->where('email_corporativo', $this->contabilidade->email_corporativo)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) {
            return $this->addError('contabilidade.email_corporativo', 'Email corporativo já existente.');
        }
        if (! is_null(DB::table('contabilidades')->where('email_contato', $this->contabilidade->email_contato)->whereNot('contabilidade_id', $this->contabilidadeAtual->getAttribute('contabilidade_id'))->first())) {
            return $this->addError('contabilidade.email_contato', 'Email de contato já existente.');
        }

        $this->endereco->tratarCamposSujos();
        $this->contabilidade->validate();

        $empresas = $this->contabilidade->empresas;

        $dadosContabilidade = $this->contabilidade->all();

        unset($dadosContabilidade['empresas']);

        $empContRepository->removeRelacionamentoContabilidade($this->contabilidadeAtual->getAttribute('contabilidade_id'));

        foreach ($empresas as $empresa) {
            $empContRepository->cadastrar([
                'empresa_id' => $empresa,
                'contabilidade_id' => $this->contabilidadeAtual->getAttribute('contabilidade_id'),
            ]);
        }

        $enderecoParaAtualizacao = array_diff($this->endereco->all(), $this->enderecoAtual->toArray());
        $contabilidadeParaAtualizacao = array_diff($dadosContabilidade, $this->contabilidadeAtual->toArray());

        $enderecoParaAtualizacao['endereco_id'] = $this->enderecoAtual->getAttribute('endereco_id');

        $contabilidadeParaAtualizacao['endereco_id'] = $enderecoParaAtualizacao['endereco_id'];
        $contabilidadeParaAtualizacao['contabilidade_id'] = $this->contabilidadeAtual->getAttribute('contabilidade_id');

        $enderecoRepository->editaEndereco($enderecoParaAtualizacao);
        $contabilidadeRepository->editaContabilidade($contabilidadeParaAtualizacao);

        $this->success('Contabilidade editada com sucesso', redirectTo: route('contabilidades'));
    }

    public function voltar(): void
    {
        redirect('/contabilidades');
    }

    public function search(string $valor = ''): void
    {
        $this->empresas = Empresa::query()->where('fantasia', 'like', "%$valor%")->orderBy('fantasia')->get();
    }
}

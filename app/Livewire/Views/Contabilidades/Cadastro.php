<?php

namespace App\Livewire\Views\Contabilidades;

use App\Livewire\Forms\ContabilidadeForm;
use App\Livewire\Forms\EnderecoForm;
use App\Models\Contabilidade;
use App\Models\EmpCont;
use App\Models\Empresa;
use App\Models\Endereco;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
    use Toast;

    public ContabilidadeForm $contabilidade;

    public EnderecoForm $endereco;

    public Collection $empresas;

    public string $dadosContabilidadeTab = 'dadosContabilidade-tab';

    public function mount(): void
    {
        $this->search();
    }

    #[Title('SAFI NFE - Cadastro de Contabilidades')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.contabilidades.cadastro');
    }

    public function cadastrar(): void
    {
        $this->contabilidade->filtraEmpresas();
        $this->contabilidade->tratarCamposSujos();
        $this->contabilidade->validate();

        if (! is_null(DB::table('contabilidades')->where('cnpj', $this->contabilidade->documento)->first())) {
            $this->addError('contabilidade.documento', 'Documento já existente.');

            return;
        }
        if (! is_null(DB::table('contabilidades')->where('email_corporativo', $this->contabilidade->email_corporativo)->first())) {
            $this->addError('contabilidade.email_corporativo', 'Email corporativo já existente.');

            return;
        }
        if (! is_null(DB::table('contabilidades')->where('email_contato', $this->contabilidade->email_contato)->first())) {
            $this->addError('contabilidade.email_contato', 'Email de contato já existente.');

            return;
        }

        $this->endereco->tratarCamposSujos();
        $this->endereco->validate();

        $endereco = Endereco::create($this->endereco->all());

        $this->contabilidade->endereco_id = $endereco->getAttribute('endereco_id');

        $empresas = $this->contabilidade->empresas;

        unset($this->contabilidade->empresas);

        $contabilidade = Contabilidade::create([
            'cnpj' => $this->contabilidade->documento,
            ...$this->contabilidade->all(),
        ]);

        foreach ($empresas as $empresa) {
            EmpCont::create([
                'empresa_id' => $empresa,
                'contabilidade_id' => $contabilidade->getAttribute('contabilidade_id'),
            ]);
        }

        $this->success('Contabilidade cadastrada com sucesso', redirectTo: route('contabilidades'));
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

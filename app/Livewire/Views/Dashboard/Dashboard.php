<?php

namespace App\Livewire\Views\Dashboard;

use App\Models\User;
use App\Models\XML;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\XMLRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public User|Authenticatable $usuario;

    public ?Collection $empresasContador = null;

    public ?Collection $empresasGeral = null;

    public ?Collection $dadosXML = null;

    public ?Collection $XMLs = null;

    public ?array $consulta = [
        'empresa_id' => null,
        'data_inicio_fim' => null,
        'data_inicio' => null,
        'data_fim' => null,
        'modelo' => 'TODAS',
        'status' => 'AUTORIZADO',
        'serie' => null,
        'numeroInicial' => null,
        'numeroFinal' => null,
    ];

    public ?array $informacoesTotaisNotas = null;

    public ?array $totalNotasPorDiaMes = null;

    public ?array $topProdutosVendidos = null;

    public function mount(EmpresaRepository $empresaRepository, XMLRepository $xmlRepository): void
    {
        $this->usuario = Auth::user();

        $this->consulta['data_inicio'] = date('Y-m-01');
        $this->consulta['data_fim'] = date('Y-m-t');

        if ($this->usuario->getAttribute('role') === 'CONTADOR') {
            $this->empresasContador = $this->usuario->contador->contabilidade->empresas;
            $this->consulta['empresa_id'] = ! is_null($this->empresasContador->first()) ? $this->empresasContador->first()->getAttribute('empresa_id') : null;
        }

        if ($this->usuario->getAttribute('role') === 'ADMIN') {
            $this->empresasGeral = $empresaRepository->listagemEmpresas();
            $this->consulta['empresa_id'] = ! is_null($this->empresasGeral->first()) ? $this->empresasGeral->first()->getAttribute('empresa_id') : null;
        }

        if ($this->usuario->getAttribute('role') === 'CLIENTE') {
            $this->consulta['empresa_id'] = $this->usuario->cliente->empresa->getAttribute('empresa_id');
            $this->montaTopProdutosVendidos();
            $this->montaInformacoesDeValoresNota();
            $this->montaTotalVendasPorDia($xmlRepository);
        }
    }

    #[Layout('components.layouts.main')]
    #[Title('SAFI NFE - Dashboard')]
    public function render()
    {
        return view('livewire.views.dashboard.dashboard');
    }

    public function consultar(DadosXMLRepository $dadosXMLRepository, XMLRepository $xmlRepository): void
    {
        $this->zeraInformacoesRelatorios();

        $this->consulta['data_inicio'] = date('Y/m/d', strtotime(explode(' até ', $this->consulta['data_inicio_fim'])[0]));
        $this->consulta['data_fim'] = date('Y/m/d', strtotime(explode(' até ', $this->consulta['data_inicio_fim'])[1]));

        $this->dadosXML = $dadosXMLRepository->preConsultaDadosXML($this->consulta, $this->consulta['empresa_id'])->orderBy('dx1.numeronf')->get();

        if ($this->dadosXML->isEmpty()) {
            $this->warning(title: 'Não foi encontrado notas nesse periodo');

            return;
        }

        $xmlIds = $this->dadosXML->pluck('xml_id');

        $this->XMLs = XML::whereIn('xml_id', $xmlIds)->pluck('xml');
    }

    private function zeraInformacoesRelatorios(): void
    {
        foreach (['informacoesTotaisNotas', 'totalNotasPorDiaMes', 'topProdutosVendidos'] as $propriedade) {
            $this->{$propriedade} = null;
        }
    }
}

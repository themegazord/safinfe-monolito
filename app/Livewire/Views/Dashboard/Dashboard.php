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

use function Laravel\Prompts\table;

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
    'numeroFinal' => null
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
      $this->consulta['empresa_id'] = !is_null($this->empresasContador->first()) ? $this->empresasContador->first()->getAttribute('empresa_id') : null;
    }

    if ($this->usuario->getAttribute('role') === 'ADMIN') {
      $this->empresasGeral = $empresaRepository->listagemEmpresas();
      $this->consulta['empresa_id'] = !is_null($this->empresasGeral->first()) ? $this->empresasGeral->first()->getAttribute('empresa_id') : null;
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

    $this->montaFaturamentoMovimento();
    // $this->montaTopProdutosVendidos();
    // $this->montaInformacoesDeValoresNota();
    // $this->montaTotalVendasPorDia($xmlRepository);
  }

  public function montaFaturamentoMovimento(): void
  {
    if (is_null($this->XMLs)) {
      return;
    }

    dd($this->dadosXML);
  }

  // public function montaInformacoesDeValoresNota(): void
  // {
  //   if (is_null($this->XMLs)) {
  //     return;
  //   }

  //   $totais = [
  //     'Totais das notas' => 0,
  //     'Total de ICMS das notas' => 0,
  //     'Total de ICMS ST das notas' => 0,
  //     'Valor total do PIS' => 0,
  //     'Valor total do COFINS' => 0,
  //   ];

  //   foreach ($this->XMLs as $xml) {
  //     $totaisNota = simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0];

  //     $totais['Totais das notas'] += (float) $totaisNota->vNF ?? 0;
  //     $totais['Total de ICMS das notas'] += (float) $totaisNota->vICMS ?? 0;
  //     $totais['Total de ICMS ST das notas'] += (float) $totaisNota->vST ?? 0;
  //     $totais['Valor total do PIS'] += (float) $totaisNota->vPIS ?? 0;
  //     $totais['Valor total do COFINS'] += (float) $totaisNota->vCOFINS ?? 0;
  //   }

  //   $this->informacoesTotaisNotas = $totais;
  // }

  // public function montaTotalVendasPorDia(XMLRepository $xmlRepository): void
  // {
  //   foreach ($this->notasPorDia() as $data => $notas) {
  //     foreach ($notas as $dadoNota) {
  //       if (!isset($this->totalNotasPorDiaMes[$data])) {
  //         $this->totalNotasPorDiaMes[$data] = 0;
  //       }
  //       $this->totalNotasPorDiaMes[$data] += (float)simplexml_load_string($xmlRepository->consultaPorId($dadoNota->xml_id)->getAttribute('xml'))->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vNF[0]->__toString();
  //     }
  //   }
  // }

  // public function montaTopProdutosVendidos(): void
  // {
  //   if (is_null($this->XMLs)) return;

  //   $produtos = [];

  //   foreach ($this->XMLs as $xml) {
  //     $xmlObj = simplexml_load_string($xml);

  //     // Suporte para XML com estrutura padrão
  //     if (!isset($xmlObj->NFe[0]->infNFe[0]->det)) continue;

  //     $detalhes = $xmlObj->NFe[0]->infNFe[0]->det;

  //     foreach ($detalhes as $detalhe) {
  //       $nomeProduto = (string) $detalhe->prod->xProd;
  //       $valor = (float) $detalhe->prod->vProd;
  //       $quantidade = (float) $detalhe->prod->qTrib;

  //       if (!isset($produtos[$nomeProduto])) {
  //         $produtos[$nomeProduto] = [
  //           'Nome Produto' => $nomeProduto,
  //           'Valor Total' => 0,
  //           'Quantidade' => 0,
  //         ];
  //       }

  //       $produtos[$nomeProduto]['Valor Total'] += $valor;
  //       $produtos[$nomeProduto]['Quantidade'] += $quantidade;
  //     }
  //   }

  //   // Ordena pela quantidade em ordem decrescente
  //   usort($produtos, fn($a, $b) => $b['Quantidade'] <=> $a['Quantidade']);

  //   // Pega os 10 primeiros
  //   $this->topProdutosVendidos = array_slice($produtos, 0, 10);
  // }

  // private function notasPorDia(): Collection
  // {
  //   if (is_null($this->dadosXML)) {
  //     return collect();
  //   }

  //   $inicio = Carbon::parse($this->consulta['data_inicio']);
  //   $diasDoMes = $inicio->daysInMonth;

  //   return collect(range(1, $diasDoMes))
  //     ->mapWithKeys(function ($dia) use ($inicio) {
  //       $data = $inicio->copy()->day($dia)->format('Y/m/d');

  //       $notasDoDia = $this->dadosXML->filter(function ($dado) use ($data) {
  //         return date('Y/m/d', strtotime($dado->dh_emissao_evento)) === $data;
  //       });

  //       return $notasDoDia->isNotEmpty() ? [$data => $notasDoDia] : [];
  //     });
  // }


  private function zeraInformacoesRelatorios(): void
  {
    foreach (['informacoesTotaisNotas', 'totalNotasPorDiaMes', 'topProdutosVendidos'] as $propriedade) {
      $this->{$propriedade} = null;
    }
  }
}

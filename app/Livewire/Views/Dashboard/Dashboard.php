<?php

namespace App\Livewire\Views\Dashboard;

use App\Models\User;
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

    $this->XMLs = collect();
    foreach ($this->dadosXML as $xml) {
      $this->XMLs->add($xmlRepository->consultaPorId($xml->xml_id)->getAttribute('xml'));
    }


    $this->montaTopProdutosVendidos();
    $this->montaInformacoesDeValoresNota();
    $this->montaTotalVendasPorDia($xmlRepository);
  }

  public function montaInformacoesDeValoresNota(): void
  {
    if (!is_null($this->XMLs)) {
      $informacoes = [
        'Totais das notas' => 0,
        'Total de ICMS das notas' => 0,
        'Total de ICMS ST das notas' => 0,
        'Valor total do PIS' => 0,
        'Valor total do COFINS' => 0,
      ];
      foreach ($this->XMLs as $xml) {
        $informacoes['Totais das notas'] += (float)simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vNF[0]->__toString();
        $informacoes['Total de ICMS das notas'] += (float)simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vICMS[0]->__toString();
        $informacoes['Total de ICMS ST das notas'] += (float)simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vST[0]->__toString();
        $informacoes['Valor total do PIS'] += (float)simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vPIS[0]->__toString();
        $informacoes['Valor total do COFINS'] += (float)simplexml_load_string($xml)->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vCOFINS[0]->__toString();
      }
      $this->informacoesTotaisNotas = $informacoes;
    }
  }

  public function montaTotalVendasPorDia(XMLRepository $xmlRepository): void
  {
    foreach ($this->notasPorDia() as $data => $notas) {
      foreach ($notas as $dadoNota) {
        if (!isset($this->totalNotasPorDiaMes[$data])) {
          $this->totalNotasPorDiaMes[$data] = 0;
        }
        $this->totalNotasPorDiaMes[$data] += (float)simplexml_load_string($xmlRepository->consultaPorId($dadoNota->xml_id)->getAttribute('xml'))->NFe[0]->infNFe[0]->total[0]->ICMSTot[0]->vNF[0]->__toString();
      }
    }
  }

  public function montaTopProdutosVendidos(): void
  {
    if (!is_null($this->XMLs)) {
      foreach ($this->XMLs as $xml) {
        $det = simplexml_load_string($xml)->NFe[0]->infNFe[0]->det[0];
        if (count(simplexml_load_string($xml)->NFe[0]->infNFe[0]->det) > 1) {
          foreach (simplexml_load_string($xml)->NFe[0]->infNFe[0]->det as $detalhe) {
            if (!isset($this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()])) {
              $this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()]['Nome Produto'] = $detalhe->prod[0]->xProd[0]->__toString();
              $this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()]['Valor Total'] = 0;
              $this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()]['Quantidade'] = 0;
            }
            $this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()]['Valor Total'] = $detalhe->prod[0]->vProd[0]->__toString();
            $this->topProdutosVendidos[$detalhe->prod[0]->xProd[0]->__toString()]['Quantidade'] = $detalhe->prod[0]->qTrib[0]->__toString();
          }
        } else {
          if (!isset($this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()])) {
            $this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()]['Nome Produto'] = $det->prod[0]->xProd[0]->__toString();
            $this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()]['Valor Total'] = 0;
            $this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()]['Quantidade'] = 0;
          }
          $this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()]['Valor Total'] = $det->prod[0]->vProd[0]->__toString();
          $this->topProdutosVendidos[$det->prod[0]->xProd[0]->__toString()]['Quantidade'] = $det->prod[0]->qTrib[0]->__toString();
        }
      }

      usort($this->topProdutosVendidos, function ($a, $b) {
        return $b['Quantidade'] <=> $a['Quantidade']; // Para ordem decrescente, use $b e $a
      });

      $this->topProdutosVendidos = array_slice($this->topProdutosVendidos, 0, 10);
    }
  }

  private function notasPorDia(): Collection
  {
    if (!is_null($this->dadosXML)) {
      $notasPorData = collect();
      for ($data = 1; $data <= date('t', strtotime($this->consulta['data_inicio'])); $data += 1) {
        $dataFormatado = str_pad($data, 2, '0', STR_PAD_LEFT);
        $notasPorData->put(date("Y/m/$dataFormatado", strtotime($this->consulta['data_inicio'])), $this->dadosXML->filter(function ($dado) use ($dataFormatado) {
          return date('Y/m/d', strtotime($dado->dh_emissao_evento)) === date("Y/m/$dataFormatado", strtotime($this->consulta['data_inicio']));
        }));
      }
      $notasPorData = $notasPorData->filter(fn($data) => $data->isNotEmpty());
      return $notasPorData;
    }
    return collect();
  }

  private function zeraInformacoesRelatorios(): void
  {
    $this->informacoesTotaisNotas = null;
    $this->totalNotasPorDiaMes = null;
    $this->topProdutosVendidos = null;
  }
}

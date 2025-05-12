<?php

namespace App\Livewire\Views\Relatorios\Faturamento;

use App\Models\DadosXML;
use App\Models\Empresa;
use App\Models\User;
use App\Models\XML;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Movimento extends Component
{
  public User|Authenticatable $usuario;
  public ?Collection $empresasContador = null;
  public ?Collection $empresasGeral = null;
  public ?Collection $dadosXML = null;
  public ?Collection $XMLs = null;
  public ?array $consulta = [
    "empresa_id" => 3,
    "data_inicio_fim" => "2025-04-01 00:00 até 2025-04-30 00:00",
    'data_inicio' => null,
    'data_fim' => null,
    'modelo' => 'TODAS',
    'status' => 'TODAS',
    'serie' => null,
    'numeroInicial' => null,
    'numeroFinal' => null
  ];

  public function mount(): void
  {
    $this->usuario = Auth::user();

    $this->consulta['data_inicio'] = date('Y-m-01');
    $this->consulta['data_fim'] = date('Y-m-t');

    if ($this->usuario->getAttribute('role') === 'CONTADOR') {
      $this->empresasContador = $this->usuario->contador->contabilidade->empresas;
      $this->consulta['empresa_id'] = !is_null($this->empresasContador->first()) ? $this->empresasContador->first()->getAttribute('empresa_id') : null;
    }

    if ($this->usuario->getAttribute('role') === 'ADMIN') {
      $this->empresasGeral = Empresa::query()
        ->get([
          'empresa_id',
          'cnpj',
          'fantasia',
        ]);
      $this->consulta['empresa_id'] = !is_null($this->empresasGeral->first()) ? $this->empresasGeral->first()->getAttribute('empresa_id') : null;
      $this->consulta['empresa_id'] = 3;
    }

    if ($this->usuario->getAttribute('role') === 'CLIENTE') {
      $this->consulta['empresa_id'] = $this->usuario->cliente->empresa->getAttribute('empresa_id');
    }
  }

  #[Title('Relatório de Movimento de Faturamento')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.relatorios.faturamento.movimento');
  }

  public function consultar(DadosXMLRepository $dadosXMLRepository): void
  {
    $this->zeraInformacoesRelatorios();

    $this->consulta['data_inicio'] = date('Y/m/d', strtotime(explode(' até ', $this->consulta['data_inicio_fim'])[0]));
    $this->consulta['data_fim'] = date('Y/m/d', strtotime(explode(' até ', $this->consulta['data_inicio_fim'])[1]));

    $this->dadosXML = $dadosXMLRepository->preConsultaDadosXML($this->consulta, $this->consulta['empresa_id'])->get();

    if ($this->dadosXML->isEmpty()) {
      $this->warning(title: 'Não foi encontrado notas nesse periodo');
      return;
    }

    $this->dadosXML = $this->dadosXML
      ->groupBy(function ($item) {
        return Carbon::parse($item->dh_emissao_evento)->format('Y-m-d');
      })
      ->map(function (Collection $notasDoDia) {
        return $notasDoDia->map(function ($dado) {
          if ($dado->status === 'AUTORIZADO') {
            $xmlRaw = XML::query()->find($dado->xml_id)?->xml;

            if (!$xmlRaw) {
              return null; // ou log de erro, se necessário
            }

            $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
            $totais = $xml->total[0]->ICMSTot[0];

            return [
              'modelo'       => $dado->modelo,
              'serie'        => $dado->serie,
              'numeronf'     => $dado->numeronf,
              'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
              'destinatario' => $dado->modelo == 55
                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                : 'CONSUMIDOR FINAL',
              'vrdesp'   => (float) ($totais->vOutro ?? 0),
              'vrfrete'   => (float) ($totais->vFrete ?? 0),
              'vrprod'   => (float) ($totais->vProd ?? 0),
              'vrtotal'  => (float) ($totais->vNF ?? 0),
              'situacao' => ucfirst(strtolower($dado->status)),
              'vripi'    => (float) ($totais->vIPI ?? 0),
              'vrbcicms'   => (float) ($totais->vBC ?? 0),
              'vricms'   => (float) ($totais->vICMS ?? 0),
              'vrfcp'    => (float) ($totais->vFCP ?? 0),
              'vrbcst'   => (float) ($totais->vBCST ?? 0),
              'vrst'     => (float) ($totais->vST ?? 0),
            ];
          } else if ($dado->status === 'CANCELADO') {
            $xmlRaw = DadosXML::query()->where('empresa_id', $dado->empresa_id)->where('numeronf', $dado->numeronf)->where('status', 'AUTORIZADO')->first()->xml->xml;

            if (!$xmlRaw) {
              return null; // ou log de erro, se necessário
            }

            $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
            $totais = $xml->total[0]->ICMSTot[0];

            return [
              'modelo'       => $dado->modelo,
              'serie'        => $dado->serie,
              'numeronf'     => $dado->numeronf,
              'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
              'destinatario' => $dado->modelo == 55
                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                : 'CONSUMIDOR FINAL',
              'vrdesp'   => (float) ($totais->vOutro ?? 0),
              'vrfrete'   => (float) ($totais->vFrete ?? 0),
              'vrprod'   => (float) ($totais->vProd ?? 0),
              'vrtotal'  => (float) ($totais->vNF ?? 0),
              'situacao' => ucfirst(strtolower($dado->status)),
              'vripi'    => (float) ($totais->vIPI ?? 0),
              'vrbcicms'   => (float) ($totais->vBC ?? 0),
              'vricms'   => (float) ($totais->vICMS ?? 0),
              'vrfcp'    => (float) ($totais->vFCP ?? 0),
              'vrbcst'   => (float) ($totais->vBCST ?? 0),
              'vrst'     => (float) ($totais->vST ?? 0),
            ];
          } else {
            $xmlModel = DadosXML::query()
              ->where('empresa_id', $dado->empresa_id)
              ->where('numeronf', $dado->numeronf)
              ->where('status', 'AUTORIZADO')
              ->first();

            $xmlRaw = $xmlModel?->xml ?? null;

            if (!$xmlRaw) {
              return [
                'modelo'       => $dado->modelo,
                'serie'        => $dado->serie,
                'numeronf'     => $dado->numeronf,
                'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
                'destinatario' => 'NOTA INUTILIZADA',
                'vrprod'   => (float) ($totais->vProd ?? 0),
                'vrtotal'  => (float) ($totais->vNF ?? 0),
                'situacao' => ucfirst(strtolower($dado->status)),
                'vripi'    => (float) ($totais->vIPI ?? 0),
                'vrbcicms'   => (float) ($totais->vBC ?? 0),
                'vricms'   => (float) ($totais->vICMS ?? 0),
                'vrfcp'    => (float) ($totais->vFCP ?? 0),
                'vrbcst'   => (float) ($totais->vBCST ?? 0),
                'vrst'     => (float) ($totais->vST ?? 0),
              ];
            }

            $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
            $totais = $xml->total[0]->ICMSTot[0];

            return [
              'modelo'       => $dado->modelo,
              'serie'        => $dado->serie,
              'numeronf'     => $dado->numeronf,
              'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
              'destinatario' => $dado->modelo == 55
                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                : 'CONSUMIDOR FINAL',
              'vrdesp'   => (float) ($totais->vOutro ?? 0),
              'vrfrete'   => (float) ($totais->vFrete ?? 0),
              'vrprod'   => (float) ($totais->vProd ?? 0),
              'vrtotal'  => (float) ($totais->vNF ?? 0),
              'situacao' => ucfirst(strtolower($dado->status)),
              'vripi'    => (float) ($totais->vIPI ?? 0),
              'vrbcicms'   => (float) ($totais->vBC ?? 0),
              'vricms'   => (float) ($totais->vICMS ?? 0),
              'vrfcp'    => (float) ($totais->vFCP ?? 0),
              'vrbcst'   => (float) ($totais->vBCST ?? 0),
              'vrst'     => (float) ($totais->vST ?? 0),
            ];
          }
        })->filter(); // remove possíveis nulls
      });
  }

  private function zeraInformacoesRelatorios(): void
  {
    foreach (['informacoesTotaisNotas', 'totalNotasPorDiaMes', 'topProdutosVendidos'] as $propriedade) {
      $this->{$propriedade} = null;
    }
  }
}

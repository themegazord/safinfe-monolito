<?php

namespace App\Livewire\Views\Consultaxml;

use App\Actions\TrataDadosGeraisNotaFiscal;
use App\Models\DadosXML;
use App\Models\Empresa;
use App\Models\User;
use App\Models\XML;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Trait\AnaliseXML\InformacaoAdicional\AnalisaInfAdicionalTrait;
use App\Trait\AnaliseXML\Pagamento\AnalisaPagamentosTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaCOFINSSTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaCOFINSXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaICMSUFDestXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaICMSXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaIIXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaIPIXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaISSQNTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaPISSTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaPISXMLTrait;
use Illuminate\Support\Facades\Response;
use SimpleXMLElement;
use ZipArchive;

class Consulta extends Component
{
  use WithPagination, Toast, AnalisaICMSXMLTrait, AnalisaIIXMLTrait, AnalisaIPIXMLTrait, AnalisaPISXMLTrait, AnalisaPISSTXMLTrait, AnalisaCOFINSXMLTrait, AnalisaISSQNTXMLTrait, AnalisaCOFINSSTXMLTrait, AnalisaPagamentosTrait, AnalisaInfAdicionalTrait, AnalisaICMSUFDestXMLTrait;
  public Authenticatable|User $usuario;
  public ?Collection $empresas;
  public ?array $dadosXMLAtual = null;
  public ?array $dadosXMLAtualCancelado = null;
  public ?array $dadosXMLAtualInutilizado = null;
  public array $dados, $tagImposto, $tagPagamento, $tagInfAdicional = [];
  public string $diretorioUsuario = '';
  public string $diretorioRARUsuario = '';
  public string $descricaoNotaFiscalTab = 'cabecalho-tab';
  public ?array $dadosXML = null;
  public ?int $porPagina = 15;
  public bool $modalVisualizacaoDadosXML = false;
  public array $expanded = [];

  public function mount(): void
  {
    $this->usuario = Auth::user();
    $this->empresas = match ($this->usuario->getAttribute('role')) {
      'CONTADOR' => $this->usuario->contador->contabilidade->empresas,
      'ADMIN' => $this->pesquisaEmpresasAdmin(),
      default => null
    };
    $this->dadosXML = ['empresa_id' => null, 'data_inicio' => null, 'data_fim' => null, 'status' => null, 'modelo' => null, 'serie' => null, 'numeroInicial' => null, 'numeroFinal' => null];
  }

  public function __destruct()
  {
    if (!empty($this->diretorioUsuario)) $this->removePasta($this->diretorioUsuario);
    if (!empty($this->diretorioRARUsuario)) $this->removePasta($this->diretorioRARUsuario);
  }

  #[Title('SAFI NFE - Consulta XML')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.consultaxml.consulta');
  }

  #[On('envia-consulta')]
  public function consulta(array $params): void
  {
    $this->dadosXML = $params;
  }

  #[On('download-direto')]
  public function downloadDireto(array $params)
  {
    $dados_xml = $this->consultaDadosDownload($params);

    if ($dados_xml->isEmpty()) {
      $this->warning("Nenhuma nota fiscal encontrada.");
      return;
    }

    $tempZipPath = tempnam(sys_get_temp_dir(), 'zip_');
    $zip = new ZipArchive();

    if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
      $this->warning("Não foi possível criar o ZIP em memória.");
      return;
    }

    foreach ($dados_xml as $dado) {
      try {
        $conteudoXML = XML::find($dado->xml_id)?->getAttribute('xml');
        if ($conteudoXML) {
          $zip->addFromString($dado->chave . '.xml', $conteudoXML);
        }
      } catch (\Exception $e) {
        $this->warning("Erro ao processar o XML ID: {$dado->xml_id}");
        Log::error("Erro ao processar o XML ID: {$dado->xml_id}, Erro: " . $e->getMessage());
        return;
      }
    }

    $zip->close();

    return response()->streamDownload(function () use ($tempZipPath) {
      readfile($tempZipPath);
      unlink($tempZipPath); // limpa após envio
    }, 'XMLArquivos.zip', [
      'Content-Type' => 'application/zip',
    ]);
  }

  public function downloadXMLUnico(int $dado_id)
  {
    $dadosXML = DadosXML::where('dados_id', $dado_id)->first();

    try {
      $nomeArquivo = $dadosXML->getAttribute('chave') . '.xml';
      $conteudo = $dadosXML->xml->xml;

      return Response::streamDownload(function () use ($conteudo) {
        echo $conteudo;
      }, $nomeArquivo, [
        'Content-Type' => 'application/xml',
      ]);
    } catch (\Exception $e) {
      Log::error("Erro ao processar o XML ID: {$dadosXML->xml_id}, Erro: " . $e->getMessage());
      $this->warning("Erro ao processar o XML ID: {$dadosXML->xml_id}, Erro: " . $e->getMessage());
    }
  }


  public function pesquisaEmpresasAdmin(?string $valor = null): Collection
  {
    return Empresa::query()->where('fantasia', 'like', "%$valor%")->get();
  }

  public function setXMLAtual(int $dado_id, TrataDadosGeraisNotaFiscal $dadosGeraisNotaFiscal): void
  {
    $dadosXML = DadosXML::findOrFail($dado_id);

    $xml = $dadosXML->status === 'AUTORIZADO'
      ? $dadosXML->xml->xml
      : DadosXML::where('numeronf', $dadosXML->numeronf)
      ->where('status', 'AUTORIZADO')
      ->latest()
      ->first()
      ?->xml->xml;

    if (!$xml) {
      throw new \Exception("XML autorizado não encontrado.");
    }

    $nfe = simplexml_load_string($xml)->NFe[0]->infNFe[0];
    $informacoesBrutaNFE = $dadosGeraisNotaFiscal->consultaDadosXML($nfe);

    if ($dadosXML->status === 'CANCELADO') {
      $this->dadosXMLAtualCancelado = $dadosGeraisNotaFiscal->formataDadosXMLEventoCancelamento(
        simplexml_load_string($dadosXML->xml->xml)->evento[0]->infEvento[0]
      );
    }

    $this->dadosXMLAtual = [
      'modelo' => (string) $informacoesBrutaNFE['ide']->mod[0],
      'numero' => (string) $informacoesBrutaNFE['ide']->nNF[0],
      'detalhesEmissor' => [
        'cnpj' => (string) $informacoesBrutaNFE['emit']->CNPJ[0],
        'nomeEmissor' => (string) $informacoesBrutaNFE['emit']->xNome[0],
        'ie' => (string) $informacoesBrutaNFE['emit']->IE[0],
        'endereco' => [
          'rua' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->xLgr[0],
          'numero' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->nro[0],
          'bairro' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->xBairro[0],
          'cep' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->CEP[0],
          'cidade' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->xMun[0],
          'uf' => (string) $informacoesBrutaNFE['emit']->enderEmit[0]->UF[0],
        ],
      ],
      'detalhesDestinatario' => [],
      'informacaoDeValoresDaNota' => [
        'totalNota' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vNF[0],
        'totalICMS' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vICMS[0],
        'totalST' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vST[0],
        'vPIS' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vPIS[0],
        'vCOFINS' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vCOFINS[0],
        'valorApxImpostosFederais' => (float) $informacoesBrutaNFE['total']->ICMSTot[0]->vTotTrib[0],
      ],
      'produtos' => [],
      'pagamento' => [],
      'infAdicional' => [],
    ];

    $modelo = (string) $informacoesBrutaNFE['ide']->mod[0];

    if ($modelo === '55') {
      $this->dadosXMLAtual['detalhesDestinatario'] = [
        'cnpj' => (string) $informacoesBrutaNFE['dest']->CNPJ[0],
        'nomeDestinatario' => (string) $informacoesBrutaNFE['dest']->xNome[0],
        'ie' => ((string) $informacoesBrutaNFE['dest']->indIEDest[0] === '1')
          ? (string) $informacoesBrutaNFE['dest']->IE[0]
          : '',
        'endereco' => [
          'rua' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->xLgr[0],
          'numero' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->nro[0],
          'bairro' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->xBairro[0],
          'cep' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->CEP[0],
          'cidade' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->xMun[0],
          'uf' => (string) $informacoesBrutaNFE['dest']->enderDest[0]->UF[0],
        ],
      ];
    } elseif ($modelo === '65') {
      $this->dadosXMLAtual['detalhesDestinatario'] = [
        'nomeDestinatario' => 'CONSUMIDOR FINAL',
      ];
    }

    foreach ($informacoesBrutaNFE['det'] as $key => $informacaoProduto) {
      $this->dadosXMLAtual['produtos'][$key] = $informacaoProduto;
      if ($this->dadosXMLAtual['produtos'][$key]['prod']) $this->dadosXMLAtual['produtos'][$key]['prod'] = (array)$this->dadosXMLAtual['produtos'][$key]['prod'];
      $this->dadosXMLAtual['produtos'][$key]['imposto'] = $this->trataDadosImpostoProduto($this->dadosXMLAtual['produtos'][$key]['imposto']);
    }


    $this->dadosXMLAtual['pagamento'] = $this->trataDadosPagamentoNotaFiscal($informacoesBrutaNFE['pag']);
    $this->dadosXMLAtual['infAdicional'] = $this->trataDadosInfAdicionalNotaFiscal($informacoesBrutaNFE['infAdic']);

    $this->modalVisualizacaoDadosXML = !$this->modalVisualizacaoDadosXML;
  }

  private function trataDadosImpostoProduto(SimpleXMLElement $imposto): array
  {
    $arrayImposto = (array) $imposto;

    $this->tagImposto = [
      'vTotTrib' => $imposto->vTotTrib[0] ?? 0,
      'ICMS' => [key($imposto->ICMS) ?? '' => []],
      'IPI' => [key($imposto->IPI) ?? '' => []],
      'II' => [key($imposto->II) ?? '' => []],
      'PIS' => [key($imposto->PIS) ?? '' => []],
      'PISST' => [key($imposto->PISST) ?? '' => []],
      'COFINS' => [key($imposto->COFINS) ?? '' => []],
      'COFINSST' => [key($imposto->COFINSST) ?? '' => []],
      'ISSQN' => [key($imposto->ISSQN) ?? '' => []],
    ];

    if (isset($arrayImposto['ICMS'])) {
      foreach ($arrayImposto['ICMS'] as $icms) {
        match (key($imposto->ICMS)) {
          'ICMS00' => $this->defineCamposICMS00($icms, 'ICMS00'),
          'ICMS10' => $this->defineCamposICMS10($icms, 'ICMS10'),
          'ICMS20' => $this->defineCamposICMS20($icms, 'ICMS20'),
          'ICMS30' => $this->defineCamposICMS30($icms, 'ICMS30'),
          'ICMS40' => $this->defineCamposICMS40($icms, 'ICMS40'),
          'ICMS51' => $this->defineCamposICMS51($icms, 'ICMS51'),
          'ICMS60' => $this->defineCamposICMS60($icms, 'ICMS60'),
          'ICMS70' => $this->defineCamposICMS70($icms, 'ICMS70'),
          'ICMS90' => $this->defineCamposICMS90($icms, 'ICMS90'),
          'ICMSPart' => $this->defineCamposICMSPart($icms, 'ICMSPart'),
          'ICMSRepasse' => $this->defineCamposICMSRepasse($icms, 'ICMSRepasse'),
          'ICMSSN101' => $this->defineCamposICMSSN101($icms, 'ICMSSN101'),
          'ICMSSN102' => $this->defineCamposICMSSN102($icms, 'ICMSSN102'),
          'ICMSSN201' => $this->defineCamposICMSSN201($icms, 'ICMSSN201'),
          'ICMSSN202' => $this->defineCamposICMSSN202($icms, 'ICMSSN202'),
          'ICMSSN500' => $this->defineCamposICMSSN500($icms, 'ICMSSN500'),
          'ICMSSN900' => $this->defineCamposICMSSN900($icms, 'ICMSSN900')
        };
      }
    }

    if (isset($arrayImposto['ICMSUFDest'])) {
      $this->defineCamposICMSUFDest($arrayImposto['ICMSUFDest'], 'ICMSUFDest');
    }

    if (isset($arrayImposto['IPI'])) {
      foreach ($arrayImposto['IPI'] as $ipi) {
        match (key($imposto->IPI)) {
          'IPI' => $this->defineCamposIPI($ipi, 'IPI'),
        };
      }
    }

    if (isset($arrayImposto['II'])) {
      foreach ($arrayImposto['II'] as $ii) {
        match (key($imposto->II)) {
          'II' => $this->defineCamposII($ii, 'II'),
        };
      }
    }

    if (isset($arrayImposto['PIS'])) {
      foreach ($arrayImposto['PIS'] as $pis) {
        match (key($imposto->PIS)) {
          'PISAliq' => $this->defineCamposPISAliq($pis, 'PISAliq'),
          'PISQtde' => $this->defineCamposPISQtde($pis, 'PISQtde'),
          'PISNT' => $this->defineCamposPISNT($pis, 'PISNT'),
          'PISOutr' => $this->defineCamposPISOutr($pis, 'PISOutr'),
        };
      }
    }
    if (isset($arrayImposto['PISST'])) {
      foreach ($arrayImposto['PISST'] as $pisST) {
        match (key($imposto->PISST)) {
          'PISST' => $this->defineCamposPISST($pisST, 'PISST'),
        };
      }
    }
    if (isset($arrayImposto['COFINS'])) {
      foreach ($arrayImposto['COFINS'] as $cofins) {
        match (key($imposto->COFINS)) {
          'COFINSAliq' => $this->defineCamposCOFINSAliq($cofins, 'COFINSAliq'),
          'COFINSQtde' => $this->defineCamposCOFINSQtde($cofins, 'COFINSQtde'),
          'COFINSNT' => $this->defineCamposCOFINSNT($cofins, 'COFINSNT'),
          'COFINSOutr' => $this->defineCamposCOFINSOutr($cofins, 'COFINSOutr'),
        };
      }
    }

    if (isset($arrayImposto['COFINSST'])) {
      foreach ($arrayImposto['COFINSST'] as $cofinsST) {
        match (key($imposto->COFINSST)) {
          'COFINSST' => $this->defineCamposCOFINSST($cofinsST, 'COFINSST'),
        };
      }
    }

    if (isset($arrayImposto['ISSQN'])) {
      foreach ($arrayImposto['ISSQN'] as $issqn) {
        match (key($imposto->ISSQN)) {
          'ISSQN' => $this->defineCamposISSQN($issqn, 'ISSQN'),
        };
      }
    }
    $this->tagImposto = $this->limparTagSuja($this->tagImposto);
    return $this->tagImposto;
  }

  private function trataDadosPagamentoNotaFiscal(SimpleXMLElement $pagamento): array
  {
    $arrayPagamento = (array) $pagamento;

    if (!isset($arrayPagamento['detPag'])) {
      return [];
    }

    $detPag = $arrayPagamento['detPag'];

    if (is_array($detPag) && array_is_list($detPag)) {
      foreach ($detPag as $key => $pag) {
        $this->analisaCamposPagamentoMultiFormas($pag, 'pag', $key);
        $this->tagPagamento[$key]['pag']['pag'] = $this->limparTagSuja($this->tagPagamento[$key]['pag']['pag']);
      }
    } else {
      $this->analisaCamposPagamento($detPag, 'pag');
      $this->tagPagamento['pag']['pag'] = $this->limparTagSuja($this->tagPagamento['pag']['pag']);
    }

    return $this->tagPagamento;
  }

  private function trataDadosInfAdicionalNotaFiscal(SimpleXMLElement $infAdicional): array
  {
    $this->defineCamposInfAdicional($infAdicional, 'infAdic');

    return $this->limparTagSuja($this->tagInfAdicional);
  }

  private function limparTagSuja(array $tags): array
  {
    return array_filter($tags, function ($item) {
      if (!is_array($item)) {
        return true;
      }

      $itemVazio = empty($item);
      $possuiChaveVazia = array_key_exists('', $item);

      return !$itemVazio && !$possuiChaveVazia;
    });
  }

  private function consultaDadosDownload(array $dadosXML): Collection
  {
    $dados_xml = DB::table('dados_xml as dx1')
      ->where(function ($query) {
        $query->whereIn('dx1.status', ['cancelado', 'denegado', 'inutilizado'])
          ->orWhere(function ($subQuery) {
            $subQuery->where('dx1.status', 'autorizado')
              ->whereNotExists(function ($subSubQuery) {
                $subSubQuery->select(DB::raw(1))
                  ->from('dados_xml as dx2')
                  ->whereRaw('dx2.numeronf = dx1.numeronf')
                  ->whereIn('dx2.status', ['cancelado', 'denegado', 'inutilizado']);
              });
          });
      })
      ->where('empresa_id', $dadosXML['empresa_id'])

      // filtro de data com when
      ->when(!is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $inicio = date('Y-m-d', strtotime($dadosXML['data_inicio']));
        $fim = date('Y-m-d', strtotime($dadosXML['data_fim']));

        if ($inicio === $fim) {
          $query->whereDate('dh_emissao_evento', $inicio);
        } else {
          $query->whereBetween(DB::raw('DATE(dh_emissao_evento)'), [$inicio, $fim]);
        }
      })
      ->when(!is_null($dadosXML['data_inicio']) && is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $query->whereDate('dh_emissao_evento', '>=', date('Y-m-d', strtotime($dadosXML['data_inicio'])));
      })
      ->when(is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $query->whereDate('dh_emissao_evento', '<=', date('Y-m-d', strtotime($dadosXML['data_fim'])));
      })

      // status
      ->when($dadosXML['status'] !== "TODAS", function ($query) use ($dadosXML) {
        $query->where('dx1.status', $dadosXML['status']);
      })

      // modelo
      ->when($dadosXML['modelo'] !== "TODAS", function ($query) use ($dadosXML) {
        $query->where('dx1.modelo', $dadosXML['modelo']);
      })

      // série
      ->when(!is_null($dadosXML['serie']), function ($query) use ($dadosXML) {
        $query->where('dx1.serie', $dadosXML['serie']);
      })

      // faixa de número NF
      ->when(!is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->whereBetween('numeronf', [$dadosXML['numeroInicial'], $dadosXML['numeroFinal']]);
      })
      ->when(!is_null($dadosXML['numeroInicial']) && is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->where('numeronf', '>=', $dadosXML['numeroInicial']);
      })
      ->when(is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->where('numeronf', '<=', $dadosXML['numeroFinal']);
      });

    return $dados_xml->get();
  }
}

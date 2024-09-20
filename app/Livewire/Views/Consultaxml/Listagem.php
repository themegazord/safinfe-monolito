<?php

namespace App\Livewire\Views\Consultaxml;

use App\Actions\TrataDadosGeraisNotaFiscal;
use App\Models\User;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Trait\AnaliseXML\Tributacao\AnalisaCOFINSSTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaCOFINSXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaICMSXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaIIXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaIPIXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaISSQNTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaPISSTXMLTrait;
use App\Trait\AnaliseXML\Tributacao\AnalisaPISXMLTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleXMLElement;

class Listagem extends Component
{
  use WithPagination, AnalisaICMSXMLTrait, AnalisaIIXMLTrait, AnalisaIPIXMLTrait, AnalisaPISXMLTrait, AnalisaPISSTXMLTrait, AnalisaCOFINSXMLTrait, AnalisaISSQNTXMLTrait, AnalisaCOFINSSTXMLTrait;

  public Authenticatable|User $usuario;
  public array $dados, $tagImposto = [];
  public Collection $dados_xml;
  public ?array $dadosXMLAtual = null;
  public string $hash = '';
  public int $perPage = 50;

  public function mount(string $hash): void
  {
    $this->usuario = Auth::user();
    $this->hash = $hash;
  }

  #[Title('SAFI NFE - Listagem de XML')]
  #[Layout('components.layouts.main')]
  public function render(DadosXMLRepository $dadosXMLRepository)
  {
    $this->dados = json_decode(base64_decode($this->hash), true);

    $dados_xml = match ($this->usuario->getAttribute('role')) {
      'CLIENTE' => $dados_xml = $dadosXMLRepository->preConsultaDadosXML($this->dados, $this->usuario->cliente->empresa->getAttribute('empresa_id')),
      'CONTADOR' => $dados_xml = $dadosXMLRepository->preConsultaDadosXML($this->dados, $this->dados['empresa_id']),
    };

    $dados_xml = $dados_xml->paginate($this->perPage);

    return view('livewire.views.consultaxml.listagem', [
      'listagem' => compact('dados_xml')
    ]);
  }

  public function selecionaXMLAtual(int $dado_id, DadosXMLRepository $dadosXMLRepository, TrataDadosGeraisNotaFiscal $dadosGeraisNotaFiscal): void
  {
    $xml = $dadosXMLRepository->consultaPorID($dado_id)->xml->xml;
    $informacoesBrutaNFE = $dadosGeraisNotaFiscal->consultaDadosXML(simplexml_load_string($xml)->NFe[0]->infNFe[0]);

    // dd($informacoesBrutaNFE);

    $this->dadosXMLAtual = [
      'modelo' => '',
      'detalhesEmissor' => [],
      'detalhesDestinatario' => [],
      'informacaoDeValoresDaNota' => [
        'totalNota' => 0,
        'totalICMS' => 0,
        'totalST' => 0,
        'vPIS' => 0,
        'vCOFINS' => 0,
        'valorApxImpostosFederais' => 0,
      ],
      'produtos' => []
    ];

    // dd($this->dadosXMLAtual);

    $this->dadosXMLAtual['modelo'] = $informacoesBrutaNFE['ide']->mod[0]->__toString();

    $this->dadosXMLAtual['detalhesEmissor'] = [
      'cnpj' => $informacoesBrutaNFE['emit']->CNPJ[0]->__toString(),
      'nomeEmissor' => $informacoesBrutaNFE['emit']->xNome[0]->__toString(),
      'ie' => $informacoesBrutaNFE['emit']->IE[0]->__toString(),
      'endereco' => [
        'rua' => $informacoesBrutaNFE['emit']->enderEmit[0]->xLgr[0]->__toString(),
        'numero' => $informacoesBrutaNFE['emit']->enderEmit[0]->nro[0]->__toString(),
        'bairro' => $informacoesBrutaNFE['emit']->enderEmit[0]->xBairro[0]->__toString(),
        'cep' => $informacoesBrutaNFE['emit']->enderEmit[0]->CEP[0]->__toString(),
        'cidade' => $informacoesBrutaNFE['emit']->enderEmit[0]->xMun[0]->__toString(),
        'uf' => $informacoesBrutaNFE['emit']->enderEmit[0]->UF[0]->__toString()
      ]
    ];

    if ($informacoesBrutaNFE['ide']->mod[0]->__toString() === '55') {
      $this->dadosXMLAtual['detalhesDestinatario'] = [
        'cnpj' => $informacoesBrutaNFE['dest']->CNPJ[0]->__toString(),
        'nomeDestinatario' => $informacoesBrutaNFE['dest']->xNome[0]->__toString(),
        'ie' => $informacoesBrutaNFE['dest']->indIEDest[0]->__toString() === '1' ? $informacoesBrutaNFE['dest']->IE[0]->__toString() : '',
        'endereco' => [
          'rua' => $informacoesBrutaNFE['dest']->enderDest[0]->xLgr[0]->__toString(),
          'numero' => $informacoesBrutaNFE['dest']->enderDest[0]->nro[0]->__toString(),
          'bairro' => $informacoesBrutaNFE['dest']->enderDest[0]->xBairro[0]->__toString(),
          'cep' => $informacoesBrutaNFE['dest']->enderDest[0]->CEP[0]->__toString(),
          'cidade' => $informacoesBrutaNFE['dest']->enderDest[0]->xMun[0]->__toString(),
          'uf' => $informacoesBrutaNFE['dest']->enderDest[0]->UF[0]->__toString()
        ]
      ];
    }

    if ($informacoesBrutaNFE['ide']->mod[0]->__toString() === '65') {
      $this->dadosXMLAtual['detalhesDestinatario'] = [
        'nomeDestinatario' => 'CONSUMIDOR FINAL'
      ];
    }

    $valoresTotaisTributos = $informacoesBrutaNFE['total']->ICMSTot[0];

    $this->dadosXMLAtual['informacaoDeValoresDaNota']['totalNota'] += $valoresTotaisTributos->vNF[0];
    $this->dadosXMLAtual['informacaoDeValoresDaNota']['totalICMS'] += $valoresTotaisTributos->vICMS[0];
    $this->dadosXMLAtual['informacaoDeValoresDaNota']['totalST'] += $valoresTotaisTributos->vST[0];
    $this->dadosXMLAtual['informacaoDeValoresDaNota']['vPIS'] += $valoresTotaisTributos->vPIS[0];
    $this->dadosXMLAtual['informacaoDeValoresDaNota']['vCOFINS'] += $valoresTotaisTributos->vCOFINS[0];
    $this->dadosXMLAtual['informacaoDeValoresDaNota']['valorApxImpostosFederais'] += $valoresTotaisTributos->vTotTrib[0];

    foreach ($informacoesBrutaNFE['det'] as $key => $informacaoProduto) {
      $this->dadosXMLAtual['produtos'][$key] = $informacaoProduto;
      if ($this->dadosXMLAtual['produtos'][$key]['prod']) $this->dadosXMLAtual['produtos'][$key]['prod'] = (array)$this->dadosXMLAtual['produtos'][$key]['prod'];
      $this->dadosXMLAtual['produtos'][$key]['imposto'] = $this->trataDadosImpostoProduto($this->dadosXMLAtual['produtos'][$key]['imposto']);
    }
  }

  private function trataDadosImpostoProduto(SimpleXMLElement $imposto): array
  {
    $arrayImposto = (array)$imposto;
    $this->tagImposto = [
      'vTotTrib' => isset($arrayImposto['vTotTrib']) ? $imposto->vTotTrib[0]->__toString() : 0,
      'ICMS' => [
        key($imposto->ICMS) => [],
      ],
      'IPI' => [
        key($imposto->IPI) => [],
      ],
      'II' => [
        key($imposto->II) => [],
      ],
      'PIS' => [
        key($imposto->PIS) => [],
      ],
      'PISST' => [
        key($imposto->PISST) => [],
      ],
      'COFINS' => [
        key($imposto->COFINS) => [],
      ],
      'COFINSST' => [
        key($imposto->COFINSST) => [],
      ],
      'ISSQN' => [
        key($imposto->ISSQN) => [],
      ],
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

    return $this->limparTagImposto($this->tagImposto);
  }

  private function limparTagImposto(array $tagImposto)
  {
    return array_filter($tagImposto, function ($item) {
      // Verifica se o item é um array e se não contém chaves em branco
      return !empty($item) && !array_key_exists('', $item);
    });
  }
}

//['descricao' => '', 'valor' =>  $valor[0]->__toString()]

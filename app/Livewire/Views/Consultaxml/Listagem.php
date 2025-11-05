<?php

namespace App\Livewire\Views\Consultaxml;

use App\Actions\TrataDadosGeraisNotaFiscal;
use App\Models\User;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
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
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleXMLElement;

class Listagem extends Component
{
    use AnalisaCOFINSSTXMLTrait, AnalisaCOFINSXMLTrait, AnalisaICMSUFDestXMLTrait, AnalisaICMSXMLTrait, AnalisaIIXMLTrait, AnalisaInfAdicionalTrait, AnalisaIPIXMLTrait, AnalisaISSQNTXMLTrait, AnalisaPagamentosTrait, AnalisaPISSTXMLTrait, AnalisaPISXMLTrait, WithPagination;

    public Authenticatable|User $usuario;

    public array $dados;

    public array $tagImposto;

    public array $tagPagamento;

    public array $tagInfAdicional = [];

    public Collection $dados_xml;

    public ?array $dadosXMLAtual = null;

    public ?array $dadosXMLAtualCancelado = null;

    public ?array $dadosXMLAtualInutilizado = null;

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
            default => $dados_xml = $dadosXMLRepository->preConsultaDadosXML($this->dados, $this->dados['empresa_id']),
        };

        $dados_xml = $dados_xml->orderBy('dh_emissao_evento', 'asc');

        $dados_xml = $dados_xml->paginate($this->perPage);

        return view('livewire.views.consultaxml.listagem', [
            'listagem' => compact('dados_xml'),
        ]);
    }

    public function resetarCampos(): void
    {
        $this->dados = [];
        $this->tagImposto = [];
        $this->tagPagamento = [];
        $this->tagInfAdicional = [];
        $this->dadosXMLAtual = null;
        $this->dadosXMLAtualCancelado = null;
        $this->dadosXMLAtualInutilizado = null;
    }

    public function selecionaXMLAtual(int $dado_id, DadosXMLRepository $dadosXMLRepository, TrataDadosGeraisNotaFiscal $dadosGeraisNotaFiscal): void
    {
        $dadosXML = $dadosXMLRepository->consultaPorID($dado_id);
        $xml = $dadosXML->getAttribute('status') === 'AUTORIZADO' ? $dadosXML->xml->xml : $dadosXMLRepository->consultaDadosNotaFiscalAutorizada($dadosXML->getAttribute('numeronf'))->xml->xml;
        $informacoesBrutaNFE = $dadosGeraisNotaFiscal->consultaDadosXML(simplexml_load_string($xml)->NFe[0]->infNFe[0]);
        if ($dadosXML->getAttribute('status') === 'CANCELADO') {
            $this->dadosXMLAtualCancelado = $dadosGeraisNotaFiscal->formataDadosXMLEventoCancelamento(simplexml_load_string($dadosXML->xml->xml)->evento[0]->infEvento[0]);
        }

        $this->dadosXMLAtual = [
            'modelo' => '',
            'numero' => '',
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
            'produtos' => [],
            'pagamento' => [],
            'infAdicional' => [],
        ];

        $this->dadosXMLAtual['modelo'] = $informacoesBrutaNFE['ide']->mod[0]->__toString();
        $this->dadosXMLAtual['numero'] = $informacoesBrutaNFE['ide']->nNF[0]->__toString();

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
                'uf' => $informacoesBrutaNFE['emit']->enderEmit[0]->UF[0]->__toString(),
            ],
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
                    'uf' => $informacoesBrutaNFE['dest']->enderDest[0]->UF[0]->__toString(),
                ],
            ];
        }

        if ($informacoesBrutaNFE['ide']->mod[0]->__toString() === '65') {
            $this->dadosXMLAtual['detalhesDestinatario'] = [
                'nomeDestinatario' => 'CONSUMIDOR FINAL',
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
            if ($this->dadosXMLAtual['produtos'][$key]['prod']) {
                $this->dadosXMLAtual['produtos'][$key]['prod'] = (array) $this->dadosXMLAtual['produtos'][$key]['prod'];
            }
            $this->dadosXMLAtual['produtos'][$key]['imposto'] = $this->trataDadosImpostoProduto($this->dadosXMLAtual['produtos'][$key]['imposto']);
        }

        $this->dadosXMLAtual['pagamento'] = $this->trataDadosPagamentoNotaFiscal($informacoesBrutaNFE['pag']);

        $this->dadosXMLAtual['infAdicional'] = $this->trataDadosInfAdicionalNotaFiscal($informacoesBrutaNFE['infAdic']);
    }

    public function selecionaXMLInutilizadoAtual(int $dado_id, DadosXMLRepository $dadosXMLRepository, TrataDadosGeraisNotaFiscal $dadosGeraisNotaFiscal): void
    {
        $this->dadosXMLAtualInutilizado = $dadosGeraisNotaFiscal->formataDadosXMLEventoInutilizado(simplexml_load_string($dadosXMLRepository->consultaPorID($dado_id)->xml->xml));
    }

    public function downloadXML(int $dado_id, DadosXMLRepository $dadosXMLRepository)
    {
        $diretorioUsuario = storage_path('app/tempXMLExportPorUsuario/'.$this->usuario->getAttribute('id'));
        $dadosXML = $dadosXMLRepository->consultaPorID($dado_id);

        if (! file_exists($diretorioUsuario)) {
            mkdir($diretorioUsuario, 0755, true);
        }

        try {
            $caminhoXML = $diretorioUsuario.'/'.$dadosXML->getAttribute('chave').'.xml';

            $xml = fopen($caminhoXML, 'a+');

            if ($xml === false) {
                throw new \Exception('Não foi possível criar o arquivo XML: '.$caminhoXML);
            }

            fwrite($xml, $dadosXML->xml->xml);
            fclose($xml);

            return response()->download($caminhoXML, $dadosXML->getAttribute('chave').'.xml', [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error("Erro ao processar o XML ID: {$dadosXML->xml_id}, Erro: ".$e->getMessage());
        }
    }

    #[Computed]
    public function formataValoresMonetarios(float $valor, string $moeda): string
    {
        $fmtRepo = new NumberFormatRepository;
        $moedaRepo = new CurrencyRepository;
        $fmt = new CurrencyFormatter($fmtRepo, $moedaRepo);

        return $fmt->format($valor, $moeda);
    }

    public function voltar(): void
    {
        redirect('/consultaxml');
    }

    private function trataDadosImpostoProduto(SimpleXMLElement $imposto): array
    {
        $arrayImposto = (array) $imposto;
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
        $tagPagamento = null;
        if (isset($arrayPagamento['detPag'])) {
            if (gettype($arrayPagamento['detPag']) === 'array') {
                foreach ($arrayPagamento['detPag'] as $key => $pag) {
                    $this->analisaCamposPagamentoMultiFormas($pag, 'pag', $key);
                    $this->tagPagamento[$key]['pag']['pag'] = $this->limparTagSuja($this->tagPagamento[$key]['pag']['pag']);
                }

                $tagPagamento = $this->tagPagamento;
            }
            $this->analisaCamposPagamento($arrayPagamento['detPag'], 'pag');
            $this->tagPagamento['pag']['pag'] = $this->limparTagSuja($this->tagPagamento['pag']['pag']);

            $tagPagamento = $this->tagPagamento;
        }

        return $tagPagamento;
    }

    private function trataDadosInfAdicionalNotaFiscal(SimpleXMLElement $infAdicional): array
    {
        $this->defineCamposInfAdicional($infAdicional, 'infAdic');

        return $this->limparTagSuja($this->tagInfAdicional);
    }

    private function limparTagSuja(array $tag)
    {
        $tributosLimpos = array_filter($tag, function ($item) {
            if (gettype($item) === 'array') {
                return ! empty($item) && ! array_key_exists('', $item);
            }
        });

        return $tributosLimpos;
    }
}

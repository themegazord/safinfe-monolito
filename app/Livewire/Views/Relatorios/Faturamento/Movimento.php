<?php

namespace App\Livewire\Views\Relatorios\Faturamento;

use App\Models\DadosXML;
use App\Models\Empresa;
use App\Models\User;
use App\Models\XML;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Movimento extends Component
{
    use Toast;

    public User|Authenticatable $usuario;

    public ?Collection $empresasContador = null;

    public ?Collection $empresasGeral = null;

    public ?Collection $dadosXML = null;

    public ?Collection $XMLs = null;

    public ?array $consulta = [
        'empresa_id' => 3,
        'data_inicio_fim' => '2025-10-01 00:00 até 2025-10-31 00:00',
        'data_inicio' => null,
        'data_fim' => null,
        'modelo' => 'TODAS',
        'status' => 'TODAS',
        'serie' => null,
        'numeroInicial' => null,
        'numeroFinal' => null,
    ];

    public function mount(): void
    {
        $this->usuario = Auth::user();

        $this->consulta['data_inicio'] = date('Y-m-01');
        $this->consulta['data_fim'] = date('Y-m-t');

        if ($this->usuario->getAttribute('role') === 'CONTADOR') {
            $this->empresasContador = $this->usuario->contador->contabilidade->empresas;
            $this->consulta['empresa_id'] = ! is_null($this->empresasContador->first()) ? $this->empresasContador->first()->getAttribute('empresa_id') : null;
        }

        if ($this->usuario->getAttribute('role') === 'ADMIN') {
            $this->empresasGeral = Empresa::query()
                ->get([
                    'empresa_id',
                    'cnpj',
                    'fantasia',
                ]);
            $this->consulta['empresa_id'] = ! is_null($this->empresasGeral->first()) ? $this->empresasGeral->first()->getAttribute('empresa_id') : null;
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

                        if (! $xmlRaw) {
                            return null; // ou log de erro, se necessário
                        }

                        $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
                        $totais = $xml->total[0]->ICMSTot[0];

                        return [
                            'modelo' => $dado->modelo,
                            'serie' => $dado->serie,
                            'numeronf' => $dado->numeronf,
                            'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
                            'destinatario' => $dado->modelo == 55
                                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                                : 'CONSUMIDOR FINAL',
                            'vrdesp' => (float) ($totais->vOutro ?? 0),
                            'vrfrete' => (float) ($totais->vFrete ?? 0),
                            'vrprod' => (float) ($totais->vProd ?? 0),
                            'vrtotal' => (float) ($totais->vNF ?? 0),
                            'situacao' => ucfirst(strtolower($dado->status)),
                            'vripi' => (float) ($totais->vIPI ?? 0),
                            'vrbcicms' => (float) ($totais->vBC ?? 0),
                            'vricms' => (float) ($totais->vICMS ?? 0),
                            'vrfcp' => (float) ($totais->vFCP ?? 0),
                            'vrbcst' => (float) ($totais->vBCST ?? 0),
                            'vrst' => (float) ($totais->vST ?? 0),
                        ];
                    } elseif ($dado->status === 'CANCELADO') {
                        $xmlRaw = DadosXML::query()->where('empresa_id', $dado->empresa_id)->where('numeronf', $dado->numeronf)->where('status', 'AUTORIZADO')->first()->xml->xml;

                        if (! $xmlRaw) {
                            return null; // ou log de erro, se necessário
                        }

                        $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
                        $totais = $xml->total[0]->ICMSTot[0];

                        return [
                            'modelo' => $dado->modelo,
                            'serie' => $dado->serie,
                            'numeronf' => $dado->numeronf,
                            'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
                            'destinatario' => $dado->modelo == 55
                                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                                : 'CONSUMIDOR FINAL',
                            'vrdesp' => (float) ($totais->vOutro ?? 0),
                            'vrfrete' => (float) ($totais->vFrete ?? 0),
                            'vrprod' => (float) ($totais->vProd ?? 0),
                            'vrtotal' => (float) ($totais->vNF ?? 0),
                            'situacao' => ucfirst(strtolower($dado->status)),
                            'vripi' => (float) ($totais->vIPI ?? 0),
                            'vrbcicms' => (float) ($totais->vBC ?? 0),
                            'vricms' => (float) ($totais->vICMS ?? 0),
                            'vrfcp' => (float) ($totais->vFCP ?? 0),
                            'vrbcst' => (float) ($totais->vBCST ?? 0),
                            'vrst' => (float) ($totais->vST ?? 0),
                        ];
                    } else {
                        $xmlModel = DadosXML::query()
                            ->where('empresa_id', $dado->empresa_id)
                            ->where('numeronf', $dado->numeronf)
                            ->where('status', 'INUTILIZADO')
                            ->first();

                        $xmlRaw = $xmlModel?->xml ?? null;

                        if (! $xmlRaw) {
                            return [
                                'modelo' => $dado->modelo,
                                'serie' => $dado->serie,
                                'numeronf' => $dado->numeronf,
                                'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
                                'destinatario' => 'NOTA INUTILIZADA',
                                'vrprod' => (float) ($totais->vProd ?? 0),
                                'vrtotal' => (float) ($totais->vNF ?? 0),
                                'situacao' => ucfirst(strtolower($dado->status)),
                                'vripi' => (float) ($totais->vIPI ?? 0),
                                'vrbcicms' => (float) ($totais->vBC ?? 0),
                                'vricms' => (float) ($totais->vICMS ?? 0),
                                'vrfcp' => (float) ($totais->vFCP ?? 0),
                                'vrbcst' => (float) ($totais->vBCST ?? 0),
                                'vrst' => (float) ($totais->vST ?? 0),
                            ];
                        }

                        $xml = simplexml_load_string($xmlRaw)->NFe[0]->infNFe[0];
                        $totais = $xml->total[0]->ICMSTot[0];

                        return [
                            'modelo' => $dado->modelo,
                            'serie' => $dado->serie,
                            'numeronf' => $dado->numeronf,
                            'data_emissao' => Carbon::parse($dado->dh_emissao_evento)->format('d/m/Y'),
                            'destinatario' => $dado->modelo == 55
                                ? (string) ($xml->dest[0]->xNome[0] ?? '---')
                                : 'CONSUMIDOR FINAL',
                            'vrdesp' => (float) ($totais->vOutro ?? 0),
                            'vrfrete' => (float) ($totais->vFrete ?? 0),
                            'vrprod' => (float) ($totais->vProd ?? 0),
                            'vrtotal' => (float) ($totais->vNF ?? 0),
                            'situacao' => ucfirst(strtolower($dado->status)),
                            'vripi' => (float) ($totais->vIPI ?? 0),
                            'vrbcicms' => (float) ($totais->vBC ?? 0),
                            'vricms' => (float) ($totais->vICMS ?? 0),
                            'vrfcp' => (float) ($totais->vFCP ?? 0),
                            'vrbcst' => (float) ($totais->vBCST ?? 0),
                            'vrst' => (float) ($totais->vST ?? 0),
                        ];
                    }
                })->filter(); // remove possíveis nulls
            });
    }

    public function exportarPDF(): StreamedResponse
    {
        $pdf = Pdf::loadView('components.relatorios.faturamento.movimento-pdf', [
            'dadosXML' => $this->dadosXML,
            'nome_fantasia' => Empresa::query()->find($this->consulta['empresa_id'])->getAttribute('fantasia'),
            'data_inicio' => $this->consulta['data_inicio'],
            'data_fim' => $this->consulta['data_fim'],
        ])->setPaper('a4');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'movimento_' . date('Y-m-d h:i:s') . '.pdf');
    }

    public function exportarXLSX()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:P1')->setCellValue('A1', 'Relatório de Movimento de Notas Fiscais');
        $sheet->mergeCells('A2:P2')->setCellValue('A2', 'Empresa: ' . Empresa::query()->find($this->consulta['empresa_id'])->getAttribute('fantasia'));
        $sheet->mergeCells('A3:P3')->setCellValue('A3', 'Periodo: ' . date('d/m/Y', strtotime($this->consulta['data_inicio'])) . ' - ' . date('d/m/Y', strtotime($this->consulta['data_fim'])));
        $sheet->setCellValue('A4', 'Modelo');
        $sheet->setCellValue('B4', 'Série');
        $sheet->setCellValue('C4', 'Número');
        $sheet->setCellValue('D4', 'Data de Emissão');
        $sheet->setCellValue('E4', 'Nome do Cadastro');
        $sheet->setCellValue('F4', 'Valor de IPI');
        $sheet->setCellValue('G4', 'Valor da Base de ICMS');
        $sheet->setCellValue('H4', 'Valor do ICMS');
        $sheet->setCellValue('I4', 'Valor do FCP');
        $sheet->setCellValue('J4', 'Valor da Base de ICMS ST');
        $sheet->setCellValue('K4', 'Valor do ICMS ST');
        $sheet->setCellValue('L4', 'Valor de outras despesas');
        $sheet->setCellValue('M4', 'Valor do frete');
        $sheet->setCellValue('N4', 'Valor total dos produtos');
        $sheet->setCellValue('O4', 'Valor total');
        $sheet->setCellValue('P4', 'Situação da NFe');

        $sheet->getStyle('A1:P4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'CCCCCC']
            ]
        ]);

        $sheet->getStyle('A4:P4')->applyFromArray([
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        $row = 5;

        foreach ($this->dadosXML as $data => $dadosDia) {
            foreach ($dadosDia as $dado) {
                $sheet->setCellValue("A{$row}", $dado['modelo']);
                $sheet->setCellValue("B{$row}", $dado['serie']);
                $sheet->setCellValue("C{$row}", $dado['numeronf']);
                $sheet->setCellValue("D{$row}", date('d/m/Y', strtotime($dado['data_emissao'])));
                $sheet->setCellValue("E{$row}", $dado['destinatario']);
                $sheet->setCellValue("F{$row}", $dado['vripi']);
                $sheet->setCellValue("G{$row}", $dado['vrbcicms']);
                $sheet->setCellValue("H{$row}", $dado['vricms']);
                $sheet->setCellValue("I{$row}", $dado['vrfcp']);
                $sheet->setCellValue("J{$row}", $dado['vrbcst']);
                $sheet->setCellValue("K{$row}", $dado['vrst']);
                $sheet->setCellValue("L{$row}", $dado['vrdesp']);
                $sheet->setCellValue("M{$row}", $dado['vrfrete']);
                $sheet->setCellValue("N{$row}", $dado['vrprod']);
                $sheet->setCellValue("O{$row}", $dado['vrtotal']);
                $sheet->setCellValue("P{$row}", $dado['situacao']);
                $row++;
            }
        }

        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'movimento_' . date('Y-m-d') . '.xlsx');
    }

    private function zeraInformacoesRelatorios(): void
    {
        foreach (['informacoesTotaisNotas', 'totalNotasPorDiaMes', 'topProdutosVendidos'] as $propriedade) {
            $this->{$propriedade} = null;
        }
    }
}

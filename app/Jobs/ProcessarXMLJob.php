<?php

namespace App\Jobs;

use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessarXMLJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $xmlDecoded;

    protected string $status;

    protected string $empresa;

    public function __construct(string $xmlDecoded, string $status, string $empresa)
    {
        $this->xmlDecoded = $xmlDecoded;
        $this->status = strtoupper($status);
        $this->empresa = $empresa;
        $this->onQueue('alta');
    }

    public function handle(XMLService $xmlService, DadosXMLService $dadosXMLService): void
    {
        $chave = $xmlService->getChaveNotaFiscal($this->xmlDecoded, $this->status);
        $existente = $dadosXMLService->consultaDadosXMLPorChave($chave);

        if (! is_null($existente) && $existente->getAttribute('status') === $this->status) {
            return;
        }

        $xml = $xmlService->cadastroteste($this->xmlDecoded);

        match ($this->status) {
            'AUTORIZADO' => $dadosXMLService->cadastro($this->xmlDecoded, $xml->getAttribute('xml_id'), $this->empresa),
            'CANCELADO' => $dadosXMLService->cadastroCancelado($this->xmlDecoded, $xml->getAttribute('xml_id'), $this->empresa),
            'INUTILIZADO' => $dadosXMLService->cadastroInutilizado(
                $this->xmlDecoded,
                $xml->getAttribute('xml_id'),
                $this->empresa,
                $chave
            ),
            default => throw new \InvalidArgumentException("Status inválido: {$this->status}"),
        };
    }
}

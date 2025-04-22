<?php

namespace App\Jobs;

use App\Services\XMLService;
use App\Services\DadosXMLService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
  }

  public function handle(XMLService $xmlService, DadosXMLService $dadosXMLService): void
  {
    $xml = $xmlService->cadastroteste($this->xmlDecoded);

    match ($this->status) {
      'AUTORIZADO' => $dadosXMLService->cadastro($this->xmlDecoded, $xml->getAttribute('xml_id'), $this->empresa),
      'CANCELADO' => $dadosXMLService->cadastroCancelado($this->xmlDecoded, $xml->getAttribute('xml_id'), $this->empresa),
      'INUTILIZADO' => $dadosXMLService->cadastroInutilizado(
        $this->xmlDecoded,
        $xml->getAttribute('xml_id'),
        $this->empresa,
        $xmlService->getChaveNotaFiscal($this->xmlDecoded, $this->status, $this->empresa)
      ),
      default => throw new \InvalidArgumentException("Status inválido: {$this->status}"),
    };
  }
}

<?php

namespace App\Actions;

use SimpleXMLElement;

class TrataDadosGeraisNotaFiscal
{
  public function consultaDadosXML(SimpleXMLElement $xml): array
  {
    return array_filter([
      'ide' => $xml->ide[0] ?? null,
      'emit' => $xml->emit[0] ?? null,
      'dest' => $xml->dest[0] ?? null,
      'retirada' => $xml->retirada[0] ?? null,
      'entrega' => $xml->entrega[0] ?? null,
      'autXML' => $xml->autXML[0] ?? null,
      'det' => is_null($xml->det[0]) ? null : $this->trataDetalhesNota($xml->det),
      'total' => $xml->total[0] ?? null,
      'transp' => $xml->transp[0] ?? null,
      'cobr' => $xml->cobr[0] ?? null,
      'pag' => $xml->pag[0] ?? null,
      'infIntermed' => $xml->infIntermed[0] ?? null,
      'infAdic' => $xml->infAdic[0] ?? null,
      'exporta' => $xml->exporta[0] ?? null,
      'compra' => $xml->compra[0] ?? null,
      'cana' => $xml->cana[0] ?? null,
    ]);
  }

  public function formataDadosXMLEventoCancelamento(SimpleXMLElement $xml): array
  {
    return [
      'cnpj' => (string) $xml->CNPJ[0],
      'chaveNFe' => (string) $xml->chNFe[0],
      'dh_cancelamento' => (string) $xml->dhEvento[0],
      'justificativa' => (string) $xml->detEvento[0]->xJust[0],
    ];
  }

  public function formataDadosXMLEventoInutilizado(SimpleXMLElement $xml): array
  {
    $infInut = $xml->inutNFe[0]->infInut[0] ?? null;
    $retInutNFe = $xml->retInutNFe[0]->infInut[0] ?? null;

    return [
      'justificativa' => (string) ($infInut->xJust[0] ?? ''),
      'cnpj' => (string) ($retInutNFe->CNPJ[0] ?? ''),
      'modelo' => (string) ($retInutNFe->mod[0] ?? ''),
      'serie' => (string) ($retInutNFe->serie[0] ?? ''),
      'nfInicial' => (string) ($retInutNFe->nNFIni[0] ?? ''),
      'nfFinal' => (string) ($retInutNFe->nNFFin[0] ?? ''),
      'dh_inutilizado' => (string) ($retInutNFe->dhRecbto[0] ?? ''),
    ];
  }

  private function trataDetalhesNota($det): array
  {
    $arrayDetalhes = array();
    foreach ($det as $detalhe) {
      $arrayDetalhes[] = array_filter([
        'prod' => $detalhe->prod,
        'imposto' => $detalhe->imposto,
        'impostoDevol' => $detalhe->impostoDevol,
        'infAdProd' => $detalhe->infAdProd,
        'obsItem' => $detalhe->obsItem,
        'nItem' => $detalhe->nItem,
      ]);
    }
    return $arrayDetalhes;
  }
}

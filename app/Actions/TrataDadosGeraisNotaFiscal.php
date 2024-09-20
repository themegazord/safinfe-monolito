<?php

namespace App\Actions;

class TrataDadosGeraisNotaFiscal
{
  public function consultaDadosXML(\SimpleXMLElement $xml): array
  {
    return array_filter([
      'ide' => is_null($xml->ide[0]) ? null : ($xml->ide[0]),
      'emit' => is_null($xml->emit[0]) ? null : ($xml->emit[0]),
      'dest' => is_null($xml->dest[0]) ? null : ($xml->dest[0]),
      'retirada' => is_null($xml->retirada[0]) ? null : ($xml->retirada[0]),
      'entrega' => is_null($xml->entrega[0]) ? null : ($xml->entrega[0]),
      'autXML' => is_null($xml->autXML[0]) ? null : ($xml->autXML[0]),
      'det' => is_null($xml->det[0]) ? null : $this->trataDetalhesNota($xml->det),
      'total' => is_null($xml->total[0]) ? null : ($xml->total[0]),
      'transp' => is_null($xml->transp[0]) ? null : ($xml->transp[0]),
      'cobr' => is_null($xml->cobr[0]) ? null : ($xml->cobr[0]),
      'pag' => is_null($xml->pag[0]) ? null : ($xml->pag[0]),
      'infIntermed' => is_null($xml->infIntermed[0]) ? null : ($xml->infIntermed[0]),
      'infAdic' => is_null($xml->infAdic[0]) ? null : ($xml->infAdic[0]),
      'exporta' => is_null($xml->exporta[0]) ? null : ($xml->exporta[0]),
      'compra' => is_null($xml->compra[0]) ? null : ($xml->compra[0]),
      'cana' => is_null($xml->cana[0]) ? null : ($xml->cana[0]),
    ]);
  }

  private function trataDetalhesNota(\SimpleXMLElement $det): array
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

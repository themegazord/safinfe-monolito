<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaPISSTXMLTrait
{
  public function defineCamposPISST(SimpleXMLElement $pisST, string $tagPIS)
  {
    foreach ($pisST as $tag => $valor) {
      $this->tagImposto['PIS'][$tagPIS][$tag] = match ($tag) {
        'vBC' => ['descricao' => 'Valor da Base de Cálculo do PIS', 'valor' => $valor[0]->__toString()],
        'pPIS' => ['descricao' => 'Alíquota do PIS (em percentual)', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota do PIS (em reais)', 'valor' => $valor[0]->__toString()],
        'vPIS' => ['descricao' => 'Valor do PIS', 'valor' => $valor[0]->__toString()],
        'indSomaPISST' => ['descricao' => 'Indica se o valor do PISST compõe o valor total da NF-e', 'valor' => $valor[0]->__toString()],
      };
    }
  }
}

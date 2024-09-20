<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaCOFINSSTXMLTrait
{
  public function defineCamposCOFINSST(SimpleXMLElement $cofinsST, string $tagCOFINS)
  {
    foreach ($cofinsST as $tag => $valor) {
      $this->tagImposto['COFINS'][$tagCOFINS][$tag] = match ($tag) {
        'vBC' => ['descricao' => 'Valor da Base de Cálculo da COFINS', 'valor' => $valor[0]->__toString()],
        'pCOFINS' => ['descricao' => 'Alíquota da COFINS (em percentual)', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota da COFINS (em reais)', 'valor' => $valor[0]->__toString()],
        'vCOFINS' => ['descricao' => 'Valor da COFINS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }
}

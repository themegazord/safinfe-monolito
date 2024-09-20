<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaIIXMLTrait
{
  public function defineCamposII(SimpleXMLElement $ii, string $tagII)
  {
    foreach ($ii as $tag => $valor) {
      $this->tagImposto['II'][$tagII][$tag] = match ($tag) {
        'vBC' => ['descricao' => 'Valor BC do Imposto de Importação', 'valor' => $valor[0]->__toString()],
        'vDespAdu' => ['descricao' => 'Valor despesas aduaneiras', 'valor' => $valor[0]->__toString()],
        'vII' => ['descricao' => 'Valor Imposto de Importação', 'valor' => $valor[0]->__toString()],
        'vIOF' => ['descricao' => 'Valor Imposto sobre Operações Financeiras', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }
}

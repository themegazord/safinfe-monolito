<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaPISXMLTrait
{
  public function defineCamposPISAliq(SimpleXMLElement $pisAliq, string $tagPIS)
  {
    foreach ($pisAliq as $tag => $valor) {
      $this->tagImposto['PIS'][$tagPIS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária do PIS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da Base de Cálculo do PIS', 'valor' => $valor[0]->__toString()],
        'pPIS' => ['descricao' => 'Alíquota do PIS (em percentual)', 'valor' => $valor[0]->__toString()],
        'vPIS' => ['descricao' => 'Valor do PIS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposPISQtde(SimpleXMLElement $pisQtde, string $tagPIS)
  {
    foreach ($pisQtde as $tag => $valor) {
      $this->tagImposto['PIS'][$tagPIS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária do PIS', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota do PIS (em reais)', 'valor' => $valor[0]->__toString()],
        'vPIS' => ['descricao' => 'Valor do PIS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposPISNT(SimpleXMLElement $pisNT, string $tagPIS)
  {
    foreach ($pisNT as $tag => $valor) {
      $this->tagImposto['PIS'][$tagPIS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária do PIS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposPISOutr(SimpleXMLElement $pisOutr, string $tagPIS)
  {
    foreach ($pisOutr as $tag => $valor) {
      $this->tagImposto['PIS'][$tagPIS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária do PIS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da Base de Cálculo do PIS', 'valor' => $valor[0]->__toString()],
        'pPIS' => ['descricao' => 'Alíquota do PIS (em percentual)', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota do PIS (em reais)', 'valor' => $valor[0]->__toString()],
        'vPIS' => ['descricao' => 'Valor do PIS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }
}

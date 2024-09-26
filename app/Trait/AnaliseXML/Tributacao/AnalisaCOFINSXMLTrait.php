<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaCOFINSXMLTrait
{
  public function defineCamposCOFINSAliq(SimpleXMLElement $cofinsAliq, string $tagCOFINS)
  {
    foreach ($cofinsAliq as $tag => $valor) {
      $this->tagImposto['COFINS'][$tagCOFINS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária da COFINS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da Base de Cálculo da COFINS', 'valor' => $valor[0]->__toString()],
        'pCOFINS' => ['descricao' => 'Alíquota da COFINS (em percentual)', 'valor' => $valor[0]->__toString()],
        'vCOFINS' => ['descricao' => 'Valor da COFINS', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposCOFINSQtde(SimpleXMLElement $cofinsQtde, string $tagCOFINS)
  {
    foreach ($cofinsQtde as $tag => $valor) {
      $this->tagImposto['COFINS'][$tagCOFINS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária da COFINS', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota da COFINS (em reais)', 'valor' => $valor[0]->__toString()],
        'vCOFINS' => ['descricao' => 'Valor da COFINS', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposCOFINSNT(SimpleXMLElement $cofinsNT, string $tagCOFINS)
  {
    foreach ($cofinsNT as $tag => $valor) {
      $this->tagImposto['COFINS'][$tagCOFINS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária da COFINS', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposCOFINSOutr(SimpleXMLElement $cofinsOutr, string $tagCOFINS)
  {
    foreach ($cofinsOutr as $tag => $valor) {
      $this->tagImposto['COFINS'][$tagCOFINS][$tag] = match ($tag) {
        'CST' => ['descricao' => 'Código de Situação Tributária da COFINS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da Base de Cálculo da COFINS', 'valor' => $valor[0]->__toString()],
        'pCOFINS' => ['descricao' => 'Alíquota da COFINS (em percentual)', 'valor' => $valor[0]->__toString()],
        'qBCProd' => ['descricao' => 'Quantidade Vendida', 'valor' => $valor[0]->__toString()],
        'vAliqProd' => ['descricao' => 'Alíquota da COFINS (em reais)', 'valor' => $valor[0]->__toString()],
        'vCOFINS' => ['descricao' => 'Valor da COFINS', 'valor' => $valor[0]->__toString()],
      };
    }
  }
}

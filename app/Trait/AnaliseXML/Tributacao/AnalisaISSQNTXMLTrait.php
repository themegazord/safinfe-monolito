<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaISSQNTXMLTrait
{
  public function defineCamposISSQN(SimpleXMLElement $issqn, string $tagISSQN)
  {
    foreach ($issqn as $tag => $valor) {
      $this->tagImposto['ISSQN'][$tagISSQN][$tag] = match ($tag) {
        'vBC' => ['descricao' => 'Valor da Base de Cálculo do ISSQN', 'valor' => $valor[0]->__toString()],
        'vAliq' => ['descricao' => 'Alíquota do ISSQN', 'valor' => $valor[0]->__toString()],
        'vISSQN' => ['descricao' => 'Valor do ISSQN', 'valor' => $valor[0]->__toString()],
        'cMunFG' => ['descricao' => 'Código do município de ocorrência do fato gerador do ISSQN', 'valor' => $valor[0]->__toString()],
        'cListServ' => ['descricao' => 'Item da Lista de Serviços', 'valor' => $valor[0]->__toString()],
        'vDeducao' => ['descricao' => 'Valor dedução para redução da Base de Cálculo', 'valor' => $valor[0]->__toString()],
        'vOutro' => ['descricao' => 'Valor outras retenções', 'valor' => $valor[0]->__toString()],
        'vDescIncond' => ['descricao' => 'Valor desconto incondicionado', 'valor' => $valor[0]->__toString()],
        'vDescCond' => ['descricao' => 'Valor desconto condicionado', 'valor' => $valor[0]->__toString()],
        'vISSRet' => ['descricao' => 'Valor retenção ISS', 'valor' => $valor[0]->__toString()],
        'indISS' => ['descricao' => 'Indicador da exigibilidade do ISS', 'valor' => $valor[0]->__toString()],
        'cServico' => ['descricao' => 'Código do serviço prestado dentro do município', 'valor' => $valor[0]->__toString()],
        'cMun' => ['descricao' => 'Código do Município de incidência do imposto', 'valor' => $valor[0]->__toString()],
        'cPais' => ['descricao' => 'Código do País onde o serviço foi prestado', 'valor' => $valor[0]->__toString()],
        'nProcesso' => ['descricao' => 'Número do processo judicial ou administrativo de suspensão da exigibilidade', 'valor' => $valor[0]->__toString()],
        'indIncentivo' => ['descricao' => 'Indicador de incentivo Fiscal', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }
}
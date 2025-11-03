<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaICMSUFDestXMLTrait
{
    public function defineCamposICMSUFDest(SimpleXMLElement $icmsUFDest, string $tagICMSUFDest)
    {
        foreach ($icmsUFDest as $tag => $valor) {
            $this->tagImposto['ICMSUFDest'][$tagICMSUFDest][$tag] = match ($tag) {
                'vBCUFDest' => ['descricao' => 'Valor da Base de Cálculo do ICMS na UF do destinatário.', 'valor' => $valor[0]->__toString()],
                'vBCFCPUFDest' => ['descricao' => 'Valor da Base de Cálculo do FCP na UF do destinatário.', 'valor' => $valor[0]->__toString()],
                'pFCPUFDest' => ['descricao' => 'Percentual adicional inserido na alíquota interna da UF de destino, relativo ao Fundo de Combate à Pobreza (FCP) naquela UF.', 'valor' => $valor[0]->__toString()],
                'pICMSUFDest' => ['descricao' => 'Alíquota adotada nas operações internas na UF do destinatário para o produto / mercadoria.', 'valor' => $valor[0]->__toString()],
                'pICMSInter' => ['descricao' => 'Alíquota interestadual das UF envolvidas:', 'valor' => $this->defineAliqInterUFEnv($valor[0]->__toString())],
                'pICMSInterPart' => ['descricao' => 'Percentual de partilha para a UF do destinatário', 'valor' => $valor[0]->__toString()],
                'vFCPUFDest' => ['descricao' => 'Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP) da UF de destino.', 'valor' => $valor[0]->__toString()],
                'vICMSUFDest' => ['descricao' => 'Valor do ICMS de partilha para a UF do destinatário.', 'valor' => $valor[0]->__toString()],
                'vICMSUFRemet' => ['descricao' => 'Valor do ICMS de partilha para a UF do remetente. Nota: A partir de 2019, este valor será zero', 'valor' => $valor[0]->__toString()],
            };
        }
    }

    private function defineAliqInterUFEnv(string $pICMSInter): string
    {
        return match ($pICMSInter) {
            '4.00' => '4% alíquota interestadual para produtos importados;',
            '7.00' => '7% para os Estados de origem do Sul e Sudeste (exceto ES), destinado para os Estados do Norte e Nordeste  ou ES;',
            '12.00' => '12% para os demais casos.',
        };
    }
}

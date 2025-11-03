<?php

namespace App\Trait\AnaliseXML\InformacaoAdicional;

use SimpleXMLElement;

trait AnalisaInfAdicionalTrait
{
    public function defineCamposInfAdicional(SimpleXMLElement|array $infAdic, string $tagInfAdicional): void
    {
        foreach ($infAdic as $tag => $valor) {
            $this->tagInfAdicional['infAdic'][$tagInfAdicional][$tag] = match ($tag) {
                'infAdFisco' => ['descricao' => 'Informações Adicionais de Interesse do Fisco', 'valor' => $valor[0]->__toString()],
                'infCpl' => ['descricao' => 'Informações Complementares de interesse do Contribuinte', 'valor' => $valor[0]->__toString()],
                default => null,
            };
        }
    }
}

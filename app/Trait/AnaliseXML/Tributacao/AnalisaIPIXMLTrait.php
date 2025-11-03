<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaIPIXMLTrait
{
    public function defineCamposIPI(SimpleXMLElement $ipi, string $tagIPI)
    {
        foreach ($ipi as $tag => $valor) {
            $this->tagImposto['IPI'][$tagIPI][$tag] = match ($tag) {
                'clEnq' => ['descricao' => 'Classe de enquadramento do IPI para Cigarros e Bebidas', 'valor' => $valor[0]->__toString()],
                'CNPJProd' => ['descricao' => 'CNPJ do produtor da mercadoria, quando diferente do emitente', 'valor' => $valor[0]->__toString()],
                'cSelo' => ['descricao' => 'Código do selo de controle IPI', 'valor' => $valor[0]->__toString()],
                'qSelo' => ['descricao' => 'Quantidade de selo de controle', 'valor' => $valor[0]->__toString()],
                'cEnq' => ['descricao' => 'Código de Enquadramento Legal do IPI', 'valor' => $valor[0]->__toString()],
                'CST' => ['descricao' => 'Código da situação tributária do IPI', 'valor' => $valor[0]->__toString()],
                'vBC' => ['descricao' => 'Valor da BC do IPI', 'valor' => $valor[0]->__toString()],
                'pIPI' => ['descricao' => 'Alíquota do IPI', 'valor' => $valor[0]->__toString()],
                'qUnid' => ['descricao' => 'Quantidade total na unidade padrão para tributação', 'valor' => $valor[0]->__toString()],
                'vUnid' => ['descricao' => 'Valor por Unidade Tributável', 'valor' => $valor[0]->__toString()],
                'vIPI' => ['descricao' => 'Valor do IPI', 'valor' => $valor[0]->__toString()],
                default => null,
            };
        }
    }
}

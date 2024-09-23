<?php

namespace App\Trait\AnaliseXML\Pagamento;

use SimpleXMLElement;

trait AnalisaPagamentosTrait {
  public function analisaCamposPagamento(SimpleXMLElement $pag, string $tagPag)
  {
    foreach ($pag as $tag => $valor) {
      $this->tagPagamento['pag'][$tagPag][$tag] = match ($tag) {
        'indPag' => ['descricao' => 'Indicador da Forma de Pagamento', 'valor' => $this->defineIndicadorPagamento($valor[0]->__toString())],
        'tPag' => ['descricao' => 'Forma de pagamento', 'valor' => $this->defineTipoFormaPagamento($valor[0]->__toString())],
        'vPag' => ['descricao' => 'Valor do Pagamento', 'valor' => $valor[0]->__toString()],
        'CNPJ' => ['descricao' => 'CNPJ da Credenciadora de cartão de crédito e/ou débito', 'valor' => $valor[0]->CNPJ[0]->__toString()],
        'tBand' => ['descricao' => 'Bandeira da operadora de cartão de crédito e/ou débito', 'valor' => $this->defineBandeiraCartaoPagamento($valor[0]->tBand[0]->__toString())],
        'cAut' => ['descricao' => 'Número de autorização da operação cartão de crédito e/ou débito', 'valor' => $valor[0]->cAut[0]->__toString()],
        default => null,
      };
    }
  }

  private function defineTipoFormaPagamento(string $tPag): string {
    return match ($tPag) {
      '01' => 'Dinheiro',
      '02' => 'Cheque',
      '03' => 'Cartão de Crédito',
      '04' => 'Cartão de Débito',
      '05' => 'Crédito Loja',
      '10' => 'Vale Alimentação',
      '11' => 'Vale Refeição',
      '12' => 'Vale Presente',
      '13' => 'Vale Combustível',
      '99' => 'Outros',
    };
  }

  private function defineBandeiraCartaoPagamento(string $codBandeira): string {
    return match($codBandeira) {
      '01' => 'Visa',
      '02' => 'Mastercard',
      '03' => 'American Express',
      '04' => 'Sorocred',
      '99' => 'Outros',
    };
  }

  private function defineIndicadorPagamento(string $indPag): string {
    return match($indPag) {
      '0' => 'Pagamento à vista',
      '1' => 'Pagamento a prazo',
      '2' => 'Outros',
    };
  }
}

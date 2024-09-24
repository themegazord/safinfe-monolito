<?php

namespace App\Trait\AnaliseXML\Pagamento;

use SimpleXMLElement;

trait AnalisaPagamentosTrait
{
  public function analisaCamposPagamento(SimpleXMLElement $pag, string $tagPag)
  {
    foreach ($pag as $tag => $valor) {
      $this->tagPagamento['pag'][$tagPag][$tag] = match ($tag) {
        'indPag' => ['descricao' => 'Indicador da Forma de Pagamento', 'valor' => $this->defineIndicadorPagamento($valor[0]->__toString())],
        'tPag' => ['descricao' => 'Forma de pagamento', 'valor' => $this->defineTipoFormaPagamento($valor[0]->__toString())],
        'vPag' => ['descricao' => 'Valor do Pagamento', 'valor' => $valor[0]->__toString()],
        'card' => $this->geraCamposCartao($valor[0]),
        default => null,
      };
    }
  }

  public function geraCamposCartao(SimpleXMLElement $card): array
  {
    return array_filter([
      'CNPJ' => !is_null($card->CNPJ[0]) ? ['descricao' => 'CNPJ da Credenciadora de cartão de crédito e/ou débito', 'valor' => $card[0]->CNPJ[0]->__toString()] : null,
      'tBand' => !is_null($card->tBand[0]) ? ['descricao' => 'Bandeira da operadora de cartão de crédito e/ou débito', 'valor' => $this->defineBandeiraCartaoPagamento($card[0]->tBand[0]->__toString())] : null,
      'cAut' => !is_null($card->cAut[0]) ?['descricao' => 'Número de autorização da operação cartão de crédito e/ou débito', 'valor' => $card[0]->cAut[0]->__toString()] : null,
      'tpIntegra' => !is_null($card->tpIntegra[0]) ? ['descricao' => 'Tipo de integração para pagamento', 'valor' => $this->defineTipoIntegracao($card[0]->tpIntegra[0]->__toString())] : null
    ]);
  }

  private function defineTipoFormaPagamento(string $tPag): string
  {
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
      '15' => 'Boleto Bancário',
      '16' => 'Depósito Bancário',
      '17' => 'Pagamento Instantâneo (PIX)',
      '18' => 'Transferência Bancária, Carteira Digital',
      '19' => 'Programa de Fidelidade, Cashback, Crédito Virtual',
      '90' => 'Sem Pagamento',
      '99' => 'Outros',
    };
  }

  private function defineBandeiraCartaoPagamento(string $codBandeira): string
  {
    return match ($codBandeira) {
      '01' => 'Visa',
      '02' => 'Mastercard',
      '03' => 'American Express',
      '04' => 'Sorocred',
      '05' => 'Diners Club',
      '06' => 'Elo',
      '07' => 'Hipercard',
      '08' => 'Aura',
      '09' => 'Cabal',
      '99' => 'Outros',
    };
  }

  private function defineIndicadorPagamento(string $indPag): string
  {
    return match ($indPag) {
      '0' => 'Pagamento à vista',
      '1' => 'Pagamento a prazo',
      '2' => 'Outros',
    };
  }

  private function defineTipoIntegracao(string $tpIntegra): string {
    return match($tpIntegra) {
      '1' => 'Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico)',
      '2' => 'Pagamento não integrado com o sistema de automação da empresa (Ex.:equipamento POS);',
    };
  }
}

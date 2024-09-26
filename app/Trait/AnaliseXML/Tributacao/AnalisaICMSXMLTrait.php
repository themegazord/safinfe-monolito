<?php

namespace App\Trait\AnaliseXML\Tributacao;

use SimpleXMLElement;

trait AnalisaICMSXMLTrait
{
  // ICMS Tributado

  public function defineCamposICMS00(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 00', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' =>  $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' =>  $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' =>  $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' =>  $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS10(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 00', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' =>  $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' =>  $valor[0]->__toString()],
        'vBCFCP' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' =>  $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' =>  $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' =>  $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' =>  $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP retido por substituicao tributaria.', 'valor' =>  $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' =>  $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor de FCP retido por substituição tributária.', 'valor' =>  $valor[0]->__toString()],
        'vICMSSTDeson' => ['descricao' => 'Valor do ICMS-ST desonerado.', 'valor' =>  $valor[0]->__toString()],
        'motDesICMSST' => ['descricao' => 'Motivo da desoneração do ICMS-ST', 'valor' =>  $this->defineMotivoDesoneracaoICMSST($valor[0]->__toString())],
      };
    }
  }

  public function defineCamposICMS20(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 20', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'vBCFCP' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado.', 'valor' =>  $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' =>  $this->defineMotivoDesoneracaoICMS($valor[0]->__toString())],
      };
    }
  }

  public function defineCamposICMS30(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' =>  $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 30', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $this->defineMotivoDesoneracaoICMS30($valor[0]->__toString())],
      };
    }
  }

  public function defineCamposICMS40(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 40, 41 ou 50', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $this->defineMotivoDesoneracaoICMS40($valor[0]->__toString())],
      };
    }
  }

  public function defineCamposICMS51(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 51', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMSOp' => ['descricao' => 'Valor do ICMS da Operação', 'valor' => $valor[0]->__toString()],
        'pDif' => ['descricao' => 'Percentual do diferimento', 'valor' => $valor[0]->__toString()],
        'vICMSDif' => ['descricao' => 'Valor do ICMS diferido', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS realmente devido', 'valor' => $valor[0]->__toString()],
        'vBCFCP' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'pFCPDif' => ['descricao' => 'Percentual do diferimento do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCPDif' => ['descricao' => 'Valor do diferimento do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCPEfet' => ['descricao' => 'Valor efetivo do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS60(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 60', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor da BC do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'pST' => ['descricao' => 'Aliquota suportada pelo consumidor final.', 'valor' => $valor[0]->__toString()],
        'vICMSSubstituto' => ['descricao' => 'Valor do ICMS Próprio do Substituto cobrado em operação anterior', 'valor' => $valor[0]->__toString()],
        'vBCFCPSTRet' => ['descricao' => 'Valor da Base de cálculo do FCP retido anteriormente por ST.', 'valor' => $valor[0]->__toString()],
        'pFCPSTRet' => ['descricao' => 'Percentual de FCP retido anteriormente por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pRedBCEfet' => ['descricao' => 'Percentual de redução da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'vBCEfet' => ['descricao' => 'Valor da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'pICMSEfet' => ['descricao' => 'Alíquota do ICMS efetiva.', 'valor' => $valor[0]->__toString()],
        'vICMSEfet' => ['descricao' => 'Valor do ICMS efetivo.', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS70(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 70', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'vBCFCP' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $this->defineMotivoDesoneracaoICMS($valor[0]->__toString())],
        'vICMSSTDeson' => ['descricao' => 'Valor do ICMS ST desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS ST', 'valor' => $this->defineMotivoDesoneracaoICMSST($valor[0]->__toString())],
      };
    }
  }

  public function defineCamposICMS90(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS = 90', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'vBCFCP' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCP' => ['descricao' => 'Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'vFCP' => ['descricao' => 'Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'vICMSSTDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMSST' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
      };
    }
  }

  // Partilha de ICMS

  public function defineCamposICMSPart(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP retido por substituicao tributaria.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pBCOp' => ['descricao' => 'Percentual da BC operação própria', 'valor' => $valor[0]->__toString()],
        'UFST' => ['descricao' => 'UF para qual é devido o ICMS ST', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  // ICMS Repasse // ICMSST

  public function defineCamposICMSRepasse(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CST' => ['descricao' => 'Tributação do ICMS', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor do BC do ICMS ST retido na UF remetente', 'valor' => $valor[0]->__toString()],
        'pST' => ['descricao' => 'Aliquota suportada pelo consumidor final.', 'valor' => $valor[0]->__toString()],
        'vICMSSubstituto' => ['descricao' => 'Valor do ICMS Próprio do Substituto cobrado em operação anterior', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido na UF remetente', 'valor' => $valor[0]->__toString()],
        'vBCFCPSTRet' => ['descricao' => 'Informar o valor da Base de Cálculo do FCP retido anteriormente por ST.', 'valor' => $valor[0]->__toString()],
        'pFCPSTRet' => ['descricao' => 'Percentual relativo ao Fundo de Combate à Pobreza (FCP) retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPSTRet' => ['descricao' => 'Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP) retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vBCSTDest' => ['descricao' => 'Valor da BC do ICMS ST da UF destino', 'valor' => $valor[0]->__toString()],
        'vICMSSTDest' => ['descricao' => 'Valor do ICMS ST da UF destino', 'valor' => $valor[0]->__toString()],
        'pRedBCEfet' => ['descricao' => 'Percentual de redução da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'vBCEfet' => ['descricao' => 'Valor da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'pICMSEfet' => ['descricao' => 'Alíquota do ICMS efetivo.', 'valor' => $valor[0]->__toString()],
        'vICMSEfet' => ['descricao' => 'Valor do ICMS efetivo.', 'valor' => $valor[0]->__toString()],
      };
    }
  }


  // Simples Nacional

  public function defineCamposICMSSN101(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMSSN102(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMSSN201(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMSSN202(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMSSN500(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor da BC do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'pST' => ['descricao' => 'Aliquota suportada pelo consumidor final.', 'valor' => $valor[0]->__toString()],
        'vICMSSubstituto' => ['descricao' => 'Valor do ICMS próprio do substituto', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vBCFCPSTRet' => ['descricao' => 'Valor da Base de cálculo do FCP retido anteriormente.', 'valor' => $valor[0]->__toString()],
        'pFCPSTRet' => ['descricao' => 'Percentual de FCP retido anteriormente por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPSTRet' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pRedBCEfet' => ['descricao' => 'Percentual de redução da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'vBCEfet' => ['descricao' => 'Valor da base de cálculo efetiva.', 'valor' => $valor[0]->__toString()],
        'pICMSEfet' => ['descricao' => 'Alíquota do ICMS efetiva.', 'valor' => $valor[0]->__toString()],
        'vICMSEfet' => ['descricao' => 'Valor do ICMS efetivo.', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMSSN900(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $this->defineOrigemMercadoria($valor[0]->__toString())],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $this->defineModalidadeBCICMS($valor[0]->__toString())],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $this->defineModalidadeBCICMSST($valor[0]->__toString())],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCFCPST' => ['descricao' => 'Valor da Base de cálculo do FCP.', 'valor' => $valor[0]->__toString()],
        'pFCPST' => ['descricao' => 'Percentual de FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'vFCPST' => ['descricao' => 'Valor do FCP retido por substituição tributária.', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional). (v2.0)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional) (v2.0)', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  private function defineOrigemMercadoria(string $origem): string {
    return match($origem) {
      '0' => '0 -> Nacional',
      '1' => '1 -> Estrangeira - Importação direta',
      '2' => '2 -> Estrangeira - Adquirida no mercado interno',
      '3' => '3 -> Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% (quarenta por cento)',
      '4' => '4 -> Nacional, cuja produção tenha sido realizada em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei nº 288/1967, e as Leis Federais n.os 8.248/1991, 8.387/1991, 10.176/2001 e 11.484/2007',
      '5' => '5 -> Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40% (quarenta por cento)',
      '6' => '6 -> Estrangeira - Importação direta, sem similar nacional, constante em lista de Resolução CAMEX',
      '7' => '7 -> Estrangeira - Adquirida no mercado interno, sem similar nacional, constante em lista de Resolução CAMEX'
    };
  }

  private function defineModalidadeBCICMS(string $modBC): string {
    return match($modBC) {
      '0' => '0 - Margem Valor Agregado (%);',
      '1' => '1 - Pauta (valor);',
      '2' => '2 - Preço Tabelado Máximo (valor);',
      '3' => '3 - Valor da Operação.',
    };
  }

  private function defineModalidadeBCICMSST(string $modBCST): string {
    return match($modBCST) {
      '0' => '0 - Preço tabelado ou máximo  sugerido;',
      '1' => '1 - Lista Negativa (valor);',
      '2' => '2 - Lista Positiva (valor);',
      '3' => '3 - Lista Neutra (valor);',
      '4' => '4 - Margem Valor Agregado (%);',
      '5' => '5 - Pauta (valor)',
      '6' => '6 - Valor da Operação;',
    };
  }

  private function defineMotivoDesoneracaoICMSST(string $motDesICMSST): string {
    return match($motDesICMSST) {
      '3' => '3 - Uso na agropecuária;',
      '9' => '9 - Outros; ',
      '12' => '12 - Fomento agropecuário.',
    };
  }

  private function defineMotivoDesoneracaoICMS(string $motDesICMS): string {
    return match($motDesICMS) {
      '3' => '3 - Uso na agropecuária;',
      '9' => '9 - Outros; ',
      '12' => '12 - Fomento agropecuário.',
    };
  }

  private function defineMotivoDesoneracaoICMS30(string $motDesICMS): string {
    return match($motDesICMS) {
      '6' => '6 - Utilitários Motocicleta A Área Livre;',
      '7' => '7 - SUFRAMA;',
      '9' => '9 - Outros',
    };
  }

  private function defineMotivoDesoneracaoICMS40(string $motDesICMS): string {
    return match($motDesICMS) {
      '1' => '1 - Táxi;',
      '3' => '3 - Produtor Agropecuário;',
      '4' => '4 - Frotista/Locadora;',
      '5' => '5 - Diplomático/Consular;',
      '6' => '6 - Utilitários e Motocicletas da Amazônia Ocidental e Áreas de Livre Comércio (Resolução 714/88 e 790/94 – CONTRAN e suas alterações);',
      '7' => '7 - SUFRAMA;',
      '8' => '8 - Venda a órgão Público;',
      '9' => '9 - Outros',
      '10' => '10- Deficiente Condutor',
      '11' => '11- Deficiente não condutor',
      '16' => '16 - Olimpíadas Rio 2016',
      '90' => '90 - Solicitado pelo Fisco',
    };
  }
}

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
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 00', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' =>  $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' =>  $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS10(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 00', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' =>  $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' =>  $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' =>  $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' =>  $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' =>  $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS20(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 20', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
      };
    }
  }

  public function defineCamposICMS30(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 30', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMS40(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 40, 41 ou 50', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMS51(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 51', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMSOp' => ['descricao' => 'Valor do ICMS da Operação', 'valor' => $valor[0]->__toString()],
        'pDif' => ['descricao' => 'Percentual do diferimento', 'valor' => $valor[0]->__toString()],
        'vICMSDif' => ['descricao' => 'Valor do ICMS diferido', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS realmente devido', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMS60(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 60', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor da BC do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMS70(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 70', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMS90(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS = 90', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vICMSDeson' => ['descricao' => 'Valor do ICMS desonerado', 'valor' => $valor[0]->__toString()],
        'motDesICMS' => ['descricao' => 'Motivo da desoneração do ICMS', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  // Partilha de ICMS

  public function defineCamposICMSPart(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'pBCOp' => ['descricao' => 'Percentual da BC operação própria', 'valor' => $valor[0]->__toString()],
        'UFST' => ['descricao' => 'UF para qual é devido o ICMS ST', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  // ICMS Repasse // ICMSST

  public function defineCamposICMSRepasse(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CST' => ['descricao' => 'Tributação do ICMS', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor do BC do ICMS ST retido na UF remetente', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido na UF remetente', 'valor' => $valor[0]->__toString()],
        'vBCSTDest' => ['descricao' => 'Valor da BC do ICMS ST da UF destino', 'valor' => $valor[0]->__toString()],
        'vICMSSTDest' => ['descricao' => 'Valor do ICMS ST da UF destino', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }


  // Simples Nacional

  public function defineCamposICMSSN101(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMSSN102(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMSSN201(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMSSN202(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pCredSN' => ['descricao' => 'Alíquota aplicável de cálculo do crédito (Simples Nacional)', 'valor' => $valor[0]->__toString()],
        'vCredICMSSN' => ['descricao' => 'Valor crédito do ICMS que pode ser aproveitado', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMSSN500(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'vBCSTRet' => ['descricao' => 'Valor da BC do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        'vICMSSTRet' => ['descricao' => 'Valor do ICMS ST retido', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }

  public function defineCamposICMSSN900(SimpleXMLElement $icms, string $tagICMS)
  {
    foreach ($icms as $tag => $valor) {
      $this->tagImposto['ICMS'][$tagICMS][$tag] = match ($tag) {
        'orig' => ['descricao' => 'Origem da mercadoria', 'valor' => $valor[0]->__toString()],
        'CSOSN' => ['descricao' => 'Código de Situação da Operação – Simples Nacional', 'valor' => $valor[0]->__toString()],
        'modBC' => ['descricao' => 'Modalidade de determinação da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'vBC' => ['descricao' => 'Valor da BC do ICMS', 'valor' => $valor[0]->__toString()],
        'pRedBC' => ['descricao' => 'Percentual da Redução de BC', 'valor' => $valor[0]->__toString()],
        'pICMS' => ['descricao' => 'Alíquota do imposto', 'valor' => $valor[0]->__toString()],
        'vICMS' => ['descricao' => 'Valor do ICMS', 'valor' => $valor[0]->__toString()],
        'modBCST' => ['descricao' => 'Modalidade de determinação da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pMVAST' => ['descricao' => 'Percentual da margem de valor Adicionado do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pRedBCST' => ['descricao' => 'Percentual da Redução de BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vBCST' => ['descricao' => 'Valor da BC do ICMS ST', 'valor' => $valor[0]->__toString()],
        'pICMSST' => ['descricao' => 'Alíquota do imposto do ICMS ST', 'valor' => $valor[0]->__toString()],
        'vICMSST' => ['descricao' => 'Valor do ICMS ST', 'valor' => $valor[0]->__toString()],
        default => null,
      };
    }
  }
}

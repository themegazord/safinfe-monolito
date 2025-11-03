<?php

namespace App\Exceptions\Tipos;

enum EnumTiposValidacao: string
{
    case ValidacaoDeContagemDeCampos = 'Validação de contagem de campos';
    case AlteracaoSchemaXLSX = 'Alteracao no schema do XLSX';
    case CampoObrigatorio = 'Campo obrigatório';
    case IntegridadeDoCampo = 'Integridade do campo';
}

<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class XMLException extends Exception
{
  public static function statusNaoInformado(): self {
    throw new self("O status não foi informado.", Response::HTTP_BAD_REQUEST);
  }

  public static function statusInvalido(string $status): self {
    throw new self("O status {$status} é inválido, consultar documentação.", Response::HTTP_BAD_REQUEST);
  }

  public static function empresaNaoInformada(): self {
    throw new self("A empresa não foi informada.", Response::HTTP_BAD_REQUEST);
  }

  public static function empresaComQuantidadeErradaDeCaracteres(string $cnpj): self {
    throw new self("O CNPJ informado {$cnpj} não contêm os 14 caracteres válidos para um CNPJ.", Response::HTTP_BAD_REQUEST);
  }

  public static function empresaNaoCadastrada(string $cnpj): self {
    throw new self("O CNPJ {$cnpj} não existe na base de dados.", Response::HTTP_NOT_FOUND);
  }

  public static function arquivoNaoInformado(): self {
    throw new self("O arquivo não foi enviado.", Response::HTTP_BAD_REQUEST);
  }

  public static function tipoDoArquivoErrado(): self {
    throw new self("O unico tipo de arquivo aceitavel é o XML.", Response::HTTP_BAD_REQUEST);
  }

  public static function empresaAPIXMLIncondizente(string $cnpjAPI, string $cnpjXML): self {
    throw new self("O CNPJ informado na API {$cnpjAPI} não condiz com o CNPJ emitente da nota fiscal {$cnpjXML}.", Response::HTTP_UNPROCESSABLE_ENTITY);
  }

  public static function xmlJaExistente(): self {
    throw new self("O XML já foi enviado.", Response::HTTP_CONFLICT);
  }
}

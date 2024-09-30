<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AutenticacaoException extends Exception
{
  public static function emailSenhaIncondizentes(string $email): self {
    throw new self("O email {$email} e a senha informada não condizem com os dados.", Response::HTTP_CONFLICT);
  }

  public static function usuarioNaoIdentificado(): self {
    throw new self("O usuário não foi identificado no sistema.", Response::HTTP_NOT_FOUND);
  }
}

<?php

namespace App\Services;

use App\Repositories\Interface\IXML;
use Illuminate\Database\Eloquent\Model;

class XMLService
{
  public function __construct(
    private readonly IXML $xmlRepository
  ) {}

  public function cadastro(string $arquivo): Model
  {
    return $this->xmlRepository->cadastro([
      'xml' => file_get_contents($arquivo)
    ]);
  }
}

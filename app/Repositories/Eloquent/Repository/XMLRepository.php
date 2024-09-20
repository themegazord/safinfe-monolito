<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\XML;
use App\Repositories\Interface\IXML;
use Illuminate\Database\Eloquent\Model;

class XMLRepository implements IXML {
  public function cadastro(array $dados): Model
  {
    return XML::query()
      ->create($dados);
  }

  public function consultaPorId(int $xml_id): ?Model
  {
    return XML::query()
      ->where('xml_id', $xml_id)
      ->first([
        'xml_id',
        'xml'
      ]);
  }
}

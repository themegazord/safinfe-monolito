<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

interface IXML {
  public function cadastro(array $dados): Model;
}

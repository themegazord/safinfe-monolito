<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versionamento extends Model
{
  use HasFactory;

  protected $primaryKey = 'versionamento_id';
  protected $table = 'versionamento';
  protected $fillable = [
    'patch',
    'detalhe'
  ];
}

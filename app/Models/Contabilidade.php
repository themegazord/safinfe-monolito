<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contabilidade extends Model
{
  use HasFactory;

  protected $primaryKey = "contabilidade_id";

  protected $fillable = [
    'endereco_id',
    'social',
    'cnpj',
    'telefone_corporativo',
    'email_corporativo',
    'email_contato',
    'telefone_contato',
    'telefone_reserva',
  ];

  public function endereco(): BelongsTo {
    return $this->belongsTo(Endereco::class, 'endereco_id', 'endereco_id');
  }
}

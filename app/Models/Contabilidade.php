<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

  public function contadores(): HasMany {
    return $this->hasMany(Contador::class, 'contabilidade_id', 'contabilidade_id');
  }

  public function empresas(): BelongsToMany {
    return $this->belongsToMany(Empresa::class, 'empcont', 'contabilidade_id', 'empresa_id');
  }
}

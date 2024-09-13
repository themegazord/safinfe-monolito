<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
  use HasFactory;

  protected $primaryKey = 'empresa_id';

  protected $fillable = [
    'endereco_id',
    'fantasia',
    'social',
    'cnpj',
    'ie',
    'email_contato',
    'telefone_contato',
    'telefone_reserva',
  ];

  public function endereco(): BelongsTo {
    return $this->belongsTo(Endereco::class, 'endereco_id', 'endereco_id');
  }

  public function dadosXML(): HasMany {
    return $this->hasMany(DadosXML::class, 'dados_id', 'dados_id');
  }
}

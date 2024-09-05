<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
  use HasFactory;

  protected $primaryKey = 'cliente_id';

  protected $fillable = [
    'usuario_id',
    'empresa_id',
    'nome',
    'email',
    'senha'
  ];

  public function empresa(): BelongsTo {
    return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
  }

  public function usuario(): BelongsTo {
    return $this->belongsTo(User::class, 'usuario_id', 'id');
  }
}

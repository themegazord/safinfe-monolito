<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contadores';

    protected $primaryKey = 'contador_id';

    protected $fillable = [
        'usuario_id',
        'contabilidade_id',
        'nome',
        'email',
        'cpf',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function contabilidade(): BelongsTo
    {
        return $this->belongsTo(Contabilidade::class, 'contabilidade_id', 'contabilidade_id');
    }
}

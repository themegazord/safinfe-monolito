<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DadosXML extends Model
{
    use HasFactory;

    protected $table = 'dados_xml';

    protected $primaryKey = 'dados_id';

    protected $fillable = [
        'xml_id',
        'empresa_id',
        'status',
        'modelo',
        'serie',
        'numeronf',
        'numeronf_final',
        'justificativa',
        'dh_emissao_evento',
        'chave',
    ];

    public function xml(): BelongsTo
    {
        return $this->belongsTo(XML::class, 'xml_id', 'xml_id');
    }

    public function empresa_id(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}

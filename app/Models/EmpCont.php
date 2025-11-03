<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpCont extends Model
{
    use HasFactory;

    protected $table = 'empcont';

    protected $primaryKey = 'empcont_id';

    protected $fillable = [
        'empresa_id',
        'contabilidade_id',
    ];
}

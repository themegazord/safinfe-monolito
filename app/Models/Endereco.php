<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $primaryKey = 'endereco_id';

    protected $fillable = [
        'rua',
        'numero',
        'cep',
        'bairro',
        'complemento',
        'cidade',
        'estado',
    ];
}

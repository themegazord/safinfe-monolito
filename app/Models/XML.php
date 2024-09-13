<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class XML extends Model
{
  use HasFactory;

  protected $table = 'xmls';
  protected $primaryKey = 'xml_id';
  protected $fillable = [
    'xml'
  ];

  public function dados(): HasOne {
    return $this->hasOne(DadosXML::class, 'xml_id', 'xml_id');
  }
}

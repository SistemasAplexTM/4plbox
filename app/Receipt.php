<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
  public $table = "factura";

  protected $fillable = [
      'agencia_id',
      'consignee_id',
      'usuario_id',
      'numero_recibo',
      'cliente',
      'cliente_datos',
      'transportador',
  ];
}

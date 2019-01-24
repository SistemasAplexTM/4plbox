<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
  public $table = "factura_detalle";

  protected $fillable = [
      'factura_id',
      'documento_detalle_id',
      'entregado',
      'cantidad',
      'trackings',
      'observacion'
  ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDetailCuba extends Model
{
  public $table = "productos_detalle_cuba";

  protected $fillable = [
      'articulo_id',
      'descripcion',
      'cantidad',
      'documento_id',
  ];
}

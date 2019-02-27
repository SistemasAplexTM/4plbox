<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PivotPuntosDetalle extends Model
{
  public $table = "pivot_puntos_detalle";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'puntos_id', 'documento_detalle_id', 'cantidad', 'total_puntos'
  ];
}

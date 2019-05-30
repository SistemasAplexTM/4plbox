<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadicadoDetalle extends Model
{
  public $table = "_radicado_detalle";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'radicado_id',
      'cantidad',
      'descripcion'
  ];

  public function radicado()
  {
      return $this->belongsTo('App\Radicado', 'radicado_id', 'id');
  }
}

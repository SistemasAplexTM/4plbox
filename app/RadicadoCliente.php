<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadicadoCliente extends Model
{
  public $table = "_radicado_clientes";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'documento',
      'nombre',
      'direccion',
      'telefono',
      'correo'
  ];
}

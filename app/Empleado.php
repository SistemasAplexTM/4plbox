<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
  public $table = "empleados";
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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radicado extends Model
{
  public $table = "_radicado";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'cliente_id',
      'empleado_id',
      'fecha'
  ];

  public function empleado()
  {
   return $this->belongsTo('App\Empleado', 'empleado_id')
   ->select(['id', 'nombre', 'direccion', 'telefono', 'correo']);
  }

  public function cliente()
  {
   return $this->belongsTo('App\RadicadoCliente', 'cliente_id')
   ->select(['id', 'nombre', 'direccion', 'telefono', 'correo']);
  }

  public function usuario()
  {
   return $this->belongsTo('App\User', 'usuario_id')->select(['id', 'name AS nombre']);
  }

  public function agencia()
  {
   return $this->belongsTo('App\Agencia', 'agencia_id')
   ->select(['id', 'descripcion AS nombre', 'direccion', 'telefono', 'logo']);
  }
}

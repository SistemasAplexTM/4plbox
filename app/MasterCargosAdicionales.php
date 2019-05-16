<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCargosAdicionales extends Model
{
  public $table = "master_cargos_adicionales";

  protected $fillable = [
      'id',
      'master_id',
      'descripcion',
      'agent_carrier',
      'valor'
  ];
}

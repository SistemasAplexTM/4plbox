<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgenciaDetalle extends Model
{
    public $table = "agencia_detalle";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        /*'agencia_id',
        'servicios_id',
        'tarifa_principal',*/
        'tarifa_agencia',
        'seguro',
        'tarifa_minima'
    ];
}

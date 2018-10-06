<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    public $table = "servicios";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'tarifa',
        'cobro_opcional',
        'cobro_peso_volumen',
        'peso_minimo',
        'seguro',
        'impuesto',
        'tipo_embarque_id',
        'posicion_arancel_id',
    ];
}

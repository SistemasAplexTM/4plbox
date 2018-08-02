<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AerolineasAeropuertos extends Model
{
    public $table = "aerolineas_aeropuertos";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'localizacion_id', 'direccion', 'zip', 'codigo', 'tipo'
    ];
}

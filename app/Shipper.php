<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    public $table = "shipper";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agencia_id',
        'localizacion_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'telefono',
        'correo',
        'zip',
        'nombre_full',
        'nombre_old'
    ];
}

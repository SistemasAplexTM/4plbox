<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transportador extends Model
{
    public $table = "transportador";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'direccion',
        'telefono',
        'email',
        'contacto',
        'ciudad',
        'estado',
        'pais',
        'zip',
        'shipper',
        'consignee',
        'carrier',
    ];
}

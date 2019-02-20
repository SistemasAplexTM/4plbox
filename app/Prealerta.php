<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prealerta extends Model
{
    public $table = "prealerta";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consignee_id',
        'agencia_id',
        'tracking',
        'contenido',
        'instruccion',
        'correo',
        'telefono',
        'despachar',
        'recibido',
    ];
}

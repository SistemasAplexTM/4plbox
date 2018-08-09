<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    public $table = "consignee";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_identificacion_id',
        'agencia_id',
        'localizacion_id',
        'documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'telefono',
        'celular',
        'correo',
        'zip',
        'tarifa',
        'po_box',
        'estatus',
        'nombre_full',
        'casillero',
        'password_casillero',
        'direccion2',
        'acepta_condiciones',
        'recibir_info',
        'cliente_id',
    ];
}

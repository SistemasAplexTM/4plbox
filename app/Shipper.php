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
        'documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'telefono',
        'whatsapp',
        'email_cc',
        'correo',
        'zip',
        'nombre_full',
        'nombre_old',
        'corporativo',
        'parent_id',
    ];

    public function city()
    {
        return $this->belongsTo('App\Ciudad', 'localizacion_id', 'id')->select(['id', 'nombre']);
    }
}

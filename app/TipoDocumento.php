<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    public $table = "tipo_documento";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'prefijo',
        'icono',
        'consecutivo_inicial',
        'funcionalidades',
        'credenciales',
        'email_plantilla_id',
        'email_copia',
        'email_copia_oculta',
    ];
}

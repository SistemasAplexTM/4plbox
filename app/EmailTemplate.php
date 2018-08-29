<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public $table = "plantillas_correo";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agencia_id', 
        'nombre', 
        'subject', 
        'mensaje', 
        'descripcion_plantilla',
        'otros_destinatarios',
        'enviar_archivo',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusReport extends Model
{
    public $table = "status_detalle";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_id',
        'usuario_id',
        'documento_detalle_id',
        'codigo',
        'fecha_status',
        'observacion',
        'transportadora',
        'num_transportadora',
        'created_at',
        'deleted_at'
    ];
}

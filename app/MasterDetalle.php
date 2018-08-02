<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterDetalle extends Model
{
    public $table = "master_detalle";
    
    protected $fillable = [
        'id',
        'master_id',
        'piezas',
        'peso',
        'peso_kl',
        'unidad_medida',
        'rate_class',
        'commodity_item',
        'peso_cobrado',
        'tarifa',
        'total',
        'descripcion'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AerolineaInventario extends Model
{
    protected $table = 'aerolineas_inventario';
    protected $fillable = [
    	'id',
    	'aerolinea_id',
    	'consecutivo_creacion',
        'guia',
        'usado'
    ];
}

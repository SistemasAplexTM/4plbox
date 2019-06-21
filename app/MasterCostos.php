<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCostos extends Model
{
    public $table = "master_costos";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'master_id',
        'moneda_id',
        'costos_id',
        'descripcion',
        'valor',
        'trm',
        'costo_gasto',
    ];
}

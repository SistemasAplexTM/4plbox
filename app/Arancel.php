<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arancel extends Model
{
    public $table = "posicion_arancelaria";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pa', 
        'descripcion',
        'arancel',
        'iva',
    ];
}

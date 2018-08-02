<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaestraMultiple extends Model
{
    public $table = "maestra_multiple";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'modulo_id',
    ];
}

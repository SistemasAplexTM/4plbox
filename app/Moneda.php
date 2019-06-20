<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    public $table = "moneda";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moneda',
        'codigo_iso',
        'simbolo',
        'descripcion',
    ];
}

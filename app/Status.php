<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = "status";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'descripcion_general',
        'color',
        'icon',
        'email',
        'view_client',
        'transportadora',
        'json_data',
    ];
}

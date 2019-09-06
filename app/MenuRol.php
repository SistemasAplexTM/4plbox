<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuRol extends Model
{
    protected $table = "aplex_menu_rol";
    public $timestamps = false;

    protected $fillable = ['menu_id', 'rol_id', 'user_id'];
}

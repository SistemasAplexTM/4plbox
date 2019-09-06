<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = "aplex_menu";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'route', 'parent', 'order_item', 'meta', 'roles', 'users'
    ];

    public function roles()
    {
      return $this->hasMany('App\MenuRol', 'menu_id')->select('rol_id AS id', 'menu_id');
    }

}

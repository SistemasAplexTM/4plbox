<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AplexConfig extends Model
{
   	public $table = "aplex_config";
   	protected $fillable = [
        'id','key', 'value', 'created_at'
    ];
}

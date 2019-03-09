<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportadorasLocales extends Model
{
  public $table = "transportadoras_locales";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nombre',
      'url_rastreo',
      'pais_id'
  ];
}

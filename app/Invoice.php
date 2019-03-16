<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  public $table = "invoice";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'agency_id',
      'consecutive',
      'date_document',
      'shipper_id',
      'consignee_id',
      'observation'
  ];
}

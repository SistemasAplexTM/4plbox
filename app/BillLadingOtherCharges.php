<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillLadingOtherCharges extends Model
{
    public $table = "bill_lading_other_charges";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bill_lading_id', 
        'description',
        'ammount_pp',
        'ammount_cll',
        'created_at',
        'updated_at',
    ];
}

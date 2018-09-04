<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillLadingDetail extends Model
{
    public $table = "bill_lading_detail";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bill_lading_id', 
        'marks_numbers',
        'number_packages',
        'description',
        'gross_weight',
        'measurement',
        'created_at',
        'updated_at',
    ];
}

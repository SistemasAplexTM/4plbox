<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillLading extends Model
{
    public $table = "bill_lading";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agencia_id', 
        'users_id',
        'num_bl',
        'date_document',
        'exporter_id',
        'consignee_id',
        'notify_party',
        'document_number',
        'export_references',
        'forwarding_agent',
        'point_origin',
        'domestic_routing',
        'loading_pier',
        'containered',
        'pre_carriage_by',
        'place_of_receipt',
        'exporting_carrier',
        'port_loading',
        'foreign_port',
        'placce_delivery',
        'agent_for_carrier',
        'created_at',
    ];
}

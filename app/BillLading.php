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
        'document_number',
        'num_bl',
        'export_references',
        'exporter_id',
        'consignee_id',
        'date_document',
        'forwarding_agent',
        'export_references2',
        'notify_party',
        'pre_carriage_by',
        'place_of_receipt',
        'domestic_routing',
        'exporting_carrier',
        'port_loading',
        'loading_pier',
        'foreign_port',
        'placce_delivery',
        'type_move',
        'containered',
        'point_origin',
        'agent_for_carrier',
        'created_at',
    ];
}

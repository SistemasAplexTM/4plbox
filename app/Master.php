<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    public $table = "master";

    protected $fillable = [
        'aerolinea_inventario_id',
        'shipper_id',
        'shipper',
        'consignee_id',
        'consignee',
        'carrier_id',
        'carrier',
        'agencia_id',
        'aerolineas_id',
        'aeropuertos_id',
        'aeropuertos_id_destino',
        'num_master',
        'account_information',
        'agent_iata_code',
        'num_account',
        'reference_num',
        'optional_shipping_info',
        'to1',
        'to2',
        'to3',
        'by1',
        'by2',
        'by_first_carrier',
        'currency',
        'chgs_code',
        'dec_value_carriage',
        'dec_value_customs',
        'fecha_vuelo1',
        'fecha_vuelo2',
        'amount_insurance',
        'handing_information',
        'master_detail',
        'sci',
        'weight_charge',
        'valuation_charge',
        'tax',
        'total_other_charge_due_agent',
        'total_other_charge_due_carrier',
        'total_prepaid',
        'total_collect',
        'currency_conversion',
        'charges_dest_currency',
        'charges_at_destination',
        'other_charges',
        'signature_shipper_agent',
        'total_collect_charges',
        'created_at',
        'deleted_at',
        'usuario_id',
        'master_id',
        'update_at',
        'user_update'
    ];
}

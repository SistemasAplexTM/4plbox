<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    public $table = "documento";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_documento_id',
        'ciudad_id',
        'central_destino_id',
        'servicios_id',
        'numero_interno',
        'fecha_vuelo',
        'numero_vuelo',
        'master',
        'shipper_id',
        'consignee_id',
        'agencia_id',
        'usuario_id',
        'transporte_id',
        'consecutivo',
        'piezas',
        'peso',
        'volumen',
        'peso_cobrado',
        'observaciones',
        'valor',
        'valor_libra',
        'flete',
        'seguro',
        'seguro_cobrado',
        'cargos_add',
        'descuento',
        'total',
        'num_guia',
        'consolidado',
        'valor_declarado',
        'pa_aduana',
        'num_consolidado',
        'user_update',
        'impuesto',
        'factura',
        'carga_peligrosa',
        're_empacado',
        'mal_empacado',
        'rota',
        'estado',
        'num_warehouse',
        'pago',
        'paquete',
        'carga_courier',
        'tipo_consolidado',
        'created_at'
    ];
}

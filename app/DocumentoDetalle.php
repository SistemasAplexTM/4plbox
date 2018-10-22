<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoDetalle extends Model
{
    public $table = "documento_detalle";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipper_id',
        'consignee_id',
        'documento_id',
        'tipo_empaque_id',
        'posicion_arancelaria_id',
        'status_id',
        'piezas',
        'dimensiones',
        'largo',
        'ancho',
        'alto',
        'contenido',
        'contenido2',
        'tracking',
        'volumen',
        'valor',
        'pago',
        'peso',
        'peso2',
        'declarado2',
        'arancel_id2',
        'created_at'
    ];
}

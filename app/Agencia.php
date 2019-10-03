<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    public $table = "agencia";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'responsable',
        'direccion',
        'zip',
        'localizacion_id',
        'telefono',
        'whatsapp',
        'observacion',
        'logo',
        'tipo_agencia',
        'prefijo_pobox',
        'file_id',
        'email',
        'email_cc',
        'email_host',
        'email_port',
        'email_encriptyon',
        'email_username',
        'email_password',
        'email_paypal',
        'compra_porcentaje_comision',
        'compra_minima_comision',
        'compra_rango_comision',
        'compra_rango_comision2',
        'zopim',
        'usar_mail_chimp',
        'usar_zopim',
        'amazon_id',
        'terminos_condiciones',
        'usar_paypal',
        'url',
        'url_terms',
        'url_casillero',
        'url_registro_casillero',
        'url_prealerta',
    ];
}

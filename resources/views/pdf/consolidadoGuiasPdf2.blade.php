<style>
    *{
        font-size: 13px;
        font-family: sans-serif;
        color: #1d68a4;
    }
    #table_content{
        width: 100%;
        /*margin-top: -10px;*/
    }
    #spaceTable{
        border-bottom: dashed 1px #000;
        margin-bottom: 5px;
    }
    .agencia{
        text-align: center;
    }
    .guia{
        text-align: center;
        font-size: 20px;
    }
    #shipper, #consignee{
        margin-left: 10px;
        color: #1d1d1e;
    }
</style>
<?php
$cont = 0;
$contRegistros = 0;
$toalRegistros = count($detalleConsolidado);
?>

@if($detalleConsolidado != '')
    @foreach ($detalleConsolidado as $value)
        <?php
        $shipper_json = '';
        $consignee_json = '';
        $cont++;
        $contRegistros++;
        if($value->shipper_json != ''){
            $shipper_json = json_decode($value->shipper_json);
        }
        if($value->consignee_json != ''){
            $consignee_json = json_decode($value->consignee_json);
        }
        ?>
                    <table border="0" id="table_content" cellspacing="0" cellpadding="0"  <?php if ($cont === 2): ?>
                   style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;margin-bottom: 0px;<?php else: ?>always<?php endif; ?>"
                   <?php
                   $cont = 0;
               endif;
               ?>>
                        <thead>
                            <tr>
                                <th colspan="2" width="300px"><img alt="image" class="img-circle" id="logo" height="120px" style="width: 100%;margin-bottom: 10px;" src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? $documento->agencia_logo : 'logo.png') }}"/></th>
                                <th width="250px" style="text-align: right;">
                                    <div class="agencia" id="nomAgencia" style="font-size: 20px;">{{ $documento->agencia }}</div>
                                    <div class="agencia" id="dirAgencia"><span style="color: #1d1d1e;">{{ $documento->agencia_dir }} - {{ $documento->agencia_ciudad }} - {{ $documento->agencia_depto }}</span></div>
                                    <div class="agencia" id="telAgencia">Teléfono: <span style="color: #1d1d1e;">{{ $documento->agencia_tel }}</span></div>
                                    <div class="agencia" id="telAgencia">Zip: <span style="color: #1d1d1e;">{{ $documento->agencia_zip }}</span></div>
                                </th>
                                <th>
                                    <div class="guia">GUIA AWB</div>
                                    <div class="guia" style="color: #1d1d1e;">{{ $value->num_guia }}</div>
                                    <div class="" style="color: #1d1d1e;text-align: center;margin-top: 10px;font-size: 12px;">Fecha: {{ substr($documento->created_at, 0, 10) }}</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    <div style="">
                                        <table border="0" id="" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <th colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">From Shipper - Remitente</th>
                                                <th colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">To Consigned - Consignatario</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="width: 50%;border-right: 1px solid #ccc;">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="shipper" style="font-weight: bold;font-size: 15px;">{{ ($value->shipper_json != '') ? $shipper_json->nombre : ((isset($value->ship_nomfull)) ? $value->ship_nomfull : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="margin-left: 10px;">Street Address - Dirección</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->direccion : ((isset($value->ship_dir)) ? $value->ship_dir : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%">
                                                                <div style="margin-left: 10px;">Phone - Teléfono</div>
                                                            </td>
                                                            <td>
                                                                <div style="margin-left: 10px;">City - Ciudad - State - Estado</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->telefono : ((isset($value->ship_tel) and $value->ship_tel != '') ? $value->ship_tel : '&nbsp;') }}</div>
                                                            </td>
                                                            <td>
                                                                <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->ciudad : ((isset($value->ship_ciudad)) ? $value->ship_ciudad : '&nbsp;') }} , {{ $value->ship_zip }}</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td colspan="4">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="consignee"  style="font-weight: bold;font-size: 15px;">{{ ($value->consignee_json != '') ? $consignee_json->nombre : ((isset($value->cons_nomfull)) ? $value->cons_nomfull : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="margin-left: 10px;">Street Address - Dirección</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->direccion : ((isset($value->cons_dir)) ? $value->cons_dir : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%">
                                                                <div style="margin-left: 10px;">Phone - Teléfono</div>
                                                            </td>
                                                            <td>
                                                                <div style="margin-left: 10px;">City - Ciudad - State - Estado</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->telefono : ((isset($value->cons_tel) and $value->cons_tel != '') ? $value->cons_tel : '&nbsp;') }}</div>
                                                            </td>
                                                            <td>
                                                                <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->ciudad : ((isset($value->cons_ciudad)) ? $value->cons_ciudad : '&nbsp;') }}, {{ $value->cons_zip }}</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"> Descripción - Contenido</td>
                                                <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                                                    <table style="width: 100%">
                                                        <tr>
                                                            <td width="35%" style="text-align: center;">DECLARED</td>
                                                            <td width="30%" style="text-align: center;">PIEZAS</td>
                                                            <td width="35%" style="text-align: center;">WEIGTH - PESO</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="margin-left: 10px;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;color: #1d1d1e;"> {{ $value->contenido2 }}</td>
                                                <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td width="35%" style="text-align: center;{{ ($value->declarado2 == 0) ? 'background-color: black;color: #fff' : ''}}">
                                                                <div style="color: #1d1d1e;">{{ '$ '.number_format($value->declarado2, 2) }}</div>
                                                                <div style="margin-top: 10px;">Master: <span style="color: #1d1d1e;">{{ $documento->num_master }}</span></div>
                                                            </td>
                                                            <td width="30%" style="color: #1d1d1e;text-align: center;">{{ 1 }}</td>
                                                            <td width="35%" style="text-align: center;"{{ ($value->peso2 == 0) ? 'background-color: black;color: #fff' : ''}}>
                                                                <div style="color: #1d1d1e;">{{ $value->peso2 }} Lbs</div>
                                                                <div style="margin-top: 10px;color: #1d1d1e;">{{ number_format(($value->peso2 * 0.453592), 2) }} Kls</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 9px;">WR</td>
                                                <td>&nbsp;</td>
                                                <td style="border-right: 1px solid #ccc;">&nbsp;</td>

                                                <td colspan="2"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 9px;color: #1d1d1e;">{{ $value->num_warehouse }}</td>
                                                <td>Date-Fecha</td>
                                                <td style="border-right: 1px solid #ccc;">Time-Hora</td>

                                                <td colspan="2"></td>
                                                <td>Date-Fecha</td>
                                                <td>Time-Hora</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" style="border-top: 1px solid #ccc;margin-top:5px;font-size: 8px;text-align: justify;">
                                                    Los bienes aquí escritos se aceptan aparentemente en buenas condiciones para su transporte de acuerdo a las siguientes cláusulas COLOMBIANA DE CARGA CORP. brindara el servicio
según lo solicita el remitente y hará los arreglos necesarios para el transporte aéreo a través de una aerolínea directa y responsable. COLOMBIANA DE CARGA CORP. Asegurara este
paquete contra perdidas o daños al valor normal de mercado limite de U$100,oo dólares durante la recogida y la entrega. La responsabilidad de COLOMBIANA DE CARGA de acuerdo con
este párrafo será reducida por el valor de cualquier otro seguro qué tenga el embarcador o por la perdida o daño del embarque. El remitente garantiza a COLOMBIANA DE CARGA CORP.
que el contenido del embarque puede ser legalmente embarcado en aviones o barcos y no contiene sustancias prohibidas de acuerdo a reglamentos y regulaciones vigentes y que se
encuentra adecuadamente envuelto para su propósito si es necesario. El remitente indemnizara a COLOMBIANA DE CARGA CORP. cualquier daño que sufra esta última por violar esta
regulación. Esto autoriza a COLOMBIANA DE CARGA CORP. o a sus agentes para designar a un corredor de aduana para que actué en representación del consignatario que es nombrado
para que efectué el trámite aduanero.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="margin-top:5px;font-size: 8px;">
                                                    NOTA: PARA MUESTRA (S.P.X) FAVOR ANEXAR FACTURA COMERCIAL * ORIGINAL Y COPIA
                                                </td>
                                                <td colspan="2" style="margin-top:5px;text-align:center;font-size: 10px;color: #1d1d1e;font-weight: bold;">
                                                    {{ $value->num_guia }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="8" style="text-align: center;font-size: 10px;">
                                                    AL DESPACHAR ESTE ENVIO MANIFIESTO QUE SU CONTENIDO NO ES DINERO, JOYAS, VALORES NEGOCIABLES, NI OBJETOS DE PROHIBIDOTRANSPORTE.
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" style="text-align: center;font-size: 10px;">
                                                    La carga puede ser inspeccionada por las autoridades competentes, tanto en el país de origen como en el país de destino (TSA, DIAN, etc.)
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    @if ($cont == 1)
                                        <div id="spaceTable">&nbsp;</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
    @endforeach
@else
    <div id="noDatos">NO HAY DATOS</div>
@endif
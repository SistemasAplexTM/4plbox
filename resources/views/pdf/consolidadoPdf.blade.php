<style>
    #mvcIcon, #mvcMain{
        display: none;
    }
    *{
        font-family: sans-serif;  
    }
    td{
        border: none;
    }
    table{
        width: 100%;
        border: 2px solid #000;
    }
    #tableContainer{
        margin-left: -18px;
        width: 105%;
    }
    #tableDetalle{
        margin-top: -2px;
        font-size: 12px;
        margin-left: -18px;
        width: 105%;
    }
    div{
        padding: 0 5px 0 5px;
    }
    #manifiesto{
        text-align: left;
    }
    #manifiesto, #nomAgencia{
        font-weight: bold;
        text-transform: uppercase;
    }
    #dirAgencia,#localAgencia,#paisAgencia,#telAgencia{
        text-transform: uppercase;
        font-size: 10px;
    }
    #creado{
        font-weight: bold;
    }
    #numManifiesto,#numMani{
        font-weight: bold;
        text-transform: uppercase;
    }
    #fcreado{
        font-size: 12px;
    }
    #origen,#destino{
        margin-top: 0;
        position: absolute;
        font-size: 11px;
        font-weight: bold;
    }
    #datosDestino,#datosOrigen{
        font-size: 9px;
    }
    #masterawb, #nummasterawb{
        font-size: 15px;
        font-weight: bold;
    }
    #datosVuelo{
        font-size: 11px;
        font-weight: bold;
    }
    #detalle{
        font-size: 12px;
        margin: 2px;
        background-color: #919191;
    }
    #space{
        border: solid 1px #919191;
        padding: 2px;
        width: 98%;
        margin: 0 auto;

    }
    #contiene{
        height: 30px;
        /*        width: 500px;*/
        padding: 0 5px 0 5px;
        text-transform: uppercase;
    }
    #bolsa{
        padding: 0 5px 0 5px;
        /*font-size: 10px;*/
        font-weight: bold;
    }
    #imgbarcode{
        width: 80px;
        height: 30px;
    }
    tfoot{
        font-size: 18px;
        font-weight: bold; 
    }
    #noDatos{
        font-size: 20px;
        text-align: center;
        font-weight: bold;
    }
    #remitente, #destinatario{
        font-size: 9px;
    }
</style>
<!--<a class="btn"  onclick="print()" id="imprimir" ><i class="icon-print"></i> Imprimir Chrome</a>-->
<table  cellspacing="0" cellpadding="0" id="tableContainer" border="0">
    <tr>
        <td colspan="2">
            <div id="nomAgencia">{{ $documento->agencia }}</div>
        </td>
        <td colspan="2">
            <div id="manifiesto">@lang('general.cargo_manifes')</div>
        </td>
    </tr>
    <tr>
        <td colspan="2"><div id="dirAgencia">{{ $documento->agencia_dir }}</div></td>
        <td colspan="2"><div id="masterawb">Master AWB: {{ $documento->master_id }}</div></td>        
    </tr>
    <tr>
        <td colspan="2"><div id="localAgencia">{{ $documento->agencia_ciudad }}, {{ $documento->agencia_depto }}. {{ $documento->agencia_zip }}</div></td>
        <td colspan="2"><div id="numManifiesto">N° @lang('general.manifest'): {{ $documento->id }}</div></td> 
    </tr>
    <tr>
        <td colspan="2"><div id="paisAgencia">{{ $documento->agencia_pais }}</div></td>
        <td colspan="2"><div id="creado">@lang('created'): {{ $documento->created_at }}</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding-bottom: 10px;"><div id="telAgencia">{{ $documento->agencia_tel }}</div></td>
        <td colspan="2"></td>
    </tr>


    <tr>
        <td style="height: 20px;">
            <div id="datosVuelo">@lang('general.carga_number'):</div>
        </td>
        <td>
            <div id="datosDestino"></div>
        </td>
        <td>
            <div id="datosVuelo">@lang('general.flight_date'):</div>
        </td>
        <td>
            <div id="datosDestino"></div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="datosVuelo">@lang('general.reference_number'):</div>
        </td>
        <td>
            <div id="datosDestino"></div>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
</table>

<table  cellspacing="0" cellpadding="0" id="tableDetalle" border="0">
    <thead>
        <tr>
            <td style="width: 5%;"><div id="detalle">#@lang('general.bag')</div></td>
            <td style="width: 20%;"><div id="detalle">@lang('general.guide')</div></td>
            <td style=""><div id="detalle">@lang('general.shipper')</div></td>
            <td style=""><div id="detalle">@lang('general.recipients')</div></td>
            <td style="width: 10%;"><div id="detalle">@lang('general.declared')</div></td>
            <td style="width: 5%;"><div id="detalle">@lang('general.pieces')</div></td>
            <td style="width: 5%;"><div id="detalle">@lang('general.weight')</div></td>
            <td style="width: 11%;"><div id="detalle">@lang('general.customs') US</div></td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totPiezas = 0;
        $piezas = 0;
        $peso = 0;
        $bolsas = 0;
        $cont = 0;
        ?>
        @if($detalleConsolidado)
        {{ var_dump($detalleConsolidado) }}
            @foreach($detalleConsolidado as $val)
            <?php
                $shipper_json = '';
                $consignee_json = '';
                if($val->shipper_json != ''){
                    $shipper_json = json_decode($val->shipper_json);
                }
                if($val->consignee_json != ''){
                    $consignee_json = json_decode($val->consignee_json);
                }
            ?>
                <tr>
                    <td>
                        <div id="bolsa">{{ $val->num_bolsa }}</div>
                    </td>
                    <td style="text-align: center;">
                        {{-- @if($val->liquidado == 0)
                            <img id="barcode" style="height: 30px;padding: 2px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($val->num_warehouse, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                            <div style="text-align: center;">{{ $val->num_warehouse }}</div>
                        @else --}}
                            <img id="barcode" style="height: 30px;padding: 2px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($val->num_guia, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                            <div style="text-align: center;">{{ $val->num_guia }}</div>
                        {{-- @endif --}}
                        
                    </td>
                    <td>
                        <div id="remitente">{{ ($val->shipper_json) ? $shipper_json->nombre : $val->nom_ship }}</div>
                        <div id="remitente">{{ ($val->shipper_json) ? $shipper_json->direccion : $val->dir_ship }}</div>
                        <div id="remitente">{{ ($val->shipper_json) ? $shipper_json->telefono : $val->tel_ship }}</div>
                        <div id="remitente">{{ ($val->shipper_json) ? $shipper_json->ciudad : $val->ciu_ship }} / {{ ($val->shipper_json) ? $shipper_json->pais : $val->pais_ship }}</div>
                    </td>
                    <td>
                        <div id="destinatario">{{ ($val->consignee_json) ? $consignee_json->nombre : $val->nom_cons }}</div>
                        <div id="destinatario">{{ ($val->consignee_json) ? $consignee_json->direccion : $val->dir_cons }}</div>
                        <div id="destinatario">{{ ($val->consignee_json) ? $consignee_json->telefono : $val->tel_cons }}</div>
                        <div id="destinatario">{{ ($val->consignee_json) ? $consignee_json->ciudad : $val->ciu_cons }} / {{ ($val->consignee_json) ? $consignee_json->pais : $val->pais_cons }}</div>
                    </td>
                    <td>
                        @if($val->declarado2 == 0)
                    	   <div style="background-color:black;color:white;">$ {{ number_format($val->declarado2,2) }}<div>
                        @else
                            $ {{ number_format($val->declarado2,2) }}
                        @endif
                    </td>

                    <td>
                        {{ $piezas = 1 }}
                    </td>
                    <td>
                        @if($val->peso2 == 0)
                           <div style="background-color:black;color:white;">{{ number_format($val->peso2,2) }}<div>
                        @else
                            {{ number_format($val->peso2,2) }}
                        @endif
                    </td>
                    <td>3055H</td>
                </tr>
                <tr><td colspan="8" style="height: 30px;"><div id="contiene"><strong>@lang('general.contains'):</strong> {{ $val->contenido2 }}</div></td></tr>
                <tr><td colspan="8"><div id="space"></div>&nbsp;</td></tr>
                <?php
                $totPiezas += $piezas;
                $peso += $val->peso2;
                $cont++;
                if($bolsas < $val->num_bolsa){
                    $bolsas = $val->num_bolsa;
                }
                ?>
            @endforeach
        @else
            <tr>
                <td colspan="8">
                    <div id="noDatos">@lang('general.there_is_no_data')</div>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="8">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center;font-weight: bold;">{{ $bolsas }}</td>
                        <td style="text-align: center;font-weight: bold;">{{ $cont }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;font-weight: bold;">{{ $totPiezas }}</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">{{ $peso }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">@lang('general.total_bag')</td>
                        <td style="text-align: center;font-weight: bold;">@lang('total_guides')</td>
                        <td style="text-align: center;font-weight: bold;"></td>
                        <td style="text-align: center;font-weight: bold;"></td>
                        <td style="text-align: center;font-weight: bold;"></td>
                        <td style="text-align: center;font-weight: bold;">@lang('general.total_pieces')</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">@lang('general.total_weight')</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

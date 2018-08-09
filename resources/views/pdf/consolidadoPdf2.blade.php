<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<style type="text/css">

    *{
        font-family: 'Roboto Condensed', sans-serif;
        font-size: 13px;
        color: #1d68a4;
    }
    #dirAgencia, #localAgencia, #telAgencia{
        color: #5d5f60;
        font-size: 13px;
        font-family: cursive;
    }
    #title{
        text-align: right;
        margin-right: 5px;
    }
    #title2{
        margin-left: 10px;
        font-size: 11px;
        font-family: 'Encode Sans Condensed', sans-serif;
        color: #1d1d1e;
    }
    #t_detalle{
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 11px;
    }
    #detalle{
        font-size: 10px;
        padding-top: 5px;
        color: #1d1d1e;
    }
</style>
{{-- CABECERA --}}
<table cellspacing="0" cellpadding="0" id="tableContainer" border="0" width="100%">
    <thead>
        <tr>
            <th colspan="2" width="250px"><img alt="image" class="img-circle" id="logo" style="width: 250px;margin-bottom: 10px;" src="{{ asset('storage/logo.png') }}"/></th>
            <th>&nbsp;</th>
            <th width="250px" style="text-align: right;">
                <div id="nomAgencia" style="font-size: 20px;">{{ $documento->agencia }}</div>
                <div id="dirAgencia">{{ $documento->agencia_dir }} - {{ $documento->agencia_ciudad }} - {{ $documento->agencia_depto }}</div>
                <div id="telAgencia">Teléfono: {{ $documento->agencia_tel }} Zip: {{ $documento->agencia_zip }}</div>
            </th>
        </tr>
        <tr>
            <th><div id="title">AIR WAYBILL#:</div></th>
            <th><div id="title2" style="font-size: 13;">{{ $documento->num_master }}</div></th>
            <th><div id="title">POINT LOADING:</div></th>
            <th width="250px">
                <div id="title2">{{ $documento->aeropuerto }}</div>
            </th>
        </tr>
        <tr>
            <th><div id="title">AIR CRAFT:</div></th>
            <th><div id="title2">{{ $documento->aerolinea }}</div></th>
            <th><div id="title">FOR USE BY OWNER:</div></th>
            <th width="250px">
                <div id="title2">{{ $documento->consignee_master }}</div>
            </th>
        </tr>
        <tr>
            <th><div id="title">DATE:</div></th>
            <th><div id="title2">{{ $documento->created_at }}</div></th>
            <th><div id="title">PO:</div></th>
            <th width="250px">
                <div id="title2">{{ $documento->ciudad_destino }}</div>
            </th>
        </tr>
    </thead>
</table>

{{-- DETALLE --}}
<table cellspacing="0" cellpadding="0" id="tableContainer" border="0" width="100%" style="margin-top: 20px;">
    <thead>
        <tr>
            <th colspan="8"><div style="font-size: 16px;text-align: center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">CARGO MANIFEST EXPRESS</div></th>
        </tr>
        <tr>
            <th><div id="t_detalle" style="text-align: center;">AWB</div></th>
            <th><div id="t_detalle">SHIPPER</div></th>
            <th><div id="t_detalle">CONSIGNEE</div></th>
            <th><div id="t_detalle">NATURE OF GOODS</div></th>
            <th><div id="t_detalle" style="text-align: center;">U.S COSTUM</div></th>
            <th><div id="t_detalle" style="text-align: center;">PCS</div></th>
            <th><div id="t_detalle" style="text-align: center;">GROSS WEIGHT</div></th>
            <th><div id="t_detalle" style="text-align: center;">FOB</div></th>
        </tr>
        <tr><th colspan="8" style="font-size:3px;background-color: #ccc">&nbsp;</th></tr>
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
                    <td id="detalle" style="width: 15%;text-align: center;">{{ $val->num_guia }}</td>
                    <td style="width: 22%">
                        <div id="detalle" >{{ ($val->shipper_json) ? $shipper_json->nombre : $val->nom_ship }}</div>
                        <div id="detalle" >{{ ($val->shipper_json) ? $shipper_json->direccion : $val->dir_ship }}</div>
                        <div id="detalle" >{{ ($val->shipper_json) ? $shipper_json->telefono : $val->tel_ship }}</div>
                        <div id="detalle" >{{ ($val->shipper_json) ? $shipper_json->ciudad : $val->ciu_ship }} / {{ ($val->shipper_json) ? $shipper_json->pais : $val->pais_ship }}, {{ $val->zip_ship }}</div>
                    </td>
                    <td id="detalle" style="width: 22%">
                        <div id="detalle" >{{ ($val->consignee_json) ? $consignee_json->nombre : $val->nom_cons }}</div>
                        <div id="detalle" >{{ ($val->consignee_json) ? $consignee_json->direccion : $val->dir_cons }}</div>
                        <div id="detalle" >{{ ($val->consignee_json) ? $consignee_json->telefono : $val->tel_cons }}</div>
                        <div id="detalle" >{{ ($val->consignee_json) ? $consignee_json->ciudad : $val->ciu_cons }} / {{ ($val->consignee_json) ? $consignee_json->pais : $val->pais_cons }}, {{ $val->zip_cons }}</div>
                    </td>
                    <td id="detalle" style="width: 15%">{{ $val->contenido2 }}</td>
                    <td id="detalle" style="text-align: center;"></td>
                    <td id="detalle" style="text-align: center;">{{ number_format($piezas = 1,2) }}</td>
                    <td id="detalle" style="text-align: center;">
                        @if($val->peso2 == 0)
                           <div style="background-color:black;color:white;">{{ number_format($val->peso2,2) }}<div>
                        @else
                            {{ number_format($val->peso2,2) }}
                        @endif
                    </td>
                    <td id="detalle" style="text-align: center;">
                        @if($val->declarado2 == 0)
                           <div style="background-color:black;color:white;">$ {{ number_format($val->declarado2,2) }}<div>
                        @else
                            $ {{ number_format($val->declarado2,2) }}
                        @endif
                    </td>
                </tr>
                <tr><th colspan="8"><div style="font-size:1px;margin-top:3px;background-color: #ccc">&nbsp;</div></th></tr>
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
                    <div id="noDatos">NO HAY DATOS</div>
                </td>
            </tr>
        @endif
        <tfoot>
            <tr>
                <th colspan="3"></th>
                <th colspan="2"><div style="margin-top:8px;text-align: right;">TOTAL PCS:</div></th>
                <th>
                    <div style="margin-top:8px;text-align: center;font-size: 14px;color:#1d1d1e;">{{ $totPiezas }}</div>
                </th>
            </tr>
            <tr>
                <th colspan="3"></th>
                <th colspan="2"><div style="margin-top:5px;text-align: right;">TOTAL GROSS WEIGHT:</div></th>
                <th>&nbsp;</th>
                <th>
                    <div style="margin-top:5px;text-align: center;font-size: 14px;color:#1d1d1e;">{{ $peso }}</div>
                </th>
            </tr>
            <tr>
                <th colspan="3"></th>
                <th colspan="5"><div style="margin-top:5px;border-bottom: 1px solid #ccc;"></div></th>
            </tr>
        </tfoot>
    </tbody>
</table>
</body>
</html>
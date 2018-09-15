<style>
    #mvcIcon, #mvcMain{
        display: none;
    }
    #imgLogo{
        width: 500px;
        margin-top: 20px;
    }
    *{
        font-size: 14px;
        font-family: sans-serif;
    }
    td {
        border:hidden;
    }
    #tableContainer{
        height: 308px;
    }
    #anuncio{
        font-size: 7px;
    }
    #recibido{
        font-size: 10px;
        margin: 0px;
        /*padding-bottom: 2px;*/
        padding-left: 5px;
    }
    #barcode{
        padding:5px;
        width: 140px;
        height: 35px;
    }
    #remitente, #destinatario{
        font-weight: bold;
        font-size: 13px;
        margin-top: 5px;
    }
    #space{
        border-bottom: solid 1px #000;
        margin-bottom: 5px;
    }
    #servicio{
        font-weight: bold;
        font-size: 25px;
        margin-bottom: 10px;
    }
    #piezas, #obs, #pa{
        font-weight: bold;
        font-size: 16px;
    }
    #contenido{
        font-size: 11px;
    }
    #noDatos{
        font-size: 20px;
        text-align: center;
        font-weight: bold;
    }
     #shipper,#consignee{
        font-size: 9px;
        height: 13px;
    } 
    #tblContenido{
        padding-bottom: 3px;
        padding-top: 8px;
        height: 90px;
    }
    #tblContenido>td{
        border: 1px solid #8B91A0;
    }
    #tbl1,#tbl2{
        float: left;
        height: 98px;
    }
     #remitente,#destinatario,#shipper,#consignee{
        padding-left: 5px;
    } 
    #piezas,#declarado,#peso{
        text-align: center;
        font-size: 10px;
    }
    #tituloCont{
        font-weight: bold;
        font-size: 12px;
        border-bottom: 1px solid #8B91A0;
        text-align: center;
        margin-top: -15px;
    }
    #contiene{
        font-size: 12px;
    }
    #spaceTable{
        /*background-color:  #8B91A0;*/
        height: 12px;
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
        @if ($cont != 1)
            <div id="spaceTable">&nbsp;</div>
        @endif
                    <table border="0" id="" width="100%" cellspacing="0" cellpadding="0"  <?php if ($cont === 3): ?>
                   style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;margin-bottom: 0px;<?php else: ?>always<?php endif; ?>"
                   <?php
                   $cont = 0;
               endif;
               ?>>
                        <tr>
                            <td>
                                {{ (isset($documento->agencia)) ? $documento->agencia : '' }}
                            </td>
                            <td align="right">
                                <img id="barcode" style="height: 25px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($value->num_guia, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                            </td>
                        </tr>
                        <tr>
                            <th> @lang('pdfs.date'): {{ $documento->created_at }}</th>
                            <th align="right">{{ $value->num_guia }}</th>
                        </tr>
                        <tr>
                            <td width="40%">
                                <table border="1" cellspacing="0" cellpadding="0" width="100%">
                                    <tr>
                                        <td>
                                            <div id="remitente">@lang('pdfs.sender')</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->nombre : ((isset($value->ship_nomfull)) ? $value->ship_nomfull : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->direccion : ((isset($value->ship_dir)) ? $value->ship_dir : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->telefono : ((isset($value->ship_tel) and $value->ship_tel != '') ? $value->ship_tel : 'Tel:') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->ciudad : ((isset($value->ship_ciudad)) ? $value->ship_ciudad : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->pais : ((isset($value->ship_pais)) ? $value->ship_pais : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table border="1" cellspacing="0" cellpadding="0" width="100%">
                                    <tr>
                                        <td>
                                            <div id="destinatario">@lang('pdfs.addressee')</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->nombre : ((isset($value->cons_nomfull)) ? $value->cons_nomfull : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->direccion : ((isset($value->cons_dir)) ? $value->cons_dir : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->telefono : ((isset($value->cons_tel) and $value->cons_tel != '') ? $value->cons_tel : 'Tel:') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->ciudad : ((isset($value->cons_ciudad)) ? $value->cons_ciudad : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->pais : ((isset($value->cons_pais)) ? $value->cons_pais : '&nbsp;') }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-bottom: 10px;">
                                <table border="1" id="tblContenido" width="100%">
                                    <tr>
                                        <td style="font-weight: bold;"><div id="tituloCont">@lang('pdfs.content')</div></td>
                                        <td style="width: 6%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">@lang('pdfs.pieces')</div></td>
                                        <td style="width: 10%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">@lang('pdfs.declared')</div></td>
                                        <td style="width: 10%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">@lang('pdfs.weight')</div></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="contiene"><strong>PA:</strong>  {{-- {{ $value->pa }} --}} {{ $value->contenido2 }}
                                                <?php
                                                $cant = strlen($value->contenido2);
                                                for ($i = 1; $i + $cant <= 110; $i++) {
                                                    echo '&nbsp;';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td style="border-left: 1px solid #8B91A0;"><div id="piezas">{{ 1 }}</div></td>
                                        <td style="border-left: 1px solid #8B91A0;"><div id="declarado" style="{{ ($value->declarado2 == 0) ? 'background-color: black;color: #fff' : ''}}">{{ '$ '.number_format($value->declarado2, 2) }}</div></td> 
                                        <td style="border-left: 1px solid #8B91A0;"><div id="peso" style="{{ ($value->peso2 == 0) ? 'background-color: black;color: #fff' : ''}}">{{ ' '.$value->peso2 }} Lbs</div><div id="peso">{{ ' '.number_format(($value->peso2 * 0.453592), 2) }} Kls</div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="recibido">@lang('pdfs.received_by') :__________________________ 
                                @lang('pdfs.id') :____________________ @lang('pdfs.date') : DD____MM____AAAA______</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 5px;">
                                <div id="anuncio">@lang('general.ad')</div>
                            </td>
                        </tr>
                    </table>
    @endforeach
@else
    <div id="noDatos">@lang('pdfs.there_is_no_data')</div>
@endif
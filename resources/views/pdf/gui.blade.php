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
        padding-bottom: 2px;
        padding-left: 5PX;
    }
    #barcode{
        padding:5px;
        width: 140px;
        height: 35px;
    }
    #remitente, #destinatario{
        font-weight: bold;
        font-size: 13px;
        margin-top: 10px;
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
        padding-top: 1px;
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
        $cont++;
        $contRegistros++;
        ?>
        @if ($cont != 1)
            <div id="spaceTable">&nbsp;</div>
        @endif
        <table border="1" cellspacing="0" cellpadding="0" id="tableContainer" 
        <?php if ($cont === 3): ?>
                   style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;margin-bottom: 0px;<?php else: ?>always<?php endif; ?>"
                   <?php
                   $cont = 0;
               endif;
               ?>
               >
            <tr>
                <td style="width: 50%;">
                    <div id="" style="padding-left: 5px; font-weight: bold;">{{ (isset($documento->agencia)) ? $documento->agencia : '' }}</div>
                </td>
                <td colspan="2" rowspan="2" align="right">
                    <div id="divBarcode"><img id="barcode" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($documento->num_guia, "C128",1,29,array(1,1,1)) }}" alt="barcode" /></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div id="" style="padding-left: 5px; font-weight: bold;">@lang('general.date'): {{ $documento->created_at }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table border="1" cellspacing="0" cellpadding="0" id="tbl1" width="50%">
                        <tr>
                            <td>
                                <div id="remitente">@lang('general.shipper')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="shipper">{{ $value->ship_nomfull }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="shipper">{{ $value->ship_dir }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="shipper">{{ (isset($value->ship_tel) and $value->ship_tel != '') ? $value->ship_tel : 'Tel:' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="shipper">{{ $value->ship_ciudad }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="shipper">{{ $value->ship_pais }}</div>
                            </td>
                        </tr>
                    </table>
                    <table border="1" cellspacing="0" cellpadding="0" id="tbl2" width="50%">
                        <tr>
                            <td>
                                <div id="destinatario">@lang('general.consignee')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="consignee">{{ $value->cons_nomfull }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="consignee">{{ $value->cons_dir }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="consignee">{{ (isset($value->cons_tel) and $value->cons_tel != '') ? $value->cons_tel : 'Tel:' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="consignee">{{ $value->cons_ciudad }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="consignee">{{ $value->cons_pais }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-bottom: 20px;">
                    <table border="1" id="tblContenido" width="100%">
                        <tr>
                            <td style="font-weight: bold;"><div id="tituloCont">@lang('general.content')</div></td>
                            <td style="width: 6%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">@lang('general.pieces')</div></td>
                            <td style="width: 10%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">Declarado</div></td>
                            <td style="width: 10%;border-left: 1px solid #8B91A0;font-weight: bold;"><div id="tituloCont">Peso</div></td>
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
                            <td style="border-left: 1px solid #8B91A0;"><div id="declarado">{{ '$ '.number_format($value->declarado2, 2) }}</div></td> 
                            <td style="border-left: 1px solid #8B91A0;"><div id="peso">{{ '$ '.$value->peso2 }} Lbs</div><div id="peso">{{ '$ '.number_format(($value->peso2 * 0.453592), 2) }} Kls</div></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div id="recibido">@lang('general.received_by'):__________________________ @lang('general.id'):____________________ @lang('general.date'): DD_____MM_____AAAA______</div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 5px;">
                    <div id="anuncio">The sender declares that he/she is not sending money, guns, chemicals, jewerly or drugs and he understands that the freight has been insured with 100% of the declare value.Understanding that if there is any total lost,there will be refound of the 100 % of the declare value.if there is partial lost,the refund will be proportional of the lost weight .we are not responsable of broken or damage merchandise .I certify that this shipment does not contain any unauthorized explosive,destructive devices or hazardous materials.I consent to a search of this shipment .I am aware that this endorsement and original signature,along with other shipping documents will be retained on file until the shipment is delivered.shipper hereby consents to a search or ispection of the cargo,including screening of the cargo.</div>
                </td>
            </tr>
        </table>

    @endforeach
@else
    <div id="noDatos">@lang('general.there_is_no_data')</div>
@endif
<script  type="text/javascript">
    function printHTML() {
        if (window.print) {
            window.print();
        }
    }

    document.addEventListener("DOMContentLoaded", function (event) {
        printHTML();
    });
</script>
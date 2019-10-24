<html>
<style>
    * {
        font-weight: bold;
        font-size: 13px;
        font-family: sans-serif;
    }

    #mvcIcon,
    #mvcMain {
        display: none;
    }

    .content {
        background-color: #cfcfcf;
    }

    #head,
    #invoice {
        font-size: 20px;
        background-color: #cfcfcf;
    }

    #invoice {
        text-align: right;
        width: 100%;
    }

    #cont1 {
        padding-top: 5px;
        padding-bottom: 0px;
    }

    #numInvoice,
    #fechaInvoice {
        text-align: right;
    }

    #ship,
    #cons {
        font-size: 15px;
        font-weight: bold;
        padding-bottom: 5px;
    }

    #titulo {
        font-weight: bold;
    }

    #total {
        text-align: right;
        margin-top: 10px;
    }

    #detalle {
        margin-top: 10px;
        margin-bottom: 10px;
        height: 55px;
        ;
    }

    #totalv {
        font-size: 20px;
    }

    #declaro {
        font-size: 8px;
        padding-top: 5px;
    }

    #declaro1 {
        font-size: 8px;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    #declaro2 {
        font-size: 8px;
        padding-bottom: 5px;
    }

    #firma {
        padding-top: 10px;
    }

    #space {
        margin-bottom: 3px;
        margin-top: 12px;
    }

    #space2 {
        margin-top: 10px;
        margin-bottom: 7px;
        border: 1px dashed #000;
        font-size: 2px;
    }
</style>
<?php
    $toalRegistros = count($detalle);
    $contRegistros = 0;
    $cont=0;

    if(isset($detalleConsolidado)){
      $detalle = $detalleConsolidado;
    }
    $copies = 1;
    if($config_copies){
        $copies = $config_copies->value;
    }
    $style = '';
    ?>

<body style="margin-top: -25px;">
    @foreach($detalle as $val)
    <?php $contRegistros++ ?>
    <?php for ($i = 1; $i <= $copies; $i++): ?>
    {{-- dar salto de pagina --}}
    @php
    if($i == $copies){
    if($contRegistros === $toalRegistros){
    $style = 'avoid';
    }else{
    $style = 'always';
    }
    }
    @endphp
    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="tableContainer"
        style="page-break-after:{{ $style }}">
        <tr>
            <td colspan="4" align="CENTER">
                <div id="space">(Copy {{ $i }})</div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="head">{{ (isset($documento->agencia)) ? $documento->agencia : '' }}</div>
            </td>
            <td>
                <div id="invoice">@lang('general.commercial_invoice')</div>
            </td>
        </tr>
        <tr>
            <td align="CENTER">
                <div id="cont1">
                    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                        <tr>
                            <td>@lang('general.address'):
                                {{ (isset($documento->agencia_dir)) ? $documento->agencia_dir : '' }}</td>
                        </tr>
                        <tr>
                            <td>@lang('general.phone'):
                                {{ (isset($documento->agencia_tel)) ? $documento->agencia_tel : '' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td align="CENTER">
                <div id="cont1">
                    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                        <tr>
                            <td>
                                <div id="numInvoice">@lang('general.invoice') N°:
                                    {{ (isset($detalle[$cont]->num_guia)) ? $detalle[$cont]->num_guia : '' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="fechaInvoice">@lang('general.date'):
                                    {{ (isset($documento->created_at)) ? $documento->created_at : '' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="CENTER">
                <div id="cont1">
                    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                        <tr>
                            <td>
                                <div id="ship">@lang('general.shipper')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="nomS"><span id="titulo">@lang('general.name'):</span>
                                    {{ ($val->ship_nomfull2) ? $val->ship_nomfull2 : $detalle[$cont]->ship_nomfull }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="dirS"><span id="titulo">@lang('general.address'):</span>
                                    {{ ($val->ship_dir2) ? $val->ship_dir2 : $detalle[$cont]->ship_dir }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="ciuS"><span id="titulo">@lang('general.country')/@lang('general.city'):</span>
                                    {{ ($val->ship_ciudad2) ? $val->ship_ciudad2 : $detalle[$cont]->ship_ciudad }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="telS"><span id="titulo">@lang('general.phone'):</span>
                                    {{ ($val->ship_tel2) ? $val->ship_tel2 : $detalle[$cont]->ship_tel }}
                                </div>
                            </td>
                        </tr>
                        {{-- <tr> --}}
                        {{-- <td><div id="telS">{{ (isset($detalle[$cont]->ship_email)) ? $detalle[$cont]->ship_email : '' }}
                </div>
            </td> --}}
            {{-- </tr> --}}
    </table>
    </div>
    </td>
    <td align="CENTER">
        <div id="cont1">
            <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                <tr>
                    <td>
                        <div id="cons">@lang('general.consignee')</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="nomC"><span id="titulo">@lang('general.name'):</span>
                            {{ ($val->cons_nomfull2) ? $val->cons_nomfull2 : $detalle[$cont]->cons_nomfull }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="dirC"><span id="titulo">@lang('general.address'):</span>
                            {{ ($val->cons_dir2) ? $val->cons_dir2 : $detalle[$cont]->cons_dir  }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="ciuC"><span id="titulo">@lang('general.country')/@lang('general.city'):</span>
                            {{ ($val->cons_ciudad2) ? $val->cons_ciudad2 :  $detalle[$cont]->cons_ciudad }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="telC"><span id="titulo">@lang('general.phone'):</span>
                            {{ ($val->cons_tel2) ? $val->cons_tel2 :  $detalle[$cont]->cons_tel }}</div>
                    </td>
                </tr>
                <!-- <tr>
                                        {{-- <td><div id="telC"><spam style="font-size: 12px;">{{ (isset($detalle[$cont]->cons_email)) ? $detalle[$cont]->cons_email : '' }}</spam></div></td> --}}
                                    </tr> -->
            </table>
        </div>
    </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td align="CENTER" colspan="2">
            <div>
                <table width="100%;" border="1" cellspacing="0" cellpadding="0" id="table1">
                    <tr>
                        <th style="width: 5%;">Cant.</th>
                        <th style="width: 68%;">Descripcion</th>
                        <th style="width: 12%;">@lang('general.value') (USD)</th>
                        <th style="width: 15%;">Subtotal (USD)</th>
                    </tr>
                    <?php $total = 0; ?>
                    <tr>
                        <td align="CENTER" style="height: 400px;">
                            <div id="detalle">{{ 1 }}</div>
                        </td>
                        <?php $leng = strlen($detalle[$cont]->contenido2); ?>
                        <td align="CENTER">
                            <div id="detalle" style="font-size: 12px; height: 45px;">
                                {{ ($leng > 215) ? str_replace(',', ', ', substr($detalle[$cont]->contenido2, 0, 215)) : str_replace(',', ', ', $detalle[$cont]->contenido2) }}
                            </div>
                        </td>
                        <td align="CENTER">
                            <div id="detalle">{{ '$' . number_format($detalle[$cont]->declarado2, 2) }}</div>
                        </td>
                        <td align="CENTER">
                            <div id="detalle">{{ '$' . number_format($detalle[$cont]->declarado2, 2) }}</div>
                        </td>
                    </tr>
                    <?php $total = $total +$detalle[$cont]->declarado2; ?>


                    <tr>
                        <th colspan="3">
                            <div id="total">Total</div>
                        </th>
                        <td align="CENTER">
                            <div id="totalv" style="{{ ($total == 0) ? 'background-color: black;color: #fff' : ''}}">
                                {{ '$' . number_format($total, 2) }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div id="declaro">Note: this declaration of commercial value replaces the original invoice.
                            </div>
                            <div id="declaro1">
                                @lang('general.declare1')
                            </div>
                            <div id="declaro2">
                                This invoice is only for customs use between united states and country of destination.
                                this invoice is not for trade. the shipper declares all the information is true and
                                correct. these commodities, technology or software were exported from the united states
                                in accordance with the export administration regulations. diversion contrary to u.s. law
                                is prohibited.
                            </div>
                        </td>
                    </tr>
                </table>
                <div id="firma">
                    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                        <tr>
                            <th>__________________________</th>
                            <th>__________________________</th>
                            <th>__________________________</th>
                        </tr>
                        <tr>
                            <th>@lang('general.signature')</th>
                            <th>No. @lang('general.id')</th>
                            <th>@lang('general.date')</th>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
    <?php if ($i === 1 and $copies != 1): ?>
    <tr>
        <td colspan="4" align="CENTER">
            <div id="space2">&nbsp;</div>
        </td>
    </tr>
    <?php endif; ?>
    </table>
    <?php endfor; ?>
    <?php $cont++; ?>
    @endforeach
</body>
{{--  <script  type="text/javascript">
        function printHTML() {
            if (window.print) {
                window.print();
            }
        }

        document.addEventListener("DOMContentLoaded", function (event) {
            printHTML();
        });
    </script> --}}

</html>
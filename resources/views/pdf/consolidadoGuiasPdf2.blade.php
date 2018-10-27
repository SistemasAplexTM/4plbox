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
        margin-top: 40px;
        margin-bottom: 40px;
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
                                <th colspan="2" width="300px"><img alt="image" class="img-circle" id="logo" style="height: 50px;margin-bottom: 10px;" src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? $documento->agencia_logo : 'logo.png') }}"/></th>
                                <th width="250px" style="text-align: right;">
                                    <div class="agencia" id="nomAgencia" style="font-size: 20px;">{{ $documento->agencia }}</div>
                                    <div class="agencia" id="dirAgencia"><span style="color: #1d1d1e;">{{ $documento->agencia_dir }} - {{ $documento->agencia_ciudad }} - {{ $documento->agencia_depto }}</span></div>
                                    <div class="agencia" id="telAgencia">@lang('general.phone'): <span style="color: #1d1d1e;">{{ $documento->agencia_tel }}</span></div>
                                    <div class="agencia" id="telAgencia">Zip: <span style="color: #1d1d1e;">{{ $documento->agencia_zip }}</span></div>
                                </th>
                                <th>
                                    <div class="guia">@lang('general.guide') AWB</div>
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
                                                <th colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">@lang('general.from_shipper') </th>
                                                <th colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">@lang('general.to_consigned') </th>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="width: 50%;border-right: 1px solid #ccc;">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="shipper" style="font-weight: bold;font-size: 13px;">{{ ($value->shipper_json != '') ? $shipper_json->nombre : ((isset($value->ship_nomfull)) ? $value->ship_nomfull : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="margin-left: 10px;"> @lang('general.address')</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="shipper">{{ ($value->shipper_json != '') ? $shipper_json->direccion : ((isset($value->ship_dir)) ? $value->ship_dir : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%">
                                                                <div style="margin-left: 10px;">@lang('general.phone')</div>
                                                            </td>
                                                            <td>
                                                                <div style="margin-left: 10px;">@lang('general.city') - @lang('general.state')</div>
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
                                                                <div id="consignee"  style="font-weight: bold;font-size: 13px;">{{ ($value->consignee_json != '') ? $consignee_json->nombre : ((isset($value->cons_nomfull)) ? $value->cons_nomfull : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="margin-left: 10px;">@lang('general.address')</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div id="consignee">{{ ($value->consignee_json != '') ? $consignee_json->direccion : ((isset($value->cons_dir)) ? $value->cons_dir : '&nbsp;') }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%">
                                                                <div style="margin-left: 10px;">@lang('general.phone')</div>
                                                            </td>
                                                            <td>
                                                                <div style="margin-left: 10px;">@lang('general.city') - @lang('general.state')</div>
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
                                                <td colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"> @lang('general.description') - @lang('general.content')</td>
                                                <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                                                    <table style="width: 100%">
                                                        <tr>
                                                            <td width="35%" style="text-align: center;">@lang('general.declared')</td>
                                                            <td width="30%" style="text-align: center;">@lang('general.pieces')</td>
                                                            <td width="35%" style="text-align: center;">@lang('general.weight')</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="margin-left: 10px;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;color: #1d1d1e;"> {{ $value->contenido2 }}</td>
                                                <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td width="35%" style="text-align: center;">
                                                                <div style="color: #1d1d1e;">{{ '$ '.number_format($value->declarado2, 2) }}</div>
                                                                <div style="margin-top: 10px;">Master: <span style="color: #1d1d1e;">{{ $documento->num_master }}</span></div>
                                                            </td>
                                                            <td width="30%" style="color: #1d1d1e;text-align: center;">{{ 1 }}</td>
                                                            <td width="35%" style="text-align: center;">
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
                                                <td>@lang('general.date')</td>
                                                <td style="border-right: 1px solid #ccc;">@lang('general.time')</td>

                                                <td colspan="2"></td>
                                                <td>@lang('general.date')</td>
                                                <td>@lang('general.time')</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" style="border-top: 1px solid #ccc;margin-top:5px;font-size: 8px;text-align: justify;">
                                                    @lang('general.message_goods')
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="margin-top:5px;font-size: 8px;">
                                                  @lang('general.note_for_sample')
                                                </td>
                                                <td colspan="2" style="margin-top:5px;text-align:center;font-size: 10px;color: #1d1d1e;font-weight: bold;">
                                                    {{ $value->num_guia }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="8" style="text-align: center;font-size: 10px;">
                                               @lang('general.by_dispatching_this_shipping')
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" style="text-align: center;font-size: 10px;">
                                              @lang('general.the_cargo_can_be_inspected')
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

    <style>
        #apDiv5, #apDiv4, #apDiv7, #apDiv10, #apDiv11{
            border: 1px solid;
        }
        #page-wrapper{
            /*margin-top: -10px;*/
        }
        #reciboWarehouse{
            background-color: #ffffff;
            /*margin-top: -30px;
            margin-left: -20px;
            margin-top: -25px;*/
        }
        *{
            font-weight: bold;
            font-size: 11px;
            font-family: sans-serif;
        }
        .importante{
            color: #F00;
            font-size: 11px;
        }
        #apDiv13{
            padding-left: 10px;
        }
        #apDiv15, #apDiv18{
            font-size: 8px;
        }
        #apDiv12{
            opacity: 0.5;
        }
        #apDiv13{
            opacity: 0.5;
        }
        #apDiv6{
            font-size: 18px;
            font-weight: bold;
        }
        #mvcIcon, #mvcMain{
            display: none;
        }
        #space{
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px dashed #000;
            font-size: 2px;
        }
        #infGuia{
            padding-top: 2px;
            padding-left: 2px;
            font-size: 7px;
            text-align: center;
        }
        b{
            padding-left: 5px;
        }
        #titulo_detalle{
            text-align: center;
            font-size: 9px;
        }
        #cont_detalle{
            font-size: 10px;
        }
        #total_detail{
            font-size: 12px;
        }
    </style>
    <?php
    $total_declarado = 0;
    $total_piezas = 0;
    $total_libras = 0;
    $total_volumen = 0;
    $total_volumen_cft = 0;
    $total_volumen_cmt = 0;
    ?>
    @if(count($detalle) > 0)
        <?php $total_piezas = count($detalle); ?>
        @foreach($detalle as $val)
            <?php
                $total_declarado += $val->valor;
                $total_libras += $val->peso;
                $total_volumen += $val->volumen;
                $total_volumen_cft += $val->volumen / 2.204622;
            ?>
        @endforeach
    @endif
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="reciboWarehouse">
            @for($i = 1; $i <= 2; $i++)
                @if (count($detalle) != 1)
                    <?php $i = 2 ?>
                @endif
                <tr>
                    <td align="left" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left">
                                    <span style="font-size:15px; font-weight:bold">{{ ((isset($documento->agencia) and $documento->agencia != '') ? $documento->agencia : '') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <span style="font-size:14px; font-weight:bold">{{ ((isset($documento->agencia_tel) and $documento->agencia_tel != '') ? $documento->agencia_tel : '') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <span style="font-weight:bold;">@lang('general.phone'):</span> {{ ((isset($documento->agencia_dir) and $documento->agencia_dir != '') ? $documento->agencia_dir : '') }}
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <span style="font-weight:bold;">@lang('general.date'):</span> {{ ((isset($documento->created_at) and $documento->created_at != '') ? $documento->created_at : '') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="right">
                                    <div id="apDiv6">Warehouse N° {{ ((isset($documento->num_warehouse) and $documento->num_warehouse != '') ? $documento->num_warehouse : '') }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <span style="font-weight:bold;">@lang('general.user'): </span>{{ ((isset($documento->usuario) and $documento->usuario != '') ? $documento->usuario : '') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div id="apDiv4">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr height="20px">
                                    <td width="20%"><b>Shipper:</b></td>
                                    <td colspan="3"> {{ ((isset($documento->ship_nomfull) and $documento->ship_nomfull != '') ? $documento->ship_nomfull : '') }} </td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.address'):</b></td>
                                    <td colspan="3"><div id="dir" style="height: 28px;">{{ ((isset($documento->ship_dir) and $documento->ship_dir != '') ? $documento->ship_dir : '') }}</div></td>

                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.phone'):</b></td>
                                    <td width="33%"> {{ ((isset($documento->ship_tel) and $documento->ship_tel != '') ? $documento->ship_tel : '') }} </td>
                                    <td width="19%"><b>@lang('general.city'):</b> </td>
                                    <td width="28%"> {{ ((isset($documento->ship_ciudad) and $documento->ship_ciudad != '') ? $documento->ship_ciudad : '') }} </td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.state'):</b></td>
                                    <td colspan="1"> {{ ((isset($documento->ship_depto) and $documento->ship_depto != '') ? $documento->ship_depto : '') }} </td>
                                    <td><b>Zip:</b></td>
                                    <td>{{ ((isset($documento->ship_zip) and $documento->ship_zip != '') ? $documento->ship_zip : '') }}</td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.email'):</b></td>
                                    <td colspan="3" style="font-size: 10px;"> {{ ((isset($documento->ship_email) and $documento->ship_email != '') ? $documento->ship_email : '') }} </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div id="apDiv5">
                            <!--Información del Consignatario-->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr height="20px">
                                    <td width="20%"><b>Consignee:</b></td>
                                    <td colspan="3"> {{ ((isset($documento->cons_nomfull) and $documento->cons_nomfull != '') ? $documento->cons_nomfull : '') }} </td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.address'):</b></td>
                                    <td colspan="3"><div id="dir" style="height: 28px;">{{ ((isset($documento->cons_dir) and $documento->cons_dir != '') ? $documento->cons_dir : '') }}</div>  </td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.phone'):</b></td>
                                    <td width="33%"> {{ ((isset($documento->cons_tel) and $documento->cons_tel != '') ? $documento->cons_tel : '') }} </td>
                                    <td width="19%"><b>@lang('general.identification_card'):</b> </td>
                                    <td width="28%"> {{ ((isset($documento->cons_documento) and $documento->cons_documento != '') ? $documento->cons_documento : '') }} </td>
                                </tr>
                                <tr height="20px">
                                    <td><b>@lang('general.city'):</b></td>
                                    <td width="33%" style="font-size: 10px;"> {{ ((isset($documento->cons_ciudad) and $documento->cons_ciudad != '') ? $documento->cons_ciudad : '') }} </td>
                                    <td width="19%"><b>C.P:</b></td>
                                    <td width="28%"> {{ ((isset($documento->cons_zip) and $documento->cons_zip != '') ? $documento->cons_zip : '') }}</td>
                                </tr>
                                <tr height="20px">
                                    <td colspan="3" style="font-size: 12px;padding-left: 5px;">{{ ((isset($documento->cons_email) and $documento->cons_email != '') ? $documento->cons_email : '') }}</td>
                                    <td colspan="2" style="font-size: 12px;border-left: 1px solid #000;border-top: 1px solid #000;">PO: <b id="apDiv12">{{ ((isset($documento->cons_pobox) and $documento->cons_pobox != '') ? $documento->cons_pobox : '') }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                <td colspan="2">
                    <div style="padding:2px 0 0 0">
                        <div id="apDiv7">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td  height="10"><b>@lang('general.invoice'):</b> &nbsp;{{ ((isset($documento->factura) and $documento->factura != 0) ? 'Si' : 'No') }}</td>
                                    <td ><b>@lang('general.dangerous_load'):</b> &nbsp;{{ ((isset($documento->carga_peligrosa) and $documento->carga_peligrosa != 0) ? 'Si' : 'No') }}</td>
                                    <td ><b>@lang('general.repacking'):</b> &nbsp;{{ ((isset($documento->re_empacado) and $documento->re_empacado != 0) ? 'Si' : 'No') }}</td>
                                    <td ><b>@lang('general.bad_packaging'):</b> &nbsp;{{ ((isset($documento->mal_empacado) and $documento->mal_empacado != 0) ? 'Si' : 'No') }}</td>
                                    <td><b>@lang('general.broken'):</b> &nbsp;{{ ((isset($documento->rota) and $documento->rota != 0) ? 'Si' : 'No') }}</td>
                                </tr>
                                <tr>
                                    <td valign="top" style="height: 50px;"><b>@lang('general.observations'):</b></td>
                                    <td height="15" colspan="5" valign="top"><span style="padding:4px 0 0 0"> {{ ((isset($documento->observaciones) and $documento->observaciones != '') ? $documento->observaciones : '') }} </span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </td>
            </tr>

                <tr>
                    <td colspan="2">
                        <div id="apDiv8">
                            <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-collapse:collapse;">
                                <tr>
                                    <th width="2%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">#.</th>
                                    <th width="6%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">@lang('general.code')</th>
                                    <th width="" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">@lang('general.content')</th>
                                    <th width="3%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">L</th>
                                    <th width="3%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">W</th>
                                    <th width="3%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">H</th>
                                    <th width="5%" rowspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">@lang('general.weight') Lbs</th>
                                    <th colspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">@lang('general.weight') Vol</th>
                                    <th colspan="2" bgcolor="lightgray" scope="col" id="titulo_detalle">@lang('general.volume')</th>
                                </tr>
                                <tr>
                                    <th width="5%" bgcolor="lightgray" scope="col" id="titulo_detalle">LBS</th>
                                    <th width="5%" bgcolor="lightgray" scope="col" id="titulo_detalle">KLS</th>
                                    <th width="5%" bgcolor="lightgray" scope="col" id="titulo_detalle">CFT</th> <!-- pie cubico-->
                                    <th width="5%" bgcolor="lightgray" scope="col" id="titulo_detalle">CMT</th><!-- metro cubico-->
                                </tr>
                                <?php
                                $cont = 1;
                                $sumPie = 0;
                                $sumMetro = 0;
                                ?>
                                @foreach($detalle as $val)
                                    <tr height='18px'>
                                        <td align='center' id="cont_detalle">{{ $cont++ }} </td>
                                        <td align='center' id="cont_detalle">
                                            <div style="padding: 0 5px">
                                                <img id="barcode" style="height: 30px;padding: 5px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($val->num_warehouse, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                                            </div>
                                            <div>{{ $val->num_warehouse }}</div>
                                        </td>
                                        <?php $leng = strlen($val->contenido); ?>
                                        <td id="cont_detalle" style="height: 50px;">
                                            {{ (($leng > 215) ? str_replace(',', '-', substr($val->contenido, 0, 215)) : str_replace(',', ', ', $val->contenido)) }} **- trackings ({{ str_replace(',', ', ', $val->tracking) }})
                                        </td>
                                        <td align='center' id="cont_detalle">{{ $val->largo }}</td>
                                        <td align='center' id="cont_detalle">{{ $val->ancho }}</td>
                                        <td align='center' id="cont_detalle">{{ $val->alto }}</td>
                                        <?php $arr = preg_split("/ /", $val->dimensiones); ?>
                                        <td align='center' id="cont_detalle">{{ $arr[0] }}</td>
                                        <td align='center' id="cont_detalle">{{ $val->volumen }}</td>
                                        <td align='center' id="cont_detalle">{{ number_format(($val->volumen / 2.204622), 2) }}</td>
                                        <td align='center' id="cont_detalle">{{ $pie = number_format(($val->largo * $val->ancho * $val->alto) / 1728, 2) }}</td>
                                        <td align='center' id="cont_detalle">{{ $metro = number_format($pie / 31.315, 2) }}</td>
                                    </tr>
                                    <?php
                                    $sumPie = $sumPie + $pie;
                                    $sumMetro = $sumMetro + $metro;
                                    ?>
                                @endforeach
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="apDiv9">
                            <table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#000000" style="border-collapse:collapse;">
                                <tr>
                                    <th width="300px" rowspan="2" scope="col"><span style="font-size:20px; color:#666">@lang('general.receives')</span></th>
                                    <th width="" height="10" bgcolor="lightgray" scope="col" style="font-size: 10px;">@lang('general.pieces')</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">@lang('general.pounds')</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. LBS</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Kls</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. KLS</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. CFT</th>
                                    <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. CMT</th>
                                </tr>
                                <tr>
                                    <td height="10" align="center"><span id="total_detail">{{ $total_piezas }} </span></td>
                                    <td align="center"><span id="total_detail">{{ $total_libras }} </span></td>
                                    <td align="center"><span id="total_detail">{{ $total_volumen }} </span></td>
                                    <td align="center"><span id="total_detail">{{ number_format($total_volumen * 0.453592,2) }}</span></td>
                                    <td align="center"><span id="total_detail">{{ number_format($total_volumen_cft,2) }} </span></td>
                                    <td align="center"><span id="total_detail"> {{ number_format($sumPie,2) }} </span></td>
                                    <td align="center"><span id="total_detail"> {{ number_format($sumMetro,2) }} </span></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-left: 2px;">
                                        <tr>
                                            <th colspan="2" style="text-align: center;">
                                                <div id="apDiv15">
                                                    <span class="importante">@lang('general.important')</span>
                                                @lang('general.the_receipt_will_be_charged')
                                                </div>
                                                <div id="apDiv15">
                                                    <span class="importante">
                                                  @lang('general.we_are_not_responsible')
                                                    </span>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">
                                                <div id="apDiv18">@lang('general.review_the_merchandise')</div>
                                            </th>

                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div id="infGuia"> The sender declares that he/she is not sending money, guns, chemicals, jewerly or drugs and he understands that the freight has been insured with 100% of the declare
                                                    value. Understanding that if there is any total lost,there will be refound of the 100 % of the declare value.if there is partial lost,the refund will be proportional of the lost
                                                    weight. We are not responsable of broken or damage merchandise .I certify that this shipment does not contain any unauthorized explosive,destructive devices or hazardous
                                                    materials. I consent to a search of this shipment .I am aware that this endorsement and original signature,along with other shipping documents will be retained on file until the
                                                    shipment is delivered.shipper hereby consents to a search or ispection of the cargo,including screening of the cargo.
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
							                <td style="width: 50%"></td>
							                <td style="text-align: right;padding-top: 10px;">__________________________</td>
							            </tr>
							            <tr>
							                <td style="width: 50%"></td>
							                <td style="text-align: right; font-weight: bold;">Received: </td>
							            </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if ($i === 1)
                    <tr>
                        <td colspan="2"><div id="space">&nbsp;</div></td>
                    </tr>
                @endif
            @endfor
        </table>
        {{-- {{ exit() }} --}}

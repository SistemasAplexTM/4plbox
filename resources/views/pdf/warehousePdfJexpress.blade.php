<!DOCTYPE>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $documento->num_warehouse }}</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        table {
           width: 100%;
           border-collapse: collapse;
           align: center;
           margin-bottom:5px;
        }
        th, td {
           vertical-align: middle;
           border-collapse: collapse;
        }
        .agency_title{
            text-align: right;
            font-size:13px;
            padding: 0px 8px;
        }
        .title_doc{
            font-weight:600;
            font-size:24px;
            padding: 0px 8px;
        }
        .separador {
            padding: 8px 8px;
        }
        .datos_terceros {
            text-align:left;
            background-color:#EEE;
            border: 1px solid #000;
            font-size:13px;
        }
        .datos_company {
            text-align:left;
            font-size:13px;
        }
        .separador_interno td {
            padding: 0px 8px;
        }
        .titles_table, th{
            background-color:#EEE;
            border: 1px solid #000;
        }
        .table_grid {
            text-align:center;
            font-size:13px;
        }
        .table_numero {
            border-bottom:1px solid #000;
            border-top:1px solid #000;

        }
        .acuerdo {
            font-size:9px;
            margin-top:10px;
            text-align:justify;
            border-top:1px solid #000;
        }
        .table_firma {
            font-size:12px;
            text-align:right;
        }
        #apDiv11{
            border-top: 1px solid;
        }
    </style>

    </head>
    <?php
    $total_declarado = 0;
    $total_piezas = 0;
    $total_libras = 0;
    $total_volumen = 0;
    $total_volumen_cft = 0;
    $total_cmt = 0;
    ?>
    <body>
        @if(count($detalle) > 0)
            @foreach($detalle as $val)
                <?php
                    $total_piezas += $val->piezas;
                    $total_declarado += $val->valor;
                    $total_libras += $val->peso;
                    $total_volumen += $val->volumen * $val->piezas;
                    //cft = lxhxw / 1728 (pie cubico)
                    //cmt = cft/35.315 (metro cubico)
                ?>
            @endforeach
        @endif
        <table>
          <tr>
            <td colspan="2" rowspan="5" style="">
              @if(env('APP_DEPELOPER'))
                <img src="{{ public_path() . '/storage/' }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? trim($documento->agencia_logo) : 'logo.png') }}" style="width: 150px"/>
              @else
                <img src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? trim($documento->agencia_logo) : 'logo.png') }}" style="width: 150px"/>
              @endif
            </td>
            <td colspan="2" class="agency_title title_doc" style="">{{ ((isset($documento->agencia) and $documento->agencia != '') ? $documento->agencia : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" rowspan="3" class="agency_title">
                <div>{{ ((isset($documento->agencia_dir) and $documento->agencia_dir != '') ? $documento->agencia_dir : '') }}</div>
                <div>{{ ((isset($documento->agencia_ciudad) and $documento->agencia_ciudad != '') ? $documento->agencia_ciudad : '') }}, {{ ((isset($documento->agencia_depto_prefijo) and $documento->agencia_depto_prefijo != '') ? $documento->agencia_depto_prefijo : $documento->agencia_depto) }} {{ ((isset($documento->agencia_zip) and $documento->agencia_zip != '') ? $documento->agencia_zip : '') }}</div>
                <div>{{ ((isset($documento->agencia_tel) and $documento->agencia_tel != '') ? $documento->agencia_tel : '') }}</div>
                <div>{{ ((isset($documento->agencia_email) and $documento->agencia_email != '') ? $documento->agencia_email : '') }}</div>
            </td>
          </tr>

        </table>
        <table class="table_numero">
          <tr>
            <td><strong>Date:</strong></td>
            <td>{{ ((isset($documento->created_at) and $documento->created_at != '') ? date('m-d-y', strtotime($documento->created_at)) : '') }}</td>
            <td class="agency_title title_doc">Receipt:</td>
            <td class="agency_title title_doc">{{ ((isset($documento->num_warehouse) and $documento->num_warehouse != '') ? $documento->num_warehouse : '') }}</td>
          </tr>
        </table>

        <table class="datos_terceros">
          <tr>
            <td class="separador">
            <table>
          <tr>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Shipper:</strong></td>
            <td style="width:3px;">&nbsp;</td>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Consignee:</strong></td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_nomfull) and $documento->ship_nomfull != '') ? $documento->ship_nomfull : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_nomfull) and $documento->cons_nomfull != '') ? $documento->cons_nomfull : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_dir) and $documento->ship_dir != '') ? $documento->ship_dir : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_dir) and $documento->cons_dir != '') ? $documento->cons_dir : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_ciudad) and $documento->ship_ciudad != '') ? $documento->ship_ciudad : '') }}, {{ ((isset($documento->ship_zip) and $documento->ship_zip != '') ? $documento->ship_zip : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_ciudad) and $documento->cons_ciudad != '') ? $documento->cons_ciudad : '') }}, {{ ((isset($documento->cons_zip) and $documento->cons_zip != '') ? $documento->cons_zip : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_tel) and $documento->ship_tel != '') ? $documento->ship_tel : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_tel) and $documento->cons_tel != '') ? $documento->cons_tel : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_email) and $documento->ship_email != '') ? $documento->ship_email : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_email) and $documento->cons_email != '') ? $documento->cons_email : '') }}</td>
          </tr>
        </table>
            </td>
          </tr>
        </table>

        <table class="datos_company separador_interno">
          <tr>
            <td style="width:15%;"><strong>Agent:</strong></td>
            <td style="width:45%;">{{ $documento->cliente }}</td>
            <td style="width:20%;"><strong>Zone:</strong></td>
            <td style="width:20%;">{{ $documento->cliente_zona }}</td>
          </tr>
          <tr>
            <td><strong>Destination:</strong></td>
            <td>{{ $documento->cliente_ciudad }}</td>
            <td><strong>Declared Value:</strong></td>
            <td>$ {{ $total_declarado }}</td>
          </tr>
          <tr>
            <td><strong>Country:</strong></td>
            <td>{{ $documento->cliente_pais }}</td>
            <td><strong>User:</strong></td>
            <td>{{ ((isset($documento->usuario) and $documento->usuario != '') ? $documento->usuario : '') }}</td>
          </tr>
        </table>

        <table border="1" class="table_grid">
          <tr>
            <th scope="col">Total Pieces</th>
            <th colspan="2" scope="col">Total Weight</th>
            <th colspan="2" scope="col">Total Weight - Volume</th>
            <th colspan="2" scope="col">Total Volume</th>
            </tr>
          <tr>
            <td style="width:15%;">{{ $total_piezas }} Pcs</td>
            <td style="width:15%;">{{ $total_libras }} Lb</td>
            <td style="width:15%;">{{ number_format($total_libras / 2.20462,2) }} Kl</td>

            <td style="width:15%;">{{ number_format((isset($total_volumen) ? ceil($total_volumen) : 0),0) }} Lb</td>
            <td style="width:15%;">{{ number_format(((isset($total_volumen) ? ceil($total_volumen) : 0) / 2.204622), 2) }} Kl</td>

            <td style="width:15%;">{{ $pie = ceil(number_format(($total_volumen * 166 / 1728), 2)) }} cuft</td>
            <td style="width:10%;">{{ ceil(number_format(($pie / 35.315), 2)) }} cbm</td>
          </tr>
        </table>
        <table border="1" class="table_grid separador_interno">
          <tr>
            <th scope="col" style="width:7%;">Pieces</th>
            <th scope="col" style="width:7%;">Weight</th>
            <th scope="col" style="width:15%;">L x W x H</th>
            <th scope="col">Description Of Content</th>
            <th scope="col">Tracking</th>
          </tr>
          <tbody>
            @foreach($detalle as $val)
              <tr>
                <td>{{ $val->piezas }}</td>
                <td>{{ $val->peso2 }}</td>
                <td>{{ $val->largo }} x {{ $val->ancho }} x {{ $val->alto }}</td>
                <td style="text-align:left;">{{ $val->contenido }}</td>
                <td style="text-align:left;">{{ str_replace(',', ', ',$val->trackings) }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            @if(env('APP_CLIENT') == 'worldcargo')
            <tr>
              <td colspan="4">
                <table>
                  <tr>
                    <td>
                        <div id="apDiv10">
                            <table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr>
                                    <td><b>@lang('general.value'): (Flete+Impuesto)</b></td>
                                    <?php $sub = ($total_declarado * $documento->impuesto / 100) + ($total_libras * $documento->valor); ?>
                                    <td align="right">$ {{ $subtotal = number_format($sub, 2) }} </td>
                                </tr>

                                <tr>
                                    <td><b>@lang('general.insurance'): </b></td>
                                    <?php $seguro = $documento->seguro_cobrado; ?>
                                    <td align="right">$ {{ number_format($seguro, 2) }} </td>
                                </tr>
                                <tr>
                                    <td><b>@lang('general.discount'):</b></td>
                                    <td align="right">$ {{ number_format($documento->descuento, 2) }} </td>
                                </tr>
                                <tr>
                                    <td><b>@lang('general.others'):</b></td>
                                    <td align="right">$ {{ number_format($documento->cargos_add, 2) }} </td>
                                </tr>
                                <tr>
                                    <td><b>Sub Total:</b></td>
                                    <td align="right">$ {{ $total = number_format($sub + $seguro + $documento->cargos_add - $documento->descuento, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div >
                            <div id="apDiv11">
                                <table width="100%" border="0" cellspacing="1" cellpadding="0">

                                    <tr>
                                        <td><b>Total:</b></td>
                                        <td align="right"><b><span style="font-size:14px;color:#F00">$ {{ $total }}</span></b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
              </td>
              <td colspan="2" valign="top">
                <table>
                  <tr>
                    <td style="height: 60px;color: #5e5e5e;font-size: 15px;">Código PoBox del Cliente:</td>
                    <td style="font-size: 15px;font-weight: bold;"><div>{{ ((isset($documento->cons_pobox) and $documento->cons_pobox != '') ? $documento->cons_pobox : '') }}</div></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div style="width: 75%;margin: 0 auto;text-align: center;color: red;font-weight: bold;">SUS PAQUETES SE ENVIARÁN A VENEZUELA UNA VEZ CUMPLIDO 10 DÍAS EN NUESTRA BODEGA</div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            @endif
          </tfoot>
        </table>
        @if(env('APP_CLIENT') == 'worldcargo')
          <table border="0">
            <tr>
              <td style="margin: 0 auto;text-align: center;font-size: 13px;font-weight: bold;padding: 5px;"><span style="color: red;">¡IMPORTANTE!</span> EL RECIBO SE COBRARÁ POR EL VALOR MAYOR, (PESO O VOLUMEN) PARA LOS ENVÍOS AÉREOS.</td>
            </tr>
            <tr>
              <td style="margin: 0 auto;font-size: 13px;font-weight: bold;padding: 5px;">
                Todo lo que venga por correo interno de los estados unidos USPS, no nos hacemos responsables, ya que no hay manera de hacer rastreo no tiene prueba de entrega
              </td>
            </tr>
            <tr>
              <td style="margin: 0 auto;font-size: 13px;font-weight: bold;color: red;padding: 5px;">
                NO NOS HACEMOS RESPONSABLES DE DAÑOS EN TELEVISORESNO QUE NO VIAJEN EN CAJA DE MADERA.
              </td>
            </tr>
          </table>

          <table class="acuerdo separador_interno">
            <tr>
              <td style="padding-right: 80px;">
                <p>La compañía, <span style="font-weight: bold;">WORLDCARGO EXPORT</span>, no es responsable por el contenido de los paquetes, y en caso de perdida el monto máximo que asumirá será de USD$ 100 por warehouse receipt .</p>
              </td>
              <td style="padding-right: 10px;">
                <p><span style="font-weight: bold;">WORLDCARGO EXPORT</span>, is not responsible for the content of the packages in case of lost or damage of merchandise the maximun payment will be $ 100.00 per WR .</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: center;font-weight: bold;font-size: 10px;padding-bottom: 5px;">
                REVISAR LA MERCANCÍA ANTES DE RETIRARLA DE LA OFICINA EN VALENCIA, LUEGO DE ESTO, NO SE ACEPTARÁ NINGÚN RECLAMO.
              </td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: center;font-weight: bold;font-size: 10px;padding-bottom: 5px;">
                ALL CARGO TENDERED TO WORLD CARGO EXPORT, INC IS SUBJECT TO INSPECTION AND SEARCH PER TSA REGULATIONS
              </td>
            </tr>
          </table>

        @endif
        <table class="table_firma separador_interno" style="{{ (env('APP_CLIENT') == 'worldcargo') ? 'padding-top:30px;' : '' }}">
          <tr>
            <td><strong>Printed:</strong></td>
            <td style="width:22%">{{ date('m-d-y h:i:s a', time()) }}</td>
          </tr>
          <tr style="height:40px;">
            <td><strong>Sign: </strong></td>
            <td style="border-bottom: 1px solid #000; vertical-align:text-bottom !important;">&nbsp;</td>
          </tr>
        </table>

        @if(env('APP_CLIENT') != 'worldcargo')
          <table class="acuerdo separador_interno">
            <tr>
              <td><p> @lang('general.i_certify1')<em> @lang('general.i_certify2')</em> @lang('general.i_certify3')</p></td>
            </tr>
          </table>
        @endif
    </body>
</html>

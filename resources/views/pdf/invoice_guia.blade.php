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
      margin-bottom: 5px;
    }

    th,
    td {
      vertical-align: middle;
      border-collapse: collapse;
    }

    .agency_title {
      text-align: right;
      font-size: 13px;
      padding: 0px 8px;
    }

    .title_doc {
      font-weight: 600;
      font-size: 24px;
      padding: 0px 8px;
    }

    .separador {
      padding: 8px 8px;
    }

    .datos_terceros {
      text-align: left;
      background-color: #EEE;
      border: 1px solid #000;
      font-size: 13px;
    }

    .datos_company {
      text-align: left;
      font-size: 13px;
    }

    .separador_interno td {
      padding: 0px 8px;
    }

    .titles_table,
    th {
      background-color: #EEE;
      border: 1px solid #000;
    }

    .table_grid {
      text-align: center;
      font-size: 13px;
    }

    .table_numero {
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;

    }

    .acuerdo {
      font-size: 9px;
      margin-top: 10px;
      text-align: justify;
      border-top: 1px solid #000;
    }

    .table_firma {
      font-size: 12px;
      text-align: right;
    }

    #apDiv11 {
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
    $pa_id = '';
    ?>

<body>
  {{-- <pre> --}}
  <?php //print_r($documento); ?>
  <?php //print_r($detalle); ?>

  {{-- </pre> --}}
  <?php //exit(); ?>
  @if(count($detalle) > 0)
  @foreach($detalle as $val)
  <?php
                    $total_piezas += $val->piezas;
                    $total_declarado += $val->valor;
                    $total_libras += $val->peso;
                    $total_volumen += $val->volumen;
                    $pa_id = $val->posicion_arancelaria_id;
                    //cft = lxhxw / 1728 (pie cubico)
                    //cmt = cft/35.315 (metro cubico)
                ?>
  @endforeach
  @endif
  <table>
    <tr>
      <td colspan="2" rowspan="5" style="width:300px;height: 100px;">
        @if(env('APP_DEPELOPER'))
        <img
          src="{{ public_path() . '/storage/' }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? trim($documento->agencia_logo) : 'logo.png') }}"
          style="width: 100%" />
        @else
        <img
          src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? trim($documento->agencia_logo) : 'logo.png') }}"
          style="width: 100%" />
        @endif
      </td>
      <td colspan="2" class="agency_title title_doc" style="">
        {{ ((isset($documento->agencia) and $documento->agencia != '') ? $documento->agencia : '') }}</td>
    </tr>
    <tr>
      <td colspan="2" class="agency_title">
        {{ ((isset($documento->agencia_dir) and $documento->agencia_dir != '') ? $documento->agencia_dir : '') }}</td>
    </tr>
    <tr>
      <td colspan="2" class="agency_title">
        {{ ((isset($documento->agencia_ciudad) and $documento->agencia_ciudad != '') ? $documento->agencia_ciudad : '') }},
        {{ ((isset($documento->agencia_depto_prefijo) and $documento->agencia_depto_prefijo != '') ? $documento->agencia_depto_prefijo : $documento->agencia_depto) }}
        {{ ((isset($documento->agencia_zip) and $documento->agencia_zip != '') ? $documento->agencia_zip : '') }}</td>
    </tr>
    <tr>
      <td colspan="2" class="agency_title">
        {{ ((isset($documento->agencia_tel) and $documento->agencia_tel != '') ? $documento->agencia_tel : '') }}</td>
    </tr>
    <tr>
      <td colspan="2" class="agency_title">
        {{ ((isset($documento->agencia_email) and $documento->agencia_email != '') ? $documento->agencia_email : '') }}
      </td>
    </tr>
  </table>
  <table class="table_numero">
    <tr>
      <td><strong>Date:</strong></td>
      <td>
        {{ ((isset($documento->created_at) and $documento->created_at != '') ? date('m-d-y', strtotime($documento->created_at)) : '') }}
      </td>
      <td class="agency_title title_doc">Receipt:</td>
      <td class="agency_title title_doc">
        {{ ((isset($documento->num_warehouse) and $documento->num_warehouse != '') ? $documento->num_warehouse : '') }}
      </td>
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
            <td>
              {{ ((isset($documento->ship_nomfull) and $documento->ship_nomfull != '') ? $documento->ship_nomfull : '') }}
            </td>
            <td>&nbsp;</td>
            <td>
              {{ ((isset($documento->cons_nomfull) and $documento->cons_nomfull != '') ? $documento->cons_nomfull : '') }}
            </td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_dir) and $documento->ship_dir != '') ? $documento->ship_dir : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_dir) and $documento->cons_dir != '') ? $documento->cons_dir : '') }}</td>
          </tr>
          <tr>
            <td>
              {{ ((isset($documento->ship_ciudad) and $documento->ship_ciudad != '') ? $documento->ship_ciudad : '') }},
              {{ ((isset($documento->ship_zip) and $documento->ship_zip != '') ? $documento->ship_zip : '') }}</td>
            <td>&nbsp;</td>
            <td>
              {{ ((isset($documento->cons_ciudad) and $documento->cons_ciudad != '') ? $documento->cons_ciudad : '') }},
              {{ ((isset($documento->cons_zip) and $documento->cons_zip != '') ? $documento->cons_zip : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_tel) and $documento->ship_tel != '') ? $documento->ship_tel : '') }}</td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_tel) and $documento->cons_tel != '') ? $documento->cons_tel : '') }}</td>
          </tr>
          <tr>
            <td>{{ ((isset($documento->ship_email) and $documento->ship_email != '') ? $documento->ship_email : '') }}
            </td>
            <td>&nbsp;</td>
            <td>{{ ((isset($documento->cons_email) and $documento->cons_email != '') ? $documento->cons_email : '') }}
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="datos_company separador_interno">
    <tr>
      <td style="width:50%;vertical-align: text-top;">
        <strong>Observaciones:</strong>
        <br>
        {{ $documento->observaciones }}
      </td>
      <td>
        <strong>Destination:</strong> {{ $documento->cliente_ciudad }} - {{ $documento->tipo_embarque }}
        <br>
        <strong>Declared Value:</strong> $ {{ $total_declarado }}
        <br>
        <strong>Payment Method:</strong>
        {{ ((isset($documento->forma_pago) and $documento->forma_pago != '') ? $documento->forma_pago : '') }} -
        {{ $documento->tipo_pago }}
      </td>
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
      <td style="width:15%;">{{ (number_format($total_libras / 2.20462,2)) }} Kl</td>
      <td style="width:15%;">{{ (number_format((isset($total_volumen) ? ($total_volumen) : 0),0)) }} Lb</td>
      <td style="width:15%;">
        {{ (number_format(((isset($total_volumen) ? ($total_volumen) : 0) / 2.204622), 2)) }} Kl</td>
      <td style="width:15%;">{{ $pie = (number_format(($total_volumen * 166 / 1728), 2)) }} cuft</td>
      <td style="width:10%;">{{ $metro = (number_format(($pie / 35.315), 2)) }} cbm</td>
    </tr>
  </table>
  <table border="1" class="table_grid separador_interno">
    @if(env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana')
    <thead>
      <tr>
        <th scope="col" width="3%">Pc.</th>
        <th scope="col" width="3%">Qty.</th>
        <th scope="col" width="10%">Dimensions</th>
        <th scope="col" width="27%">Tracking</th>
        <th scope="col">Content</th>
        <th scope="col" style="width: 10%">Weight<br>Lb / Kg</th>
        {{-- <th scope="col" style="width: 7%">Weight<br>Kg</th> --}}
        <th scope="col" style="width: 10%">Vol<br>Lb / Kg</th>
        {{-- <th scope="col" style="width: 7%">Vol<br>Kg</th> --}}
        {{-- <th scope="col" style="width: 7%">Weight<br>Ft3</th> --}}
        <th scope="col" style="width: 10%">Vol<br>Ft3 / Mt3</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detalle as $val)
      <tr>
        <td>P{{ $val->paquete }}</td>
        <td>{{ $val->piezas }}</td>
        <td>{{ $val->largo . 'x'.$val->ancho. 'x'. $val->alto }}</td>
        <td style="text-align: left;">{{ str_replace(',', ' ',$val->trackings) }}</td>
        <td style="text-align: left;">{{ str_replace(',', ' ',$val->contenido) }} </td>
        <td>{{ $val->peso2 }} / {{ (number_format($val->peso2 / 2.205)) }}</td>
        {{-- <td>{{ (number_format($val->peso2 / 2.205)) }}</td> --}}
        <td>{{ ($val->volumen) }} / {{ (number_format($val->volumen / 2.204622)) }}</td>
        {{-- <td>{{ (number_format($val->volumen / 2.204622)) }}</td> --}}
        {{-- <td>{{ (number_format($val->volumen * 166 / 1728)) }}</td> --}}
        <td>{{ (number_format($val->volumen * 166 / 1728)) }} /
          {{ (number_format(($val->volumen * 166 / 1728) / 35.315)) }}</td>
      </tr>
      @endforeach
    </tbody>
    @else
    <tr>
      <th scope="col" style="width:10%;">Length</th>
      <th scope="col" style="width:10%;">Width</th>
      <th scope="col" style="width:10%;">Heigth</th>
      <th scope="col" style="width:10%;">Pieces</th>
      <th scope="col" style="width:10%;">Weight</th>
      <th scope="col">Description Of Content</th>
    </tr>
    <tbody>
      @foreach($detalle as $val)
      <tr>
        <td>{{ $val->largo }}</td>
        <td>{{ $val->ancho }}</td>
        <td>{{ $val->alto }}</td>
        <td>{{ $val->piezas }}</td>
        <td>{{ $val->peso2 }}</td>
        <td style="text-align:left;">{{ $val->contenido }}</td>
      </tr>
      @endforeach
    </tbody>
    @endif
    <tfoot>
      <tr>
        <td colspan="{{ (env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana') ? '8' : '6' }}">&nbsp;
        </td>
      </tr>
      @if(env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana')
      <tr>
        <td colspan="4">
          <table>
            <tr>
              <td>
                <div id="apDiv10">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    {{-- <tr> --}}
                    <?php
                                    $flete = 0;
                                    $impuesto = 0;
                                      if($documento->tipo_embarque_id == '8'){
                                        if($pa_id == '1'){
                                          $flete = $pie * $documento->valor_libra;
                                        }else{
                                          $flete = $metro * $documento->valor_libra;
                                        }
                                      }else{
                                        // if($total_libras > $total_volumen){
                                          $flete = ($documento->flete);
                                          $impuesto = ($total_declarado * $documento->impuesto / 100);
                                        // }else{
                                        //   $sub = ($total_declarado * $documento->impuesto / 100) + ($documento->flete);
                                        // }
                                      }
                                      $subtotal = number_format(($flete + $impuesto), 2)
                                    ?>
                    {{-- <td><b>@lang('general.value'): (Flete+Impuesto) </b></td>
                                    <td align="right">$ {{ $subtotal }}
              </td>
            </tr> --}}
            <tr>
              <td><b>Freight: </b></td>
              <td align="right">$ {{ number_format(($documento->flete), 2) }} </td>
            </tr>
            <tr>
              <td><b>Tax: </b></td>
              <td align="right">$ {{ number_format(($total_declarado * $documento->impuesto / 100), 2) }} </td>
            </tr>

            <tr>
              <td><b>@lang('general.insurance'): </b></td>
              <?php $seguro = $documento->seguro_cobrado; ?>
              <td align="right">$ {{ number_format(($seguro), 2) }} </td>
            </tr>
            <tr>
              <td><b>@lang('general.discount'):</b></td>
              <td align="right">$ {{ number_format(($documento->descuento), 2) }} </td>
            </tr>
            <tr>
              <td><b>@lang('general.others'):</b></td>
              <td align="right">$ {{ number_format(($documento->cargos_add), 2) }} </td>
            </tr>
            {{-- <tr>
                                    <td><b>Sub Total:</b></td>
                                    <td align="right">$ {{ $total = number_format(($flete + $seguro + $documento->cargos_add - $documento->descuento), 2) }}
        </td>
      </tr> --}}
  </table>
  </div>
  <div>
    <div id="apDiv11">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">

        <tr>
          <td><b>Total:</b></td>
          <td align="right"><b><span style="font-size:14px;color:#F00">$
                {{ $total = number_format(($flete + $impuesto + $seguro + $documento->cargos_add - $documento->descuento), 2) }}</span></b>
          </td>
        </tr>
      </table>
    </div>
  </div>
  </td>
  </tr>
  </table>
  </td>
  <td colspan="{{ (env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana') ? '4' : '3' }}" valign="top">
    <table>
      <tr>
        <td style="height: 60px;color: #5e5e5e;font-size: 13px;width: 55%;">PoBox:</td>
        <td style="font-size: 25px;font-weight: bold;">
          <div>{{ ((isset($documento->cons_pobox) and $documento->cons_pobox != '') ? $documento->cons_pobox : '') }}
          </div>
        </td>
      </tr>
      @if(env('APP_CLIENT') == 'worldcargo')
      <tr>
        <td colspan="2">
          <div style="width: 75%;margin: 0 auto;text-align: center;color: red;font-weight: bold;">Pago a nombre de World
            Cargo</div>
        </td>
      </tr>
      @endif
    </table>
  </td>
  </tr>
  @endif
  </tfoot>
  </table>
  @if(env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana')
  <table border="0">
    <tr>
      <td style="margin: 0 auto;text-align: center;font-size: 13px;font-weight: bold;padding: 5px;"><span
          style="color: red;">¡IMPORTANTE!</span> EL RECIBO SE COBRARÁ POR EL VALOR MAYOR, (PESO O VOLUMEN) PARA LOS
        ENVÍOS AÉREOS.</td>
    </tr>
    @if(env('APP_CLIENT') == 'worldcargo')
    <tr>
      <td style="margin: 0 auto;font-size: 13px;font-weight: bold;padding: 5px;">
        Todo lo que venga por correo interno de los estados unidos USPS, no nos hacemos responsables, ya que no hay
        manera de hacer rastreo no tiene prueba de entrega
      </td>
    </tr>
    @endif
    @if(env('APP_CLIENT') == 'worldcargo')
    <tr>
      <td style="margin: 0 auto;font-size: 13px;font-weight: bold;color: red;padding: 5px;">
        SUS PAQUETES SE ENVIARÁN A VENEZUELA UNA VEZ CUMPLIDO 10 DÍAS EN NUESTRA BODEGA
      </td>
    </tr>
    @endif
  </table>

  <table class="acuerdo separador_interno">
    <?php  $agencia = ((isset($documento->agencia) and $documento->agencia != '') ? $documento->agencia : '') ?>
    <tr>
      <td style="padding-right: 80px;">
        <p>La compañía, <span style="font-weight: bold;">{{ $agencia }} </span>, no es responsable por el contenido de
          los paquetes, y en caso de perdida el monto máximo que asumirá será de USD$ 100 por warehouse receipt .</p>
      </td>
      <td style="padding-right: 10px;">
        <p><span style="font-weight: bold;">{{ $agencia }} </span>, is not responsible for the content of the packages
          in case of lost or damage of merchandise the maximun payment will be $ 100.00 per WR .</p>
      </td>
    </tr>
    @if(env('APP_CLIENT') == 'worldcargo')
    <tr>
      <td colspan="2" style="text-align: center;font-weight: bold;font-size: 10px;padding-bottom: 5px;">
        REVISAR LA MERCANCÍA ANTES DE RETIRARLA DE LA OFICINA EN VALENCIA, LUEGO DE ESTO, NO SE ACEPTARÁ NINGÚN RECLAMO.
      </td>
    </tr>
    @endif
    <tr>
      <td colspan="2" style="text-align: center;font-weight: bold;font-size: 10px;padding-bottom: 5px;">
        ALL CARGO TENDERED TO {{ $agencia }} IS SUBJECT TO INSPECTION AND SEARCH PER TSA REGULATIONS
      </td>
    </tr>
  </table>

  @endif
  <table class="table_firma separador_interno"
    style="{{ (env('APP_CLIENT') == 'worldcargo' || env('APP_CLIENT') == 'colombiana') ? 'padding-top:30px;' : '' }}">
    <tr>
      <td><strong>Printed:</strong></td>
      <td style="width:22%">{{ date('m-d-y h:i:s a', time()) }}</td>
    </tr>
    <tr style="height:40px;">
      <td><strong>Sign: </strong></td>
      <td style="border-bottom: 1px solid #000; vertical-align:text-bottom !important;">&nbsp;</td>
    </tr>
  </table>

  @if(env('APP_CLIENT') != 'worldcargo' || env('APP_CLIENT') == 'colombiana')
  <table class="acuerdo separador_interno">
    <tr>
      <td>
        <p> @lang('general.i_certify1')<em> @lang('general.i_certify2')</em> @lang('general.i_certify3')</p>
      </td>
    </tr>
  </table>
  @endif
</body>

</html>
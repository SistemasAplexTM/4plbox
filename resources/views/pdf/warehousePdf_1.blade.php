<!DOCTYPE>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Warehouse No: </title>

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
    </style>

    </head>
    <?php 
    $total_declarado = 0;
    $total_piezas = 0;
    $total_libras = 0;
    $total_volumen = 0;
    $total_volumen_cft = 0;
    $total_volumen_cmt = 0;
    ?>
    <body>
        @if(count($detalle) > 0)
            @foreach($detalle as $val)
                <?php 
                    $total_piezas += $val->valor;
                    $total_declarado += $val->valor;
                    $total_libras += $val->peso;
                    $total_volumen += $val->volumen;
                    $total_volumen_cft += $val->volumen / 2.204622;
                ?>
            @endforeach
        @endif
        <table>
          <tr>
            <td colspan="2" rowspan="5" style="width:300px;">
                <img src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? $documento->agencia_logo : 'logo.png') }}" height="120px" style="width: 100%"/>
            </td>
            <td colspan="2" class="agency_title title_doc" style="">{{ ((isset($documento->agencia) and $documento->agencia != '') ? $documento->agencia : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($documento->agencia_dir) and $documento->agencia_dir != '') ? $documento->agencia_dir : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($documento->agencia_ciudad) and $documento->agencia_ciudad != '') ? $documento->agencia_ciudad : '') }}, {{ ((isset($documento->agencia_depto_prefijo) and $documento->agencia_depto_prefijo != '') ? $documento->agencia_depto_prefijo : $documento->agencia_depto) }} {{ ((isset($documento->agencia_zip) and $documento->agencia_zip != '') ? $documento->agencia_zip : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($documento->agencia_tel) and $documento->agencia_tel != '') ? $documento->agencia_tel : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($documento->agencia_email) and $documento->agencia_email != '') ? $documento->agencia_email : '') }}</td>
          </tr>
        </table>
        <table class="table_numero">
          <tr>
            <td><strong>Date:</strong></td>
            <td>{{ ((isset($documento->created_at) and $documento->created_at != '') ? date('d-m-y', strtotime($documento->created_at)) : '') }}</td>
            <td class="agency_title title_doc">Receipt:</td>
            <td class="agency_title title_doc">{{ ((isset($documento->num_warehouse) and $documento->num_warehouse != '') ? $documento->num_warehouse : '') }}</td>
          </tr>
        </table>

        <table class="datos_terceros">
          <tr>
            <td class="separador">
            <table>
          <tr>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Consignee:</strong></td>
            <td style="width:3px;">&nbsp;</td>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Shipper:</strong></td>
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
            <td style="width:15%;">{{ number_format($total_libras * 2.20462,2) }} Kl</td>
            <td style="width:15%;">{{ isset($val->volumen) ? $val->volumen : 0 }} Lb</td>
            <td style="width:15%;">{{ number_format(((isset($val->volumen) ? $val->volumen : 0) / 2.204622), 2) }} Kl</td>
            <td style="width:15%;">{{ $pie = number_format(((isset($val->largo) ? $val->largo : 0) * (isset($val->ancho) ? $val->ancho : 0) * (isset($val->alto) ? $val->alto : 0)) / 1728, 2) }} cuft</td>
            <td style="width:10%;">0.65 cbm</td>
          </tr>
        </table>
        <table border="1" class="table_grid separador_interno">
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
          <tfoot>
              <tr>
                <td colspan="6">&nbsp;</td>
              </tr>
          </tfoot>
        </table>
        <table class="table_firma separador_interno">
          <tr>
            <td><strong>Printed:</strong></td>
            <td style="width:22%">{{ date('d-m-y h:i:s a', time()) }}</td>    
          </tr>
          <tr style="height:40px;">
            <td><strong>Sign: </strong></td>
            <td style="border-bottom: 1px solid #000; vertical-align:text-bottom !important;">&nbsp;</td>    
          </tr>
        </table>

        <table class="acuerdo separador_interno">
          <tr>
            <td><p>Certifico que el contenido del presente envío, se ajusta a lo declarado en la guía <em>y me hago directamente responsable, ante las autoridades nacionales y extranjeras por el contenido, valor declarado y este envío cumple los parámetros aduaneros del país de destino</em>. Adicionalmente certifico que el envío no contiene dinero, valores negociables, ni objetos de prohibido transporte, según las normas internacionales y la legislación aplicable en el país de destino u origen, Por lo tanto, acuerdo indemnizar y absolver, por cualquier reclamo, obligación y/o costo debido al incumplimineto de cualquier ley o regulación aplicable en el país de origen o destino, de la mercancía amparada en este documento.</p></td>
          </tr>
        </table>
    </body>
</html>
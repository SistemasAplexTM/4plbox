<!DOCTYPE>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@lang('general.report')</title>

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
        .detalle{
        	font-size: 12px;
        	margin-top: 20px;
        }
        .detalle td {
            padding: 0px 8px;
            border-bottom: 1px solid #000;
        }
        th{
            background-color:#EEE;
            border: 1px solid #000;
        }
        .right, .right2{
        	text-align: right;
        }
        .right2{
        	padding: 0px 8px;
        }
    </style>

    </head>
    <?php
    $total_piezas = 0;
    $total_peso = 0;
    $total_volumen = 0;
    $total_volumen_cft = 0;

    ?>
    <body>
        @if(count($data) > 0)
            @foreach($data as $val)
                <?php
                    $total_piezas += $val->piezas;
                    $total_peso += $val->peso;
                    $total_volumen += $val->volumen;
                    $total_volumen_cft += $val->volumen / 2.204622;
                ?>
            @endforeach
        @endif
        <table>
          <tr>
            <td colspan="2" rowspan="5" style="width:300px;">
              @if(env('APP_DEPELOPER'))
                <img src="{{ public_path() . '/storage/' }}/{{ ((isset($agencia->logo) and $agencia->logo != '') ? $agencia->logo : 'logo.png') }}" height="120px" style="width: 100%"/>
              @else
                <img src="{{ asset('storage/') }}/{{ ((isset($agencia->logo) and $agencia->logo != '') ? $agencia->logo : 'logo.png') }}" height="120px" style="width: 100%"/>
              @endif
            </td>
            <td colspan="2" class="agency_title title_doc" style="">{{ ((isset($agencia->descripcion) and $agencia->descripcion != '') ? $agencia->descripcion : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($agencia->direccion) and $agencia->direccion != '') ? $agencia->direccion : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($agencia->ciudad) and $agencia->ciudad != '') ? $agencia->ciudad : '') }}, {{ ((isset($agencia->agencia_depto_prefijo) and $agencia->agencia_depto_prefijo != '') ? $agencia->agencia_depto_prefijo : $agencia->depto) }} {{ ((isset($agencia->zip) and $agencia->zip != '') ? $agencia->zip : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($agencia->telefono) and $agencia->telefono != '') ? $agencia->telefono : '') }}</td>
          </tr>
          <tr>
            <td colspan="2" class="agency_title">{{ ((isset($agencia->email) and $agencia->email != '') ? $agencia->email : '') }}</td>
          </tr>
        </table>
        <table class="detalle" style="width: 100%">
        	<thead>
        		<tr>
        			<th style="width: 80px;"># @lang('general.receipt')</th>
        			<th@lang('general.state')</th>
        			<th style="width: 80px;">@lang('general.date')</th>
        			<th>@lang('general.shipper')</th>
        			<th>@lang('general.consignee')</th>
        			<th>@lang('general.boxes')</th>
        			<th>@lang('general.weight')</th>
        			<th>@lang('general.volume')</th>
        		</tr>
        	</thead>
        	<tbody>
        		@if(count($data) > 0)
		            @foreach($data as $val)
		        		<tr>
		        			<td>{{ $val->num_warehouse }}</td>
		        			<td>{{ $val->estado }}</td>
		        			<td>{{ $val->fecha }}</td>
		        			<td>{{ $val->shipper }}</td>
		        			<td>{{ $val->consignee }}</td>
		        			<td class="right">{{ $val->piezas }}</td>
		        			<td class="right">{{ $val->peso }}</td>
		        			<td class="right">{{ $val->volumen }}</td>
		        		</tr>
		        	@endforeach
		        @endif
        	</tbody>
        	<tfoot>
        		<tr>
        			<th colspan="5" style="text-align: right;padding-right: 8px;">@lang('general.totals')</th>
        			<th class="right2">{{ $total_piezas }}</th>
        			<th class="right2">{{ $total_peso }}</th>
        			<th class="right2">{{ $total_volumen }}</th>
        		</tr>
        	</tfoot>
        </table>

    </body>
</html>

<style>
    *{
        font-family: sans-serif;
        /*font-weight: bold;*/
    }
    #tableLabel{
    	position: absolute;
    }
    .title{
    	font-size: 11px;
    	padding:5px;
    }
    .content{
    	font-size: 27px;
    	text-align: center;
    }
</style>
<?php 
	$cont = 0;
    $toalRegistros = count($detalle);
    $contRegistros = 0;
    $piezas = 0;
?>
@foreach ($detalle as $value)
    <?php $piezas += $value->piezas ?>
@endforeach
@foreach ($detalle as $value)
	@for($i = 1; $i <= $value->piezas; $i++)
		<?php $contRegistros++ ?>
		<table border="1" cellpadding="0" cellspacing="0" id="tableLabel" style="page-break-after:{{ ($contRegistros === $piezas) ? 'avoid' : 'always' }}" width="100%">
		    <tr>
		    	<td colspan="2" style="border: 1px solid #000;">
		    		<span class="title">Airline</span>
		    		<div class="content">{{ $data->nombre_aerolinea }}</div>
		    	</td>
		    </tr>
		    <tr>
		    	<td colspan="2">
		    		<div style="padding: 0 5px;text-align: center;">
		                <img id="barcode" style="height: 80px;padding: 5px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($data->num_master, "C128",1,40,array(1,1,1)) }}" alt="barcode" />
		            </div>
		            <div style="text-align: center;">{{ $data->num_master }}</div>
		    	</td>
		    </tr>
		    <tr>
		    	<td colspan="2" style="border: 1px solid #000;">
		    		<span class="title">Air Waybill No.</span>
		    		<div class="content" style="font-size: 40px;">{{ $data->codigo_aerolinea .'-'. substr($data->num_master,3) }}</div>
		    	</td>
		    </tr>
		    <tr>
		    	<td colspan="2" style="border: 1px solid #000;">
		    		<span class="title">Consignee.</span>
		    		<div class="" style="font-size: 40px;text-align: center;">{{ $data->nombre_consignee }}</div>
		    	</td>
		    </tr>
		    <tr>
		    	<td style="width:70%;border: 1px solid #000;">
		    		<span class="title">Destination</span>
		    		<div class="content">{{ $data->ciudad_consignee }}</div>
		    	</td>
		    	<td style="border: 1px solid #000;">
		    		<span class="title">Total No. of Pieces</span>
		    		<div class="content">{{ $contRegistros .'/'. $piezas }}</div>
		    	</td>
		    </tr>
		    <tr>
		    	<td colspan="2" style="border: 1px solid #000;height: 80px;">
		    		<span class="title" style="position: absolute;">Optional Information</span>
		    		<div style="text-align: right; font-size: 40px;padding-right: 10px;">{{ $data->aeropuerto_codigo }}</div>
		    	</td>
		    </tr>
		    <?php $cont++ ?>
		</table>
	 @endfor
@endforeach

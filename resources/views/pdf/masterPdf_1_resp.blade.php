<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AWB-{{ $data->num_master }}</title>

<style>

	:root {
		/*
		#FF0000 (ROJO)
		#000099 (AZUL)
		#00CC00 (VERDE)
		#FF9900 (NARANJA)
		*/
	    --color: #FF0000;
		/*
		#FFDFDF (Rojo)
		#E1F0FF (Azul)
		*/
		--background-color: #FFDFDF;

	}
	body {
		font-family:Tahoma, Geneva, sans-serif;
		font-size:12px;
		vertical-align:middle;
	}
	table, th, td {
		border-collapse: collapse;
		align: center;
	}
	table td.special {
	    border: 3px solid /*var(--color)*/;
	}
	div.r {
	    display: inline-block; /* the default for span */
	    border: 1px solid blue;
		float:left;
	}
	.main_table {
		border-collapse: collapse;
		align: center;
		border: 1px solid /*var(--color)*/;
		width:100%;
	}
	.main_table_2 {
		border-collapse: collapse;
		align: center;
		border-bottom: 1px solid /*var(--color)*/;
		border-left: 1px solid /*var(--color)*/;
		border-right: 1px solid /*var(--color)*/;
		width:100%;
	}
	.main_table_3 {
		border-collapse: collapse;
		align: center;
		border-left: 1px solid /*var(--color)*/;
		width:100%;
	}
	.left_line{
		border-left: 1px solid /*var(--color)*/;
	}
	.rigth_line {
		border-right: 1px solid /*var(--color)*/;
	}
	.left_bottom_line {
		border-left: 1px solid /*var(--color)*/;
		border-bottom: 1px solid /*var(--color)*/;
	}
	.left_top_line {
		border-left: 1px solid /*var(--color)*/;
		border-top: 1px solid /*var(--color)*/;
	}
	.top_line {
		border-top: 1px solid /*var(--color)*/;
	}
	.top_line_dasherd {
			border-style: dashed solid none none;
			border-color: /*var(--color)*/;
	}
	.bottom_line {
		border-bottom: 1px solid /*var(--color)*/;
	}

	.margin_div {
		margin-bottom:5px;
	}
	.text_titles {
		font-size:8px;
		text-align:left;
		padding-left:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tl {
		font-size:8px;
		text-align:left;
		vertical-align:top;
		padding-left:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tr {
		font-size:8px;
		text-align:right;
		vertical-align:top;
		padding-right:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tc {
		font-size:8px;
		text-align:center;
		vertical-align:top;
		color: /*var(--color)*/;
	}
	.text_titles_c {
		font-size:8px;
		text-align:center;
		color: /*var(--color)*/;
	}
	.text_titles_j {
		font-size:9px;
		text-align:justify;
		padding: 2px;
		color: /*var(--color)*/;
	}
	.text_regular_l {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;
	}
	.text_regular_r {
		font-family:'Courier New', Courier, monospace;
		text-align:right;
		padding-right: 3px;
	}
	.text_regular_c {
		font-family:'Courier New', Courier, monospace;
		text-align:center;
	}
	.text_total {
		font-family:'Courier New', Courier, monospace;
		text-align:right;
		padding-right: 3px;
	}
	.text_normal {
		color: /*var(--color)*/;
		font-size:12px;
	}
	.big_title {
		font-size:20px;
		font-weight:700;
		color: /*var(--color)*/;
	}
	.altura{
		height:25px;
	}
	.altura_2{
		height:30px;
	}
	.altura_interna{
		height:15px;
	}
	div.relative {
		position: relative;
		top: 0px;
		left: 0px;
	}
	.bg_azul {
		background-color:var(--background-color);

	}
	.line1{
		width: 17px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-8px)
			rotate(50deg);
		position: absolute;
	}
	.line2{
		width: 17px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-13px)
			rotate(-50deg);
		position: absolute;
	}
	.line3{
		width: 14px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-10px)
			translateX(-4px)
			rotate(58deg);
		position: absolute;
	}
	.line4{
		width: 14px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-10px)
			translateX(-15px)
			rotate(-52deg);
		position: absolute;
	}
	.line6{
		width: 16px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-16px)
			rotate(-50deg);
		position: absolute;
	}
	.persons_data {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;

		margin-top:-13px;
		margin-bottom:5px;
		padding-left:3px;
		font-size:12px
	}
	.persons_data_2 {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;

		margin-top:3px;
		margin-bottom:5px;
		padding-left:3px;
		font-size:12px
	}
	@page{
		margin-left: 25px;
		margin-right: 15px;
		margin-top: 15px;
		margin-bottom: 15px;
	}
</style>



</head>

<body id="imprimir">
@for($i = 1; $i <= $cantidad; $i++)
			@if($cantidad == 1)
	        <?php $i=8; ?>
	        @endif
	        <?php $color = '#000'; ?>
	        <?php $background = '#eaeaea'; ?>
	        @if($i == 1)
	        <?php $destino = 'ORIGINAL COPY 1 (AIRPORT OF DESTINATION)' ?>
	        <?php $color = '#000'; ?>
	        <?php $background = '#eaeaea'; ?>
	        @elseif($i == 2)
	        <?php $destino = 'ORIGINAL COPY 2 (FOR CONSIGNEE)' ?>
	        <?php $color = '#ff0000'; ?>
	        <?php $background = '#ffdfdf'; ?>
	        @elseif($i == 3)
	        <?php $destino = 'ORIGINAL COPY 3 (FOR ISSUING CARRIER)' ?>
	        <?php $color = '#00CC00'; ?>
	        <?php $background = '#caffca'; ?>
	        @elseif($i == 4)
	        <?php $destino = 'ORIGINAL COPY 4 (FOR SHIPPER)' ?>
	        <?php $color = '#000099'; ?>
	        <?php $background = '#e1f0ff'; ?>
	        @elseif($i == 5)
	        <?php $destino = 'ORIGINAL COPY 5 (DELIVERY RECEIPT)' ?>
	        <?php $color = '#FF9900'; ?>
	        <?php $background = '#ffd697'; ?>
	        @elseif($i == 6)
	        <?php $destino = 'EXTRA COPY' ?>
	        @elseif($i == 7 || $i == 8)
	        <?php $destino = 'EXTRA COPY' ?>
	        @endif
	<div id="guia_master" style="">
		<table border="0" cellpadding="0" cellspacing="0" id="tableContainer" style="page-break-after:always;">
            <tr>
                <td>
					<table width="100%">
					  <tr>
					    <td width="70%" style="font-size:18px">
					    <table width="40%" align="left">
					      <tr>
					        <td width="25%" align="center">{{ $data->codigo_aerolinea  }}</td>
					        <td class="left_line" style="border-left: 1px solid {{ $color }};" style="text-align:center" width="25%">{{ $data->prefijo }}</td>
					        <td class="left_line" style="border-left: 1px solid {{ $color }};" width="50%"><div style="padding-left:3px;">{{ substr($data->num_master,3) }}</div></td>
					      </tr>
					    </table></td>
					    <td align="center"><table width="40%" style="text-align:right; font-size:18px;">
					      <tr>
					        <td width="40%">{{ $data->codigo_aerolinea  }}</td>
					        <td width="5%"> - </td>
					        <td width="55%" align="center">{{ substr($data->num_master,3) }}</td>
					      </tr>
					    </table></td>
					  </tr>
					</table>

					<table width="100%" class="main_table" style="border: 1px solid {{ $color }}">
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td height="70px" width="48.5%" colspan="6" valign="top">
					    	<table width="100%" border="0">
					          <tr class="altura">
					            <td width="24.25%" class="text_titles_tl" style="color: {{ $color }}">
									<div class="text_titles_tl" style="color: {{ $color }}">Shipper Name and Address</div>
					            </td>
					            <td width="24.25%" align="center" valign="top" class="left_bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					            	<div class="text_titles_tc margin_div" style="color: {{ $color }}">Shipper's Account Number</div>
					            	<div id="shipper_an" class="text_regular_c">&nbsp;</div>
					            </td>
					          </tr>
					          <tr>
					            <td colspan="2">
														<pre id="shipper_n" class="persons_data">{{ $data->shipper }}</pre>
														{{-- <div id="shipper_n" class="persons_data"> --}}
					                    {{-- <div>{{ $data->nombre_shipper }}</div>
					                    <div>{{ $data->direccion_shipper }}</div>
					                    <div>{{ $data->ciudad_shipper }}, {{ $data->estado_shipper }} - {{ $data->pais_shipper }} - {{ $data->zip_shipper }}</div>
					                    <div>{{ $data->telefono_shipper }}</div>
					                    <div>@lang('general.contact'): {{ $data->contacto_shipper }}</div> --}}
					                {{-- </div> --}}
					            </td>
					          </tr>
					        </table>
					    </td>
					    <td width="51.5%" colspan="2" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    <table width="100%" border="0">
					  <tr>
					    <td width="30%" height="20px">
					    <div class="text_titles_tl" style="color: {{ $color }}">Not Negotiable</div>
					    <div class="big_title" style="color: {{ $color }};font-size: 16px;">Air Waybill</div></td>
					    <td valign="bottom">
					    	<div id="aerolinea"><span style="font-size:18px; font-weight:700;">{{ $data->nombre_aerolinea }}</span></div>
					    </td>
					  </tr>
					  <tr>
					    <td width="25%" height="25px" class="text_titles_tl bottom_line" style="border-bottom: 1px solid {{ $color }};" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div style="color: {{ $color }}">Issued By</div></td>
					    <td class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    	<div id="direccion_aerolinea">{{ $data->dir_aerolinea }}</div>
					        <div id="direccion_aerolinea2">&nbsp;</div>
					        <div id="direccion_aerolinea3">&nbsp;{{-- MIAMI, FL, 33122 --}}</div>
					    </td>
					  </tr>
					  <tr>
					    <td height="15px" colspan="2" valign="middle" class="text_titles" style="color: {{ $color }}"><div>Copies 1, 2 and 3 of this Air Waybill are originals and have the same validity.</div></td>
					    </tr>
					</table>


					    </td>
					  </tr>
					  <tr height="70px" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td width="48.5%" colspan="6" valign="top">
					    	<table width="100%" border="0">
					          <tr class="altura">
					            <td width="24.25%" class="text_titles_tl" style="color: {{ $color }}">
									<div class="text_titles_tl" style="color: {{ $color }}">Consignee Name and Address</div>
					            </td>
					            <td width="24.25%" align="center" valign="top" class="left_bottom_line bg_azul" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};background-color:{{ $background }};">
					            	<div class="text_titles_tc margin_div" style="color: {{ $color }}">Consignee's Account Numberr</div>
					            	<div id="shipper_an" class="text_regular_c">&nbsp;</div>
					            </td>
					          </tr>
					          <tr>
					            <td height="70px" colspan="2">
												<pre id="consignee_n" class="persons_data">{{ $data->consignee }}</pre>
					                {{-- <div id="consignee_n" class="persons_data">
					                    <div>{{ $data->nombre_consignee }}</div>
					                    <div>{{ $data->direccion_consignee }}</div>
					                    <div>{{ $data->ciudad_consignee }}, {{ $data->estado_consignee }} - {{ $data->pais_consignee }} - {{ $data->zip_consignee }}</div>
					                    <div>{{ $data->telefono_consignee }}</div>
					                    <div>@lang('general.contact'): {{ $data->contacto_consignee }}</div>
					                </div> --}}
					            </td>
					          </tr>
					        </table>


					    </td>
					    <td width="52%" colspan="2" valign="middle" class="left_line  text_titles_j" style="border-left: 1px solid {{ $color }};color: {{ $color }}">
					    	<div style="font-size: 8px;">
					        It is agreed that the goods described herein are accepted in apparent good order and condition
					(except as noted) for carriage subject to the conditions of contract on the reverse hereof, all goods may be carried by any other means including road or any other carrier unless specific contrary instructions are given hereon by the shipper, and shipper agrees that the shipment may be carried via intermediate stopping places which the carrier deems appropriate the shipper's attention is drawn to the notice concerning carrier's limitation of liability. Shipper may increase such limitation of liability by declaring a higher value for
					carriage and paying a supplemental charge if required.
					        </div>
					    </td>
					  </tr>
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td height="70px" colspan="6" valign="top"><div class="text_titles_tl" style="color: {{ $color }}">Issuing Carrier's Agent Name and City</div>
								<pre id="carrier_n" class="persons_data_2">{{ $data->carrier }}</pre>
									{{-- <div id="carrier_n" class="persons_data_2">
	                    <div>{{ $data->nombre_carrier }}</div>
	                    <div>{{ $data->direccion_carrier }}</div>
	                    <div>{{ $data->ciudad_carrier }}, {{ $data->zip_carrier }}</div>
	                    <div>{{ $data->telefono_carrier }}</div>
	                    <div>{{ $data->contacto_carrier }}</div>
	                </div> --}}
					    </td>
					    <td width="51.5%" height="70px" valign="top" colspan="2" rowspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div class="text_titles_tl margin_div" style="color: {{ $color }}">Accounting Information</div>
					    <div id="accounting_information" class="text_regular_l">{{ $data->account_information }}</div>
					    </td>
					  </tr>
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td width="24.5%" height="30px" valign="top" colspan="5">
					    	<div class="text_titles_tl margin_div" style="color: {{ $color }}">Agent's AITA Code</div>
					        <div id="agent_iata" class="text_regular_l">{{ $data->agent_iata_code }}</div>
					    </td>
					    <td width="24.5%" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div class="text_titles_tl margin_div" style="color: {{ $color }}">Account No.</div>
					    	<div id="account_number" class="text_regular_l">{{ $data->num_account }}</div>
					    </td>
					  </tr>
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td height="30px" colspan="6" valign="top">
					    	<div class="text_titles_tl margin_div" style="color: {{ $color }}">Airport of Departure (Addr. of First Carrier) and Requested Routing</div>
					        <div class="text_regular_l">{{ $data->nombre_aeropuerto }}</div>
					    </td>
					    <td width="51.5%" colspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					            <table width="100%">
					              <tr>
					                <td colspan="3">
					            <table width="100%">
					              <tr>

					            <td width="28%" height="10px" class="text_titles_tl" style="color: {{ $color }}"><div>Reference Number</div></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td width="44%" class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Optional Shipping Information</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td width="32%" class="text_titles_tr" style="color: {{ $color }}"></td>

					              </tr>
					            </table>
					                </td>
					              </tr>
					              <tr>
					                <td width="33%" height="20px">
					                <div id="reference_number" class="text_regular_c">NVD</div>
					                </td>
					                <td width="34%" align="center" valign="middle" class="left_line" style="border-left: 1px solid {{ $color }};">
					                	<div id="optional_shipping_info" class="text_regular_c">NVD</div>
					                </td>
					                <td width="33%" class="left_line" style="border-left: 1px solid {{ $color }};">&nbsp;</td>
					              </tr>
					            </table>


					    </td>
					  </tr>

					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <td width="48.5%" valign="top" colspan="6">
					            <table width="100%">
					              <tr>
					                <td width="6%" align="center" valign="top" height="40px"><div class="text_titles_tc margin_div" style="color: {{ $color }}">To</div>
					                <div id="destino" class="text_regular_l">{{ $data->to1 }}</div>
					                </td>
					                <td width="22.5%" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					                <div class="text_titles_tl margin_div" style="color: {{ $color }}">By First Carrier</div>
					                <div id="first_carrier" class="text_regular_l">{{ $data->by_first_carrier }}</div>
					                </td>
					                <td width="6%" align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					                <div class="margin_div text_titles_tc" style="color: {{ $color }}">to</div>
					                <div id="to_1" class="text_regular_c">{{ $data->to2 }}</div>
					                </td>
					                <td width="4%" align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					                <div class="margin_div text_titles_tc" style="color: {{ $color }}">by</div>
					                <div id="by_1" class="text_regular_c">{{ $data->by1 }}</div>
					                </td>
					                <td width="6%" align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					                <div class="margin_div text_titles_tc" style="color: {{ $color }}">to</div>
					                <div id="to_2" class="text_regular_c">{{ $data->to3 }}</div>
					                </td>
					                <td width="4%" align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					                <div class="margin_div text_titles_tc" style="color: {{ $color }}">by</div>
					                <div id="by_2" class="text_regular_c">{{ $data->by2 }}</div>
					                </td>
					              </tr>
					            </table>

					    </td>
					    <td colspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					        <table width="100%">
					          <tr>
					            <td width="8%" height="10px" rowspan="2"><div class="text_titles_tc" style="color: {{ $color }}">Currency</div></td>
					            <td width="5%" rowspan="2" class="left_line text_titles_tc" style="border-left: 1px solid {{ $color }};"><div style="color: {{ $color }}">CHGS<br />
					            Code</div></td>
					            <td colspan="2" class="left_line text_titles_tc bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}">WT/VAL</td>
					            <td colspan="2" class="left_line text_titles_tc bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}">Other</td>
					            <td width="34%" align="center" valign="top" rowspan="3" class="left_line" style="border-left: 1px solid {{ $color }};">
					            	  <div class="text_titles_tc margin_div" style="color: {{ $color }}">Declared Value for Carriage</div>
					                  <div id="declared_carriage" class="text_regular_c">NVD</div>
					            </td>
					            <td width="33%" align="center" valign="top" rowspan="3" class="left_line" style="border-left: 1px solid {{ $color }};">
					                 <div class="text_titles_tc margin_div" style="color: {{ $color }}">Declared Value for Customs</div>
					                 <div id="declared_customs" class="text_regular_c">NVD</div>

					            </td>
					          </tr>
					          <tr>
					            <td width="5%" class="left_line text_titles_tc" style="border-left: 1px solid {{ $color }};color: {{ $color }}">PP</td>
					            <td width="5%" class="left_line text_titles_tc" style="border-left: 1px solid {{ $color }};color: {{ $color }}">COLL</td>
					            <td width="5%" class="left_line text_titles_tc" style="border-left: 1px solid {{ $color }};color: {{ $color }}">PP</td>
					            <td width="5%" class="left_line text_titles_tc" style="border-left: 1px solid {{ $color }};color: {{ $color }}">COLL</td>
					          </tr>
					          <tr>
					            <td width="8%" align="center" height="15px" class="text_regular_c">{{ $data->currency }}</td>
					            <td width="5%" align="center" class="left_line" style="border-left: 1px solid {{ $color }};">
					            	<div id="chgs_code" class="text_regular_c">{{ $data->chgs_code }}</div>
					            </td>
					            <td width="5%" align="center" class="left_line text_regular_c" style="border-left: 1px solid {{ $color }};">{!! ($data->chgs_code == 'PP') ? 'X' : '' !!}</td>
					            <td width="5%" align="center" class="left_line text_regular_c" style="border-left: 1px solid {{ $color }};">{!! ($data->chgs_code == 'CLL') ? 'X' : '' !!}</td>
					            <td width="5%" align="center" class="left_line text_regular_c" style="border-left: 1px solid {{ $color }};">{!! ($data->chgs_code == 'PP') ? 'X' : '' !!}</td>
					            <td width="5%" align="center" class="left_line text_regular_c" style="border-left: 1px solid {{ $color }};">{!! ($data->chgs_code == 'CLL') ? 'X' : '' !!}</td>
					          </tr>
					        </table>
					    </td>
					  </tr>
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">

					    <td width="24.5%" height="30px" valign="top" colspan="4" class="{{-- special --}}" style="color: {{ $color }}">
					    <div class="text_titles_tc margin_div" style="color: {{ $color }}">Airport of Destination</div>
					    <div id="airport_destination" class="text_regular_l" style="color: #000;font-size: 8px;">{{ $data->aeropuerto_destino }}</div>
					    </td>

					    <td width="24%" colspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					        <table width="100%">
								<tr>
					            <td colspan="2">
					            <table width="100%">
					              <tr>

					            <td class="text_titles_tl" style="color: {{ $color }}"><div>Flight Date</div></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>For Carrier Use Only</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"><div>Flight Date</div></td>

					              </tr>
					            </table>

					            </td>
					            </tr>
					          <tr>
					            <td width="12%" align="center" valign="middle">
					            	<div id="flight" class="text_regular_c">{{ $data->fecha_vuelo1 }}</div>
					            </td>
					            <td width="12%" align="center" valign="middle" class="left_line" style="border-left: 1px solid {{ $color }};">
					            	<div id="flight_2" class="text_regular_c">{{ $data->fecha_vuelo2 }}</div>
					            </td>
					          </tr>
					        </table>
					    </td>
					    <td width="13%" class="left_line" style="border-left: 1px solid {{ $color }};" align="center" valign="top">
					    	<div class="text_titles_tc margin_div" style="color: {{ $color }}">Amount of Insurance</div>
					        <div id="amount_insurance" class="text_regular_c">{{ $data->amount_insurance }}</div>
					    </td>
					    <td width="38.5%" class="left_line text_titles_j" style="border-left: 1px solid {{ $color }};color: {{ $color }}"><div style="font-size: 8px;">
					    INSURANCE - If carrier offers insurance, and such insurance is requested
					in accordance with the consitions thereof, indicate amount to be insured in
					figures in box marked"Amount of Insurance".
					    </div></td>
					  </tr>
					</table>

					<table width="100%" class="main_table_2" style="border-bottom: 1px solid {{ $color }};border-left: 1px solid {{ $color }};border-right: 1px solid {{ $color }};">
					  <tr>
					    <td width="85%" valign="top" height="40px" rowspan="2">
					    <div class="text_titles_tl margin_div" style="color: {{ $color }}">Handling Information</div>
					    <div id="handling_information" class="text_regular_l">{{ $data->handing_information }}
					</div>
					    </td>
					    <td class="text_titles_tl" style="color: {{ $color }}">&nbsp;</td>
					  </tr>
					  <tr>
					    <td class="left_top_line text_titles_tc" style="border-left: 1px solid {{ $color }};border-top: 1px solid {{ $color }};color: {{ $color }}"><div>SCI</div></td>
					  </tr>
					</table>

					<table width="100%" height="150px" class="main_table_2" style="border-bottom: 1px solid {{ $color }};border-left: 1px solid {{ $color }};border-right: 1px solid {{ $color }};">
					  <tr>
					    <td width="7%" height="5" valign="middle" rowspan="2" class="text_titles_c bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}">No. of<br />
					      Pieces<br />
					    RCP</td>
					    <td width="8%" rowspan="2" valign="middle" class="left_line text_titles_c bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}">Gross<br />
					    Weight</td>
					    <td width="1%" valign="middle" align="center" rowspan="2" class="text_titles_c left_line bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}">kg<br />
					    lb</td>
					    <td width="1%" rowspan="2" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td height="5" colspan="2" class="left_line text_titles_tl" style="border-left: 1px solid {{ $color }};color: {{ $color }}"><div>Rate Class</div></td>
					    <td width="1%" rowspan="2" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td width="10%" rowspan="2" class="left_line text_titles_c bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}" valign="middle"><div>Chargeable<br />
					    Weight</div></td>
					    <td width="1%" rowspan="2" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td width="10%" rowspan="2" class="left_line bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};"><div class="text_titles_tl" style="color: {{ $color }}">Rate</div>
					    <div class="text_titles_tr" style="color: {{ $color }}">Charge</div></td>
					    <td width="1%" rowspan="2" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td width="14%" rowspan="2" class="left_line text_titles_c bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Total</div></td>
					    <td width="1%" rowspan="2" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td valign="middle" rowspan="2" class="left_line bottom_line text_titles_c" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Nature and Quantity of Goods<br />
					    (Inc. Dimensions or Volume)</div></td>
					  </tr>
					  <tr>
					    <td width="1%" class="left_line" style="border-left: 1px solid {{ $color }};">&nbsp;</td>
					    <td width="8%" height="10" class="left_line text_titles_tl top_line bottom_line" style="border-left: 1px solid {{ $color }};border-top: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Commodity<br />
					    Item No.</div></td>
					  </tr>
					  <tr>
					    <td align="center" valign="top">
					    	<div id="pieces" class="text_regular_c">{{ $detalle->piezas }}</div>
					    </td>
					    <td align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    <div id="kilos" class="margin_div text_regular_c">
                            {{ round($detalle->peso * 0.453592, 2) }}
                            <br>
					    	{{ round($detalle->peso,2) }}
                            </br>
					    </div></td>
					    <td align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    <div id="kilos_2" class="margin_div text_regular_c">Kl<br>Lb</div></td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="rate_code" class="text_regular_c">{{ $detalle->rate_class }}</div>
					    </td>
					    <td align="center" valign="top" rowspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="item_commodity" class="text_regular_c">1</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td align="center" valign="top" rowspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="kilos_3" class="margin_div text_regular_c">{{ $detalle->peso_cobrado }}</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td align="center" valign="top" rowspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="rate" class="margin_div text_total">{{ $detalle->tarifa }}</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td align="center" valign="top" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="total" class="margin_div text_total">$ {{ number_format($detalle->total,2) }}</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td valign="top" rowspan="2" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="nature_goods" class="margin_div text_regular_l">
					    	  {{ $detalle->descripcion }}
					    	</div>
					    </td>
					  </tr>
					  <tr>
					    <td height="30px" align="center" valign="middle" class="top_line" style="border-top: 1px solid {{ $color }};">
					    	<div id="pieces_2" class="text_regular_c">{{ $detalle->piezas }}</div>
					    </td>
					    <td align="center" valign="middle" class="left_line top_line" style="border-left: 1px solid {{ $color }};border-top: 1px solid {{ $color }};">
					    	<div id="total_kilos" class="text_regular_c">{{ round($detalle->peso * 0.453592, 2) }}</div>
					    </td>
					    <td align="center" valign="middle" class="left_line" style="border-left: 1px solid {{ $color }};">
					    	<div id="kilos_4" class="margin_div text_regular_c">Kl</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td class="left_line" style="border-left: 1px solid {{ $color }};">&nbsp;</td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					    <td align="center" valign="middle" class="left_line top_line" style="border-left: 1px solid {{ $color }};border-top: 1px solid {{ $color }};">
					    	<div id="total_2" class="margin_div text_total">$ {{ number_format($detalle->total,2) }}</div>
					    </td>
					    <td class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">&nbsp;</td>
					  </tr>
					</table>


					<table width="100%" class="main_table_3" style="border-left: 1px solid {{ $color }};">
					  <tr>
					    <td width="36%" height="75px" colspan="2" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					    <table width="100%" border="0">
					      <tr>
					        <td width="36%" height="10px" colspan="2">
					        <table width="100%" border="0">
					  <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Prepaid</div></td>
					            <td></td>
					            <td width="2%" class="line6" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Weight Charge</div></td>
					            <td></td>
					            <td width="2%" class="line6" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Collect</div></td>
					            <td></td>
					            <td width="2%" class="line6" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					  </tr>
					</table>

					        </td>
					        </tr>
					      <tr>
					        <td width="18%" height="12px" align="center" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					        	<div id="pp_w_charge" class="text_total">{!! ($data->chgs_code == 'PP') ?  '$ '.number_format($detalle->total,2) : '' !!}</div>
					        </td>
					        <td width="18%" align="center" class="left_line bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					        	<div id="coll_w_charge" class="text_total">{!! ($data->chgs_code == 'CLL') ?  '$ '.number_format($detalle->total,2) : '' !!}</div>
					        </td>
					      </tr>
					      <tr>
					        <td height="10px" align="center" colspan="2">
					            <table width="50%" align="center">
					              <tr>

					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line3" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Valuation Charge</div></td>
					            <td></td>
					            <td width="2%" class="line4" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>

					              </tr>
					            </table>

					        </td>
					        </tr>
					      <tr>
					        <td width="18%" height="12px" align="center" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					        	<div id="pp_valuation_charge" class="text_total">{{-- 0.00 --}}</div>
					        </td>
					        <td width="18%" align="center" class="left_bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					        	<div id="coll_valuation_charge" class="text_total">{{-- 0.00 --}}</div>
					        </td>
					      </tr>
					      <tr>
					        <td height="10px" colspan="2"><table width="20%" align="center">
					              <tr>

					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line3" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Tax</div></td>
					            <td></td>
					            <td width="2%" class="line4" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>

					              </tr>
					            </table>


					        </td>
					        </tr>
					      <tr>
					        <td width="18%" height="12" align="center">
					        	<div id="pp_tax" class="text_total">{{-- 0.00 --}}</div>
					        </td>
					        <td width="18%" align="center" class="left-line">
					        	<div id="coll_tax" class="text_total">{{-- 0.00 --}}</div>
					        </td>
					      </tr>
					    </table>

					    </td>
					    <td width="64%" colspan="3" valign="top" class="left_line bottom_line  rigth_line " style="border-left: 1px solid {{ $color }}; border-right: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};"><div class="margin_div text_titles_tl" style="color: {{ $color }}">Other Charges</div>
					    <table width="90%" align="center">
						  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
						    <td height="15px">Description</td>
						    <td>Amount</td>
						    <td>Entitlement</td>
						  </tr>
						  @foreach($other['data'] AS $ot)
							  <tr>
							    <td width="65%" valign="top">
							    	<div class="text_regular_l">{{ $ot->oc_description }}</div>
							     </td>
							    <td width="25%" valign="top">
							    	<div class="text_regular_l">$ {{ number_format($ot->oc_value, 2) }}</div>
							    </td>
							    <td width="10%" valign="top">
							    	<div class="text_regular_l">{{ (($ot->oc_due == 1) ? 'C' : 'A') }}</div>
							    </td>
							  </tr>
						  @endforeach
						</table>

					    </td>
					  </tr>
					  <tr>
					    <td height="75px" colspan="2" rowspan="2" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">

					 <table width="100%" border="0">
					      <tr>
					        <td height="10" colspan="2">
					            <table width="70%" align="center">
					              <tr>

					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Total Other Charges Due Agent</div></td>
					            <td></td>
					            <td width="2%" class="line4" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>

					              </tr>
					            </table>


					        </td>
					        </tr>
					      <tr>
					        <td width="18%" height="12" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					        	<div id="pp_due_agent" class="text_total">{!! ($data->chgs_code == 'PP') ?  '$ '. number_format($data->total_other_charge_due_agent,2) : '' !!}</div>
					        </td>
					        <td width="18%" class="left_line bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					        	<div id="coll_due_agent" class="text_total">{!! ($data->chgs_code == 'CLL') ?  '$ '. number_format($data->total_other_charge_due_agent,2) : '' !!}</div>
					        </td>
					      </tr>
					      <tr>
					        <td height="10" align="center" colspan="2">
					            <table width="70%" align="center">
					              <tr>

					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Total Other Charges Due Carrier</div></td>
					            <td></td>
					            <td width="2%" class="line4" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>

					              </tr>
					            </table>

					        </td>
					        </tr>
					      <tr>
					        <td height="12" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					        	<div id="pp_due_carrier" class="text_total">{!! ($data->chgs_code == 'PP') ?  '$ '. number_format($data->total_other_charge_due_carrier,2) : '' !!}</div>
					        </td>
					        <td width="18%" class="left_bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					        	<div id="coll_due_carrier" class="text_total">{!! ($data->chgs_code == 'CLL') ?  '$ '. number_format($data->total_other_charge_due_carrier,2) : '' !!}</div>
					        </td>
					      </tr>
					      <tr>
					        <td width="36%" height="25px" colspan="2" class="bg_azul" style="background-color:{{ $background }};">&nbsp;</td>
					        </tr>
					    </table>

					    </td>
					    <td valign="top" colspan="3" class="left_line rigth_line bottom_line" style="border-left: 1px solid {{ $color }};border-right: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					      <div class="text_titles_j margin_div" style="color: {{ $color }}">Shipper certifies that the particulars on the face hereof are correct and that<strong> insofar as any part of the consignment
					        contains dangerous goods, such part is property decribed by name and is in proper condition for carriage by air
					      according to the applicable Dangerous Goods Regulations.</strong></div>
					      <div id="shipper_certifies" class="text_regular_l">SHIPPER HEREBY CONSENTS TO A SEARCH OR INSPECTION OF THE CARGO.</div>
					      <div id="shipper_signin" class="text_regular_c">{{--  RAMON OCAMPO --}}</div>
					    </td>
					  </tr>
					  <tr>
					    <td height="15px" colspan="3" valign="top" class="left_line rigth_line bottom_line top_line_dasherd" style="border-left: 1px solid {{ $color }};border-right: 1px solid {{ $color }};border-style: dashed solid none none;border-color: {{ $color }};border-bottom: 1px solid {{ $color }};"><div class="text_titles_tc" style="color: {{ $color }}">Signature of Shipper or his Agent</div></td>
					  </tr>
					  <tr>
					    <td height="50px" colspan="2" rowspan="2" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					<table width="100%" border="0">
					      <tr>
					        <td width="18%" height="10" align="center">
					            <table width="45%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div >Total Prepaid</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>

					        </td>
					        <td height="10" align="center" class="left_line" style="border-left: 1px solid {{ $color }};">
					            <table width="45%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div >Total Collect</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>
					        </td>
					        </tr>
					      <tr>
					        <td height="12" class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					        	<div id="pp_total" class="text_total">{!! ($data->chgs_code == 'PP') ?  '$ '.number_format(($data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $detalle->total),2) : '' !!}</div>
					        </td>
					        <td width="18%" class="left_bottom_line" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};">
					        	<div id="coll_total" class="text_total">{!! ($data->chgs_code == 'CLL') ?  '$ '. number_format(($data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $detalle->total), 2) : '' !!}</div>
					        </td>
					      </tr>
					      <tr class="bg_azul" style="background-color:{{ $background }};">
					        <td height="12">
					            <table width="97%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line3" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Currency Convertion Rates</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>
					        </td>
					        <td class="left_line" style="border-left: 1px solid {{ $color }};">

					            <table width="97%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line3" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>CC Charges in Dest. Currency</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>
					        </td>
					      </tr>
					      <tr class="bg_azul" style="background-color:{{ $background }};">
					        <td width="18%" height="12">
					        <div id="currency_convertions" class="text_total">{{-- 50.00 --}}</div>
					        </td>
					        <td width="18%" class="left_line" style="border-left: 1px solid {{ $color }};">
					        	<div id="currency_dest" class="text_total">{{-- 10.00 --}}</div>
					        </td>
					      </tr>
					    </table>


					    </td>
					    <td height="35px" valign="bottom" colspan="3" class="left_line rigth_line" style="border-left: 1px solid {{ $color }};border-right: 1px solid {{ $color }};">
						    {{-- <div id="sign_description" class="text_regular_l margin_div">DESCRIPCIÓN DE LA FIRMA</div> --}}
						    <div style="width:20%; float:left" class="text_regular_l">{{ date('m-d-y', strtotime($data->fecha_vuelo1)) }}</div>
						    <div style="width:24%; float:left" class="text_regular_c">{{-- MIA --}}</div>
						    <div style="width:50%; float:right" class="text_regular_r">
							    <span>
		                            {{ $data->nombre_carrier }}
		                        </span>
		                        <br>
		                        <span>
		                            {{ $data->telefono_carrier }} / {{ $data->direccion_carrier }}
		                        </span>
							</div>
					    </td>
					  </tr>
					  <tr class="bottom_line" style="border-bottom: 1px solid {{ $color }};">
					<td width="64%" height="15px" valign="top" colspan="3" class="top_line_dasherd rigth_line" style="border-right: 1px solid {{ $color }};border-style: dashed solid none none;border-color: {{ $color }};">

					    <div style="width:30%; float:left" class="text_titles_tl" style="color: {{ $color }}">Executed on (date)</div>
					    <div style="width:34%; float:left" class="text_titles_c" style="color: {{ $color }}">at (place)</div>
					    <div style="width:30%; float:right" class="text_titles_tr" style="color: {{ $color }}">Signature of Issuing Carrier or its Agent</div>
					</td>
					  </tr>
					  <tr>
					    <td width="18%" height="25px" align="center" valign="middle" rowspan="2" class="bg_azul bottom_line text_normal" style="border-bottom: 1px solid {{ $color }};color: {{ $color }};background-color:{{ $background }};">For Carriers Use only<br />
					    at Destination</td>
					    <td width="18%" class="left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">

					            <table width="70%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Charges at Destination</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>

					    </td>
					    <td class=" left_line bg_azul" style="border-left: 1px solid {{ $color }};background-color:{{ $background }};">
					            <table width="70%" align="center">
					              <tr>
					            <td class="text_titles_tl" style="color: {{ $color }}"></td>
					            <td width="2%" class="line1" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tc bottom_line" style="border-bottom: 1px solid {{ $color }};color: {{ $color }}"><div>Total Collect Charges</div></td>
					            <td></td>
					            <td width="2%" class="line2" style="border-bottom: 1px solid {{ $color }};">&nbsp;</td>
					            <td class="text_titles_tr" style="color: {{ $color }}"></td>
					      			</tr>
					            </table>
					    </td>
					    <td rowspan="2" class=" left_line" style="border-left: 1px solid {{ $color }};">&nbsp;</td>
					    <td align="center" rowspan="2"><div id="master_bottom" style="font-size:18px" >{{ $data->codigo_aerolinea .'-'. substr($data->num_master,3) }}</div></td>
					  </tr>
					  <tr>
					    <td width="18%" class="left_line bottom_line bg_azul" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};background-color:{{ $background }};">
					    	<div id="charges_dest" class="text_total">{{-- 100.00 --}}</div>
					    </td>
					    <td width="21.3%" class=" left_line bottom_line bg_azul" style="border-left: 1px solid {{ $color }};border-bottom: 1px solid {{ $color }};background-color:{{ $background }};">
					    	<div id="total_charges" class="text_total">{{-- 15.00 --}}</div>
					    </td>
					  </tr>
					</table>
					<table style="width:100%;">
						<tr>
							<td><div id="copia" style="width:100%; font-size:14px; padding-top:10px; text-align:center;color: {{ $color }}" class="text_normal">
								{{-- Original 1 (for Issuing Carrier) --}}{{ $destino }}
							</div></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
@endfor
</body>
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
</html>

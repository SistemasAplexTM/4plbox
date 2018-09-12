<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>@lang('general.bill_of_lading')</title>
	<style>
		*{
			font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
		}
		.bill{
			width: 100%;
		}
		.content{
			width: 100%;
		}
		.title{
			font-size: 8px;
			padding-bottom: 5px;
			padding-top: 2px;
			padding-left: 2px
		}
		.b-top{
			border-top: 1px solid #000000;
		}
		.b-bottom{
			border-bottom: 1px solid #000000;
		}
		.b-left{
			border-left: 1px solid #000000;
		}
		.b-right{
			border-right: 1px solid #000000;
		}
		.p-left{
			padding-left: 10px;
		}
		.var{
			font-size: 17px;
		/*font-weight: bold;*/
		}
		.detail .title{
			padding: 5px;
		}
		pre {
			margin: 0;
		}
	</style>
</head>
<body>

	<table class="bill">
		<thead>
			<tr>
				<td style="padding-left: 5px;font-size: 25px">Dole</td>
				<td style="text-align: right;padding-right: 5px;font-size: 25px">@lang('general.bill_of_lading')</td>
			</tr>
		</thead>
		<tbody style="border: 1px solid #030303;">
			<tr>
				<td colspan="2">
					<table class="content" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top" style="width: 55%" class="b-top b-right">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td colspan="2" class="title">2. EXPORTER (Principal or seller -licensee and address including ZIP Code )</td>
									</tr>
									<tr>
										<td colspan="" class="p-left">
											<pre>{{ $data->exporter }}</pre>
										</td>
									</tr>
									<tr>
										<td style="width: 70%;height: 40px"></td>
										<td class="b-top b-left" valign="top">
											<div style="font-size: 10px;margin-left: 2px">ZIP CO'DE</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title b-right" style="width: 50%;padding-left: 2px;height: 35px">5. DOCUMENT NUMBER
											<div class="var">{{ ($data->document_number != '') ? $data->document_number : '&nbsp;' }}</div>
										</td>
										<td valign="top" class="title" style="padding-left: 2px">5a. B/L NUMBER
											<div class="var">{{ $data->num_bl }}</div>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="title b-top">6. EXPORT REFERENCES</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="p-left" style="">{{ $data->export_references }}</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td valign="top" style="width: 55%" class="b-top b-right">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td colspan="2" class="title">3. CONSIGNED TO</td>
									</tr>
									<tr>
										<td colspan="" class="p-left">
											<pre>{{ $data->consignee }}</pre>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title" style="padding-left: 2px;height: 55px">
											7. FORWARDING AGENT (Name and address - references )
											<div class="var"><pre>{{ $data->forwarding_agent }}</pre></div>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="title b-top" style="padding-left: 2px;">
											8. POINT (STATE) OF ORIGIN OR FTZ NUMBER
											<div class="var">{{ ($data->point_origin != '') ? $data->point_origin : '&nbsp;' }}</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td valign="top" style="width: 55%" class="b-top b-right">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td colspan="2" class="title">4. NOTIFY PARTY /INTERMEDIATE CONSIGNEE (Name and address )</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="p-left" style="height: 70px">
											<pre>{{ $data->notify_party }}</pre>
										</td>
									</tr>
									<tr>
										<td class="title b-top b-right" style="width: 60%;">12. PRE-CARRIAGE BY</td>
										<td class="title b-top">13. PLACE OF RECEIPT BY PRE -CARRIER</td>
									</tr>
									<tr>
										<td valign="top" class="p-left b-right">
											<div class="var">{{ ($data->pre_carriage_by != '') ? $data->pre_carriage_by : '&nbsp;' }}</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var">{{ ($data->place_of_receipt != '') ? $data->place_of_receipt : '&nbsp;'}}</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title" style="padding-left: 2px;height: 55px">
											9. DOMESTIC ROUTING/EXPORT INSTRUCTIONS
											<div class="var"><pre>{{ $data->domestic_routing }}</pre></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td valign="top" style="width: 55%" class="b-right">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td class="title b-top b-right" style="width: 60%;">14. EXPORTING CARRIER</td>
										<td class="title b-top">15. PORT OF LOADING /EXPORT</td>
									</tr>
									<tr>
										<td valign="top" class="p-left b-right">
											<div class="var">{{ ($data->exporting_carrier != '') ? $data->exporting_carrier : '&nbsp;' }}</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var">{{ ($data->port_loading != '') ? $data->port_loading : '&nbsp;' }}</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title" style="padding-left: 2px;">10. LOADING PIER /TERMINAL</td>
									</tr>
									<tr>
										<td valign="top" class="p-left">
											<div class="var">{{ ($data->loading_pier != '') ? $data->loading_pier : '&nbsp;' }}</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td valign="top" style="width: 55%" class="b-right">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td class="title b-top b-right" style="width: 60%;">16. FOREIGN PORT OF UNLOADING (Vessel and air only )</td>
										<td class="title b-top">17. PLACE OF DELIVERY BY ON -CARRIER</td>
									</tr>
									<tr>
										<td valign="top" class="p-left b-right">
											<div class="var">{{ ($data->foreign_port != '') ? $data->foreign_port : '&nbsp;' }}</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var">{{ ($data->placce_delivery != '') ? $data->placce_delivery : '&nbsp;' }}</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td class="title b-right" style="width: 50%;">11. TYPE OF MOVE</td>
										<td class="title">11a. CONTAINERIZED (Vessel only )</td>
									</tr>
									<tr>
										<td valign="top" class="p-left b-right">
											<div class="var">{{ $data->type_move }}</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var" style="width: 50%;float: left">Yes &nbsp;{{ ($data->containered == 0) ? 'X' : '' }}</div>
											<div class="var" style="width: 50%;float: left">No  &nbsp;{{ ($data->containered == 1) ? 'X' : '' }}</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="b-top b-bottom">
								<table width="100%" cellspacing="0" cellpadding="0" class="detail">
									<tr style="text-align: center;">
										<td class="title b-right" style="width: 150px;">MARKS AND NUMBERS <br> (18)</td>
										<td class="title b-right" style="width: 88px;">NUMBER OF PACKAGES <br> (19)</td>
										<td class="title b-right" style="width: 300px;">DESCRIPTION OF COMMODITIES <br> (20)</td>
										<td class="title b-right">GROSS WEIGHT <br> (kilos) (21)</td>
										<td class="title">MEASUREMENT <br> (22)</td>
									</tr>
									@if(count($detalle) > 0)
										@foreach($detalle as $dt)
											<tr>
												<td valign="top" class="b-top b-right" style="height: 250px">
													<div class="var"><pre>{{ $dt->marks_numbers }}</pre></div>
												</td>
												<td valign="top" class="b-top b-right">
													<div class="var">{{ $dt->number_packages }}</div>
												</td>
												<td valign="top" class="b-top b-right">
													<div class="var">{{ $dt->description }}</div>
												</td>
												<td valign="top" class="b-top b-right">
													<div class="var" style="text-align: right;">{{ $dt->gross_weight }} KLS<br>{{ number_format(($dt->gross_weight * 2.20462), 2) }} LBS</div>
												</td>
												<td valign="top" class="b-top">
													<div class="var" style="text-align: right;">{{ $dt->measurement }} FT<br>{{ number_format(($dt->measurement / 35.315),2) }} MT3</div>
												</td>
											</tr>
										@endforeach
									@endif
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="title">Carrier has a policy against payment , solicitation, or receipt of any rebate , directly or indirectly , which would be unlawful under the United States Shipping Act , 1984 as amended .</td>
						</tr>
						<tr>
							<td colspan="2" class="title">DECLARED VALUE __________________________ READ CLAUSE 29 HEREOF CONCERNING EXTRA FREIGHT AND CARRIER 'S LIMITATION DECLARED VALUE OF LIABILITY .</td>
						</tr>
						<tr>
							<td colspan="2">
								<table cellspacing="0" cellpadding="0" style="width: 100%">
									<tr>
										<td width="50%" class="b-top b-right" style="padding: 6px 6px 0px 0px" valign="top">
											<table cellspacing="0" cellpadding="0" style="width: 100%">
												<tr>
													<td colspan="3" style="font-size: 11px;font-weight: bold;text-align: center;">FREIGHT RATES, CHARGES, WEIGHTS AND/OR MEASUREMENTS</td>
												</tr>
												<tr>
													<td style="font-size: 11px;text-align: center;" class="b-right">SUBJECT TO CORRECTION</td>
													<td style="font-size: 11px;text-align: center;" class="b-top b-right">PREPAID</td>
													<td style="font-size: 11px;text-align: center;" class="b-top b-right">COLLECT</td>
												</tr>
												<?php $total_pp = 0; ?>
												<?php $total_cll = 0; ?>
												@if(count($other) > 0)
													@foreach($other as $ot)
														<tr>
															<td valign="top"  class="b-top b-right" style="height: 200px">{{ $ot->description }}</td>
															<td valign="top"  class="b-top b-right">{{ number_format($ot->ammount_pp, 2) }}</td>
															<td valign="top"  class="b-top b-right">{{ number_format($ot->ammount_cll, 2) }}</td>
														</tr>
														<?php $total_pp += $ot->ammount_pp; ?>
														<?php $total_cll += $ot->ammount_cll; ?>
													@endforeach
												@endif
												<tr>
													<td valign="top"  class="b-top b-right" style="padding: 10px;text-align: right">GRAND TOTAL :</td>
													<td valign="top"  class="b-top b-right">{{ number_format($total_pp, 2) }}</td>
													<td valign="top"  class="b-top b-right">{{ number_format($total_cll, 2) }}</td>
												</tr>
											</table>
										</td>
										<td valign="top" width="50%" class="b-top" style="padding: 18px 0px 0px 6px">
											<table cellspacing="0" cellpadding="0" style="width: 100%">
												<tr>
													<td valign="top" colspan="2" style="font-size: 8px;font-weight: bold;text-align: center;text-align: justify">Received by the Carrier for shipment by ocean vessel between port of loading and port of
													discharge , and for arrangement or procurement of pre -carriage from place of receipt and on -
													carriage to place of delivery , where stated above , the goods as specified above in apparent
													good order and condition unless otherwise stated . The goods to be delivered at the above
													mentioned port of discharge or place of delivery , whichever is applicable , subject always to the
													exceptions , limitations, conditions and liberties set out on the reverse side hereof , to which the
													Shipper and /or Consignee agree to accepting this Bill of Lading .
													IN WITNESS WHEREOF three (3) original Bills of Lading have been signed , not otherwise
													stated above , one of which being accomplished the others shall be void .</td>
												</tr>
												<tr>
													<td colspan="2" style="padding-top: 20px;">
														<div style="width: 20%;float: left;">DATED AT</div>
														<div style="width: 80%;float: left;border-bottom: 1px solid #000000;">&nbsp;</div>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="padding-top: 10px;">
														<div style="width: 5%;float: left;">BY</div>
														<div style="width: 95%;float: left;border-bottom: 1px solid #000000;">
															<div style="width: 100%;text-align: center;font-size: 20px;">{{ $data->agent_for_carrier }}</div>
														</div>
														<div style="font-size: 12px;text-align: center;">AGENT FOR THE CARRIER</div>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="padding-top: 5px;">
														<div style="width: 35%;float: left;">{{ date('m', strtotime($data->date_document)) }}</div>
														<div style="width: 30%;float: left;text-align: center">{{ date('d', strtotime($data->date_document)) }}</div>
														<div style="width: 35%;float: left;text-align: right">{{ date('Y', strtotime($data->date_document)) }}</div>
													</td>
												</tr>
												<tr><td colspan="2"><div style="border-bottom: 1px solid #000000;"></div></td></tr>
												<tr>
													<td colspan="2" style="padding-top: 5px;">
														<div style="width: 35%;float: left;">MO.</div>
														<div style="width: 30%;float: left;text-align: center">DAY</div>
														<div style="width: 35%;float: left;text-align: right;">YEAR</div>
													</td>
												</tr>
												<tr>
													<td style="width: 50%;">&nbsp;</td>
													<td class="title b-top b-left">B/L No.</td>
												</tr>
												<tr>
													<td style="width: 50%;">&nbsp;</td>
													<td class="b-left" style="text-align: right;padding-right: 5px;font-weight: bold;">{{ $data->num_bl }}</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
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
</body>
</html>







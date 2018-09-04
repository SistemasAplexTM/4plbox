<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
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
/*			font-weight: bold;*/
		}
		.detail .title{
			padding: 5px;
		}
	</style>
</head>
{{-- {{ print_r($data) }} --}}
<body>
	<table class="bill">
		<thead>
			<tr>
				<td style="padding-left: 5px;font-size: 25px">Dole</td>
				<td style="text-align: right;padding-right: 5px;font-size: 25px">BILL OF LADING</td>
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
											<div class="var">{{ nl2br($data->exporter)  }}</div>
										</td>
									</tr>
									<tr>
										<td style="width: 70%;height: 40px"></td>
										<td class="b-top b-left" valign="top">
											<div style="font-size: 10px;margin-left: 2px">ZIP CODE</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title b-right" style="width: 50%;padding-left: 2px;height: 35px">5. DOCUMENT NUMBER
											<div class="var">BK: SANL2497GQ</div>
										</td>
										<td valign="top" class="title" style="padding-left: 2px">5a. B/L NUMBER
											<div class="var">HBL-6719</div>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="title b-top">6. EXPORT REFERENCES</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="p-left" style="">6. EXPORT REFERENCES</td>
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
											<div class="var">{{ $data->consignee }}</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title" style="padding-left: 2px;height: 55px">
											7. FORWARDING AGENT (Name and address - references )
											<div class="var"></div>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2" class="title b-top" style="padding-left: 2px;">
											6. EXPORT REFERENCES
											<div class="var">CALIFORNIA</div>
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
											<div class="var">DISEÑO EXCLUSIVO DISEX S.A</div>
										</td>
									</tr>
									<tr>
										<td class="title b-top b-right" style="width: 60%;">12. PRE-CARRIAGE BY</td>
										<td class="title b-top">13. PLACE OF RECEIPT BY PRE -CARRIER</td>
									</tr>
									<tr>
										<td valign="top" class="p-left b-right">
											<div class="var">LOS ANGELES</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var">LOS ANGELES</div>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" class="b-top">
								<table width="100%" cellspacing="0" cellpadding="0" class="">
									<tr>
										<td valign="top" class="title" style="padding-left: 2px;height: 55px">
											9. DOMESTIC ROUTING/EXPORT INSTRUCTIONS
											<div class="var"></div>
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
											<div class="var">&nbsp;</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var"></div>
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
											<div class="var">SAN DIEGO</div>
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
											<div class="var">GUAYAQUIL</div>
										</td>
										<td valign="top" class="p-left">
											<div class="var"></div>
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
											<div class="var"></div>
										</td>
										<td valign="top" class="p-left">
											<div class="var" style="width: 50%;float: left">Yes </div>
											<div class="var" style="width: 50%;float: left">No </div>
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
									<tr>
										<td valign="top" class="b-top b-right" style="height: 250px">
											<div class="var">BK: SANL2497GQ DFIU: 425134-8 SEAL: UL-1336440</div>
										</td>
										<td valign="top" class="b-top b-right">
											<div class="var">24 PCS</div>
										</td>
										<td valign="top" class="b-top b-right">
											<div class="var">STC: TEXTILE ROLLS</div>
										</td>
										<td valign="top" class="b-top b-right">
											<div class="var">532.06KG 1173.00LBS</div>
										</td>
										<td valign="top" class="b-top">
											<div class="var">79.00FT 2.23MT3</div>
										</td>
									</tr>
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
										<td width="50%" class="b-top b-right" style="padding: 6px 6px 0px 0px">
											<table cellspacing="0" cellpadding="0" style="width: 100%">
												<tr>
													<td colspan="3" style="font-size: 11px;font-weight: bold;text-align: center;">FREIGHT RATES, CHARGES, WEIGHTS AND/OR MEASUREMENTS</td>
												</tr>
												<tr>
													<td style="font-size: 11px;text-align: center;" class="b-right">SUBJECT TO CORRECTION</td>
													<td style="font-size: 11px;text-align: center;" class="b-top b-right">PREPAID</td>
													<td style="font-size: 11px;text-align: center;" class="b-top b-right">COLLECT</td>
												</tr>
												<tr>
													<td valign="top"  class="b-top b-right" style="height: 200px">OCEAN FREIGHT</td>
													<td valign="top"  class="b-top b-right"></td>
													<td valign="top"  class="b-top b-right"></td>
												</tr>
												<tr>
													<td valign="top"  class="b-top b-right" style="padding: 10px;text-align: right">GRAND TOTAL :</td>
													<td valign="top"  class="b-top b-right"></td>
													<td valign="top"  class="b-top b-right"></td>
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
														<div style="width: 95%;float: left;border-bottom: 1px solid #000000;">&nbsp;</div>
														<div style="font-size: 12px;text-align: center;">AGENT FOR THE CARRIER</div>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="padding-top: 5px;">
														<div style="width: 35%;float: left;">04</div>
														<div style="width: 30%;float: left;text-align: center">17</div>
														<div style="width: 35%;float: left;text-align: right">2018</div>
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
													<td class="b-left" style="text-align: right;padding-right: 5px;font-weight: bold;">HBL-6719</td>
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







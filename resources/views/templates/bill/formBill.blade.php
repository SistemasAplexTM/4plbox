<table class="bill">
	<thead>
		<tr>
			<td style="padding-left: 5px;font-size: 25px">Dole</td>
			<td style="text-align: right;padding-right: 5px;font-size: 25px">BILL OF LADING</td>
		</tr>
	</thead>
	<tbody style="">
		<tr>
			<td colspan="2">
				<table class="content" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" style="width: 55%" class="b-top b-right">
							<table width="100%" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td colspan="2" class="title">
									2. EXPORTER (Principal or seller -licensee and address including ZIP Code )
									<a class="btn btn-info btn-xs search" data-toggle="tooltip" title="Buscar" @click="SearchPartie('Shipper')"><i class="fa fa-search"></i></a>
								</td>
								</tr>
								<tr>
									<td colspan="2" class="p-left">
										<textarea class="form-control txt-shipper" placeholder="EXPORTER" rows="4" name="exporter" v-model="exporter"></textarea>
										<input type="hidden" name="exporter_id" v-model="exporter_id">
									</td>
								</tr>
								<tr>
									<td style="width: 70%;height: 40px"></td>
									<td class="b-top b-left" valign="top" style="padding: 3px;">
										<div style="font-size: 10px;margin-left: 2px">ZIP CODE</div>
										<input type="text" name="exporter_zip" placeholder="Zip" v-model="exporter_zip" class="form-control">
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" class="b-top">
							<table width="100%" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td valign="top" class="title b-right" style="width: 50%;padding-left: 2px;height: 35px">5. DOCUMENT NUMBER
										<div class="var"><input type="text" name="document_number" placeholder="DOCUMENT NUMBER" v-model="document_number" class="form-control"></div>
									</td>
									<td valign="top" class="title" style="padding-left: 2px">5a. B/L NUMBER
										<div class="var"><input type="text" name="num_bl" placeholder="B/L NUMBER" v-model="num_bl" class="form-control"></div>
									</td>
								</tr>
								<tr>
									<td valign="top" colspan="2" class="title b-top">6. EXPORT REFERENCES</td>
								</tr>
								<tr>
									<td valign="top" colspan="2" class="p-left" style="">
										<textarea class="form-control txt-export" placeholder="EXPORT REFERENCES" rows="3" v-model="export_references"></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 55%" class="b-top b-right">
							<table width="100%" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td colspan="2" class="title">3. CONSIGNED TO <a class="btn btn-info btn-xs search" data-toggle="tooltip" title="Buscar" @click="SearchPartie('Consignee')"><i class="fa fa-search"></i></a></td>
								</tr>
								<tr>
									<td colspan="2" class="p-left">
										<textarea class="form-control txt-consignee" placeholder="CONSIGNED TO" rows="4" name="consignee" v-model="consignee"></textarea>
										<input type="hidden" name="consignee_id" v-model="consignee_id">
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" class="b-top">
							<table width="100%" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td valign="top" class="title" style="padding-left: 2px;height: 65px">
										7. FORWARDING AGENT (Name and address - references )
										<div class="var">
											<textarea class="form-control txt-forwarding_agent" placeholder="FORWARDING AGENT" rows="2" name="forwarding_agent" v-model="forwarding_agent"></textarea>
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top" colspan="2" class="title b-top" style="padding-left: 2px;">
										8. POINT (STATE) OF ORIGIN OR FTZ NUMBER
										<div class="var"><input type="text" name="point_origin" placeholder="POINT (STATE) OF ORIGIN" v-model="point_origin" class="form-control"></div>
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
										<div class="var">
											<textarea class="form-control txt-notify_party" placeholder="NOTIFY PARTY" rows="2" name="notify_party" v-model="notify_party"></textarea>
											<input type="hidden" name="notify_party_id" v-model="notify_party_id">
										</div>
									</td>
								</tr>
								<tr>
									<td class="title b-top b-right" style="width: 60%;">12. PRE-CARRIAGE BY</td>
									<td class="title b-top">13. PLACE OF RECEIPT BY PRE -CARRIER</td>
								</tr>
								<tr>
									<td valign="top" class="p-left b-right">
										<div class="var"><input type="text" name="pre_carriage_by" placeholder="PRE-CARRIAGE BY" v-model="pre_carriage_by" class="form-control"></div>
									</td>
									<td valign="top" class="p-left">
										<div class="var"><input type="text" name="place_of_receipt" placeholder="PLACE OF RECEIPT" v-model="place_of_receipt" class="form-control"></div>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" class="b-top">
							<table width="100%" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td valign="top" class="title" style="padding-left: 2px;height: 55px">
										9. DOMESTIC ROUTING/EXPORT INSTRUCTIONS
										<div class="var">
											<textarea class="form-control txt-domestic_routing" placeholder="DOMESTIC ROUTING/EXPORT INSTRUCTIONS" rows="5" name="domestic_routing" v-model="domestic_routing"></textarea>
										</div>
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
										<div class="var"><input type="text" name="exporting_carrier" placeholder="EXPORTING CARRIER" v-model="exporting_carrier" class="form-control"></div>
									</td>
									<td valign="top" class="p-left">
										<div class="var"><input type="text" name="port_loading" placeholder="PORT OF LOADING" v-model="port_loading" class="form-control"></div>
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
										<div class="var"><input type="text" name="loading_pier" placeholder="LOADING PIER" v-model="loading_pier" class="form-control"></div>
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
										<div class="var"><input type="text" name="foreign_port" placeholder="FOREIGN PORT" v-model="foreign_port" class="form-control"></div>
									</td>
									<td valign="top" class="p-left">
										<div class="var"><input type="text" name="placce_delivery" placeholder="PLACE OF DELIVERY" v-model="placce_delivery" class="form-control"></div>
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
										<div class="var"><input type="text" name="type_move" placeholder="TYPE OF MOVE" v-model="type_move" class="form-control"></div>
									</td>
									<td valign="top" class="p-left">
										<div class="var" style="width: 50%;float: left">
											<div class="radio radio-info radio-inline">
	                                            <input type="radio" id="yes" value="0" name="containered" v-model="containered">
	                                            <label for="yes"> Yes </label>
	                                        </div>
										</div>
										<div class="var" style="width: 50%;float: left">
											<div class="radio radio-info radio-inline">
	                                            <input type="radio" id="no" value="1" name="containered" v-model="containered" checked="">
	                                            <label for="no"> No </label>
	                                        </div>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="b-top b-bottom">
							<table width="100%" cellspacing="0" cellpadding="0" class="detail">
								<tr style="text-align: center;">
									<td class="title b-right" style="width: 20%;">MARKS AND NUMBERS <br> (18)</td>
									<td class="title b-right" style="width: 13%;">NUMBER OF PACKAGES <br> (19)</td>
									<td class="title b-right" style="width: 40%">DESCRIPTION OF COMMODITIES <br> (20)</td>
									<td class="title b-right">GROSS WEIGHT <br> (kilos) (21)</td>
									<td class="title b-right">MEASUREMENT <br> (22)</td>
									<td><a class="btn btn-xs btn-primary" v-on:click="addDetail()" data-toggle="tooltip" title="Agregar fila"><i class="fa fa-plus"></i></a></td>
								</tr>
								<tr v-for="(find, index) in detail">
									<td valign="top" class="b-top b-right">
										<div class="var" :class="{'has-error': errors.has('marks_numbers') }">
											<textarea class="form-control" name="marks_numbers" v-model="find.marks_numbers" v-validate="'required'"></textarea>
										</div>
									</td>
		                          	<td valign="top" class="b-top b-right">
		                            	<div class="var" :class="{'has-error': errors.has('number_packages') }">
											<input type="text" class="form-control" name="number_packages" v-model="find.number_packages" v-validate="'required'">
										</div>
		                          	</td>
		                          	<td valign="top" class="b-top b-right">
		                            	<div class="var" :class="{'has-error': errors.has('description') }">
											<textarea class="form-control" name="description" v-model="find.description" v-validate="'required'"></textarea>
										</div>
		                          	</td>
		                          	<td valign="top" class="b-top b-right">
		                            	<div class="var" :class="{'has-error': errors.has('gross_weight') }">
											<input type="number" class="form-control" name="gross_weight" v-model="find.gross_weight" v-validate="'required'">
										</div>
		                          	</td>
		                          	<td valign="top" class="b-top b-right">
		                            	<div class="var" :class="{'has-error': errors.has('measurement') }">
											<input type="number" class="form-control" name="measurement" v-model="find.measurement" v-validate="'required'">
										</div>
		                          	</td>
			                        <td valign="top" class="b-top" style="padding: 5px;">
			                            <a class="btn btn-xs btn-danger" @click="deleteRow(index)" data-toggle="tooltip" title="Eliminar"><i class="fa fa-trash"></i></a>
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
									<td valign="top" width="50%" class="b-top b-right" style="padding: 6px 6px 0px 0px">
										<table cellspacing="0" cellpadding="0" style="width: 100%">
											<tr>
												<td colspan="3" style="font-size: 11px;font-weight: bold;text-align: center;">FREIGHT RATES, CHARGES, WEIGHTS AND/OR MEASUREMENTS</td>
											</tr>
											<tr>
												<td style="font-size: 11px;text-align: center;" class="b-right">
													<a class="btn btn-primary btn-xs addOther" data-toggle="tooltip" title="Agregar fila" @click="addDetailOther"><i class="fa fa-plus"></i></a>
													SUBJECT TO CORRECTION
												</td>
												<td style="font-size: 11px;text-align: center;" class="b-top b-right">PREPAID</td>
												<td style="font-size: 11px;text-align: center;" class="b-top b-right">COLLECT</td>
											</tr>
											<tr v-for="(find2, index) in other">
												<td valign="top"  class="b-top b-right" style="width: 60%;">
													<div class=" col-sm-1">
														<a class="btn btn-danger btn-xs deleteOther" data-toggle="tooltip" title="Eliminar" @click="deleteRowOther"><i class="fa fa-trash"></i></a>
													</div>
													<div class="var col-sm-11" :class="{'has-error': errors.has('description') }">
														<input type="text" class="form-control" name="description" v-model="find2.description">
													</div>
												</td>
												<td valign="top"  class="b-top b-right">
													<div class="var" :class="{'has-error': errors.has('ammount_pp') }">
														<input type="number" class="form-control" name="ammount_pp" v-model="find2.ammount_pp" v-on:keyup="sumar">
													</div>
												</td>
												<td valign="top"  class="b-top b-right">
													<div class="var" :class="{'has-error': errors.has('ammount_cll') }">
														<input type="number" class="form-control" name="ammount_cll" v-model="find2.ammount_cll" v-on:keyup="sumar">
													</div>
												</td>
											</tr>
											<tr>
												<td valign="top"  class="b-top b-right" style="padding: 10px;text-align: right">GRAND TOTAL :</td>
												<td valign="top"  class="b-top b-right">&nbsp;@{{ oc_total_pp }}</td>
												<td valign="top"  class="b-top b-right">&nbsp;@{{ oc_total_cll }}</td>
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
													<div style="width: 95%;float: left;border-bottom: 1px solid #000000;" class="var">
														<input type="text" name="agent_for_carrier" placeholder="Agent" v-model="agent_for_carrier" class="form-control">
													</div>
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
												<td class="b-left" style="text-align: right;padding-right: 5px;font-weight: bold;">@{{ num_bl }}</td>
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
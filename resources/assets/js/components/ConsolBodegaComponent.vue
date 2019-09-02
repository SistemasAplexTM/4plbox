<!-- estilos -->
<style type="text/css">
	#tbl-modalguiasconsolidado_wrapper{
        padding-bottom:0 !important;
    }
	#tbl-consolidado_wrapper{
		padding-bottom: 0px !important;
	    padding-right: 0px !important;
	}
	.d-center {
	  display: flex;
	  align-items: center;
	}
	.danger,
	.danger .dropdown-toggle,
	.danger .selected-tag {
	  color: red;
	  border-color: red;
	}
    a.badge:focus, a.badge:hover{
        text-decoration:none;
    }
    .editable{
        font-weight: bold;
        /*color: orangered;*/
    }
    .client-detail{
    	height: 300px;
    	box-shadow: inset -2px -1px 13px -7px #000000
    }
    .btn-cajas{
        font-size: 35px!important;
        margin-right: 0px;
    }
    .btn-large-dim{
    	width: 60px;
    	height: 60px;
    }
    hr{
    	margin-top: 10px;
    	margin-bottom: 10px;
    }
</style>
<template>
	<div>
		<div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ documento.tipo_nombre }}</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="ibox-content col-lg-12">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="col-sm-12">
                                            <div class="form-group" :class="{ danger: errors.has('central_destino') }">
                                                <label for="central_destino_id">Central destino (agencia)</label>
                                                <v-select name="central_destino" :disabled="disabled_agencia" v-model="central_destino_id" label="name" :filterable="false" :options="branchs" @search="onSearchBranch" v-validate="'required'">
                                                </v-select>
                                            </div>
                                            <span class="danger">{{ errors.first('central_destino') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="col-sm-12">
                                            <div class="form-group" :class="{ danger: errors.has('pais') }">
                                                <label for="pais_id">País destino</label>
                                                <v-select name="pais" v-model="pais_id" :disabled="disabled_pais" label="name" :filterable="false" :options="countries" @search="onSearch" v-validate="'required'">
                                                </v-select>
                                            </div>
                                            <span class="danger">{{ errors.first('pais') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="col-sm-12">
                                            <div class="form-group" :class="{ danger: errors.has('transporte_id') }">
                                                <label for="transporte_id">Transporte</label>
                                                <v-select name="transporte_id" v-model="transporte_id" :disabled="disabled_transporte" label="nombre" :filterable="false" :options="transportes" v-validate="'required'">
                                                </v-select>
                                            </div>
                                            <span class="danger">{{ errors.first('transporte_id') }}</span>
                                        </div>
                                    </div>
                                    <!-- BOTONES DE IMPRESION -->
                                    <div class="col-sm-4">
                                        <div class="col-sm-12">
                                            <label class="control-label col-lg-12">&nbsp;</label>
                                            <a v-if="app_type == 'courier'" hfer="#" target="blank_" class="btn btn-info btn-sm printDocument" data-toggle="tooltip" data-placement="top" title="Imprimir manifiesto"><i class="fal fa-print"></i> Manifiesto</a>
                                            <a v-if="app_type == 'courier'" hfer="#" target="blank_" class="btn btn-info btn-sm printDocumentGuias" data-toggle="tooltip" data-placement="top" title="Imprimir guias hijas"><i class="fal fa-print"></i> Guias hijas</a>
                                            <a hfer="#" target="blank_" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir instrucciones"><i class="fal fa-print"></i> Instrucciones</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="observacion">Observación</label>
                                                <input type="text" v-model="observacion" name="observacion" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label for="status_id">Estatus</label>
                                                <v-select name="status_id" v-model="status_id" label="descripcion" :filterable="false" :options="status"></v-select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="status_id" style="width: 100%;">&nbsp;</label>
                                                <button class="btn btn-primary btn-sm" type="button" data-toggle="tooltip" title="Agregar estatus a guias" @click="addStatusConsolidado()"><i class="fal fa-save"></i> Aplicar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"><div class="hr-line-dashed"></div></div>
                                
                                <div class="row" style="margin-bottom: 50px;">
						                <div class="col-sm-4">
						                	<div class="row">
			                                	<div class="col-sm-4 pull-right">
			                                        <div class="col-sm-12">
			                                            <div class="form-group">
			                                                <label for="num_bolsa" class="">N° Bolsa</label>
															    <div class="input-group">
																    <span class="input-group-btn">
																        <button @click="increaseBoxes()" class="btn btn-info" type="button" data-toggle="tooltip" title="Agregar bolsa" style="padding: 8px 12px;"><li class="fal fa-cubes"></li></button>
																    </span>
																    <input type="number" min="1" class="form-control" style="" v-model="num_bolsa" name="num_bolsa" id="num_bolsa"  value="1">
															    </div>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
				                            <div class="tab-content">
				                                <div id="contact-1" class="tab-pane active">
				                                    <div class="client-detail">
					                                    <div class="full-height-scroll">
					                                        <ul class="list-group clear-list">
					                                        	<template  v-if="boxes.length > 0" v-for="box in boxes">
						                                            <li class="list-group-item fist-item">
						                                            	<div class="row">
							                                            	<div class="col-sm-4">
								                                                <button class="btn btn-success dim btn-large-dim btn-outline btn-cajas" data-toggle="tooltip" title="Bolsa" data-placement="right" type="button" @click="getDataDetail(box.num_bolsa)">
														                            {{ box.num_bolsa }}
														                        </button>
							                                            	</div>
							                                            	<div class="col-sm-5">
							                                            		<div><strong>Cajas: </strong><span>{{ box.cantidad }}</span></div>
							                                            		<div><strong>Kilos: </strong><span>{{ box.peso_kl }}</span><strong> - Libras: </strong><span>{{ box.peso }}</span></div>
							                                            		<div><strong>Volumen: </strong><span>{{ box.volumen }}</span></div>
							                                            	</div>
							                                            	<div class="col-sm-3" style="text-align: center;">
							                                            		<br>
							                                            		<a onclick="" class="boxEdit" title="Trasladar" data-toggle="tooltip" style="color:#FFC107;display: none;" @click="chageBoxModal(box.num_bolsa)"><i class="material-icons">import_export</i></a>
							                                            		<a onclick="" class="boxDelete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;display: none;" @click="removeBox(box.num_bolsa)"><i class="material-icons">&#xE872;</i></a>
							                                            	</div>
						                                            	</div>
						                                            </li><hr>
					                                            </template>
					                                            <template v-if="boxes.length == 0">
					                                            	<li class="list-group-item fist-item">
						                                            	<h1>No hay bolsas ingresadas</h1>
						                                            </li>
					                                            </template>				                                            
					                                        </ul>
					                                    </div>
				                                    </div>
				                                </div>
				                            </div>
						                </div>
						                <div class="col-sm-8">
						                	<div class="row">
							                	<div class="col-sm-4">
			                                        <div class="col-sm-12">
			                                            <div class="form-group">
			                                                <label for="num_guia" class="">Número de Guía/WRH</label>
			                                                <div class="input-group">
					                                        		<input type="text" class="form-control" v-model="num_guia" @keyup.enter="addGuiasToConsolidado()" name="num_guia">
																    <span class="input-group-btn">
																        <button class="btn btn-info" @click="addGuiasToConsolidado()" type="button" id="agregarBolsa" data-toggle="tooltip" title="Agregar guia" style="padding: 8px 12px;"><li class="fal fa-plus"></li></button>
																    </span>
															    </div>
			                                            </div>
			                                        </div>
			                                    </div>
			                                    <div class="col-sm-4">
			                                        <div class="col-sm-12">
			                                            <div class="form-group">
			                                                <label class="control-label col-lg-12">&nbsp;</label>
					                                        <button class="btn btn-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="Guias Disponobles" id="btn_buscarGuias" @click="getModalGuias()"><i class="fal fa-search-plus"></i> Buscar guias</button>
			                                            </div>
			                                        </div>
			                                    </div>
		                                    </div>
				                            <span class="small pull-right" style="color: orangered;" v-if="msn !== ''"><strong><i class="fal fa-exclamation-triangle"></i> Atención!</strong> {{ msn }}</span>
				                            <h2># Bolsa {{ n_bolsa }}</h2>
		                                    	<table id="tbl-consolidado" class="table table-striped table-hover table-bordered dataTable" style="width: 100%;margin-top: 30px;">
		                                    		<thead>
						                                <tr>
						                                    <th>#Recibo</th>
						                                    <th>Peso</th>
						                                    <th>Volumen</th>
						                                    <th>Consignee</th>
						                                    <th>Acciónes</th>
						                                </tr>
						                            </thead>
		                                    	</table>
				                        </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12 col-sm-offset-0 guardar">
                                            <button type="button" id="saveForm" class="ladda-button btn btn-success" data-style="expand-right" @click="saveConsolidado()"><i class="fal fa-save fa-fw"></i> Guardar Cambios</button>

                                        	<div class="btn-group dropup">
	                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                                                <i class="fal fa-print"></i>  Imprmir <span class="caret"></span>
	                                            </button>
	                                            <ul class="dropdown-menu">
		                                            <li><a href="" id="printDocument" class="printDocument" data-style="expand-right" target="blank_"><i class="fal fa-print fa-fw"></i> Imprimir Manifiesto</a></li>
		                                            <li><a href="" id="printDocumentGuias" class="printDocumentGuias" data-style="expand-right" target="blank_"><i class="fal fa-print fa-fw"></i> Imprimir Guias</a></li>
	                                            </ul>
	                                        </div>
		                                     <a @click="cancelDocument()" type="button" class="btn btn-white"><i class="fal fa-times fa-fw"></i> Cancelar </a>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-example-modal-lg" id="modalguiasconsolidado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <!--Este input es para cuando se haga el llamado desde el consolidado.. 
		            se llenara con el valor del contador del detalle o el id del campo -->
		            <input type="hidden" id="op" value="">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		                <h4 class="modal-title" id="myModalLabel">
		                    Guias / Warehouses pendientes por consolidar
		                </h4>
		            </div>
		            <div class="modal-body">
		            	<form id="formGuiasConsolidado" name="formGuiasConsolidado" method="POST" action="">
		            		<p>Seleccione los documentos que desea ingresar al consolidado y acontinuacion de click en el boton <strong>Agregar</strong></p>
							<div class="table-responsive">
		                        <table id="tbl-modalguiasconsolidado" class="table table-striped table-hover table-bordered" style="width: 100%;">
		                            <thead>
		                            	<tr>
		                            		<th class="text-center" style="width: 20px;"></th>
		                            		<th>Creacion</th>
		                            		<th>Numero Guia</th>
		                            		<th>Peso lb</th>
		                            		<th>Declarado</th>
		                            	</tr>
		                            </thead>
		                        </table>
		                    </div>
		                </form>
		            </div>
		            <div class="modal-footer">
		                <button type="button" id="" @click="addGuiasToConsolidadoModal()" class="btn btn-primary" data-dismiss="modal">Agregar</button>
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		            </div>
		        </div>
		    </div>
		</div>
		<!-- MODAL CONSIGNEES -->
		<div class="modal fade bs-example" id="modalShipperConsigneeConsolidado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	    <div class="modal-dialog" style="width: 50%!important;">
    	        <div class="modal-content">
    	            <div class="modal-header">
    	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    	                <h4 class="modal-title" id="myModalLabel">
    	                    <i class="fal fa-user-circle"></i> {{ tituloModal }}
    	                </h4>
    	            </div>
    	            <div class="modal-body">
    					<div class="table-responsive">
                            <table id="tbl-consolidado_sc" class="table table-striped table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Ciudad</th>
                                        <th>Zip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<tr v-if="contactos_fields != null" v-for="contacto in contactos_fields">
                                		<td v-if="contacto.nombre != null" style="width: 150px;">
                                			<button @click="selectedShipperConsignee(contacto)" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fal fa-check'></i></button>
                                		</td>
                                		<td v-if="contacto.nombre != null">{{ contacto.nombre }}</td>
                                		<td v-if="contacto.nombre != null">{{ contacto.telefono }}</td>
                                		<td v-if="contacto.nombre != null">{{ contacto.ciudad }}</td>
                                		<td v-if="contacto.nombre != null">{{ contacto.zip }}</td>
                                	</tr>
                                	<tr v-else>
                                		<td colspan="5">No hay datos</td>
                                	</tr>
                                </tbody>
                            </table>
                        </div> 
    	            </div>
    	            <div class="modal-footer">
    	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    	            </div>
    	        </div>
    	    </div>
    	</div>
        <!-- MODAL AGRUPAR GUIAS -->
        <div class="modal fade bs-example-modal-lg" id="modalagrupar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 30%!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <i class="fal fa-cubes"></i> Guias disponibles para agrupar
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form id="formGuiasAgrupar">
                            <p>Selecione las guias que desea agrupar en este registro.</p>
                            <div class="table-responsive">
                                <table id="tbl-modalagrupar" class="table table-striped table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px;"></th>
                                            <th>Numero Guia</th>
                                            <th>Peso lb</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="" @click="agruparGuiasConsolidado()" class="btn btn-primary" data-dismiss="modal">Agregar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL PARA TRASLADAR BOLSA DE UN CONSOLIDADO A OTRO -->
        <div class="modal fade bs-example-modal-lg" id="modalaTrasladarBolsa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 30%!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <i class="fal fa-exchange"></i> Trasladar bolsa
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                            	<p>Selecione el numero de consolidado al que desea trasladar esta bolsa.</p>
                                <div class="form-group">
			                      <label for="consolidado_id">Consolidado</label>
			                      <v-select name="consolidado_id" v-model="consolidado_id" label="consolidado" :filterable="false" :options="consolidados" @search="onSearchConsolidados" placeholder="# Consolidado">
			                        <template slot="option" slot-scope="option">
			                            {{ option.consolidado }} | <i class="fal fa-calendar"></i> {{ option.fecha }} | <i class="fal fa-globe"></i> {{ option.pais }}
			                        </template>
			                        <template slot="selected-option" slot-scope="option">
			                          <div class="selected d-center">
			                            {{ option.consolidado }} | <i class="fal fa-calendar"></i> {{ option.fecha }} | <i class="fal fa-globe"></i> {{ option.pais }}
			                          </div>
			                        </template>
			                      </v-select>
			                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="" @click="chageBox()" class="btn btn-primary" data-dismiss="modal">Trasladar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
	
    export default {
    	props: {
			documento: {
		      type: Object,
		      required: true
		    },contactos: {
		      type: Object,
		      required: false
		    },restore: {
              type: Object,
              required: false
            },agrupar: {
              type: Object,
              required: false
            },removeragrupado: {
              type: Object,
              required: false
            },permission: {
              type: Object,
              required: false
            },app_type: {
              type: String,
              required: true
            },ref_boxes: {
              type: Boolean
            },
    	},
    	watch:{
    		ref_boxes:function(values){
                this.getBoxesConsolidado();
            },
            permission:function(values){
                this.permissions = values;
            },
    		contactos:function(option){
    			this.contactos_fields = null;
                if(option.opcion === 'shipper'){
                    var id = option.idShipCons;
                	$('#modalShipperConsigneeConsolidado').modal('show');
                	this.tituloModal = 'Remitente (Shipper)';
                	var contact = this.shipper_contactos[id];
                	if(contact != null){
	                	this.contactos_fields = (JSON.parse(contact.replace(/&quot;/g, '"'))).campos;
	                }
                }
                if(option.opcion === 'consignee'){
                    var id = option.idShipCons;
                	$('#modalShipperConsigneeConsolidado').modal('show');
                	this.tituloModal = 'Destinatario (Consignee)';
                	var contact = this.consignee_contactos[id];
                	if(contact != null){
	                	this.contactos_fields = (JSON.parse(contact.replace(/&quot;/g, '"'))).campos;
	                }
                }
			},
            restore:function(option){
                let me = this;
                axios.get('restoreShipperConsignee/' + option.id + '/' + option.table).then(response => {
                    toastr.success('Registro original restaurado.');
                    me.updateTableDetail();
                }).catch(function(error) {
                    console.log(error);
                    toastr.warning('Error: -' + error);
                });
            },
            agrupar:function(option){
                let me = this;
                if ($.fn.DataTable.isDataTable('#tbl-modalagrupar')) {
                    $('#tbl-modalagrupar tbody').empty();
                    $('#tbl-modalagrupar').dataTable().fnDestroy();
                }
                var table = $('#tbl-modalagrupar').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: 'getGuiasAgrupar/'+ option.id,
                    columns: [{
                        "render": function (data, type, full, meta) {
                            return '<div class="checkbox checkbox-success"><input type="checkbox" data-id_guia="' + full.documento_detalle_id + '" id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '"></label></div>';
                        }
                    }, {
                        data: 'codigo',
                        name: 'codigo'
                    }, {
                        data: 'peso2',
                        name: 'peso2'
                    }]
                });
                $('#modalagrupar').modal('show');
            },
            removeragrupado:function(option){
                let me = this;
                axios.get('removerGuiaAgrupada/' + option.id + '/' + option.id_guia_detalle).then(response => {
                    toastr.success('Registro quitado correctamente.');
                    me.updateTableDetail();
                }).catch(function(error) {
                    console.log(error);
                    toastr.warning('Error: -' + error);
                });
            }
    	},
        mounted() {
        	$('#document_type').val('consolidado');
        	$('.printDocument').attr('href', '../../impresion-documento/' + $('#id_documento').val() + '/consolidado');
        	$('.printDocumentGuias').attr('href', '../../impresion-documento/' + $('#id_documento').val() + '/consolidado_guias');
			this.getDataDetail();
            this.getTransportes();
			this.getStatus();
			this.getBoxesConsolidado();
			if(this.documento.pais_id != null){
				this.pais_id = {id: this.documento.pais_id, name: this.documento.pais}
				this.disabled_pais = true;
			}
			if(this.documento.central_destino_id != null){
				this.central_destino_id = {id: this.documento.central_destino_id, name: this.documento.central_destino};
				this.disabled_agencia = true;
			}
			if(this.documento.transporte_id != null){
				this.transporte_id = {id: this.documento.transporte_id, nombre: this.documento.transporte}
				this.disabled_transporte = true;
			}
			if(this.documento.observaciones != null){
				this.observacion = this.documento.observaciones;
			}		
		},
		created(){
			/* CUSTOM MESSAGES VE-VALIDATOR*/
	        const dict = {
	            custom: {
	                transporte_id: {
	                    required: 'El Transporte es obligatorio'
	                },
	                pais_id: {
	                    required: 'El Pais es obligatorio'
	                },
	                central_destino_id: {
	                    required: 'La Central destino es obligatoria'
	                }
	            }
	        };
	        this.$validator.localize('es', dict);
	    },
		data () {
	        return {
                transporte_id: {id: 7, nombre: 'Aereo'},
	        	status_id: {id: 5, descripcion: 'Consolidada'},
                status: [],
	        	transportes: [],
	        	countries: [],
	        	branchs: [],
	        	services: [],
	        	details: [],
                contactos_fields: [],
                shipper_contactos: {},
                consignee_contactos: {},
	        	permissions: {},
	        	num_bolsa: 1,
	        	pais_id: null,
	        	central_destino_id: null,
	        	observacion: null,
	        	num_guia: null,
	        	disabled_transporte: false,
	        	disabled_agencia: false,
	        	disabled_pais: false,
	        	msn:'',
	        	tituloModal:'',
	        	n_bolsa: null,
	        	boxes: {},
	        	boxChangeC: null,
	        	consolidados: [],
		        consolidado_id:{
		          id: null,
		          consolidado: null
		        },
	        }
	    },
		methods: {
			chageBoxModal(bolsa){
				console.log(bolsa);
				this.boxChangeC = bolsa;
				$('#modalaTrasladarBolsa').modal('show');
			},
			chageBox(){
				let me = this;
                axios.get('changeBoxConsolidado/'+ me.boxChangeC + '/' + me.consolidado_id.id).then(function (response) { 
                	if(response.data.code === 200){
                    	me.getBoxesConsolidado();
                    	toastr.success('Bolsa trasladada correctamente.');
		                toastr.options.closeButton = true;
                    }else{
                    	toastr.warning('Atención! ha ocurrido un error.');
                    }
                }).catch(function (error) {
                    console.log(error);
                    toastr.warning('Error.');
                    toastr.options.closeButton = true;
                });
			},
			removeBox(bolsa){
				let me = this;
				swal({
                title: "<div><span style='color: rgb(212, 103, 82);'>Atención!</span><br> La bolsa sera removida de este consolidado y no podra recuperarse despues.</div>",
                text: "¿Desea Continuar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, Crear",
                cancelButtonText: "No, Cancelar!",
	            }).then((result) => {
	                if (result.value) {
		                axios.get('removeBoxConsolidado/' + bolsa).then(function (response) { 
		                    if(response.data.code === 200){
		                    	me.getBoxesConsolidado();
		                    	toastr.success('Bolsa eliminada correctamente.');
				                toastr.options.closeButton = true;
		                    }else{
		                    	toastr.warning('Atención! ha ocurrido un error.');
		                    }
		                }).catch(function (error) {
		                    console.log(error);
		                    toastr.warning('Error.');
		                    toastr.options.closeButton = true;
		                });
	                }
	            });
			},
			getBoxesConsolidado(){
				let me = this;
                axios.get('getBoxesConsolidado').then(function (response) { 
                    me.boxes = response.data.data;
                }).catch(function (error) {
                    console.log(error);
                    toastr.warning('Error.');
                    toastr.options.closeButton = true;
                });
			},
            agruparGuiasConsolidado: function(){
                $('#modalagrupar').modal('hide');
                let me = this;
                var datos = $("#formGuiasAgrupar").serializeArray();
                var ids = {};
                $.each(datos, function(i, field) {
                    if (field.name === 'chk[]') {
                        ids[i] =  $('#chk' + field.value).data('id_guia');
                    }
                });
                axios.post('agruparGuiasConsolidadoCreate',{
                    'id_detalle': me.agrupar.id,
                    'ids_guias': ids
                }).then(function (response) { 
                    toastr.success('Se agrupo correctamente.');
                    me.updateTableDetail();
                }).catch(function (error) {
                    console.log(error);
                    toastr.warning('Error.');
                    toastr.options.closeButton = true;
                });
            },
            addStatusConsolidado: function(){
                let me = this;
                axios.post('addStatusToGuias',{
                    'status_id': me.status_id.id
                }).then(function (response) { 
                    toastr.success('Registro Exitoso.');
                }).catch(function (error) {
                    console.log(error);
                    toastr.warning('Error.');
                    toastr.options.closeButton = true;
                });
            },
			selectedShipperConsignee: function(campos){
				let me = this;
		        axios.post('createContactsConsolidadoDetalle',{
		        	'campos': campos,
		        	'data': me.contactos
		        }).then(function (response) { 
		            toastr.success('Cambio Exitoso.');
                    $('#modalShipperConsigneeConsolidado').modal('hide');
                    me.updateTableDetail();
		        }).catch(function (error) {
		        	console.log(error);
		            toastr.warning('Error.');
                	toastr.options.closeButton = true;
		        });
			},
            getStatus: function(){
                let me = this;
                axios.get('../../status/all').then(function (response) { 
                    me.status = response.data.data;
                }).catch(function (error) {
                    console.log(error);
                    toastr.warning('Error.');
                    toastr.options.closeButton = true;
                });
            },
			getTransportes: function(){
				let me = this;
		        axios.get('../../administracion/5/all').then(function (response) { 
		            me.transportes = response.data.data;
		        }).catch(function (error) {
		        	console.log(error);
		            toastr.warning('Error.');
                	toastr.options.closeButton = true;
		        });
			},
			addGuiasToConsolidadoModal: function(){
				let me = this;
				var datos = $("#formGuiasConsolidado").serializeArray();
		        $.each(datos, function(i, field) {
		            if (field.name === 'chk[]') {
		                 me.addGuiasToConsolidado($('#chk' + field.value).data('numguia'));
		            }
		        });
			},
			getModalGuias: function(){
		        var me = this;
				if(me.pais_id != null && me.central_destino_id != null && me.transporte_id != null){
		            var codigoGW='';
		            $('#modalguiasconsolidado').modal('show');
		            if ($.fn.DataTable.isDataTable('#tbl-modalguiasconsolidado')) {
		                $('#tbl-modalguiasconsolidado tbody').empty();
		                $('#tbl-modalguiasconsolidado').dataTable().fnDestroy();
		            }
		            var table = $('#tbl-modalguiasconsolidado').DataTable({
		            	"language": {
					        "paginate": {
					            "previous": "Anterior",
					            "next": "Siguiente",
					        },
					        /*"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",*/
					        "info": "Registros del _START_ al _END_  de un total de _TOTAL_",
					        "search": "Buscar",
					        "lengthMenu": "Mostrar _MENU_ Registros",
					        "infoEmpty": "Mostrando registros del 0 al 0",
					        "emptyTable": "No hay datos disponibles en la tabla",
					        "infoFiltered": "(Filtrando para _MAX_ Registros totales)",
					        "zeroRecords": "No se encontraron registros coincidentes",
					    },
					    processing: true,
					    serverSide: true,
					    searching: true,
		                ajax: 'getAllGuiasDisponibles/'+ me.pais_id.id+'/'+me.transporte_id.id,
		                columns: [{
		                    "render": function (data, type, full, meta) {
			                	return '<div class="checkbox checkbox-success"><input type="checkbox" data-numguia="' + full.num_guia + '" id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '"></label></div>';
			                }
		                }, {
		                    data: 'created_at',
		                    name: 'created_at'
		                }, {
		                    "render": function (data, type, full, meta) {
                                if(me.app_type === 'courier'){
                                    if(full.liquidado == 0){
                                        codigoGW = full.num_warehouse;
                                        return full.num_warehouse;
                                    }else{
                                        if(full.liquidado == 1){
                                            codigoGW = full.num_guia;
                                            return full.num_guia;
                                        }
                                    }
                                }else{
                                    codigoGW = full.num_warehouse;
                                    return full.num_warehouse;
                                }
			                }
		                }, {
		                    data: 'peso2',
		                    name: 'peso2'
		                }, {
		                    "render": function (data, type, full, meta) {
			                	return '$ '+full.declarado2;
			                }
		                }]
		            });
		        }else{
		        	toastr.info('Porfavor, selecciona una central destino, un pais y un transporte para poder continuar.');
		        }
	        },
			saveConsolidado: function() {
				if(this.validateForm()){
		            var me = this;
		            var rowData = {
		                'document_type': $('#document_type').val(),
		                'pais_id': me.pais_id.id,
		                'central_destino_id': me.central_destino_id.id,
                        'transporte_id': me.transporte_id.id,
		                'status_id': me.status_id.id,
		                'observacion': me.observacion
		            }
		            axios.put('../'+$('#id_documento').val(), rowData).then(function (response) {    
			            toastr.success('Registro actualizado correctamente.');
	                	toastr.options.closeButton = true;
	                	me.disabled_agencia = true;
	                	me.disabled_pais = true;
	                	me.disabled_transporte = true;
			        }).catch(function (error) {
			            toastr.warning('Error.');
	                	toastr.options.closeButton = true;
			        });
			    }
	        },
	        validateForm: function() {
	        	let me = this;
	            if(me.central_destino_id == null){
	            	me.msn = 'Es necesario seleccionar una central destino para continuar.';
	            	return false;
	            }
	            if(me.pais_id == null){
	            	me.msn = 'Es necesario seleccionar un pais para continuar.';
	            	return false;
	            }
	            if(me.transporte_id == null){
	            	me.msn = 'Es necesario seleccionar un transporte para continuar.';
	            	return false;
	            }
	            return true;
	        },
	        cancelDocument: function() {
	            window.location.href = '../';
	        },
			updateDataDetail(rowData) {
		        var me = this;
		        axios.put('updateDetailConsolidado', {rowData}).then(function (response) {    
		            toastr.success('Registro actualizado correctamente.');
                	toastr.options.closeButton = true;
                	me.updateTableDetail();
		        }).catch(function (error) {
		            toastr.success('Error.');
                	toastr.options.closeButton = true;
		        });
		    },
			getDataDetail(num_bolsa){
				if ($.fn.DataTable.isDataTable('#tbl-consolidado')) {
					var dataTable = $('#tbl-consolidado').DataTable();
                    dataTable.clear();
                    $('#tbl-consolidado').dataTable().fnDestroy();
                }
				let me=this;
				me.n_bolsa = num_bolsa;
				var href_print_label = '';
				/* SOLO SI ES EL DOCUMNTO CONSOLIDADO*/
			    var table = $('#tbl-consolidado').DataTable({
			        processing: true,
			        serverSide: true,
			        responsive: true,
			        ajax: 'getAllConsolidadoDetalle/' + num_bolsa,
			        columns: [
			            {
			                "render": function (data, type, full, meta) {
                                var groupGuias = full.guias_agrupadas;
                                var btn_delete = "<a style='float: right;cursor:pointer;''><i class='material-icons'>clear</i></a>";
                                if(groupGuias != null && groupGuias != 'null' && groupGuias != ''){
                                    groupGuias = groupGuias.replace(/,/g, "<br>");
                                    groupGuias = groupGuias.replace(/@/g, ",");//SEPARADOR AL CREAR EL ONCLIC EN EL CONTROLADOR
                                }else{
                                    groupGuias = '';
                                }
                                var color = 'default';
                                if(parseInt(full.agrupadas) > 0){
                                    color = 'primary';
                                }
                                if(me.app_type === 'courier'){
                                    if(full.liquidado == 0){
                                        return full.num_warehouse + '<a style="float: right;cursor:pointer;" class="badge badge-'+color+' pop" role="button" \n\
                                            data-html="true" \n\
                                            data-toggle="popover" \n\
                                            data-trigger="hover" \n\
                                            title="<b>Guias agrupadas</b>" \n\
                                            data-content="'+groupGuias+'" \n\
                                            onclick="agruparGuias('+full.id+')">'+full.agrupadas+'</a>';
                                    }else{
                                        if(full.liquidado == 1){
                                            return full.num_guia + '<a style="float: right;cursor:pointer;" class="badge badge-'+color+' pop" \n\
                                            role="button" \n\
                                            data-html="true" \n\
                                            data-toggle="popover" \n\
                                            data-trigger="hover" \n\
                                            title="<b>Guias agrupadas</b>" \n\
                                            data-content="'+groupGuias+'" \n\
                                            onclick="agruparGuias('+full.id+')">'+full.agrupadas+'</a>';
                                            
                                        }
                                    }
                                }else{
                                    return full.num_warehouse + '<a style="float: right;cursor:pointer;" class="badge badge-'+color+' pop" role="button" \n\
                                            data-html="true" \n\
                                            data-toggle="popover" \n\
                                            data-trigger="hover" \n\
                                            title="<b>Guias agrupadas</b>" \n\
                                            data-content="'+groupGuias+'" \n\
                                            onclick="agruparGuias('+full.id+')">'+full.agrupadas+'</a>';
                                }
			                }
			            },
                        {
                            "render": function (data, type, full, meta) {
                                return '<a data-name="peso2" data-pk="'+full.documento_detalle_id+'" class="td_edit" data-type="text" data-placement="right" data-title="Peso">'+full.peso2+'</a>';
                            }
                        },
                        {data: 'peso', name: 'peso'},
			            {
			                "render": function (data, type, full, meta) {
			                	var nom_cons = full.consignee;
			                	var json = '';
			                	if(full.consignee == null){
			                		nom_cons = '';
			                	}
			                	if(full.consignee_json != null){
									json = JSON.parse(full.consignee_json.replace(/&quot;/g, '"'));
									nom_cons = json.nombre;
			                	}
			                	me.consignee_contactos[full.consignee_id] = full.consignee_contactos;
			                	return nom_cons + ' <a  data-toggle="tooltip" title="Canbiar" class="edit" style="float:right;color:#FFC107;" onclick="showModalShipperConsigneeConsolidado('+full.id+', '+full.consignee_id+',\'consignee\')"><i class="material-icons">&#xE254;</i></a> <a onclick=\"restoreShipperConsignee('+full.id+',\'consignee\')\" class="delete" title="Restaurar original" data-toggle="tooltip" style="float:right;color:#2196F3;"><i class="material-icons">cached</i></a>';
			                }
			            },
                        {
                            sortable: false,
                            "render": function (data, type, full, meta) {
                                var btn_delete = '';
                                if(full.liquidado == 0){
                                    href_print_label = "../../impresion-documento-label/"+ full.documento_id + "/warehouse/"+full.documento_detalle_id+"";
                                }else{
                                    if(full.liquidado == 1){
                                        href_print_label = "../../impresion-documento-label/"+ full.documento_id + "/guia/"+full.documento_detalle_id+"";
                                    }
                                }

                                var btn_invoice =  "<a href='../../impresion-documento/" + full.documento_id + "/invoice/"+full.documento_detalle_id+"' target='blank_' class=''><i class='fal fa-file' style='padding: 3px 2px;'></i> Imprimir invoice</a> ";
                                if (me.permissions.pdfLabel) {
                                    var btn_label =  "<a href='"+href_print_label+"' target='blank_' class=''><i class='fal fa-barcode' style='padding: 3px 2px;'></i> Imprimir label</a> ";
                                }
                                if (me.permissions.deleteDetailConsolidado) {
                                    var btn_delete = " <a onclick=\"eliminarConsolidado(" + full.id + ","+false+")\" class='' style='color:#E34724;'><i class='fal fa-trash' style='padding: 3px 2px;'></i> Eliminar</a> ";
                                }
                                var btn_group = '<div class="btn-group" data-toggle="tooltip" title="Acciones">'+
                                        '<button type="button" class="btn btn-default btn-outline dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                          '<i class="fal fa-cog"></i>'+
                                        '</button>'+
                                        '<ul class="dropdown-menu dropdown-menu-right pull-right">'+
                                          '<li>'+btn_invoice+'</li>'+
                                          '<li>'+btn_label+'</li>'+
                                          '<li role="separator" class="divider"></li>'+
                                          '<li>'+btn_delete+'</li>'+
                                        '</ul>'+
                                      '</div>';
                                // return btn_invoice + btn_label + btn_delete;
                                return btn_group;
                            }
                        }
			        ],
			        'columnDefs': [
			        	{ "targets": [ 0 ], width: 40, },
			        	{ "targets": [ 3 ], width: 60, },
			        	{ "targets": [ 4 ], width: 10, },
			        ],
			        "drawCallback": function () {
                        $('.edit, .delete').hide().children('i').css('font-size', '17px');
                        /* EDITABLE FIELD */
                        if (me.permissions.editDetail) {
                            $(".td_edit").editable({
                                ajaxOptions: {
                                    type: 'post',
                                    dataType: 'json'
                                },
                                url: "updateDetailConsolidado",
                                validate:function(value){
                                    if($.trim(value) == ''){
                                        return 'Este campo es obligatorio!';
                                    }
                                },
                                success: function(response, newValue) {
                                    me.updateTableDetail();
                                }
                            });
                        }
                        /* POPOVER PARA LAS GUIAS AGRUPADAS (BADGED) */
                        $(".pop").popover({ trigger: "manual" , html: true})
                            .on("mouseenter", function () {
                                var _this = this;
                                $(this).popover("show");
                                $(".popover").on("mouseleave", function () {
                                    $(_this).popover('hide');
                                });
                            }).on("mouseleave", function () {
                                var _this = this;
                                setTimeout(function () {
                                    if (!$(".popover:hover").length) {
                                        $(_this).popover("hide");
                                    }
                                }, 300);
                        });
                    },
			    });
			},
			addGuiasToConsolidado: function(num_guia){
				if(num_guia){
					this.num_guia = num_guia;
				}
				let me = this;
                me.msn = '';
                if(this.num_guia == ''){
					toastr.warning('Debe ingresar un numero de guia o warehouse para continuar.');
                    toastr.options.closeButton = true;
                }else{
                	if(this.validateForm()){
						axios.get('buscarGuias/' + this.num_guia + '/'+ this.num_bolsa + '/'+ this.pais_id.id).then(response => {
			                if(response.data.code === 200){
			                	var table = $('#tbl-consolidado').DataTable();
								if (!table.data().count()) {
									this.saveConsolidado();
									this.disabled_pais = true;
									this.disabled_agencia = true;
									this.disabled_transporte = true;
								}
			                	me.updateTableDetail();
			                	me.getBoxesConsolidado();
			                	toastr.success('Registro agregado correctamente.');
		                    	toastr.options.closeButton = true;
		                    	this.num_guia = '';
			                }else{
			                	if(response.data.code === 600){
			                		me.msn = response.data.data;
			                		this.num_guia = '';
			                	}
			                }
			            }).catch(function(error) {
			                console.log(error);
			                toastr.error("Error.", {
			                    timeOut: 50000
			                });
			            });
			        }
		        }
			},
			updateTableDetail(){
				var table = $('#tbl-consolidado').DataTable();
	            table.ajax.reload();
			},
			increaseBoxes(){
				this.num_bolsa = parseInt(this.num_bolsa)+1;
			},
			onSearchBranch(search, loading) {
		      loading(true);
		      this.searchBranch(loading, search, this);
		    },
		    searchBranch: _.debounce((loading, search, vm) => {
		      fetch(
		        `../vueSelectTransportadorMaster/${escape(search)}`
		      ).then(res => {
		        res.json().then(json => (vm.branchs = json.items));
		        loading(false);
		      });
		    }, 350),

			onSearch(search, loading) {
		      loading(true);
		      this.search(loading, search, this);
		    },
		    search: _.debounce((loading, search, vm) => {
		      fetch(
		        `../vueSelect/${escape(search)}`
		      ).then(res => {
		        res.json().then(json => (vm.countries = json.items));
		        loading(false);
		      });
		    }, 350),
		    onSearchConsolidados(search, loading) {
	          loading(true);
	          this.searchConsolidados(loading, search, this);
	        },
	        searchConsolidados: _.debounce((loading, search, vm) => {
	            fetch(
	              `../../master/vueSelectConsolidados/${escape(search)}`
	            ).then(res => {
	              res.json().then(json => (vm.consolidados = json.items));
	              loading(false);
	            });
	        }, 350)
		}
    }
</script>
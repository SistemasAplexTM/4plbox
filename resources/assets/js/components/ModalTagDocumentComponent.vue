<!-- estilos -->
<style type="text/css">
    #tbl-statusReport_wrapper{
        padding-bottom:0 !important;
    }
    #tbl-status_wrapper, #tbl-notas_wrapper{
		padding-bottom: 0px !important;
	    padding-right: 0px !important;
	}
    .modal-dialog{width: 40%!important;}
    #num_track{
    	font-size: 25px;
    }
    #register{
    	margin-top: 15px;
    }
    .v-select{
		background-color:#FFFFFF;
	}
	.v-select .dropdown li {
	  border-bottom: 1px solid rgba(112, 128, 144, 0.1);
	}

	.v-select .dropdown li:last-child {
	  border-bottom: none;
	}

	.v-select .dropdown li a {
	  padding: 10px 20px;
	  width: 100%;
	  font-size: 1.25em;
	  color: #3c3c3c;
	}

	.v-select .dropdown-menu .active > a {
	  color: #fff;
	}
	.dropdown-toggle>input[type="search"] {
    width: 130px !important;
	}
	.dropdown-toggle>input[type="search"]:focus:valid {
	    width: 100% !important;
	}
	button.dim{
		margin-bottom: 0px!important;
	}
	.button_print{
		margin-top: -5px;
    	float: right;
	}
</style>
<template>
	<!-- modal shipper -->
    <div class="modal fade bs-example" id="modalTagDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title" id="myModalLabel">
	                    <label id="num_track"><i class="fa fa-cubes"></i> {{ num_track }} <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cantidad de cajas" style="font-size:17px;">{{ cantidad }}</span></label>
	                </h4>
	                <div class="">
	                	<span><i class="fa fa-user"></i> {{ cliente_cons }}</span>
	                	<button @click="openUrl(urlSendEmail)" style="color: #23c6c8;" class="btn btn-default dim button_print" type="button" data-toggle="tooltip" title="Enviar email" id="btn-sendEmail">
	                		<i class="fa fa-envelope fa-lg" style=""></i>
	                	</button>
                        <!-- <button @click="openUrl(urlPrintLabel)" style="color: #1ab394;" class="btn btn-default dim button_print" type="button" data-toggle="tooltip" title="Imprimir label">
	                		<i class="fa fa-barcode fa-lg" style=""></i>
	                	</button>
	                	<button @click="openUrl(urlPrint)" style="color: #ffbb33;" class="btn btn-default dim button_print" type="button" data-toggle="tooltip" title="Imprimir documento">
                        	<i class="fa fa-file-o fa-lg" style=""></i>
                        </button> -->
	                </div>
	                <div class=""><span><i class="fa fa-envelope "></i> {{ cliente_email }}</span></div>
	            </div>
	            <div class="modal-body">
	            	<div class="row">
		            	<div class="col-lg-12">
		                    <!-- Nav tabs -->
							  <ul class="nav nav-tabs" role="tablist">
							    <li role="presentation" class="active"><a href="#status" aria-controls="status" role="tab" data-toggle="tab"><i class="fa fa-clock-o"></i> Status</a></li>
							    <li role="presentation"><a href="#notas" aria-controls="notas" role="tab" data-toggle="tab"><i class="fa fa-commenting-o"></i> Notas</a></li>
							  </ul>
							<div class="tab-content">
							    <div role="tabpanel" class="tab-pane fade active in" id="status">
							    	<div class="row form-group" id="register">
										<div class="col-lg-6" :class="{ 'has-error': errors.has('warehouse') }">
											<v-select name="warehouse" v-model="warehouse_codigo" label="name" :options="warehouses" v-validate.disable="'required'" placeholder="Warehouse/Guia"></v-select>
											<small class="help-block">{{ errors.first('warehouse') }}</small>
										</div>
						        		<div class="col-lg-6" :class="{ 'has-error': errors.has('estatus') }">
											<v-select name="estatus" v-model="estatus_id" label="name" :options="status" v-validate.disable="'required'" placeholder="Status"></v-select>
											<small class="help-block">{{ errors.first('estatus') }}</small>
										</div>
										<div class="col-lg-10">
								        	<input type="text" class="form-control" v-model="observacion" placeholder="Observación">
								        </div>
										<div class="col-lg-2">
											<button class="btn btn-primary" data-toggle="tooltip" title="Agregar" @click="createStatusReport()"><i class="fa fa-plus"></i></button>
										</div>
							        </div>
									<div class="table-responsive">
				                        <table id="tbl-statusReport" class="table table-striped table-hover table-bordered" style="width: 100%;">
				                            <thead>
				                                <tr>
				                                    <th>Fecha</th>
				                                    <th>Estatus</th> 
				                                    <th>Observación</th>
				                                    <th>Usuario</th>
				                                    <th>Accion</th>
				                                </tr>
				                            </thead>
				                        </table>
				                    </div>
					            </div>
						        <div role="tabpanel" class="tab-pane fade " id="notas">
						        	<div class="row form-group" id="register">
						        		<div class="col-lg-10" :class="{ 'has-error': errors.has('nota_name') }">
								        	<input type="text" name="nota_name" class="form-control" v-model="nota" placeholder="Agregar nota" v-validate.disable="'required'">
								        	<small class="help-block">{{ errors.first('nota_name') }}</small>
								        </div>
								        <div class="col-lg-2">
											<button class="btn btn-primary" data-toggle="tooltip" title="Agregar" @click="createNota()"><i class="fa fa-plus"></i></button>
										</div>
							        </div>
									<div class="table-responsive">
				                        <table id="tbl-notas" class="table table-striped table-hover table-bordered" style="width: 100%;">
				                            <thead>
				                                <tr>
				                                    <th>Fecha</th>
				                                    <th>Nota</th> 
				                                    <th>Usuario</th> 
				                                    <th>Accion</th>
				                                </tr>
				                            </thead>
				                        </table>
				                    </div>
					            </div>
					        </div>
					    </div>
					</div>
				</div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
    export default {
    	props:{
    		params: {
		      type: Object
		    },
		    id_status: Number,
		    table_delete: String,
    	},
        mounted() {
        	
        },
        data () {
	        return {
	        	id_document: null,
	        	cliente_cons: null,
	        	cliente_email: null,
	        	num_track: null,
	        	cantidad: null,
	        	urlPrint: null,
	        	urlPrintLabel: null,
	        	urlSendEmail: null,
	        	estatus_id: null,
	        	nota: null,
	        	observacion: null,
	        	id_status_nota: null,
	        	warehouse_codigo: null,
	        	status: [],
	        	warehouses: [],
	        }
	    },
	    watch:{
	    	params: function (newQuestion) {
	    		this.id_document = newQuestion.id;
	    		this.cliente_cons = newQuestion.cliente;
	    		this.cliente_email = newQuestion.correo;
	    		this.num_track = newQuestion.codigo;
	    		this.cantidad = newQuestion.cantidad;
	    		if(newQuestion.correo == 'Sin correo' || newQuestion.correo == ''){
	    			$('#btn-sendEmail').attr('disabled', true);
	    		}else{
	    			$('#btn-sendEmail').attr('disabled', false);
	    		}
	    		if(newQuestion.liquidado == 1){
					this.urlSendEmail = 'documento/sendEmailDocument/'+this.id_document;
		    		this.urlPrint = 'impresion-documento/'+newQuestion.id+'/guia';
					this.urlPrintLabel = 'impresion-documento-label/'+newQuestion.id+'/guia';
	    		}else{
		    		this.urlSendEmail = 'documento/sendEmailDocument/'+this.id_document;
		    		this.urlPrint = 'impresion-documento/'+newQuestion.id+'/warehouse';
					this.urlPrintLabel = 'impresion-documento-label/'+newQuestion.id+'/warehouse';
	    		}
				this.getSelectStatus();
	        	this.getSelectWarehouses();
	            this.getStatus();
	            this.getNotas();
			},
			id_status: function (newQuestion) {
				if(newQuestion != null){
					this.id_status_nota = newQuestion;
					this.deleteStatusNota();
				}
			},
			table_delete: function (newQuestion) {
				this.table_delete = newQuestion;
			}
	    },
		methods: {
			openUrl: function(url){
				window.open(url, '_blank');
			},
			resetForm: function() {
	            this.estatus_id= null,
	            this.warehouse_codigo= null,
	        	this.nota= null,
	        	this.observacion= null
	        },
			updateTable: function(table) {
	            $('#tbl-' + table).dataTable()._fnAjaxUpdate();
	        },
			getSelectStatus: function(){
				axios.get('status/getDataSelectModalTagGuia').then(response => {
					this.status = response.data.data;
	            });
			},
			getSelectWarehouses: function(){
				axios.get('documento/getDataSelectWarehousesModalTagGuia/'+ this.id_document).then(response => {
					this.warehouses = response.data.data;
	            });
			},
			getStatus: function(){
				if ($.fn.DataTable.isDataTable('#tbl-statusReport')) {
	                $('#tbl-statusReport tbody').empty();
	                $('#tbl-statusReport').dataTable().fnDestroy();
	            }
				var table = $('#tbl-statusReport').DataTable({
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
            		lengthMenu: [[5, 10, 15, 20, 50], [5, 10, 15, 20, 50]],
	                ajax: 'statusReport/getAllGrid/'+this.id_document,
	                columns: [ {
	                    data: 'fecha_status',
	                    name: 'fecha_status'
	                }, {
	                	sortable: false,
	                	"render": function (data, type, full, meta) {
		                    return '<span style="color:#439a46;font-weight: 900;">'+full.codigo+'</span><div>'+full.status_name+'</div> ';
		                }
	                }, {
	                    data: 'observacion',
	                    name: 'observacion'
	                }, {
	                    data: 'name',
	                    name: 'usuario_id'
	                },{
	                    "render": function (data, type, full, meta) {
		                	return '<a class="btn btn-danger btn-xs btn-outline" data-toggle="tooltip" title="Eliminar" onclick="deleteStatusNota('+full.id+', \'statusReport\')"><i class="fa fa-trash"></i></a>';
		                }
	                }, ]
	            });
			},
			createStatusReport: function() {
	            let me = this;
	            this.$validator.validateAll(['estatus', 'warehouse']).then((result) => {
	            	if (result) {
			            axios.post('statusReport', {
			                'status_id': this.estatus_id.id,
			                'codigo': this.warehouse_codigo.name,
			                'observacion': this.observacion,
			                // 'transportadora': this.transportadora,
			                // 'num_transportadora': this.num_transportadora,
			            }).then(function(response) {
			                if (response.data['code'] == 200) {
			                    toastr.success('Registro creado correctamente.');
			                    toastr.options.closeButton = true;
			                    me.resetForm();
			                    me.updateTable('statusReport');
			                } else {
			                    toastr.warning(response.data['error']);
			                    toastr.options.closeButton = true;
			                }
			            }).catch(function(error) {
			                if (error.response.status === 422) {
			                    me.formErrors = error.response.data; //guardo los errores
			                    me.listErrors = me.formErrors.errors; //genero lista de errores
			                }
			                $.each(me.formErrors.errors, function(key, value) {
			                    $('.result-' + key).html(value);
			                });
			                toastr.error("Porfavor completa los campos obligatorios.", {
			                    timeOut: 50000
			                });
			            });
			        }
	            }).catch(function(error) {
	                toastr.warning('Error: Completa los campos.');
	            });
	        },
			deleteStatusNota: function(){
				let me = this;
				deleteStatusNota(null, me.table_delete);
				swal({
	                title: "¿Desea eliminar el registro seleccionado?",
	                text: "No podras restaurarlo despues!",
	                type: 'warning',
	                showCancelButton: true,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: "Si",
	                cancelButtonText: "No, Cancelar!",
	            }).then((result) => {
	                if (result.value) {
						console.log('borrar : '+me.id_status_nota+' table = '+me.table_delete);
						axios.get(me.table_delete+'/delete/' + me.id_status_nota + '/' +false).then(response => {
                        toastr.success("Registro eliminado exitosamente.");
                        toastr.options.closeButton = true;
                        var table = $('#tbl-'+me.table_delete).DataTable();
                        table.ajax.reload();
                    });
					}
	            })
			},
			/* NOTAS */
			getNotas: function(){
				if ($.fn.DataTable.isDataTable('#tbl-notas')) {
	                $('#tbl-notas tbody').empty();
	                $('#tbl-notas').dataTable().fnDestroy();
	            }
				var table = $('#tbl-notas').DataTable({
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
            		lengthMenu: [[5, 10, 15, 20, 50], [5, 10, 15, 20, 50]],
	                ajax: 'documento/getAllGridNotas/'+this.id_document,
	                columns: [ {
	                    data: 'created_at',
	                    name: 'created_at'
	                }, {
	                    data: 'nota',
	                    name: 'nota'
	                }, {
	                    data: 'name',
	                    name: 'name'
	                },{
	                    "render": function (data, type, full, meta) {
		                	return '<a class="btn btn-danger btn-xs btn-outline" data-toggle="tooltip" title="Eliminar" onclick="deleteStatusNota('+full.id+', \'notas\')"><i class="fa fa-trash"></i></a>';
		                }
	                }, ]
	            });
			},
			createNota: function(){
            let me = this;
            this.$validator.validateAll(['nota_name']).then((result) => {
            	if (result) {
		            axios.post('documento/ajaxCreateNota/'+this.id_document,{
		                'nota' : this.nota
		            }).then(function(response){
		                if(response.data['code'] == 200){
		                    toastr.success('Registro creado correctamente.');
		                    toastr.options.closeButton = true;
		                }else{
		                    toastr.warning(response.data['error']);
		                    toastr.options.closeButton = true;
		                }
		                me.resetForm();
		                me.updateTable('notas');
		            }).catch(function(error){
		                console.log(error);
		                toastr.error("Porfavor completa los campos obligatorios.", {timeOut: 50000});
		            });
		        }
            }).catch(function(error) {
                toastr.warning('Error: Completa los campos.');
            });
        },
		}
    }
</script>
<!-- estilos -->
<style type="text/css">
    .modal-dialog{width: 50%}
</style>
<template>
	<!-- modal shipper -->
    <div class="modal fade bs-example-modal-lg" id="modalCargosAdd" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title" id="myModalLabel">Cargos Adicionales</h4>
	            </div>
	            <div class="modal-body">
	                <div class="row">
	                    <div class="col-lg-12 form-horizontal">
	                        <div class="col-lg-7">
	                            <div class="col-sm-12">
	                                <div class="form-group" :class="{ 'has-error': errors.has('concepto_c') }">
	                                    <label for="concepto_c" class="">Concepto</label>
	                                    <input type="text" id="concepto_c" name="concepto_c" value="" class="form-control" v-model="concepto" v-validate.disable="'required'">
	                                    <small class="help-block">{{ errors.first('concepto_c') }}</small>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-2">
	                            <div class="col-sm-12">
	                                <div class="form-group" :class="{ 'has-error': errors.has('cantidad_c') }">
	                                    <label for="cantidad_c" class="">Cantidad</label>
	                                    <input type="number" id="cantidad_c" name="cantidad_c" value="1" class="form-control" v-model="cantidad" v-validate.disable="'required'">
	                                    <small class="help-block">{{ errors.first('cantidad_c') }}</small>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-2">
	                            <div class="col-sm-12">
	                                <div class="form-group" :class="{ 'has-error': errors.has('precio_c') }">
	                                    <label for="precio_c" class="">Precio</label>
	                                    <input type="number" placeholder="$" id="precio_c" name="precio_c" value="" class="form-control" v-model="precio" v-validate.disable="'required'">
	                                    <small class="help-block">{{ errors.first('precio_c') }}</small>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-1">
	                            <div class="col-sm-12">
	                                <div class="form-group">
	                                    <label for="" class="">&nbsp;</label>
	                                    <div class="input-group">
	                                        <button class="btn btn-primary btn-sm" type="button" id="btn_Cargoadd" value="" @click="addAdditionalCharges()"><i class="fa fa-plus"></i></button>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-lg-12">
	                        <div class="col-sm-12">
	                            <div class="form-group">
	                                <div class="table-responsive">
	                                    <table class="table table-striped table-bordered table-hover" id="tbl-cargosAdd">
	                                        <thead>
	                                            <tr>
	                                                <th>Concepto</th>
	                                                <th>Cantidad</th>
	                                                <th>Precio</th>
	                                                <th>Total</th>
	                                                <th>Acciones</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                        	<tr id="row_" v-for="chargue in chargues">
	                                        		<td>{{ chargue.concepto }}</td>
	                                        		<td>{{ chargue.cantidad }}</td>
	                                        		<td>{{ chargue.precio }}</td>
	                                        		<td class="td_total">{{ chargue.cantidad * chargue.precio }}</td>
	                                        		<td>
	                                        			<a type="button" id="btn_remove" @click="deleteAdditionalCharges(chargue.id)" data-toggle="tooltip" title="" class="btn btn-danger btn-xs" data-original-title="Eliminar"><i class="fa fa-times"></i></a>
	                                        		</td>
	                                        	</tr>
	                                        </tbody>
	                                        <tfoot>
	                                            <tr>
	                                                <td colspan="3"></td>
	                                                <th colspan="2" style="padding: 0px; color: red;">
	                                                    <input type="text" style="padding-left: 20px;" readonly="" class="form-control" v-model="total_chargues">
	                                                </th>
	                                            </tr>
	                                        </tfoot>
	                                    </table>
	                                    <div id="noEnviarCargosAdd" class="col-lg-12" style="text-align: center; color: red; display: none;">Ingrese datos correctos</div>
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
        props: [
          "showmodal"
        ],
		data () {
	        return {
	          concepto: '',
	          cantidad: 1,
	          precio: 0,
	          total_chargues: 0,
	          chargues: []
	        }
	    },
	    watch:{
			total_chargues: function (newQuestion) {
				 // console.log(newQuestion + ' cargos_add = ' + $('#cargos_add').val());
	             $('#cargos_add').val(newQuestion);
	             totalizeDocument();
			},
			showmodal: function(newQuestion){
				this.getDataAdditionalCharges();
			}
	    },
		methods: {
			getDataAdditionalCharges(){
				var me = this;
				axios.get('additionalChargues/getAll/' + $('#id_documento').val()).then(function(response) {
	                if (response.data['code'] == 200) {
	                    me.chargues = response.data.data;
	                    var total = 0;
	                    for (var i = me.chargues.length - 1; i >= 0; i--) {
	                    	total += me.chargues[i]['total'];
	                    }
	                    me.total_chargues = total;
	                } else {
	                    toastr.warning(response.data['error']);
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                console.log(error);
	                if (error.response.status === 422) {
	                    me.formErrors = error.response.data; //guardo los errores
	                    me.listErrors = me.formErrors.errors; //genero lista de errores
	                }
	                $.each(me.formErrors.errors, function(key, value) {
	                    $('.result-' + key).html(value);
	                });
	                toastr.error("Error en transacción.", {
	                    timeOut: 50000
	                });
	            });
			},
			addAdditionalCharges(){
				this.$validator.validateAll().then((result) => {
	            	if (result) {
						var me = this;
			            axios.post('additionalChargues', {
			                'documento_id': $('#id_documento').val(),
			                'concepto': me.concepto,
			                'precio': me.cantidad,
			                'cantidad': me.precio,
			                'total': me.cantidad * me.precio
			            }).then(function(response) {
			                if (response.data['code'] == 200) {
			                    toastr.success('Registro editado correctamente.');
			                    toastr.options.closeButton = true;
			                    me.getDataAdditionalCharges();
			                    me.concepto = null;
			                    me.precio = 0;
			                } else {
			                    toastr.warning(response.data['error']);
			                    toastr.options.closeButton = true;
			                }
			            }).catch(function(error) {
			                console.log(error);
			                if (error.response.status === 422) {
			                    me.formErrors = error.response.data; //guardo los errores
			                    me.listErrors = me.formErrors.errors; //genero lista de errores
			                }
			                $.each(me.formErrors.errors, function(key, value) {
			                    $('.result-' + key).html(value);
			                });
			                toastr.error("Por favor completa los campos obligatorios.", {
			                    timeOut: 50000
			                });
			            });
			        }
			    }).catch(function(error) {
	                toastr.warning('Error: Por favor completa los campos obligatorios.');
	            });
        	},
        	deleteAdditionalCharges(id){
        		var me = this;
        		axios.get('additionalChargues/delete/' + id).then(function(response) {
	                if (response.data['code'] == 200) {
	                    toastr.success('Registro eliminado correctamente.');
	                    toastr.options.closeButton = true;
	                    me.getDataAdditionalCharges();
	                } else {
	                    toastr.warning(response.data['error']);
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                console.log(error);
	                if (error.response.status === 422) {
	                    me.formErrors = error.response.data; //guardo los errores
	                    me.listErrors = me.formErrors.errors; //genero lista de errores
	                }
	                $.each(me.formErrors.errors, function(key, value) {
	                    $('.result-' + key).html(value);
	                });
	                toastr.error("Error en transacción.", {
	                    timeOut: 50000
	                });
	            });
        	}
		}
    }
</script>
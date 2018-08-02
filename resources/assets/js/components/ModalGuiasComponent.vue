<!-- estilos -->
<style type="text/css">
    #tbl-modalguiasconsolidado_wrapper{
        padding-bottom:0 !important;
    }
</style>
<template>
	<!-- modal shipper -->
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
	                <button type="button" id="addGuiasToConsolidado" class="btn btn-primary" data-dismiss="modal">Agregar</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
    export default {
        mounted() {
            // 
        },
        data () {
	        return {
	        	//
	        }
	    },
		methods: {
			
		}
    }
</script>
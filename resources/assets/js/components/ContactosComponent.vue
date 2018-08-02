<style type="text/css">
@media (max-width: 1080px) {
  .modal-dialog {
    width: 90% !important;
  }
}
</style>
<template>
	<div class="modal fade" id="mdl-contactos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div style="width: 60%;" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> Registrar contactos</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-4" v-for="campo in campos">
                <div class="panel panel-info">
                  <div class="panel-heading">Contacto {{ campo.id }}</div>
                  <div class="panel-body">
                    <p>
                      <input v-model="campo.nombre" id="nombre1" placeholder="Nombre" type="text" class="form-control" data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="campo.telefono" id="telefono1" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="campo.direccion" id="direccion1" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                    </p>
                    <p>
                        <input v-model="campo.ciudad" id="ciudad1" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="campo.pais" id="pais1" placeholder="País" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='País'>
                    </p>
                    <p>
                      <input v-model="campo.zip" id="zip1" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" @click.prevent="store">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        props:[
          "parametro",
          "table"
        ],
        data(){
        	return {
              campos: [{
                id: 1,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 2,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null,
              },
              {
                id: 3,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 4,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 5,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 6,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              }
            ]
          }
        },
        watch: {
          parametro: function (newQuestion) {
            this.get();
          }
        },
        methods: {
          resetFields(){
            this.campos = [{
                id: 1,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 2,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null,
              },
              {
                id: 3,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 4,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 5,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              },
              {
                id: 6,
                nombre: null,
                telefono: null,
                ciudad: null,
                pais: null,
                direccion: null,
                zip: null
              }
            ];
          },
        	get(){
        		var me = this;
	            axios.get('obtener_contactos/' + this.parametro + '/'+ this.table)
	            .then(function(response) {
                if(response.data){
                  me.campos =  response.data.campos;
                }else{
                  me.resetFields();
                }
	            }).catch(function(error) {
	                alert('Ocurrió un error al consultar registros');
	            });
        	},
        	store(){
        		var me = this;
	            axios.post('agregar_contactos/'+ this.parametro + '/'+ this.table, {
                'campos': me.campos,
	            }).then(function(response) {
	                if (response.data['code'] == 200) {
	                    toastr.success('Registro editado correctamente.');
	                    toastr.options.closeButton = true;
                      $('#mdl-contactos').modal('hide');
	                } else {
	                    toastr.warning('Error');
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                alert('Ocurrió un error al intentar registrar');
	            });
        	}
        }
    };
</script>

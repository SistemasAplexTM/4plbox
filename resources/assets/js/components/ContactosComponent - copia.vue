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
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 1</div>
                    <div class="panel-body">
                      <p>
                        <input v-model="nombre1" placeholder="Nombre" type="text" class="form-control" data-toggle='tooltip' data-placement='top' title='Nombre'>
                      </p>
                      <p>
                        <input v-model="telefono1" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                      </p>
                      <p>
                        <input v-model="ciudad1" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                      </p>
                      <p>
                        <input v-model="zip1" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                      </p>
                      <p>
                        <input v-model="direccion1" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                      </p>
                    </div>
                </div>
              </div>
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 2</div>
                    <div class="panel-body">
                    <p>
                      <input v-model="nombre2" placeholder="Nombre" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="telefono2" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="ciudad2" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="zip2" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                    <p>
                      <input v-model="direccion2" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 3</div>
                    <div class="panel-body">
                    <p>
                      <input v-model="nombre3" placeholder="Nombre" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="telefono3" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="ciudad3" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="zip3" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                    <p>
                      <input v-model="direccion3" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='DIrección'>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 4</div>
                    <div class="panel-body">
                    <p>
                      <input v-model="nombre4" placeholder="Nombre" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="telefono4" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="ciudad4" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="zip4" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                    <p>
                      <input v-model="direccion4" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 5</div>
                    <div class="panel-body">
                    <p>
                      <input v-model="nombre5" placeholder="Nombre" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="telefono5" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="ciudad5" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="zip5" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                    <p>
                      <input v-model="direccion5" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">Contacto 6</div>
                    <div class="panel-body">
                    <p>
                      <input v-model="nombre6" placeholder="Nombre" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Nombre'>
                    </p>
                    <p>
                      <input v-model="telefono6" placeholder="Teléfono" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Teléfono'>
                    </p>
                    <p>
                      <input v-model="ciudad6" placeholder="Ciudad" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Ciudad'>
                    </p>
                    <p>
                      <input v-model="zip6" placeholder="Zip" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Zip'>
                    </p>
                    <p>
                      <input v-model="direccion6" placeholder="Dirección" type="text" class="form-control"  data-toggle='tooltip' data-placement='top' title='Dirección'>
                    </p>
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
  </div>
</template>

<script>
    export default {
        mounted() {
            
        },
        props:[
          "parametro",
          "table"
        ],
        data(){
        	return {
            id1: 1,
            nombre1: null,
            direccion1: null,
            zip1: null,
            ciudad1: null,
            telefono1: null,
            id2: 2,
            nombre2: null,
            direccion2: null,
            zip2: null,
            ciudad2: null,
            telefono2: null,
            id3: 3,
            nombre3: null,
            direccion3: null,
            zip3: null,
            ciudad3: null,
            telefono3: null,
            id4: 4,
            nombre4: null,
            direccion4: null,
            zip4: null,
            ciudad4: null,
            telefono4: null,
            id5: 5,
            nombre5: null,
            direccion5: null,
            zip5: null,
            ciudad5: null,
            telefono5: null,
            id6: 6,
            nombre6: null,
            direccion6: null,
            zip6: null,
            ciudad6: null,
            telefono6: null
        	}
        },
        watch: {
          parametro: function (newQuestion) {
            this.get();
          }
        },
        methods: {
        	get(){
        		var me = this;
	            axios.get('obtener_contactos/' + this.parametro + '/'+ this.table)
	            .then(function(response) {
                me.nombre1 =  response.data.nombre1;
                me.direccion1 =  response.data.direccion1;
                me.zip1 =  response.data.zip1;
                me.ciudad1 =  response.data.ciudad1;
                me.telefono1 =  response.data.telefono1;
                me.nombre2 =  response.data.nombre2;
                me.direccion2 =  response.data.direccion2;
                me.zip2 =  response.data.zip2;
                me.ciudad2 =  response.data.ciudad2;
                me.telefono2 =  response.data.telefono2;
                me.nombre3 =  response.data.nombre3;
                me.direccion3 =  response.data.direccion3;
                me.zip3 =  response.data.zip3;
                me.ciudad3 =  response.data.ciudad3;
                me.telefono3 =  response.data.telefono3;
                me.nombre4 =  response.data.nombre4;
                me.direccion4 =  response.data.direccion4;
                me.zip4 =  response.data.zip4;
                me.ciudad4 =  response.data.ciudad4;
                me.telefono4 =  response.data.telefono4;
                me.nombre5 =  response.data.nombre5;
                me.direccion5 =  response.data.direccion5;
                me.zip5 =  response.data.zip5;
                me.ciudad5 =  response.data.ciudad5;
                me.telefono5 =  response.data.telefono5;
                me.nombre6 =  response.data.nombre6;
                me.direccion6 =  response.data.direccion6;
                me.zip6 =  response.data.zip6;
                me.ciudad6 =  response.data.ciudad6;
                me.telefono6 =  response.data.telefono6;
	            }).catch(function(error) {
	                alert('Ocurrió un error al consultar registros');
	            });
        	},
        	store(){
        		var me = this;
	            axios.post('agregar_contactos/'+ this.parametro + '/'+ this.table, {
                'id1': me.id1,
                'nombre1': me.nombre1,
                'direccion1': me.direccion1,
                'zip1': me.zip1,
                'ciudad1': me.ciudad1,
                'telefono1': me.telefono1,
                'datos1': me.datos1,
                'id2': me.id2,
                'nombre2': me.nombre2,
                'direccion2': me.direccion2,
                'zip2': me.zip2,
                'ciudad2': me.ciudad2,
                'telefono2': me.telefono2,
                'id3': me.id3,
                'nombre3': me.nombre3,
                'direccion3': me.direccion3,
                'zip3': me.zip3,
                'ciudad3': me.ciudad3,
                'telefono3': me.telefono3,
                'id4': me.id4,
                'nombre4': me.nombre4,
                'direccion4': me.direccion4,
                'zip4': me.zip4,
                'ciudad4': me.ciudad4,
                'telefono4': me.telefono4,
                'id5': me.id5,
                'nombre5': me.nombre5,
                'direccion5': me.direccion5,
                'zip5': me.zip5,
                'ciudad5': me.ciudad5,
                'telefono5': me.telefono5,
                'id6': me.id6,
                'nombre6': me.nombre6,
                'direccion6': me.direccion6,
                'zip6': me.zip6,
                'ciudad6': me.ciudad6,
                'telefono6': me.telefono6
	            }).then(function(response) {
	                if (response.data['code'] == 200) {
	                    toastr.success('Registro editado correctamente.');
	                    toastr.options.closeButton = true;
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

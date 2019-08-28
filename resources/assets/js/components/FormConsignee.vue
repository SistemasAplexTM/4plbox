<template lang="html">
  <div class="">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="agencia_id" class="control-label gcore-label-top">Agencia:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="agencia_id" size="medium" filterable placeholder="Select" value-key="id" :class="{ 'error_field': errors_data }">
              <el-option
                v-for="item in branchs"
                :key="item.id"
                :label="item.descripcion"
                :value="item.id">
              </el-option>
            </el-select>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="showId">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="tipo_identificacion_id" class="control-label gcore-label-top">Tipo identificación:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="tipo_identificacion_id" size="medium" filterable placeholder="Select">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group" >
          <div class="col-sm-4">
            <label for="documento" class="control-label gcore-label-top">Nit/Doc:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="N° Nit/Doc" v-model="documento" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="primer_nombre" class="control-label gcore-label-top">Primer Nombre:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Primer Nombre" v-model="primer_nombre" size="medium" clearable></el-input>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="segundo_nombre" class="control-label gcore-label-top">Segundo Nombre:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Segundo Nombre" v-model="segundo_nombre" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="primer_apellido" class="control-label gcore-label-top">Primer Apellido:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Primer Apellido" v-model="primer_apellido" size="medium" clearable></el-input>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="segundo_apellido" class="control-label gcore-label-top">Segundo Apellido:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Segundo Apellido" v-model="segundo_apellido" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="direccion" class="control-label gcore-label-top">Dirección:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Dirección" v-model="direccion" size="medium" clearable></el-input>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="telefono" class="control-label gcore-label-top">Teléfono:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Teléfono" v-model="telefono" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="correo" class="control-label gcore-label-top">Email:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Teléfono" v-model="telefono" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group" >
          <div class="col-sm-4">
            <label for="localizacion_id" class="control-label gcore-label-top">Ciudad:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <city-component @get="setCity($event)" :selected="city_selected_s"></city-component>
            <small class="help-block" v-show="errors_data">Error</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
              <label for="zip" class="control-label gcore-label-top">Zip Code:</label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Zip Code" v-model="zip" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="cliente_id" class="control-label gcore-label-top">Cliente:</label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="cliente_id" size="medium" filterable placeholder="Select" value-key="id">
              <el-option
                v-for="item in clientes"
                :key="item.id"
                :label="item.nombre"
                :value="item.id">
              </el-option>
            </el-select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="emailsend" class="control-label gcore-label-top">Enviar Email:</label>
          </div>
          <div class="col-sm-8">
            <div class="checkbox checkbox-success checkbox-inline">
              <input v-model="emailsend" type="checkbox">
              <label for="emailsend"><i class="fa fa-envelope"></i> Enviar email con datos de su casillero. </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
          </div>
          <div class="col-sm-4">
            <el-input-number size="medium" v-model="tarifa" :min="1" :precision="2" :step="0.1" placeholder="Tarifa 0.00"></el-input-number>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  props:["agency"],
  data() {
    return {
      showId: false,
      errors_data: false,
      agencia_id: null,
      tipo_identificacion_id: null,
      documento: null,
      primer_nombre: null,
      segundo_nombre: null,
      primer_apellido: null,
      segundo_apellido: null,
      direccion: null,
      telefono: null,
      correo: null,
      localizacion_id: null,
      city_selected_s: '',
      branchs: [],
      zip: null,
      cliente_id: null,
      clientes: null,
      emailsend: null,
      tarifa: 0,
    };
  },
  mounted() {
    this.getSelectBranch();
    this.getSelectClient();
  },
  methods: {
    getSelectBranch: function(){
      axios.get('/agencia/getAgencies').then(response => {
        this.branchs = response.data;
        this.agencia_id = this.agency.id
      });
    },
    getSelectClient: function(){
      axios.get('/clientes/all').then(response => {
        this.clientes = response.data.data;
      });
    },
    setCity(data){
      this.localizacion_id = data.id;
    },
  }
}
</script>

<style lang="css">
  [class*=" el-icon-"], [class^=el-icon-]{
    line-height: inherit;
  }
  .error_field > .el-input > input{
    border-color: #f56c6c;
  }
</style>
<style lang="css" scoped>
  .help-block{
    color: #f56c6c;
    font-size: 11;
    position: absolute;
    margin-top: 0px;
    margin-bottom: 0px;
  }
</style>

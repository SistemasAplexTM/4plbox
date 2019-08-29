<template lang="html">
  <div class="">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="agencia_id" class="control-label gcore-label-top">Agencia:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="agencia_id" size="medium" filterable placeholder="Seleccione" value-key="id"
            :class="{ 'error_field': errors_data.agencia_id }"
            @blur="validateFields('agencia_id')">
              <el-option
                v-for="item in branchs"
                :key="item.id"
                :label="item.descripcion"
                :value="item.id">
              </el-option>
            </el-select>
            <small class="help-block" v-show="errors_data.agencia_id">Campo obligatorio</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="" class="control-label gcore-label-top">&nbsp;</label>
          </div>
          <div class="col-sm-8">
            <el-tooltip :content="(company) ? 'Persona' : 'Compañia'" placement="top">
              <el-switch
                v-model="company"
                active-text="Compañia"
                inactive-text="Persona natural"
                active-icon-class="el-icon-office-building"
                inactive-icon-class="el-icon-user">
              </el-switch>
            </el-tooltip>
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
            <small class="help-block" v-show="errors_data">Campo obligatorio</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group" >
          <div class="col-sm-4">
            <label for="documento" class="control-label gcore-label-top" v-if="!company">Documento:</label>
            <label for="documento" class="control-label gcore-label-top" v-if="company">Nit:</label>
          </div>
          <div class="col-sm-8">
            <el-input  :placeholder="(company) ? 'Nit' : 'Documento'" v-model="documento" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="primer_nombre" class="control-label gcore-label-top" v-if="!company">Primer Nombre:<samp id="require">*</samp></label>
            <label for="primer_nombre" class="control-label gcore-label-top" v-if="company">Razon social:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  :placeholder="(company) ? 'Razon social' : 'Primer Nombre'"
            v-model="primer_nombre" size="medium" clearable
            @blur="validateFields('primer_nombre')"
            :class="{ 'error_field': errors_data.primer_nombre }"></el-input>
            <small class="help-block" v-show="errors_data.primer_nombre">Campo obligatorio</small>
          </div>
        </div>
      </div>
    </div>
    <transition name="fade">
      <div class="row" v-show="!company">
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
    </transition>
    <transition name="fade">
      <div class="row" v-show="!company">
        <div class="col-lg-12">
          <div class="form-group">
            <div class="col-sm-4">
              <label for="primer_apellido" class="control-label gcore-label-top">Primer Apellido:<samp id="require">*</samp></label>
            </div>
            <div class="col-sm-8">
              <el-input  placeholder="Primer Apellido" v-model="primer_apellido" size="medium" clearable
              @blur="validateFields('primer_apellido')"
              :class="{ 'error_field': errors_data.primer_apellido }"></el-input>
              <small class="help-block" v-show="errors_data.primer_apellido">Campo obligatorio</small>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="row" v-show="!company">
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
    </transition>

    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="direccion" class="control-label gcore-label-top">Dirección:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  placeholder="Dirección" v-model="direccion" size="medium" clearable
            @blur="validateFields('direccion')"
            :class="{ 'error_field': errors_data.direccion }"></el-input>
            <small class="help-block" v-show="errors_data.direccion">Campo obligatorio</small>
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
            <el-input  placeholder="Email" v-model="email" size="medium" clearable></el-input>
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
            <city-component @get="setCity($event)" :selected="city_selected_s" clearable
            @blur="validateFields('localizacion_id')"
            :class="{ 'error_field': errors_data.localizacion_id }"></city-component>
            <small class="help-block" v-show="errors_data.localizacion_id">Campo obligatorio</small>
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
            <el-select v-model="cliente_id" size="medium" filterable clearable placeholder="Selecione" value-key="id">
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
            <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
          </div>
          <div class="col-sm-4">
            <el-input-number size="medium" v-model="tarifa" :min="0" :precision="2" :step="0.1" placeholder="Tarifa 0.00"></el-input-number>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="emailsend" class="control-label gcore-label-top">&nbsp;</label>
          </div>
          <div class="col-sm-8">
            <el-checkbox v-model="emailsend"><i class="fal fa-envelope"></i> Enviar email con datos de su casillero.</el-checkbox>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-12">
            <el-button type="success" :loading="loading" @click="beforeSend()" v-if="!edit"><i class="fal fa-save"></i> Guardar</el-button>
            <el-button type="primary" :loading="loading" @click="beforeSend(true)" v-if="edit"><i class="fal fa-edit"></i> Actualizar</el-button>
            <el-button @click="resetForm()" v-if="edit"><i class="fal fa-times"></i> Cancelar</el-button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  props:["agency", "consignee_id"],
  data() {
    return {
      loading: false,
      edit: false,
      showId: false,
      errors_data: {
        agencia_id: false,
        primer_nombre: false,
        primer_apellido: false,
        direccion: false,
        localizacion_id: false,
      },
      company: false,
      agencia_id: null,
      tipo_identificacion_id: null,
      documento: null,
      primer_nombre: null,
      segundo_nombre: null,
      primer_apellido: null,
      segundo_apellido: null,
      direccion: null,
      telefono: null,
      email: null,
      localizacion_id: null,
      branchs: [],
      zip: null,
      cliente_id: null,
      clientes: null,
      emailsend: false,
      tarifa: 0,
      city_selected_s: '',
    };
  },
  watch:{
    consignee_id:function(val){
      axios.get('consignee/getDataById/' + val).then(response => {
        console.log(response.data);
        let dat = response.data;
        if (dat != null) {
          this.company = false;
          this.tipo_identificacion_id = dat.tipo_identificacion_id;
          this.documento = dat.documento;
          this.primer_nombre = dat.primer_nombre;
          this.segundo_nombre = dat.segundo_nombre;
          this.primer_apellido = dat.primer_apellido;
          this.segundo_apellido = dat.segundo_apellido;
          this.direccion = dat.direccion;
          this.telefono = dat.telefono;
          this.email = dat.correo;
          this.localizacion_id = dat.localizacion_id;
          this.zip = dat.zip;
          this.cliente_id = dat.cliente_id;
          this.tarifa = dat.tarifa;
          this.city_selected_s = dat.ciudad;
          this.edit=true;
        }
      });
    }
  },
  mounted() {
    this.getSelectBranch();
    this.getSelectClient();
  },
  methods: {
    beforeSend(){
      this.loading = true;
      if (this.validateFields(false)) {
        this.store();
      }else{
        this.loading = false;
      }
    },
    validateFields(field){
      let save = true;
      if(this.agencia_id === null || this.agencia_id === ''){
        if(!field || field === 'agencia_id'){
          this.errors_data.agencia_id = true;
          save = false;
        }
      }else {
        this.errors_data.agencia_id = false;
      }
      if(this.primer_nombre === null || this.primer_nombre === ''){
        if(!field || field === 'primer_nombre'){
          this.errors_data.primer_nombre = true;
          save = false;
        }
      }else{
        this.errors_data.primer_nombre = false;
      }
      if(this.direccion === null || this.direccion === ''){
        if(!field || field === 'direccion'){
          this.errors_data.direccion = true;
          save = false;
        }
      }else{
        this.errors_data.direccion = false;
      }
      if(this.localizacion_id === null || this.localizacion_id === ''){
        if(!field || field === 'localizacion_id'){
          this.errors_data.localizacion_id = true;
          save = false;
        }
      }else{
        this.errors_data.localizacion_id = false;
      }
      if(!this.company){
        if(this.primer_apellido === null || this.primer_apellido === ''){
          if(!field || field === 'primer_apellido'){
            this.errors_data.primer_apellido = true;
            save = false;
          }
        }else{
          this.errors_data.primer_apellido = false;
        }
      }else{
        this.errors_data.primer_apellido = false;
      }
      return save;
    },
    store(){
      let me = this;
      axios.post('consignee', {
          'agencia_id': this.agencia_id,
          'localizacion_id': this.localizacion_id,
          'documento': this.documento,
          'primer_nombre': this.primer_nombre,
          'segundo_nombre': this.segundo_nombre,
          'primer_apellido': this.primer_apellido,
          'segundo_apellido': this.segundo_apellido,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'correo': this.email,
          'zip': this.zip,
          'tarifa': this.tarifa,
          'emailsend': this.emailsend,
          'cliente_id': this.cliente_id,
      }).then(function(response) {
          if (response.data['code'] == 200) {
              toastr.success('Registro creado correctamente.');
              me.loading = false;
              me.resetForm();
              // me.updateTable();
          } else {
            me.loading = false;
              toastr.warning(response.data['error']);
          }
      }).catch(function(error) {
          /*console.log(error);*/
          me.loading = false;
          console.log(error);
          toastr.error("Error." + error, {
              timeOut: 50000
          });
      });
    },
    resetForm(){
      this.errors_data = {
        agencia_id: false,
        primer_nombre: false,
        primer_apellido: false,
        direccion: false,
        localizacion_id: false,
      };
      this.company = false;
      this.tipo_identificacion_id = null;
      this.documento = null;
      this.primer_nombre = null;
      this.segundo_nombre = null;
      this.primer_apellido = null;
      this.segundo_apellido = null;
      this.direccion = null;
      this.telefono = null;
      this.email = null;
      this.localizacion_id = null;
      this.zip = null;
      this.cliente_id = null;
      this.emailsend = false;
      this.tarifa = 0;
      this.city_selected_s = null;
      this.edit=false;
    },
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
  .error_field > .el-input > input, .error_field > .el-input__inner{
    border-color: #f56c6c;
  }
</style>
<style lang="css" scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .help-block{
    color: #f56c6c;
    font-size: 11;
    position: absolute;
    margin-top: 0px;
    margin-bottom: 0px;
  }
</style>

<template lang="html">
  <div class="">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="agencia_id" class="control-label gcore-label-top">Agencia:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="form.agencia_id" size="medium" filterable placeholder="Seleccione" value-key="id"
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
            <el-tooltip :content="(form.corporativo) ? 'Persona' : 'Compañia'" placement="top">
              <el-switch
                v-model="form.corporativo"
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
            <el-select v-model="form.tipo_identificacion_id" size="medium" filterable placeholder="Select">
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
            <label for="documento" class="control-label gcore-label-top" v-if="!form.corporativo">Documento:</label>
            <label for="documento" class="control-label gcore-label-top" v-if="form.corporativo">Nit:</label>
          </div>
          <div class="col-sm-8">
            <el-input  :placeholder="(form.corporativo) ? 'Nit' : 'Documento'" v-model="form.documento" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="primer_nombre" class="control-label gcore-label-top" v-if="!form.corporativo">Primer Nombre:<samp id="require">*</samp></label>
            <label for="primer_nombre" class="control-label gcore-label-top" v-if="form.corporativo">Razon social:<samp id="require">*</samp></label>
          </div>
          <div class="col-sm-8">
            <el-input  :placeholder="(form.corporativo) ? 'Razon social' : 'Primer Nombre'"
            v-model="form.primer_nombre" size="medium" clearable
            @blur="validateFields('primer_nombre')"
            :class="{ 'error_field': errors_data.primer_nombre }"></el-input>
            <small class="help-block" v-show="errors_data.primer_nombre">Campo obligatorio</small>
          </div>
        </div>
      </div>
    </div>
    <transition name="fade">
      <div class="row" v-show="!form.corporativo">
        <div class="col-lg-12">
          <div class="form-group">
            <div class="col-sm-4">
              <label for="segundo_nombre" class="control-label gcore-label-top">Segundo Nombre:</label>
            </div>
            <div class="col-sm-8">
              <el-input  placeholder="Segundo Nombre" v-model="form.segundo_nombre" size="medium" clearable></el-input>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="row" v-show="!form.corporativo">
        <div class="col-lg-12">
          <div class="form-group">
            <div class="col-sm-4">
              <label for="primer_apellido" class="control-label gcore-label-top">Primer Apellido:<samp id="require">*</samp></label>
            </div>
            <div class="col-sm-8">
              <el-input  placeholder="Primer Apellido" v-model="form.primer_apellido" size="medium" clearable
              @blur="validateFields('primer_apellido')"
              :class="{ 'error_field': errors_data.primer_apellido }"></el-input>
              <small class="help-block" v-show="errors_data.primer_apellido">Campo obligatorio</small>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="row" v-show="!form.corporativo">
        <div class="col-lg-12">
          <div class="form-group">
            <div class="col-sm-4">
              <label for="segundo_apellido" class="control-label gcore-label-top">Segundo Apellido:</label>
            </div>
            <div class="col-sm-8">
              <el-input  placeholder="Segundo Apellido" v-model="form.segundo_apellido" size="medium" clearable></el-input>
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
            <el-input  placeholder="Dirección" v-model="form.direccion" size="medium" clearable
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
            <el-input  placeholder="Teléfono" v-model="form.telefono" size="medium" clearable></el-input>
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
            <el-input  placeholder="Email" v-model="form.correo" size="medium" clearable></el-input>
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
            <el-input  placeholder="Zip Code" v-model="form.zip" size="medium" clearable></el-input>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="this.table === 'consignee'">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="cliente_id" class="control-label gcore-label-top">Cliente:</label>
          </div>
          <div class="col-sm-8">
            <el-select v-model="form.cliente_id" size="medium" filterable clearable placeholder="Selecione" value-key="id">
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
    <div class="row" v-if="this.table === 'consignee'">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
          </div>
          <div class="col-sm-4">
            <el-input-number size="medium" v-model="form.tarifa" :min="0" :precision="2" :step="0.1" placeholder="Tarifa 0.00"></el-input-number>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="this.table === 'consignee'">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-sm-4">
            <label for="emailsend" class="control-label gcore-label-top">&nbsp;</label>
          </div>
          <div class="col-sm-8">
            <el-checkbox v-model="form.emailsend"><i class="fal fa-envelope"></i> Enviar email con datos de su casillero.</el-checkbox>
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
  props:["table", "agency", "field_id"],
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
      form: {
        corporativo: false,
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
        zip: null,
        cliente_id: null,
        tarifa: 0,
        emailsend: false,
      },
      branchs: [],
      clientes: null,
      city_selected_s: '',
    };
  },
  watch:{
    field_id:function(val){
      let me = this;
      if (val != null) {
        setTimeout(function () {
          me.getConsigneeById(val);
        }, 1000);
      }
    }
  },
  mounted() {
    this.getSelectBranch();
    this.getSelectClient();
  },
  methods: {
    beforeSend(edit){
      this.loading = true;
      if (this.validateFields(false)) {
        if (edit) {
          this.update();
        }else{
          this.store();
        }
      }else{
        this.loading = false;
      }
    },
    store(){
      let me = this;
      axios.post(this.table, this.form).then(function(response) {
        if (response.data['code'] == 200) {
          toastr.success('Registro creado correctamente.');
          me.loading = false;
          me.resetForm();
          me.$emit('updatetable');
        } else {
          me.loading = false;
          toastr.warning(response.data['error']);
        }
      }).catch(function(error) {
        console.log(error);
        me.loading = false;
        toastr.error("Error." + error, {
            timeOut: 50000
        });
      });
    },
    update(){
      let me = this;
      axios.put(this.table + '/' + this.field_id, this.form).then(function(response) {
        if (response.data['code'] == 200) {
          toastr.success('Registro Actualizado correctamente.');
          me.loading = false;
          me.resetForm();
          me.$emit('updatetable');
        } else {
          me.loading = false;
          toastr.warning(response.data['error']);
        }
      }).catch(function(error) {
        console.log(error);
        me.loading = false;
        toastr.error("Error." + error, {
            timeOut: 50000
        });
      });
    },
    validateFields(field){
      let save = true;
      if(this.form.agencia_id === null || this.form.agencia_id === ''){
        if(!field || field === 'agencia_id'){
          this.errors_data.agencia_id = true;
          save = false;
        }
      }else {
        this.errors_data.agencia_id = false;
      }
      if(this.form.primer_nombre === null || this.form.primer_nombre === ''){
        if(!field || field === 'primer_nombre'){
          this.errors_data.primer_nombre = true;
          save = false;
        }
      }else{
        this.errors_data.primer_nombre = false;
      }
      if(this.form.direccion === null || this.form.direccion === ''){
        if(!field || field === 'direccion'){
          this.errors_data.direccion = true;
          save = false;
        }
      }else{
        this.errors_data.direccion = false;
      }
      if(this.form.localizacion_id === null || this.form.localizacion_id === ''){
        if(!field || field === 'localizacion_id'){
          this.errors_data.localizacion_id = true;
          save = false;
        }
      }else{
        this.errors_data.localizacion_id = false;
      }
      if(!this.form.corporativo){
        if(this.form.primer_apellido === null || this.form.primer_apellido === ''){
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
    resetForm(){
      this.errors_data = {
        agencia_id: false,
        primer_nombre: false,
        primer_apellido: false,
        direccion: false,
        localizacion_id: false,
      };
      this.form.corporativo = false;
      this.form.tipo_identificacion_id = null;
      this.form.documento = null;
      this.form.primer_nombre = null;
      this.form.segundo_nombre = null;
      this.form.primer_apellido = null;
      this.form.segundo_apellido = null;
      this.form.direccion = null;
      this.form.telefono = null;
      this.form.correo = null;
      this.form.localizacion_id = null;
      this.form.zip = null;
      this.form.cliente_id = null;
      this.form.tarifa = 0;
      this.form.emailsend = false;
      this.city_selected_s = null;
      this.edit=false;
    },
    getSelectBranch: function(){
      axios.get('/agencia/getAgencies').then(response => {
        this.branchs = response.data;
        this.form.agencia_id = this.agency.id
      });
    },
    getSelectClient: function(){
      axios.get('/clientes/all').then(response => {
        this.clientes = response.data.data;
      });
    },
    setCity(data){
      this.form.localizacion_id = data.id;
    },
    getConsigneeById(id){
      let me = this;
      axios.get(this.table + '/getDataById/' + id).then(response => {
        me.form = response.data;
        me.form.cliente_id = me.form.cliente_id + '';
        if (me.form.corporativo == 1) {
          me.form.corporativo = true;
        }else{
          me.form.corporativo = false;
        }
        me.city_selected_s = response.data.ciudad;
        me.edit=true;
      });
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

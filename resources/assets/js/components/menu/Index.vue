<template lang="html">
  <div class="row">
    <div class="col-lg-3 col-lg-offset-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Registrar menú</h5>
          <div class="ibox-tools">

          </div>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <!-- <div class="col-sm-4">
                  <label for="" class="control-label gcore-label-top">&nbsp;</label>
                </div> -->
                <div class="col-sm-12">
                  <el-input placeholder="Nombre" v-model="form.name" size="medium" clearable></el-input>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <!-- <div class="col-sm-4">
                  <label for="" class="control-label gcore-label-top">&nbsp;</label>
                </div> -->
                <div class="col-sm-12">
                  <el-input placeholder="Ruta" v-model="form.route" size="medium" clearable></el-input>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <!-- <div class="col-sm-4">
                  <label for="" class="control-label gcore-label-top">&nbsp;</label>
                </div> -->
                <div class="col-sm-12">
                  <el-input placeholder="Icono" v-model="form.icon" size="medium" clearable></el-input>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group" :class="{ 'has-error': errors.has('rol_id') }">
                <div class="col-sm-12">
                  <v-select name="rol_id" v-model="form.rol_id" label="name" :options="form.roles" v-validate.disable="'required'" placeholder="Roles" style="width: 100%"></v-select>
                  <!-- <small class="help-block">@{{ errors.first('rol_id') }}</small> -->
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <div class="col-sm-12">
                  <el-button type="success" :loading="loading" @click="submit()" v-if="!edit"><i class="fal fa-save"></i> Guardar</el-button>
                  <el-button type="primary" :loading="loading" @click="beforeSend(true)" v-if="edit"><i class="fal fa-edit"></i> Actualizar</el-button>
                  <el-button @click="resetForm()" v-if="edit"><i class="fal fa-times"></i> Cancelar</el-button>
                </div>
              </div>
            </div>
          </div>
          <br>
          <el-alert
           v-if="error"
           title="Error"
           type="error"
           description=""
           @close="error = false"
           show-icon>
           <ul>
             <li v-for="error in listErrors">{{ error }}</li>
           </ul>
         </el-alert>
        </div>
      </div>
    </div>

    <div class="col-lg-6">

      <order-list ref="list"/>

    </div>
  </div>
</template>

<script>
import OrderList from './OrderList'
export default {
  components: { OrderList },
  data() {
    return {
      form: {
        name: null,
        route: null,
        icon: null,
        roles: [],
        rol_id: null
      },
      edit: false,
      error: false,
      loading: false,
      listErrors: [],
    }
  },
  created(){
    this.getRoles()
  },
  methods: {
    submit() {
      this.loading = true
      axios.post('menu', this.form).then(response => {
        if (response.data.code == 200) {
          this.$refs.list.getMenu()
          this.$message({type: 'success', message: 'Registrado con éxito.'})
          this.resetForm()
        }
        if (response.data.error) {
          this.error = true
          this.listErrors = response.data.error.validator.customMessages
        }else{
            this.error = false
        }
        this.loading = false
      }).catch(error => {
        this.listErrors = error
        this.loading = false
      });
    },
    resetForm(){
      this.form = {
        name: null,
        route: null,
        icon: null,
        rol_id: null
      }
      this.error = false
      this.listErrors = []
    },
    getRoles(){
      axios.get('user/getDataSelect/roles').then(response => {
        this.form.roles = response.data.data;
      });
    }
  }
}
</script>

<style lang="css" >

</style>

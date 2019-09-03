<template lang="html">
  <el-row :gutter="24">
      <el-col :span="24">
        <el-input placeholder="Nombre" v-model="form.name" size="medium" clearable></el-input>
      </el-col>
      <el-col :span="24">
        <el-input placeholder="Ruta" v-model="form.route" size="medium" clearable></el-input>
      </el-col>
      <el-col :span="24">
        <el-input placeholder="Icono" v-model="form.icon" size="medium" clearable></el-input>
      </el-col>
      <el-col :span="24">
        <el-select v-model="form.rol_id" clearable multiple collapse-tags placeholder="Selecione roles">
          <el-option
            v-for="item in roles"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-col>
      <el-col :span="24">
        <el-button type="success" @click="open()">Abrir</el-button>
        <el-button type="success" :loading="loading" @click="submit()" v-if="!edit"><i class="fal fa-save"></i> Guardar</el-button>
        <el-button type="primary" :loading="loading" @click="beforeSend(true)" v-if="edit"><i class="fal fa-edit"></i> Actualizar</el-button>
        <el-button @click="resetForm()" v-if="edit"><i class="fal fa-times"></i> Cancelar</el-button>
      </el-col>
      <el-col :span="24">
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
      </el-col>
  </el-row>

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
        rol_id: null
      },
      roles: [],
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
        this.roles = response.data.data;
      });
    },
    open(){
      var data = {component: 'menu-component', title: 'Menú', icon: 'fal fa-list'}
      bus.$emit('open', data)
    }
  }
}
</script>

<style lang="css">
.el-col {
  margin-bottom: 10px;
}
</style>

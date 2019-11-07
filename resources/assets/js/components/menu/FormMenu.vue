<template lang="html">
  <!-- <el-card>
    <div slot="header" class="clearfix">
      <span>Formulario de {{ (edit) ? 'edición' : 'registro'}}</span>
    </div> -->
    <el-row :gutter="24">
      <el-col :span="24">
        <el-input placeholder="Nombre" v-model="form.name" size="medium" clearable></el-input>
      </el-col>
      <el-col :span="20">
        <el-input placeholder="Icono" v-model="icon" size="medium" clearable></el-input>
      </el-col>
      <el-col :span="4">
        <el-color-picker v-model="form.color" size="small"></el-color-picker>
      </el-col>
      <el-col :span="24">
        <el-select v-model="module_selected" value-key="id" clearable filterable placeholder="Selecione módulo">
          <el-option
            v-for="item in modules"
            :key="item.id"
            :label="item.nombre"
            :value="item">
          </el-option>
        </el-select>
      </el-col>
      <el-col :span="24">
        <el-select v-model="roles_selected" clearable collapse-tags multiple placeholder="Selecione roles">
          <el-option
            v-for="item in roles"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
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
      <el-col :span="24">
        <el-button type="success" size="small" :loading="loading" @click="submit()" v-if="!edit"><i class="fal fa-save"> </i>  Guardar</el-button>
        <el-button type="primary" size="small" :loading="loading" @click="update(true)" v-if="edit"><i class="fal fa-edit"> </i>  Actualizar</el-button>
        <el-button @click="resetForm()" size="small" v-if="edit"><i class="fal fa-times"></i> Cancelar</el-button>
      </el-col>
    </el-row>
  <!-- </el-card> -->

</template>

<script>
import OrderList from './OrderList'
export default {
  components: { OrderList },
  props:['menu'],
  data() {
    return {
      form: {
        name: null,
        color: null,
        route: null,
        roles_selected: null,
        module_id: null
      },
      icon: null,
      roles_selected: null,
      module_selected: null,
      roles: [],
      modules: [],
      edit: false,
      error: false,
      loading: false,
      listErrors: [],
    }
  },
  created(){
    let me = this
    me.getRoles()
    me.getModules()
    bus.$on('edit_menu', function (payload) {
      me.edit= true
      me.getById(payload)
    })
  },
  methods: {
    getById(id){
      let me = this
      axios.get('menu/id/' + id).then(({data}) => {
        me.form = data;
        me.roles_selected = []
        for (var i = 0; i < data.roles.length; i++) {
          me.roles_selected.push(data.roles[i].id)
        }
        var meta = JSON.parse(data.meta)
        me.icon = meta.icon
        me.form.color = meta.color
        var module = me.modules.filter(result => result.route == data.route)
        me.module_selected = module[0]
      });
    },
    update() {
      this.loading = true
      this.form.roles_selected = this.roles_selected
      this.form.icon = this.icon
      this.form.route = this.module_selected.route
      this.form.module_id = this.module_selected.id
      axios.put('menu/' + this.form.id, this.form).then(response => {
        if (response.data.code == 200) {
          this.$message({type: 'success', message: 'Actualizado con éxito.'})
          bus.$emit('refreshList', true)
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
    submit() {
      this.loading = true
      this.form.roles_selected = this.roles_selected
      this.form.icon = this.icon
      this.form.route = this.module_selected.route
      this.form.module_id = this.module_selected.id
      this.form.menu = this.menu
      axios.post('menu', this.form).then(response => {
        if (response.data.code == 200) {
          this.$message({type: 'success', message: 'Registrado con éxito.'})
          bus.$emit('refreshList', true)
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
        roles_selected: null
      }
      this.roles_selected = null
      this.module_selected = null
      this.icon = null
      this.edit = false
      this.error = false
      this.listErrors = []
    },
    getRoles(){
      axios.get('user/getDataSelect/roles').then(response => {
        this.roles = response.data.data;
      });
    },
    getModules(){
      axios.get('getForSelect/modulo/menu').then(({data}) => {
        this.modules = data
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

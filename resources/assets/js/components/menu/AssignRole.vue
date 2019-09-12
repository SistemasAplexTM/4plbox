<template lang="html">
  <el-card class="">
    <div slot="header" class="clearfix">
      <el-button @click="savePermissions" class="fr" size="small" type="primary">
         <i class="fal fa-save"></i>
          Guardar
      </el-button>
    </div>
    <el-row>
      <el-col :span="24">
        <el-divider>
          <h3 class="text-center">
            <i :class="meta.icon"> </i>
            {{ (menu.name) ? menu.name : 'Menú' }}
          </h3>
        </el-divider>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-select v-model="rol_selected" @change="changeRol" clearable placeholder="Selecione rol">
          <el-option
            v-for="item in roles"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-divider>
          <h3 class="text-center">
            {{ (menu.modules) ? menu.modules.nombre : 'Módulo' }}
          </h3>
        </el-divider>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <table class="el-table el-table--fit el-table--striped el-table--border el-table--fluid-height el-table--enable-row-hover el-table--enable-row-transition">
          <thead>
            <th>C</th>
            <th>R</th>
            <th>U</th>
            <th>D</th>
          </thead>
          <tbody>
            <td><el-checkbox v-model="permissions_selected.c"></el-checkbox></td>
            <td><el-checkbox v-model="permissions_selected.r"></el-checkbox></td>
            <td><el-checkbox v-model="permissions_selected.u"></el-checkbox></td>
            <td><el-checkbox v-model="permissions_selected.d"></el-checkbox></td>
          </tbody>
        </table>
        <!-- <el-table
          :data="permissions"
          max-height="110"
          empty-text="No hay datos"
          stripe
          border
          style="width: 100%">
          <el-table-column
            prop="id"
            align="center"
            label="C">
            <template slot-scope="scope">
              <el-checkbox :value="permissions_selected['id_'+scope.row.c].value"></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column
            prop="name"
            align="center"
            label="R">
            <template slot-scope="scope">
              <el-checkbox v-model="permissions_selected['id_'+scope.row.r].value"></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column
            prop="slug"
            align="center"
            label="U">
            <template slot-scope="scope">
              <el-checkbox v-model="permissions_selected['id_'+scope.row.u].value"></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column
            prop="crud"
            align="center"
            label="D">
            <template slot-scope="scope">
              <el-checkbox v-model="permissions_selected['id_'+scope.row.d].value"></el-checkbox>
            </template>
          </el-table-column>
        </el-table> -->
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-divider>
          <h3 class="text-center">
            Permisos especiales
          </h3>
        </el-divider>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-table
          :data="special_permissions"
          :max-height="(special_permissions.length > 0) ? 250 : 110"
          stripe
          border
          style="width: 100%">
          <el-table-column
            prop="name"
            label="Descripción">
          </el-table-column>
          <el-table-column
            prop="name"
            align="center"
            label=""
            width="70">
            <template slot-scope="scope">
              <el-checkbox v-model="permissions_selected[scope.row.d]"></el-checkbox>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </el-card>
</template>

<script>
export default {
  data() {
    return {
      menu: {},
      meta: {},
      roles: [],
      permissions: [],
      special_permissions: [],
      permissions_selected: {
        c: true,
        r: true,
        u: true,
        d: true
      },
      rol_selected: null,
      loading: false
    }
  },
  created(){
    let me = this
    bus.$on('assing_role', function (payload) {
      me.loading = true
      me.getById(payload)
      me.getRoles()
    })
  },
  methods: {
    savePermissions() {
      return false
      if (!this.rol_selected) {
      }
      let me = this;
      axios.post('accessControl',{
        'role_id': this.rol_selected,
        'special': null,
        'datos': this.permissions_selected
      }).then(function (response) {
        if(response.data.code == 200){
          toastr.success('Registro exitoso.');
          this.rol_selected = null;
        }else{
          var error = response.data.error;
          if(Array.isArray(error)){
              error = response.data.error['errorInfo'];
          }
          toastr.warning(error);
        }
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error. ' + error);
      });
    },
    getById(id){
      let me = this
      axios.get('menu/id/' + id).then(({data}) => {
        me.menu = data
        me.meta = JSON.parse(data.meta)
      });
    },
    getRoles(){
      axios.get('user/getDataSelect/roles').then(({data}) => {
        this.roles = data.data
        this.loading = false
      })
    },
    changeRol(){
      this.permissions_selected = []
      this.permissions = []
      this.special_permissions = []
      if (this.rol_selected) {
        this.getPermisionsRole()
      }
    },
    getPermisionsRole(){
      var module_id = null
      if (this.menu.module_id) {
          module_id = this.menu.module_id
      }
      axios.get('accessControl/getPermisionsRole/' + this.rol_selected + '/' + module_id).then(({data}) => {
        this.permissions = data.data
        this.special_permissions = data.special_permissions.data
        var id = ''
        for (var i = 0; i < data.permissions.length; i++) {
          if (data.permissions[i].id != null) {
            id = data.permissions[i].id
            this.permissions_selected[id] = true
            id = ''
          }
        }
        // for (var i = 0; i < data.special_permissions.data.length; i++) {
        //   this.permissions_selected[data.special_permissions.data[i].id] = true
        // }
      })
    }
  }
}
</script>

<style lang="css">

</style>

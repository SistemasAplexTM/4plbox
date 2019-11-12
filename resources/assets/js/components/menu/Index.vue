<template lang="html">
  <div class="">
    <el-row :gutter="24">
      <el-col :span="14" :offset="5">
        <el-card class="box-card" >
          <div slot="header" class="clearfix">
            <el-col :span="8">
              <!-- <p>Selecione menú</p>
              <el-select v-model="menu" clearable @change="changeMenu" placeholder="Seleccione menú" value-key="id">
                <el-option
                  v-for="item in menus"
                  :key="item.id"
                  :label="item.nombre"
                  :value="item">
                </el-option>
              </el-select> -->
            </el-col>
            <el-col :span="16">
                <h2 class="fr">{{ menu.nombre }}</h2>
            </el-col>
          </div>
          <el-col :span="10">
            <form-menu :menu="menu.id"/>
          </el-col>
          <el-col :span="14">
            <order-list :menu="menu.id"/>
          </el-col>
          <!-- <el-col :span="8">
            <assign-role/>
          </el-col> -->
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import OrderList from './OrderList'
import FormMenu from './FormMenu'
import AssignRole from './AssignRole'
export default {
  components: { OrderList, FormMenu, AssignRole },
  data() {
    return {
      menu: {id: 17, nombre: 'Menú principal'},
      menus: []
    }
  },
  created(){
    this.getMenu()
  },
  methods: {
    changeMenu(){
      let me = this
      bus.$emit('changeMenu', me.menu.id)
    },
    getMenu(){
      axios.get('administracion/menu/getSelect').then(({data}) => {
        this.menus = data
      }).catch(error => error)
    }
  }
}
</script>

<style lang="css" scope>
.box-card{
  padding-bottom: 30px;
}
</style>

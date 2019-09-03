<template lang="html">
  <div class="">
    <i class="fal fa-list fa-3x fr"></i>
    <p> <strong>Gestione</strong> el menú de su aplicación para que se adecúe a sus necesidades</p>
    <el-divider/>
    <el-tabs @tab-click="handleClick">
      <el-tab-pane label="Registrar">
        <form-menu/>
      </el-tab-pane>
      <el-tab-pane label="Ordenar">
        <order-list ref="list"/>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import OrderList from './OrderList'
import FormMenu from './FormMenu'
export default {
  components: { OrderList, FormMenu },
  data() {
    return {
      loading: false,
      activeName: 'first'
    }
  },
  created(){
  },
  methods: {
    open(){
      bus.$emit('open', true)
    },
    handleClick(tab, event) {
   }
  }
}
</script>

<style lang="css" >

</style>

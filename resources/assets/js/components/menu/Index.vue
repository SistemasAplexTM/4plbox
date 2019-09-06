<template lang="html">
  <div class="">
    <el-row>
        <el-col :span="14" :offset="5">
          <p>
            <!-- <i class="fal fa-list fa-3x fr"></i> -->
            <strong>Gestione</strong> el menú de su aplicación para que se adecúe a sus necesidades</p>
          <el-divider/>
          <el-row :gutter="24">
            <el-col :span="10">
              <form-menu/>
            </el-col>
            <el-col :span="14">
              <order-list ref="list"/>
            </el-col>
          </el-row>
        </el-col>
    </el-row>
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

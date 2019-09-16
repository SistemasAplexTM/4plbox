<template>
  <el-drawer
    :visible.sync="drawer"
    :direction="direction"
    size="25%"
    :show-close="false"
    :destroy-on-close="true">
    <span slot="title" class="mb-5">
      <i class="fr fa-4x o-010" :class="data.icon"></i>
      <el-page-header @back="drawer=false" title="" style="color: white !important;margin-top: 12px;">
      <span slot="content" class="w-100">
      <h3><i :class="data.icon"></i> {{ data.title }}</h3>
      </span>
      </el-page-header>
    </span>
    <transition name="fade">
      <component :is="component_active" :payload="data"></component>
    </transition>
  </el-drawer>
</template>
<script>
import { mapGetters } from 'vuex'
  export default {
    // props:["table", "agency_data", "field_id"],
    data() {
      return {
        component_active: 'menu-component',
        direction: 'rtl',
        drawer: false,
        data: {
          icon: '',
          title: ''
        }
      };
    },
    created(){
      let me = this
      bus.$on('open', function (payload) {
        me.data = payload
        me.component_active = payload.component
        me.drawer = true
      })
    },
    methods: {
      handleClose(done) {
        this.$confirm('Are you sure you want to close this?')
          .then(_ => {
            done();
          })
          .catch(_ => {});
      }
    }
  };
</script>
<style lang="css">
  .el-drawer__body{
    padding: 20px;
  }
  .el-drawer {
    overflow: auto !important;
  }
  .el-drawer__header {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
    background-color: #5f8fdf !important;
    color: white;
    margin-bottom: 0px;
  }
  .el-page-header__content{
    color: white !important;
  }
</style>

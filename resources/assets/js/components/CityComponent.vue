<template>
  <div class="">
    <el-select
      size="medium"
      clearable
      v-model="city_id"
      filterable
      remote
      reserve-keyword
      placeholder="Buscar Ciudad"
      :remote-method="remoteMethod"
      :loading="loading"
      :disabled="disabled"
      loading-text="Cargando..."
      no-data-text="No hay datos"
      @change="handleSelect"
      value-key="id">
      <el-option
        v-for="item in options"
        :key="item.id"
        :label="item.name"
        :value="item">
        <span style="float: left">{{ item.name }}</span>
        <span style="float: right; color: #8492a6; font-size: 13px"><small>{{ item.deptos }} / {{ item.prefijo_pais }}</small></span>
      </el-option>
    </el-select>
  </div>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
  props:["data", "disabled", "selected"],
  data(){
    return {
      options: [],
      city_id: [],
      list: [],
      loading: false,
    }
  },
  watch:{
    selected:function(value) {
      this.city_id = value
    },
    data:function(value) {
      this.list = value;
    }
  },
  mounted(){
    //
  },
  updated: debounce(function () {
      let me = this;
      this.$nextTick(() => {
        setTimeout(() => {
          if(me.list.length === 0){
              me.getData();
          }
        }, 2000);
      })
  }, 1000), // increase to ur needs
  methods:{
    getData(){
      var me = this;
      axios.get('/ciudad/getSelectCity').then(function(response) {
          me.list = response.data.data;
      }).catch(function(error) {
          console.log(error);
          toastr.warning('Error: -' + error);
      });
    },
    remoteMethod(query) {
      if (query !== '') {
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
          this.options = this.list.filter(item => {
            return item.name.toLowerCase()
              .indexOf(query.toLowerCase()) > -1;
          });
        }, 200);
      } else {
        this.options = [];
      }
    },
    handleSelect(item) {
      this.$emit('get', item);
    }
  }
}
</script>

<style lang="css" scoped>
  .el-select{
    width: 100%;
  }
</style>

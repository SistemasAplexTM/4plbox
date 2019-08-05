<template lang="html">
  <el-autocomplete
    clearable
    class="inline-input"
    v-model="value_id.nombre_full"
    :fetch-suggestions="querySearch"
    :trigger-on-focus="false"
    placeholder="Seleccione"
    @select="handleSelect"
    size="medium"
  >
    <template slot-scope="{ item }">
      <div>
        <label class="value_item"><i class="fal fa-user"></i> {{ item.nombre_full }}</label>
        <!-- <div>
        <small>
          {{ item.ciudad }}
        </small>
         </div> -->
      </div>
    </template>
  </el-autocomplete>
</template>

<script>
export default {
  props: ['shipper_id', 'consignee_id', 'option'],
  data() {
    return {
      options: [],
      value: [],
      list: [],
      loading: false,
      value_id: {}
    }
  },
  watch:{
   shipper_id(newVal, oldVal){
      setTimeout(() => {
        this.setDefault(newVal)
      }, 100);
   },
   consignee_id(newVal, oldVal){
      setTimeout(() => {
       this.setDefault(newVal)
     }, 100);
   }
  },
  methods: {
    setDefault(value){
       var me = this;
       var datos = null;
       if(me.option == 'shipper'){
         axios.get('/documento/getDataShipperConsigneeById/shipper/'+value).then(function(response) {
           datos = response.data.data;
           me.value_id = datos;
           me.$emit('get', datos);
         }).catch(function(error) {
           console.log(error);
           toastr.warning('Error: -' + error);
         });
       }else{
         axios.get('/documento/getDataShipperConsigneeById/consignee/'+value).then(function(response) {
           datos = response.data.data;
           me.value_id = datos;
           me.$emit('get', datos);
         }).catch(function(error) {
           console.log(error);
           toastr.warning('Error: -' + error);
         });
       }

    },
    querySearch(queryString, cb) {
      var me = this;
      if(me.option == 'shipper'){
        axios.get('/documento/getDataShipperConsignee/shipper/'+queryString).then(function(response) {
          cb(response.data.data);
        }).catch(function(error) {
          console.log(error);
          toastr.warning('Error: -' + error);
        });
      }else{
        axios.get('/documento/getDataShipperConsignee/consignee/'+queryString).then(function(response) {
          cb(response.data.data);
        }).catch(function(error) {
          console.log(error);
          toastr.warning('Error: -' + error);
        });
      }
    },
    handleSelect(item) {
      this.value_id = item;
      this.$emit('get', item);
    }
  }
}
</script>

<style lang="css" scoped>
  .el-autocomplete{
    width: 100%!important;
  }
</style>

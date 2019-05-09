<template lang="html">
  <el-select v-model="value_id"
    placeholder="Seleccione"
    filterable
    clearable
    remote
    reserve-keyword
    value-key="id"
    size="medium"
    :remote-method="remoteMethod"
    :loading="loading"
    @change="handleSelect">
    <el-option
      v-for="item in options"
      :key="item.id"
      :label="item.nombre_full"
      :value="item">
      <span style="float: left">{{ item.nombre_full }}</span>
      <span style="float: right; color: #8492a6; font-size: 13px">{{ item.ciudad }}</span>
    </el-option>
  </el-select>
</template>

<script>
export default {
  props: ['data', 'shipper_id', 'consignee_id', 'option'],
  data() {
    return {
      options: [],
      value: [],
      list: [],
      loading: false,
      value_id: null
    }
  },
  watch:{
   shipper_id(newVal, oldVal){
      setTimeout(() => {
        this.setDefault(newVal)
      }, 1000);
   },
   consignee_id(newVal, oldVal){
      setTimeout(() => {
       this.setDefault(newVal)
      }, 1000);
   },
   data(newVal, oldVal){
     this.list = newVal;
   }
  },
  methods: {
    setDefault(value){
       var result = this.list.filter(({id}) => id == value );
       this.value_id = result[0].nombre_full;
       this.value = result[0]
       this.$emit('get', result[0]);
    },
    remoteMethod(query) {
      if (query !== '') {
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
          this.options = this.list.filter(item => {
            if(item.nombre_full !== null){
              return item.nombre_full.toLowerCase()
              .indexOf(query.toLowerCase()) > -1;
            }
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
</style>

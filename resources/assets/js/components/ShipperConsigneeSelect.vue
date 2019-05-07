<template lang="html">
  <el-select v-model="value"
    placeholder="Seleccione"
    filterable
    remote
    reserve-keyword
    value-key="id"
    size="medium"
    :remote-method="remoteMethod"
    :loading="loading">
    <el-option
      v-for="item in options"
      :key="item.id"
      :label="item.nombre_full"
      :value="item.id">
      <span style="float: left">{{ item.nombre_full }}</span>
      <span style="float: right; color: #8492a6; font-size: 13px">{{ item.ciudad }}</span>
    </el-option>
  </el-select>
</template>

<script>
export default {
  props: ['data', 'default', 'option'],
  data() {
    return {
      options: [],
      value: [],
      list: [],
      loading: false,
      value: null
    }
  },
  watch:{
   default(newVal, oldVal){
    this.setDefault(newVal)
   },
   data(newVal, oldVal){
     this.list = newVal;
   }
  },
  methods: {
    setDefault(value){
       var result = this.list.filter(({id}) => id == value );
       this.value = result[0]
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
    }
  }
}
</script>

<style lang="css" scoped>
</style>

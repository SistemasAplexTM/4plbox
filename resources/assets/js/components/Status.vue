<template lang="html">
 <el-select v-model="selected" placeholder="Status" value-key="id" @change="handleSelect">
   <el-option
     v-for="item in status"
     :key="item.id"
     :label="item.descripcion"
     :value="item">
   </el-option>
 </el-select>
</template>

<script>
export default {
 data(){
  return {
   selected: null,
   status: []
  }
 },
 props: ['default'],
 mounted(){
  this.getData()
 },
 methods: {
  getData(){
   let me = this
   axios.get('status/all').then(({data}) => {
    me.status = data.data
    if (me.default) {
     var result = me.status.filter(({id}) => id == me.default );
     me.selected = result[0]
    }
   }).catch((error) => {
      console.log(error);
   });
  },
  handleSelect(item) {
    this.$emit('get', item);
  }
 }
}
</script>

<style lang="css" scoped>
</style>

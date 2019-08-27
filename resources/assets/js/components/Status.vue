<template lang="html">
 <el-select v-model="selected" placeholder="Status" value-key="id" @change="handleSelect" filterable size="medium">
   <el-option
     v-for="item in status"
     :key="item.id"
     :label="item.descripcion"
     :value="item">
     <div>
       <i :class="item.icon" style="text-align: center;width: 25px;" :style="'color:'+ item.color"></i> {{ item.descripcion }}
       <i v-if="item.view_client === '1'" class="fal fa-bell" style="float: right;margin-top: 10px; color: #dbdee0;"></i>
     </div>
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
 props: ['default', 'data'],
 watch:{
  default(newVal, oldVal){
   this.setDefault(newVal)
  }
 },
 mounted(){
    if(typeof this.data !== "undefined"){
      this.status = this.data;
      this.setDefault(this.default);
    }else{
      this.getData()
    }
 },
 methods: {
  getData(){
   let me = this
   axios.get('status/all').then(({data}) => {
    me.status = data.data
    me.setDefault(me.default);
   }).catch((error) => {
      console.log(error);
   });
  },
  handleSelect(item) {
    this.$emit('get', item);
  },
  setDefault(value){
   var result = this.status.filter(({id}) => id == value );
   this.selected = result[0]
  }
 }
}
</script>

<style lang="css" scoped>
</style>

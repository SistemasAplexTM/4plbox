<template lang="html">
  <div>
    <el-card class="box-card">
      <h3><i class="fal fa-user icon"></i> {{ item.consignee }}</h3>

      <div class="content-text-search">
        <i class="fal fa-user-tag icon"></i> {{ item.procedencia }}
      </div>
      <div class="content-text-search">
        <i class="fal fa-box-open icon"></i> {{ item.name }}
      </div>
      <div class="content-text-search">
        <i class="fal fa-balance-scale icon"></i> {{ item.peso }} Lb
      </div>
      <div class="content-text-search" v-for="i in trackings">
        <i class="fal fa-truck icon"></i> {{ i.tracking }} <i class="fal fa-calendar-alt icon"></i> {{ i.create }} 
      </div>
      <div class="content-text-search">
        <i class="fal fa-comment-edit icon"></i> {{ item.contenido }}
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  props:["payload"],
  data() {
    return {
      item: null,
      tracking: [],
      tracking_create: [],
      tracking_content: [],
      trackings: [],
    }
  },
  created() {
    this.item = this.payload.datos

    var trac = this.item.tracking
    if(trac != ''){
      this.tracking = trac.split(',')
    }
    var trac_create = this.item.tracking_create
    if(trac_create != ''){
      this.tracking_create = trac_create.split(',')
    }

    var trac_content = this.item.contenido
    if(trac_content != ''){
      this.tracking_content = trac_content.split(',')
    }

    for (let i = 0; i < this.tracking.length; i++) {
      this.trackings.push({tracking : this.tracking[i], content : this.tracking_content[i], create: this.tracking_create[i]});
    }
    console.log(this.trackings);
    
    
  },
}
</script>

<style lang="css" scoped>
  .content-text-search{
    margin: 5px;
  }
</style>

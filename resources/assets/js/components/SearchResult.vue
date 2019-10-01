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
      <div class="content-text-search">
        <i class="fal fa-truck icon"></i> {{ item.tracking }}
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
      item: null
    }
  },
  created() {
    this.item = this.payload.datos
  },
}
</script>

<style lang="css" scoped>
  .content-text-search{
    margin: 5px;
  }
</style>

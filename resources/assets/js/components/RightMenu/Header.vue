<template lang="html">
  <span class="mb-5">
    <i class="fr fa-4x o-010" :class="data.icon"></i>
    <el-page-header @back="$emit('back')" title="" style="color: white !important;margin-top: 12px;">
      <span slot="content" class="w-100">
        <h3><i :class="data.icon"></i> {{ data.title }}</h3>
      </span>
    </el-page-header>
  </span>
</template>

<script>
export default {
  name: 'HeaderComponent',
  props: ['data']
}
</script>

<style lang="css" scoped>
.header {

}
</style>

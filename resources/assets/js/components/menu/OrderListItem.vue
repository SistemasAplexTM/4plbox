<template lang="html">
  <li class="dd-item dd3-item" :data-id="item.id">
    <div class="dd-handle dd3-handle" :style="'background:' + formatMeta(item.meta, 'color')"> </div>
    <div class="dd3-content">
      <a @click="destroy" class="eliminar-menu tooltipsC"><i class="fal fa-trash fr text-danger actions-menu-item"> </i></a>
      <i @click="edit(item.id)" class="fal fa-edit fr text-success actions-menu-item"> </i>
      <!-- <i @click="assignRoles(item.id)" class="fal fa-user-lock fr text-primary actions-menu-item"> </i> -->
      <i class="" :class="formatMeta(item.meta, 'icon')"></i>
      <span class="text-menu">
        {{ item.name }}
      </span>
    </div>
    <slot></slot>
  </li>
</template>

<script>
export default {
  props: ['item'],
  methods: {
    destroy(item){
      this.$confirm('Deseas eliminar este menú?')
       .then(_ => {
         this.getMenu()
       })
       .catch(_ => {});
    },
    edit(id){
      bus.$emit('edit_menu', id)
    },
    assignRoles(id){
      bus.$emit('assing_role', id)
    },
    formatMeta (meta, param) {
      if (meta) {
        var data = JSON.parse(meta)
        return data[param]
      }
    }
  }
}
</script>

<style lang="css" scoped>
</style>

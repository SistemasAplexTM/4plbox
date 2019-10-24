<template>
  <el-tabs v-model="active" type="card">
    <el-tab-pane name="form" label="Formulario">
      <span slot="label">
        <i class="fal fa-user"></i> Formulario
      </span>
      <form-csc
        :payload="payload"
        @updatetable="updateTable"
        @cancel="payload.field_id = null"
        :parent="payload.id_c"
      ></form-csc>
    </el-tab-pane>
    <el-tab-pane name="list" label="Listado">
      <span slot="label">
        <i class="fal fa-list"></i> Listado
      </span>
      <list-contact :id_data="payload.id_c" :table="payload.table" />
    </el-tab-pane>
  </el-tabs>
</template>

<script>
import ListContact from "./contact/ListContact";
export default {
  components: { ListContact },
  props: ["payload"],
  data() {
    return {
      active: "form"
    };
  },
  methods: {
    updateTable() {
      bus.$emit("updateContact");
    }
  }
};
</script>

<style>
</style>
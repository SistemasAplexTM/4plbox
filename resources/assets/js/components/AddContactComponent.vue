<template>
  <el-tabs v-model="active" type="card">
    <el-tab-pane name="form" label="Formulario">
      <form-csc
        :payload="payload"
        @updatetable="updateTable"
        @cancel="payload.field_id = null"
        :parent="payload.id_c"
      ></form-csc>
    </el-tab-pane>
    <el-tab-pane name="list" label="Listado">
      <list-contact :id_consignee="payload.id_c" />
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
    updateTable() {}
  }
};
</script>

<style>
</style>
<template>
  <div>
    <h2>{{ payload.name }}</h2>
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
      <el-tab-pane name="exist" label="Existentes">
        <span slot="label">
          <i class="fal fa-list"></i> Existentes
        </span>
        <existing-contact
          :id_data="payload.id_c"
          :table="payload.table"
          @assignedSuccess="active='list'"
          @updatetable="updateTable"
        />
      </el-tab-pane>
      <el-tab-pane name="list" label="Asignados">
        <span slot="label">
          <i class="fal fa-users"></i> Asignados
        </span>
        <list-contact :id_data="payload.id_c" :table="payload.table" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import ListContact from "./contact/ListContact";
import ExistingContact from "./contact/Existing";
export default {
  components: { ListContact, ExistingContact },
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
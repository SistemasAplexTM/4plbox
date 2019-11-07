<template>
  <div>
    <span
      for="agency"
      id="agencia_name"
      style="font-family: 'Russo One', sans-serif; font-size: 40px; float: left;font-weight: bold;"
    >{{ agency.descripcion }}</span>

    <el-dropdown
      title="Cambiar agencia"
      data-toggle="tooltip"
      @command="handleCommand"
      style="top: 22px;left: 10px;"
    >
      <span class="el-dropdown-link">
        <i class="fal fa-sync-alt change_agency"></i>
      </span>
      <el-dropdown-menu slot="dropdown" class="agencies_menu">
        <el-dropdown-item
          v-for="item in agency_list"
          v-bind:key="item.id"
          :command="item"
          icon="fal fa-warehouse-alt"
        >{{ item.descripcion }}</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>
<script>
export default {
  props: ["agency"],
  data() {
    return {
      agency_list: []
    };
  },
  created() {
    this.getAgencies();
  },
  methods: {
    getAgencies() {
      let me = this;
      axios.get("/agencia/all").then(({ data }) => {
        me.agency_list = data.data;
      });
    },
    handleCommand(command) {
      console.log(command);
      $("#agencia_id").val(command.id);
      $("#agencia_name").html(command.descripcion);
    }
  }
};
</script>
<style lang="css" scoped>
.agencies_menu {
  overflow-y: auto;
  height: 200px;
}
</style>
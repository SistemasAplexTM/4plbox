<template>
  <div>
    <span for="agency" id="agencia_name" :style="style_default">{{ agency.descripcion }}</span>
    <el-dropdown
      title="Cambiar agencia"
      data-toggle="tooltip"
      @command="handleCommand"
      :style="style_default_icon"
      v-if="role == 1"
    >
      <span class="el-dropdown-link">
        <i class="fal fa-sync-alt change_agency"></i>
      </span>
      <el-dropdown-menu slot="dropdown" class="agencies_menu">
        <el-dropdown-item v-for="item in agency_list" v-bind:key="item.id" :command="item">
          <img
            :src="'/storage/' + ((item.logo != null) ? item.logo : 'icon-no-image.svg')"
            width="50"
          />
          {{ item.descripcion }}
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>
<script>
export default {
  props: ["agency", "role", "style_"],
  data() {
    return {
      agency_list: [],
      style_default:
        "font-family: 'Russo One', sans-serif; font-size: 40px; float: left;font-weight: bold;",
      style_default_icon: "top: 22px;left: 10px;"
    };
  },
  created() {
    this.getAgencies();
    if (typeof this.style_ != "undefined") {
      this.style_default =
        "font-family: 'Russo One', sans-serif;" + this.style_;
      this.style_default_icon = "top: 5px;left: 10px;";
    }
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
.el-dropdown-link {
  cursor: pointer;
  color: #409eff;
  /* font-size: 20px; */
}
</style>
<template lang="html">
  <div class="">
    <el-autocomplete
      class="inline-input"
      v-model="datos.nombre_full"
      :fetch-suggestions="querySearch"
      :trigger-on-focus="false"
      placeholder="Buscar consignee"
      @select="assignContact"
    >
      <template slot-scope="{ item }">
        <div class="content-select">
          <div style="">
            <i class="fal fa-user icon"></i> {{ item.nombre_full }}
          </div>
        </div> 
        <hr class="hr-search">
  		</template>
    </el-autocomplete>

  </div>
</template>

<script>
export default {
  props: ["id_data", "table"],
  data() {
    return {
      datos: {},
      options: [],
      data: null
    };
  },
  methods: {
    querySearch(queryString, cb) {
      var me = this;
      axios
        .get(
          "/consignee/getExisting/" +
            queryString +
            "/" +
            this.id_data +
            "/" +
            this.table
        )
        .then(function({ data }) {
          me.options = data;
          cb(me.options);
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    async assignContact(item) {
      try {
        await axios.post(
          "/consignee/assignContact/" + this.id_data + "/" + this.table,
          item
        );
        toastr.success("Contacto agregado");
        this.$emit("updatetable");
        this.$emit("assignedSuccess");
      } catch (error) {
        toastr.error("Error al agregar contacto");
      }
    }
  }
};
</script>

<style lang="css" scoped>
.content-search {
  overflow: hidden;
}
.content-search-item {
  float: left;
}
.hr-search {
  margin-bottom: 0px;
}
.content-select {
  padding-top: 10px;
}
.el-autocomplete,
.inline-input {
  width: 100%;
}
.el-autocomplete-suggestion {
  /* width: max-content!important; */
  z-index: 9999 !important;
}
.icon {
  font-size: 11px;
}
.content-select {
  padding-top: 7px;
  line-height: 17px;
}
/* .el-select-dropdown__item{
    height: 70px;
  } */
</style>

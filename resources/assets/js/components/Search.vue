<template lang="html">
  <div class="">
    <el-autocomplete
      class="inline-input"
      v-model="datos.name"
      :fetch-suggestions="querySearch"
      :trigger-on-focus="false"
      placeholder="Buscar..."
      @select="handleSelect"
      size="small"
    >
      <template slot="prepend">
        <el-dropdown @command="changeItem">
          <span class="el-dropdown-link">
            {{ typeSearch }} <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="Warehouse">Warehouse</el-dropdown-item>
            <el-dropdown-item command="Consignee">Consignee</el-dropdown-item>
            <el-dropdown-item command="Tracking">Tracking</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </template>
      <template slot-scope="{ item }">
  <div class="content-select">
    <div style>
      <i class="fal fa-user icon"></i>
      {{ item.consignee }}
    </div>
    <div style="color: #8492a6;">
      <i class="fal fa-box-open icon"></i>
      {{ item.name }} &nbsp;&nbsp;
      <i class="fal fa-balance-scale icon"></i>
      {{ item.peso }} Lb &nbsp;&nbsp;
    </div>
    <div style="color: #8492a6; font-size: 13px">
      <div>
        <i class="fal fa-truck icon"></i>
        {{ item.tracking }}
      </div>
      <div class="content-search">
        <div class="content-search-item">
          <i class="fal fa-comment-edit icon"></i>
          {{ item.contenido }}
        </div>
      </div>
    </div>
    <hr class="hr-search" />
  </div>
</template>
    </el-autocomplete>
  </div>
</template>

<script>
export default {
  data() {
    return {
      datos: {},
      options: [],
      data: null,
      typeSearch: "Tracking"
    };
  },
  mounted() {
    //
  },
  methods: {
    changeItem(item) {
      this.datos = {};
      this.typeSearch = item;
    },
    querySearch(queryString, cb) {
      var me = this;
      axios
        .get(
          "/documento/getDataSearchDocument/" +
            queryString +
            "/" +
            this.typeSearch
        )
        .then(function(response) {
          me.options = response.data.data;
          let data2 = response.data.data2;
          if (data2.length > 0) {
            for (var i = 0; i < data2.length; i++) {
              me.options.push(data2[i]);
            }
          }
          // me.options.push(response.data.data2);
          cb(me.options);
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    handleSelect(item) {
      var data = {
        component: "search-result",
        title: "Resultado de la Busqueda",
        icon: "fal fa-search",
        datos: item,
        btn_remove: true
      };
      bus.$emit("open", data);
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
  width: 300px;
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

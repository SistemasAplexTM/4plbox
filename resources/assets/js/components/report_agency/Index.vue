<template lang="html">
  <el-row>
      <el-col :span="24">
        <el-row>
            <el-col :span="24" v-if="edit">
              <h1># {{ payload.consecutive }}</h1>
            </el-col>
            <el-col :span="24">
              <el-tabs v-model="activeName" type="border-card">
                <el-tab-pane label="Seleccionados" name="first">
                  <span slot="label"><i class="fal fa-plus"></i> Agregar</span>
                  <el-table
                    ref="multipleTable"
                    :data="tableData"
                    style="width: 100%"
                    :row-key="tableData.id"
                    @selection-change="handleSelectionChange">
                    <el-table-column
                    type="selection"
                    width="55">
                  </el-table-column>
                  <el-table-column
                    property="num_guia"
                    label="Warehouse">
                    <template slot="header" slot-scope="scope">
                      <span> <i class="fal fa-box-open"></i> Warehouse</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    property="pais"
                    label="País">
                  </el-table-column>
                </el-table>
              </el-tab-pane>
              <el-tab-pane v-if="edit" label="Agregar" name="second">
                <span slot="label"><i class="fal fa-check"></i> Seleccionados</span>
                <el-table
                  ref="multipleTableById"
                  :data="tableDataById"
                  style="width: 100%"
                  @selection-change="handleSelectionChangeById">
                  <el-table-column
                    type="selection"
                    width="55">
                  </el-table-column>
                  <el-table-column
                    property="num_guia"
                    label="Warehouse">
                    <template slot="header" slot-scope="scope">
  <span>
    <i class="fal fa-box-open"></i> Warehouse
  </span>
</template>
                  </el-table-column>
                  <el-table-column
                    property="pais"
                    label="País">
                  </el-table-column>
                </el-table>
              </el-tab-pane>
            </el-tabs>
          </el-col>
        </el-row>
      </el-col>
  </el-row>
</template>

<script>
export default {
  props: ["payload"],
  data() {
    return {
      edit: this.payload.edit,
      activeName: "first",
      tableData: [],
      tableDataById: [],
      multipleSelection: [],
      multipleSelectionById: []
    };
  },
  created() {
    let me = this;
    me.get();
    if (me.edit) {
      me.getById();
    }
    bus.$on("update", function(payload) {
      me.update();
    });
    bus.$on("save", function(payload) {
      me.generate();
    });
    bus.$on("cancel", function(payload) {
      me.toggleSelection();
    });
  },
  methods: {
    toggleSelection(rows) {
      this.$refs.multipleTable.clearSelection();
      this.$refs.multipleTableById.clearSelection();
      bus.$emit("close");
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    handleSelectionChangeById(val) {
      this.multipleSelectionById = val;
    },
    generate() {
      if (this.multipleSelection.length > 0) {
        axios
          .post("/reportDispatch", this.multipleSelection)
          .then(({ data }) => {
            console.log(data);
            this.$refs.multipleTable.clearSelection();
            this.multipleSelection = [];
            toastr.success("Registro creado correctamente.");
            refreshTable("tbl-documento_agencia");
            bus.$emit("close");
          })
          .catch(error => error);
      } else {
        // toastr.warning('Seleccione al menos un warehouse para generar el informe.');
        return false;
      }
    },
    update() {
      let me = this;
      var payload = {
        data: me.multipleSelection,
        dataById: me.multipleSelectionById
      };
      axios
        .post("/reportDispatch/54", payload)
        .then(({ data }) => {
          console.log(data);
          this.$refs.multipleTable.clearSelection();
          this.$refs.multipleTableById.clearSelection();
          this.multipleSelection = [];
          this.multipleSelectionById = [];
          toastr.success("Actualizado correctamente.");
          refreshTable("tbl-documento_agencia");
          bus.$emit("close");
        })
        .catch(error => error);
    },
    get() {
      axios
        .get("/reportDispatch")
        .then(({ data }) => {
          this.tableData = data;
        })
        .catch(error => error);
    },
    getById() {
      axios
        .get("/reportDispatch/" + this.payload.id)
        .then(({ data }) => {
          this.tableDataById = data;
          this.$refs.multipleTableById.toggleAllSelection();
        })
        .catch(error => error);
    }
  }
};
</script>

<style lang="css" scoped>
</style>

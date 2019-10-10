<template>
  <div>
    <el-dialog
      :visible.sync="openGroup"
      width="30%"
      :append-to-body="true"
      :before-close="closeModal"
    >
      <h3 slot="title">
        <i class="fal fa-cubes"></i> Documentos disponibles para agrupar
      </h3>
      <p>Selecione los documentos que desea agrupar en este registro.</p>
      <el-table
        :data="gridData"
        ref="multipleTable"
        max-height="300"
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="40"></el-table-column>
        <el-table-column property="codigo" label="Documento" width="150"></el-table-column>
        <el-table-column property="piezas" label="Piezas"></el-table-column>
        <el-table-column label="Peso">
          <template slot-scope="scope">
            <span>{{ scope.row.peso }} lb</span>
          </template>
        </el-table-column>
        <el-table-column label="Declarado">
          <template slot-scope="scope">
            <i class="fal fa-dollar-sign"></i>
            <span style="margin-left: 2px">{{ scope.row.declarado }}</span>
          </template>
        </el-table-column>
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">
          <i class="fal fa-times"></i> Cancelar
        </el-button>
        <el-button type="success" @click="beforeSend(false)">
          <i class="fal fa-layer-group"></i> Agrupar Mintic
        </el-button>
        <el-button type="primary" @click="beforeSend(true)">
          <i class="fal fa-box-full"></i> Agrupar
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  props: ["open_group", "id_document"],
  data() {
    return {
      openGroup: false,
      idDocument: null,
      gridData: [],
      multipleSelection: []
    };
  },
  watch: {
    open_group: function(val) {
      this.openGroup = val;
      this.idDocument = this.id_document;
      this.getDataTable();
    }
  },
  methods: {
    closeModal() {
      this.openGroup = false;
      this.$emit("set");
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    getDataTable() {
      let me = this;
      axios
        .get("documento/0/getGuiasAgrupar/" + this.idDocument + "/document")
        .then(function(response) {
          me.gridData = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.", error.message);
          toastr.options.closeButton = true;
        });
    },
    beforeSend(op) {
      if (op) {
        swal({
          title: "Atención!",
          html:
            "Los valores de peso, declarado y piezas, seran actualizados con la " +
            "sumatoria de peso, declarado y piezas de los documentos seleccionados. <br>Desea continuar?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si",
          cancelButtonText: "No"
        }).then(result => {
          if (result.value) {
            this.agruparDocumentoDetalle(false);
          }
        });
      } else {
        // agrupa como mintic
        this.agruparDocumentoDetalle(true);
      }
    },
    agruparDocumentoDetalle: function(mintic) {
      let me = this;
      var ids = {};
      $.each(me.multipleSelection, function(i, field) {
        ids[i + 1] = parseInt(field.id);
      });
      axios
        .post("documento/0/agruparGuiasConsolidadoCreate", {
          id_detalle: me.idDocument,
          ids_guias: ids,
          mintic: mintic,
          document: true
        })
        .then(function(response) {
          me.closeModal();
          toastr.success("Se agrupo correctamente.");
          refreshTable("tbl-documento2");
          me.$emit("set");
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    }
  }
};
</script>
<style lang="" scope>
</style>
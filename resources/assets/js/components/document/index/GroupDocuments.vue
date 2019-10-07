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
      <el-table :data="gridData" ref="multipleTable" @selection-change="handleSelectionChange">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column property="date" label="Date" width="150"></el-table-column>
        <el-table-column property="name" label="Name" width="200"></el-table-column>
        <el-table-column property="address" label="Address"></el-table-column>
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" @click="openGroup = false">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  props: ["open_group"],
  data() {
    return {
      openGroup: false,
      gridData: [
        {
          date: "2016-05-02",
          name: "John Smith",
          address: "No.1518,  Jinshajiang Road, Putuo District"
        },
        {
          date: "2016-05-04",
          name: "John Smith",
          address: "No.1518,  Jinshajiang Road, Putuo District"
        },
        {
          date: "2016-05-01",
          name: "John Smith",
          address: "No.1518,  Jinshajiang Road, Putuo District"
        },
        {
          date: "2016-05-03",
          name: "John Smith",
          address: "No.1518,  Jinshajiang Road, Putuo District"
        }
      ],
      multipleSelection: []
    };
  },
  watch: {
    open_group: function(val) {
      this.openGroup = val;
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
      axios
        .get("documento/insertStatusUploadDocument")
        .then(function(response) {})
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.", error.message);
          toastr.options.closeButton = true;
        });
    }
  }
};
</script>
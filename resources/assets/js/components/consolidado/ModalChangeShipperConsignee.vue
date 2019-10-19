<template>
  <div>
    <el-dialog
      title="Shipping address"
      :visible.sync="dialogTableVisible"
      :append-to-body="true"
      :height="250"
      style="width: 100%"
    >
      <el-table :data="gridData">
        <span slot="empty">No hay datos :(</span>

        <el-table-column property="nombre_full" label="Nombre"></el-table-column>
        <el-table-column property="telefono" label="Teléfono"></el-table-column>
        <el-table-column property="city.nombre" label="Ciudad"></el-table-column>
        <el-table-column property="zip" label="Zip"></el-table-column>
        <el-table-column label="Acciones">
          <template slot-scope="scope">
            <el-button size="mini" type="success" @click="selectData(scope.$index, scope.row)">
              <i class="fal fa-check"></i> Seleccionar
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>
<script>
export default {
  props: {
    open_modal: {
      type: Object,
      required: true
    }
  },
  watch: {
    open_modal: function(val) {
      if (val.open) {
        this.getData(val.id_data);
      }
    }
  },
  data() {
    return {
      gridData: [],
      dialogTableVisible: false
    };
  },
  methods: {
    async getData(id) {
      try {
        let data = await axios.get(`/consignee/getContacts/${id}`);
        this.gridData = data.data;
        this.dialogTableVisible = true;
      } catch (error) {
        console.error(error);
      }
    },
    async selectData(index, row) {
      console.log(index, row);

      try {
        // this.gridData = await axios.get(`/consignee/getContacts/${id}`);
      } catch (error) {
        console.error(error);
      }
    }
  }
};
</script>
<style lang="css">
.el-table__empty-block {
  height: 300px;
}
</style>
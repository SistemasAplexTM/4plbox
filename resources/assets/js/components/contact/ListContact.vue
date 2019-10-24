<template>
  <el-collapse v-model="activeNames">
    <el-collapse-item
      v-for="item in contacts.data"
      :key="item.id"
      :title="item.nombre_full"
      :name="item.id"
    >
      <div>
        <table class="table_contact">
          <tbody>
            <tr>
              <td width="90">Dirección</td>
              <td>{{ item.direccion }}</td>
            </tr>
            <tr>
              <td>Teléfono</td>
              <td>{{ item.telefono }}</td>
            </tr>
            <tr>
              <td>Ciudad</td>
              <td>{{ item.city.nombre }}</td>
            </tr>
            <tr>
              <td>Zip code</td>
              <td>{{ item.zip }}</td>
            </tr>
            <tr class="text-center">
              <td width="100%" class="text-center" colspan="2">
                <el-button type="danger" @click="destroy(item.id)" size="small" plain class="w100">
                  <i class="fal fa-trash"></i>
                </el-button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </el-collapse-item>
  </el-collapse>
</template>
<script>
export default {
  props: ["id_data", "table"],
  data() {
    return {
      activeNames: ["1"],
      contacts: []
    };
  },
  created() {
    let me = this;
    me.get();
    bus.$on("updateContact", function(payload) {
      me.get();
    });
  },
  methods: {
    async get() {
      try {
        this.contacts = await axios.get(
          "/" + this.table + "/getContacts/" + this.id_data
        );
      } catch (error) {
        console.error(error);
      }
    },
    destroy(id) {
      swal({
        title: "Seguro que desea eliminar este registro?",
        text: "No lo podrás recuperar después!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No, cancelar!"
      }).then(result => {
        if (result.value) {
          axios
            .delete(`/${this.table}/${id}`)
            .then(response => {
              this.get();
              toastr.success("Registro eliminado correctamente.");
            })
            .catch(error => {
              console.log(error);
            });
        }
      });
    }
  }
};
</script>

<style lang="css" scoped>
.table_contact {
  width: 100%;
  /* border: 1px solid #e4e7ed; */
}
td {
  border-top: 1px solid #e4e7ed;
  border-bottom: 1px solid #e4e7ed;
  padding: 8px;
}
</style>
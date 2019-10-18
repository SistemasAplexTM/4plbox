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
          </tbody>
        </table>
      </div>
    </el-collapse-item>
  </el-collapse>
</template>
<script>
export default {
  props: ["id_consignee"],
  data() {
    return {
      activeNames: ["1"],
      contacts: []
    };
  },
  created() {
    this.get();
  },
  methods: {
    async get() {
      try {
        this.contacts = await axios.get(
          "/consignee/getContacts/" + this.id_consignee
        );
      } catch (error) {
        console.error(error);
      }
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
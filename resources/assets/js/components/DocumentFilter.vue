<template lang="html">
  <div class="">
    <!-- MODAL FILTRAR DOCUMENTO -->
      <el-dialog
        :visible.sync="dialogvisible"
        :before-close="closeModal"
        width="25%" :append-to-body="true" @open="openFilter">
        <span slot="title"><i class="fal fa-filter"></i> Buscar Documento</span>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <el-input size="medium" clearable placeholder="# Warehouse" v-model="warehouse"></el-input>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-group">
              <el-select
                size="medium" clearable
                v-model="client_id"
                filterable
                remote
                reserve-keyword
                placeholder="Buscar destinatario"
                :remote-method="remoteMethod"
                :loading="loading"
                loading-text="Cargando..."
                no-data-text="No hay datos">
                <el-option
                  v-for="item in options"
                  :key="item.id"
                  :label="item.nombre_full"
                  :value="item.id">
                </el-option>
              </el-select>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-group">
              <el-date-picker
                size="medium"
                v-model="date_range"
                type="daterange"
                align="right"
                unlink-panels
                range-separator="-"
                start-placeholder="Fecha de inicio"
                end-placeholder="Fecha de fin"
                :picker-options="pickerOptions"
                format="yyyy/MM/dd"
                value-format="yyyy-MM-dd">
              </el-date-picker>
            </div>
          </div>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="filterDocument" icon="el-icon-search">Filtrar</el-button>
          <el-button @click="closeModal"><i class="fal fa-times"></i> Cerrar</el-button>
        </span>
      </el-dialog>

  </div>
</template>

<script>
export default {
  props: ["dialogvisible", "courier_carga"],
  data(){
    return {
      pickerOptions: {
        shortcuts: [{
          text: '-Ult. semana',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '-Ult mes',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '-Ult. 3 meses',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      date_range: '',
      list: [],
      warehouse: null,
      options: [],
      loading: false,
      client_id: [],
    }
  },
  methods: {
    filterDocument(){
      var filter = {
        'warehouse' : this.warehouse,
        'consignee_id' : this.client_id,
        'dates' : this.date_range
      }
      let data = {
        filter: filter,
        courier_carga: this.courier_carga,
      }
      this.$emit('get', data);
    },
    remoteMethod(query) {
      if (query !== '') {
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
          this.options = this.list.filter(item => {
            return item.nombre_full.toLowerCase()
              .indexOf(query.toLowerCase()) > -1;
          });
        }, 200);
      } else {
        this.options = [];
      }
    },
    openFilter(){
      var me = this;
      axios.get('/consignee/getSelect').then(function(response) {
          me.list = response.data.data;
      }).catch(function(error) {
          console.log(error);
          toastr.warning('Error: -' + error);
      });
    },
    closeModal(){
      this.$emit('close');
    },
  }
}
</script>

<style lang="css" scoped>
</style>

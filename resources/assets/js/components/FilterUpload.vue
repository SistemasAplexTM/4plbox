<template lang="html">
  <div class="">
    <!-- MODAL FILTRAR DOCUMENTO -->
      <el-dialog
        :visible.sync="dialogVisible"
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
          <el-button @click="dialogVisible = false">Cancelar</el-button>
          <el-button type="primary" @click="filterDocument" icon="el-icon-search">Filtrar</el-button>
        </span>
      </el-dialog>

    <!-- MODAL SUBIR ESTATUS DOCUMENTO -->
      <!-- <el-dialog
        :visible.sync="uploadFileStatus"
        width="40%" :append-to-body="true">
        <span slot="title"><i class="fal fa-upload"></i> Cargar archivo</span>
        <div class="row">
          <div class="col-lg-12" style="text-align: center;">
            <el-upload
              class="upload-demo"
              drag
              action="/documento/uploadFileStatus"
              :headers="headerFile"
              :on-success="handleSuccess"
              :on-remove="handleRemove"
              :file-list="fileList" :limit="1">
              <i class="el-icon-upload"></i>
              <div class="el-upload__text">Suelta tu archivo aquí o <em>haz clic para cargar</em></div>
              <div slot="tip" class="el-upload__tip">Solo archivos xlsx con un tamaño menor de 2MB. <a href="{{ asset('/download/Status.xlsx') }}" target="_blank" class="downloadLink">Descargar archivo demo aqui <i class="fal fa-download"></i></a></div>
            </el-upload>
          </div>
        </div>
        <div class="row" style="margin-top: 30px;">
          <div class="col-lg-12">
            <el-alert
              :closable="false"
              title="Atención! Por favor verifique la información del archivo"
              type="warning"
              show-icon
              v-if="errorUpload.length > 0">
              <div style="margin-top: 13px;">
                  <p v-for="error in errorUpload">
                    - @{{ error.wh }}
                    <el-tag type="info" size="mini" style="float: right;" v-if="error.documento_detalle_id === null">Warehouse <i class="fal fa-times"></i></el-tag>
                    <el-tag type="danger" size="mini" style="float: right;" v-if="error.status_id === null">Status <i class="fal fa-times"></i></el-tag>
                  </p>
              </div>
            </el-alert>
            <el-alert
              v-if="uploadSuccess"
              :title="title_msn"
              :type="type_msn"
              show-icon
              :closable="false">
              <div>@{{ textSuccess }}</div>
            </el-alert>
          </div>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" :loading="upload_s" :disabled="errorUpload.length !== 0" @click="insertStatusUploadDocument"><i class="fal fa-upload"></i> Cargar Status</el-button>
          <el-button @click="uploadFileStatus = false"><i class="fal fa-times"></i> Cerrar</el-button>
        </span>
      </el-dialog> -->
  </div>
</template>

<script>
export default {
  props: ["dialogVisible"],
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
      fileList:[],
      // filter
      warehouse: null,
      options: [],
      list: [],
      loading: false,
      client_id: [],
      headerFile:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      errorUpload:[],
      upload_s: false,
      uploadSuccess: false,
      textSuccess: 'Archivo listo para ser cargado',
      title_msn: '',
      type_msn: 'info',
    }
  },
  methods: {
    insertStatusUploadDocument(){
      let me = this;
      me.upload_s = true
      axios.get('documento/insertStatusUploadDocument').then(function (response) {
        console.log(response.data);
        if (response.data.code == 200) {
          me.uploadSuccess = false
          me.upload_s = false;
          me.uploadSuccess = true
          me.title_msn = 'Proceso finalizado!',
          me.type_msn = 'success',
          me.textSuccess = 'Los status han sido agregados correctamente'
        }else{
          console.log(response.data);
          if(response.data.error.errorInfo[2]){
            toastr.warning('Error.', response.data.error.errorInfo[2]);
          }else{
            toastr.warning('Error.', response.data.error);
          }
          toastr.options.closeButton = true;
          me.upload_s = false;
        };
      }).catch(function (error) {
          console.log(error);
          toastr.warning('Error.', error.message);
          toastr.options.closeButton = true;
      });
    },
    handleSuccess(response, file, fileList) {
      $('.el-upload').toggle("slow");
      let me = this;
      axios.get('documento/validateUploadDocs').then(function (response) {
        me.errorUpload = response.data;
        if(response.data.length === 0){
          me.uploadSuccess = true
        }
      }).catch(function (error) {
          console.log(error);
          toastr.warning('Error.');
          toastr.options.closeButton = true;
      });
    },
    handleRemove(file, fileList){
      $('.el-upload').toggle("slow");
      this.errorUpload = []
      this.uploadSuccess = false
      this.title_msn = '',
      this.type_msn = 'info',
      this.textSuccess = 'Archivo listo para ser cargado'
    },
    handleExceed(files, fileList) {
      this.$message.warning(`El límite es 1, haz seleccionado ${files.length} archivos esta vez, añade hasta ${files.length + fileList.length}`);
    },
    filterDocument(){
      var filter = {
        'warehouse' : this.warehouse,
        'consignee_id' : this.client_id,
        'dates' : this.date_range
      }
      var courier_carga = this.courier_carga;
      listDocument(1, null, null, null, true, filter, courier_carga);
      this.dialogVisible = false;
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
  }
}
</script>

<style lang="css" scoped>
</style>

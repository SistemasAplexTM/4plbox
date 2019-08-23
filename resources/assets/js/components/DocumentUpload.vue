<template lang="html">
  <div class="">
      <!--  MODAL SUBIR ESTATUS DOCUMENTO -->
      <el-dialog
        :visible.sync="dialogvisibleupload"
        :before-close="closeModal"
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
              <div slot="tip" class="el-upload__tip">Solo archivos xlsx con un tamaño menor de 2MB. <a href="/download/Status.xlsx" target="_blank" class="downloadLink">Descargar archivo demo aqui <i class="fal fa-download"></i></a></div>
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
                    - {{ error.wh }}
                    <el-tag type="info" size="mini" style="float: right;" v-if="error.documento_detalle_id === null">Warehouse <i class="fal fa-times"></i></el-tag>
                    &nbsp;
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
              <div>{{ textSuccess }}</div>
            </el-alert>
          </div>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" :loading="upload_s" :disabled="this.errorUpload !== 0 && disabled" @click="insertStatusUploadDocument"><i class="fal fa-upload"></i> Cargar Status</el-button>
          <el-button @click="closeModal"><i class="fal fa-times"></i> Cerrar</el-button>
        </span>
      </el-dialog>

  </div>
</template>

<script>
export default {
  props: ["dialogvisibleupload"],
  data(){
    return {
      fileList:[],
      headerFile:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      errorUpload:[],
      disabled: true,
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
        if (response.data.code == 200) {
          me.disabled = true
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
          me.disabled = false
        }else{
          me.uploadSuccess = false
          me.disabled = true
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
      this.disabled = true
      this.uploadSuccess = false
      this.title_msn = '',
      this.type_msn = 'info',
      this.textSuccess = 'Archivo listo para ser cargado'
    },
    handleExceed(files, fileList) {
      this.disabled = true
      this.$message.warning(`El límite es 1, haz seleccionado ${files.length} archivos esta vez, añade hasta ${files.length + fileList.length}`);
    },
    closeModal(){
      this.$emit('close');
    },
  }
}
</script>

<style lang="css" scoped>
</style>

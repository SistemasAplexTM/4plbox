/* objetos VUE index */
var objVue = new Vue({
    el: '#documentoIndex',
    watch:{
      status_id:function(value){
          var status_id = '';
          if(value != null){
              status_id = value.id;
          }
          listDocument(this.type_document, null, null, null, true, status_id);
      },
      datosAgrupar:function(val){
          let me = this;
          if ($.fn.DataTable.isDataTable('#tbl-modalagrupar')) {
              $('#tbl-modalagrupar tbody').empty();
              $('#tbl-modalagrupar').dataTable().fnDestroy();
          }
          var table = $('#tbl-modalagrupar').DataTable({
              "language": {
                  "paginate": {
                      "previous": "Anterior",
                      "next": "Siguiente",
                  },
                  /*"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",*/
                  "info": "Registros del _START_ al _END_  de un total de _TOTAL_",
                  "search": "Buscar",
                  "lengthMenu": "Mostrar _MENU_ Registros",
                  "infoEmpty": "Mostrando registros del 0 al 0",
                  "emptyTable": "No hay datos disponibles en la tabla",
                  "infoFiltered": "(Filtrando para _MAX_ Registros totales)",
                  "zeroRecords": "No se encontraron registros coincidentes",
              },
              "order": [[ 1, "desc" ]],
              processing: true,
              serverSide: true,
              searching: true,
              ajax: 'documento/0/getGuiasAgrupar/'+ val.id+'/document',
              columns: [{
                  "render": function (data, type, full, meta) {
                      return '<div class="checkbox checkbox-success"><input type="checkbox" data-id_guia="' + full.id + '" id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '"></label></div>';
                  }
              }, {
                  data: 'codigo',
                  name: 'codigo'
              }, {
                  data: 'peso',
                  name: 'peso'
              }]
          });
          $('#modalagrupar').modal('show');
      },
      removerAgrupado:function(option){
            let me = this;
            axios.get('documento/0/removerGuiaAgrupada/' + option.id + '/' + option.id + '/' + true).then(response => {
                toastr.success('Registro quitado correctamente.');
                refreshTable('tbl-documento2');
            }).catch(function(error) {
                console.log(error);
                toastr.warning('Error: -' + error);
            });
        }
    },
    mounted: function() {
      let me = this;
      me.typeDocumentList();
      setTimeout(function () {
        me.printDocument();
      },1500)
      $('#date').val(this.getTime());
    },
    created(){
      //
    },
    data: {
      id_status: null,
      tableDelete: null,
      params: {},
      type_document: null,
      datosAgrupar: {},
      removerAgrupado: {}, //es para poder remover guias agrupadas en el consolidado
      status_id: {id: 5, descripcion: 'Consolidada'},
      status: [],
      id_consolidado_selected: null,
      dialogVisible: false,
      uploadFileStatus: false,
      // filter
      warehouse: null,
      options: [],
      list: [],
      loading: false,
      states: ["Alabama", "Alaska", "Arizona",
      "Arkansas", "California", "Colorado",
      "Connecticut", "Delaware", "Florida",
      "Georgia", "Hawaii", "Idaho", "Illinois",
      "Indiana", "Iowa", "Kansas", "Kentucky",
      "Louisiana", "Maine", "Maryland",
      "Massachusetts", "Michigan", "Minnesota",
      "Mississippi", "Missouri", "Montana",
      "Nebraska", "Nevada", "New Hampshire",
      "New Jersey", "New Mexico", "New York",
      "North Carolina", "North Dakota", "Ohio",
      "Oklahoma", "Oregon", "Pennsylvania",
      "Rhode Island", "South Carolina",
      "South Dakota", "Tennessee", "Texas",
      "Utah", "Vermont", "Virginia",
      "Washington", "West Virginia", "Wisconsin",
      "Wyoming"],
      client_id: [],
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

      fileList:[],
      headerFile:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      errorUpload:[],
      upload_s: false,
      uploadSuccess: false,
      textSuccess: 'Archivo listo para ser cargado',
      title_msn: '',
      type_msn: 'info',
      // variable para saber que pestaña mostrar de la grilla principal de documentos si courier (true) o carga (false)
      courier_carga:2,
      showFilter: true,
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
      addStatusConsolidado: function(){
          var l = Ladda.create(document.querySelector('.ladda-button'));
          console.log(l);
          l.start();
          let me = this;
          axios.post('documento/' + me.id_consolidado_selected + '/addStatusToGuias',{
              'status_id': me.status_id.id
          }).then(function (response) {
              l.stop();
              toastr.success('Registro Exitoso.');
          }).catch(function (error) {
              l.stop();
              console.log(error);
              toastr.warning('Error.');
              toastr.options.closeButton = true;
          });
      },
      getStatusDocument: function(){
          let me = this;
          axios.post('documento/' + me.id_consolidado_selected + '/getStatusDocument').then(function (response) {
              // console.log(' ahsdias ', response.data);
          }).catch(function (error) {
              console.log(error);
              toastr.warning('Error.');
              toastr.options.closeButton = true;
          });
      },
      pendign(){
        let me = this;
        setTimeout(function() {
          if($('#li-pending').hasClass('active')){
            me.courier_carga = 4;
            me.showFilter = false;
            if (!$.fn.DataTable.isDataTable('#tbl-documento4')) {
              datatableDocument(4, 1);
            }
            $('.pending').removeClass('ligth');
          }else{
            if($('#li-load').hasClass('active')){
              me.courier_carga = 3;
              me.showFilter = true;
              if (!$.fn.DataTable.isDataTable('#tbl-documento3')) {
                datatableDocument(3, 1);
              }
              $('.pending').addClass('ligth');
            }else{
              if ($('#default').hasClass('active')) {
                me.courier_carga = 2;
                me.showFilter = true;
                if (!$.fn.DataTable.isDataTable('#tbl-documento2')) {
                  datatableDocument(2, 1);
                }
              }
              $('.pending').addClass('ligth');
            }
          }
        },100)
      },
      agruparDocumentoDetalle: function(){
        $('#modalagrupar').modal('hide');
        let me = this;
        var datos = $("#formGuiasAgrupar").serializeArray();
        var ids = {};
        $.each(datos, function(i, field) {
            if (field.name === 'chk[]') {
                ids[i] =  $('#chk' + field.value).data('id_guia');
            }
        });

        axios.post('documento/0/agruparGuiasConsolidadoCreate',{
            'id_detalle': me.datosAgrupar.id,
            'ids_guias': ids,
            'document': true
        }).then(function (response) {
            toastr.success('Se agrupo correctamente.');
            refreshTable('tbl-documento2');
        }).catch(function (error) {
            console.log(error);
            toastr.warning('Error.');
            toastr.options.closeButton = true;
        });
    },
      getStatus: function(){
          let me = this;
          axios.get('status/all').then(function (response) {
              me.status = response.data.data;
          }).catch(function (error) {
              console.log(error);
              toastr.warning('Error.');
              toastr.options.closeButton = true;
          });
      },
      printDocument: function(){
          if( $('#documentoIndex').data('id_print') != '' && $('#documentoIndex').data('doc_print') != ''){
              var name = "Nitro PDF Creator (Pro 10)";
              var format = "PDF";
              // javascript:jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + name + "&filetype="+ format +"&id=" + $('#documentoIndex').data('id_print') + "&agency_id="+agency_id+"&document="+$('#documentoIndex').data('doc_print')+"&label=true")

              setTimeout(function() {
              //   javascript:jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + name + "&filetype="+ format +"&id=" + $('#documentoIndex').data('id_print') + "&agency_id="+agency_id+"&document="+$('#documentoIndex').data('doc_print'))
                window.open('impresion-documento-label/' + $('#documentoIndex').data('id_print') + '/'+$('#documentoIndex').data('doc_print'), '_blank');
              }, 1000);
              if($('#documentoIndex').data('doc_print') == 'guia'){
                setTimeout(function() {
                  window.open('impresion-documento/' + $('#documentoIndex').data('id_print') + '/invoice_guia', '_blank');
                }, 1500);
              }
              window.open('impresion-documento/' + $('#documentoIndex').data('id_print') + '/'+$('#documentoIndex').data('doc_print'), '_blank');
          }
      },
      sendMail: function(id) {
          axios.get('documento/sendEmailDocument/' + id).then(function(response) {
              toastr.success('Email enviado correctamente.');
          }).catch(function(error) {
              toastr.error("Error.", {
                  timeOut: 50000
              });
          });
      },
      typeDocumentList: function() {
        let me = this;
          axios.get('tipoDocumento/all').then(function(response) {
              $.each(response.data.data, function(key, value) {
                  var lista = '<button type="button" id="btn' + value.id + '" ' + ' onclick="listDocument(' + value.id + ',\'' + value.nombre + '\',\'' + value.icono + '\',\'' + value.funcionalidades + '\',\'' + true + '\')"' + ' class="btn btn-default btn-block" style="text-align:left;">' + ' <i class="' + value.icono + '" aria-hidden="true"></i>  ' + value.nombre +'</button>';
                  if (value.id == 1) {
                    listDocument(value.id, value.nombre, value.icono, value.funcionalidades, false, false, me.courier_carga);
                  }
                  $('#listaDocumentos').append(lista);
              });
          }).catch(function(error) {
              toastr.error("Error.", {
                  timeOut: 50000
              });
          });
      },
      createNewDocument: function(data) {
        let type = '';
        if(typeof data.type != 'undefined'){
          type = 'para ' + data.type;
        }
          swal({
              title: "<div>Se creará un(a) <span style='color: rgb(212, 103, 82);'>" + data.name + ".</span> " + type + "</div>",
              text: "¿Desea Continuar?.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: "Si, Crear",
              cancelButtonText: "No, Cancelar!",
          }).then((result) => {
              if (result.value) {
                  axios.post('documento/ajaxCreate/' + data.tipo_doc_id, {
                      'tipo_documento_id': data.tipo_doc_id,
                      'type_id': data.type_id,
                      'funcionalidaddes': data.functionalities,
                      'created_at': this.getTime()
                  }).then(function(response) {
                      var res = response.data;
                      if (response.data['code'] == 200) {
                          toastr.success('Registro creado correctamente.');
                          window.location.href = 'documento/' + res.datos['id'] + '/edit';
                      } else {
                          toastr.warning(response.data['error']);
                      }
                  }).catch(function(error) {
                      console.log(error);
                      if (error.response.status === 422) {
                          me.formErrors = error.response.data; //guardo los errores
                          me.listErrors = me.formErrors.errors; //genero lista de errores
                      }
                      $.each(me.formErrors.errors, function(key, value) {
                          $('.result-' + key).html(value);
                      });
                      toastr.error("Porfavor completa los campos obligatorios.", {
                          timeOut: 50000
                      });
                  });
              }
          })
      },
      deleteDocument(id){
          let me = this;
          swal({
          title: "<div><span style='color: rgb(212, 103, 82);'>Atención!</span></div>",
          text: "¿Desea eliminar este documento?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: "Si",
          cancelButtonText: "No, Cancelar!",
          }).then((result) => {
              if (result.value) {
                  axios.delete('documento/' + id).then(function (response) {
                      if(response.data.code === 200){
                          refreshTable('tbl-documento2');
                          toastr.success('Documento eliminado exitosamente.');
                          toastr.options.closeButton = true;
                      }else{
                          toastr.warning('Atención! ha ocurrido un error.');
                      }
                  }).catch(function (error) {
                      console.log(error);
                      toastr.warning('Error.'+ error);
                      toastr.options.closeButton = true;
                  });
              }
          });
      },
    },
});

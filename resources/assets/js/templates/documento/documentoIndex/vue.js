/* objetos VUE index */
var objVue = new Vue({
  el: '#documentoIndex',
  watch: {
    status_id: function (value) {
      var status_id = '';
      if (value != null) {
        status_id = value.id;
      }
      listDocument(this.type_document, null, null, null, true, status_id);
    },
    datosAgrupar: function (val) {
      let me = this;
      if (val != '' && val != null) {
        me.openGroup = true
        me.idDocument = val
      }
    },
    removerAgrupado: function (option) {
      let me = this;
      axios.get('documento/0/removerGuiaAgrupada/' + option.id + '/' + option.id + '/' + true).then(response => {
        toastr.success('Registro quitado correctamente.');
        refreshTable('tbl-documento2');
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });
    }
  },
  mounted: function () {
    let me = this;
    me.typeDocumentList();
    setTimeout(function () {
      me.printDocument();
    }, 1500)
    $('#date').val(this.getTime());
  },
  created() {
    //
  },
  data: {
    id_status: null,
    tableDelete: null,
    params: {},
    type_document: null,
    datosAgrupar: null,
    removerAgrupado: {}, //es para poder remover guias agrupadas en el consolidado
    status_id: {
      id: 5,
      descripcion: 'Consolidada'
    },
    status: [],
    id_consolidado_selected: null,
    dialogVisible: false,
    dialogVisibleUpload: false,
    // variable para saber que pestaña mostrar de la grilla principal de documentos si courier (true) o carga (false)
    courier_carga: 2,
    showFilter: true,
    openGroup: false,
    idDocument: null,
    courier_today: 'HOY'
  },
  methods: {
    open(id, consecutive) {
      var data = {
        component: 'report-agency-component',
        title: 'Consolidado Agencia',
        icon: 'fal fa-box-check'
      }
      if (id) {
        data = {
          component: 'report-agency-component',
          title: 'Consolidado Agencia',
          icon: 'fal fa-box-check',
          edit: true,
          id: id,
          consecutive: consecutive
        }
      }
      bus.$emit('open', data)
    },
    closeModal() {
      this.dialogVisible = false;
      this.dialogVisibleUpload = false;
    },
    filter(data) {
      this.courier_today = '';
      listDocument(1, null, null, null, true, data.filter, data.courier_carga);
      this.dialogVisible = false;
    },
    closeDocument: function (id) {
      let me = this;
      swal({
        title: 'Seguro que desea CERRAR este documento?',
        text: "No lo podras abrir nuevamente!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No, cancelar!'
      }).then((result) => {
        if (result.value) {
          axios.get('documento/' + id + '/closeDocument').then(response => {
            me.close = true;
            toastr.success("Documento cerrado exitosamente.");
          });
        }
      });
    },
    addStatusConsolidado: function () {
      var l = Ladda.create(document.querySelector('.ladda-button'));
      console.log(l);
      l.start();
      let me = this;
      axios.post('documento/' + me.id_consolidado_selected + '/addStatusToGuias', {
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
    getStatusDocument: function () {
      let me = this;
      axios.post('documento/' + me.id_consolidado_selected + '/getStatusDocument').then(function (response) {
        // console.log(' ahsdias ', response.data);
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error.');
        toastr.options.closeButton = true;
      });
    },
    pendign() {
      let me = this;
      setTimeout(function () {
        if ($('#li-pending').hasClass('active')) {
          me.courier_carga = 4;
          me.showFilter = false;
          if (!$.fn.DataTable.isDataTable('#tbl-documento4')) {
            datatableDocument(4, 1);
          }
          $('.pending').removeClass('ligth');
        } else {
          if ($('#li-load').hasClass('active')) {
            me.courier_carga = 3;
            me.showFilter = true;
            if (!$.fn.DataTable.isDataTable('#tbl-documento3')) {
              datatableDocument(3, 1);
            }
            $('.pending').addClass('ligth');
          } else {
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
      }, 100)
    },
    resetVariables() {
      this.openGroup = false;
      this.datosAgrupar = null;
    },
    getStatus: function () {
      let me = this;
      axios.get('status/all').then(function (response) {
        me.status = response.data.data;
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error.');
        toastr.options.closeButton = true;
      });
    },
    printDocument: function () {
      if ($('#documentoIndex').data('id_print') != '' && $('#documentoIndex').data('doc_print') != '') {
        if (print_labels != '') {
          javascript: jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + print_labels + "&filetype=" + print_format + "&id=" + $('#documentoIndex').data('id_print') + "&agency_id=" + agency_id + "&document=" + $('#documentoIndex').data('doc_print') + "&label=true")
        }
        else {
          window.open('impresion-documento-label/' + $('#documentoIndex').data('id_print') + '/' + $('#documentoIndex').data('doc_print'), '_blank');
        }
        setTimeout(function () {
          if (print_documents != '') {
            javascript: jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + print_documents + "&filetype=" + print_format + "&id=" + $('#documentoIndex').data('id_print') + "&agency_id=" + agency_id + "&document=" + $('#documentoIndex').data('doc_print'))
          }
          else {
            window.open('impresion-documento/' + $('#documentoIndex').data('id_print') + '/' + $('#documentoIndex').data('doc_print'), '_blank');
          }
        }, 1000);
        if ($('#documentoIndex').data('doc_print') == 'guia') {
          setTimeout(function () {
            window.open('impresion-documento/' + $('#documentoIndex').data('id_print') + '/invoice_guia', '_blank');
          }, 1500);
        }
      }
    },
    sendMail: function (id) {
      axios.get('documento/sendEmailDocument/' + id).then(function (response) {
        toastr.success('Email enviado correctamente.');
      }).catch(function (error) {
        toastr.error("Error.", {
          timeOut: 50000
        });
      });
    },
    typeDocumentList: function () {
      let me = this;
      axios.get('tipoDocumento/all').then(function (response) {
        $.each(response.data.data, function (key, value) {
          var lista = '<button type="button" id="btn' + value.id + '" ' + ' onclick="listDocument(' + value.id + ',\'' + value.nombre + '\',\'' + value.icono + '\',\'' + value.funcionalidades + '\',\'' + true + '\')"' + ' class="btn btn-default btn-block" style="text-align:left;">' + ' <i class="' + value.icono + '" aria-hidden="true"></i>  ' + value.nombre + '</button>';
          if (value.id == 1) {
            listDocument(value.id, value.nombre, value.icono, value.funcionalidades, false, false, me.courier_carga);
          }
          $('#listaDocumentos').append(lista);
        });
      }).catch(function (error) {
        toastr.error("Error.", {
          timeOut: 50000
        });
      });
    },
    createNewDocument: function (data) {
      let type = '';
      if (typeof data.type != 'undefined') {
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
          }).then(function (response) {
            var res = response.data;
            if (response.data['code'] == 200) {
              toastr.success('Registro creado correctamente.');
              window.location.href = 'documento/' + res.datos['id'] + '/edit';
            } else {
              toastr.warning(response.data['error']);
            }
          }).catch(function (error) {
            console.log(error);
            if (error.response.status === 422) {
              me.formErrors = error.response.data; //guardo los errores
              me.listErrors = me.formErrors.errors; //genero lista de errores
            }
            $.each(me.formErrors.errors, function (key, value) {
              $('.result-' + key).html(value);
            });
            toastr.error("Porfavor completa los campos obligatorios.", {
              timeOut: 50000
            });
          });
        }
      })
    },
    deleteDocument(id) {
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
            if (response.data.code === 200) {
              refreshTable('tbl-documento2');
              toastr.success('Documento eliminado exitosamente.');
              toastr.options.closeButton = true;
            } else {
              toastr.warning('Atención! ha ocurrido un error.');
            }
          }).catch(function (error) {
            console.log(error);
            toastr.warning('Error.' + error);
            toastr.options.closeButton = true;
          });
        }
      });
    },
  },
});
$(document).ready(function() {
  loadTable('tbl-tracking', false);
  loadTable('tbl-tracking-bodega', true);
  loadTableCreateReceipt();
});

function loadTable(name, bodega) {
  var table = $('#' + name).DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: 'tracking/all/'+ true + '/' + false + '/'+ false + '/'+ false + '/' + bodega,
      columns: [{
          data: "fecha",
          name: 'fecha'
      }, {
          data: "cliente",
          name: 'cliente'
      }, {
          data: "cliente_email",
          name: 'cliente_email'
      },{
          data: "codigo",
          name: 'codigo'
      }, {
        "render": function(data, type, full, meta) {
          return '<div>'+ ((full.num_warehouse === null) ? '' : full.num_warehouse) +'</div><small style="color:#2196F3">'+ ((full.estatus === null) ? '' : full.estatus) +'</small>'
        }
      }, {
          data: "contenido",
          name: 'contenido'
      },
      // {
      //     sortable: false,
      //     "render": function(data, type, full, meta) {
      //         var color = '#ccc';
      //         var label = 'Sin acción';
      //         if (full.confirmed_send == 1) {
      //             color = '#4caf50';
      //             label = 'Despachar';
      //         }
      //         return '<div style="color:' + color + '" class="text-center" data-toggle="tooltip" title="' + label + '"><i class="fa fa-flag"></i></div>';
      //     }
      // },
      {
          sortable: false,
          "render": function(data, type, full, meta) {
              var btn_delete = '';
              if (permission_delete) {
                  var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
              }
              return btn_delete;
          }
      }],
  });
}

function loadTableCreateReceipt() {
  var table = $('#tbl-tracking-group').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: 'tracking/getTrackingByCreateReceipt/',
      columns: [{
          data: "cliente",
          name: 'cliente'
      }, {
          sortable: false,
          class: 'text-center',
          "render": function(data, type, full, meta) {
              return '<label class="badge badge-success">'+full.cantidad+'</label>';
          }
      },
      {
          sortable: false,
          "render": function(data, type, full, meta) {
              return " <a onclick=\"showDataToCreateReceipt(" + full.consignee_id + ", '" + full.cliente + "')\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Crear recibo'><i class='far fa-file-signature'></i></a> ";
          }
      }],
  });
}

function showDataToCreateReceipt(consignee_id, client) {
  if ($.fn.DataTable.isDataTable('#tbl-trackings-client')) {
      $('#tbl-trackings-client' + ' tbody').empty();
      $('#tbl-trackings-client').dataTable().fnDestroy();
  }
  var table = $('#tbl-trackings-client').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      "paging": false,
      ajax: 'tracking/getTrackingByIdConsignee/' + consignee_id,
      columns: [{
          "render": function (data, type, full, meta) {
          return '<div class="checkbox checkbox-success"><input type="checkbox" checked="true" data-contenido="'+ full.contenido +'" id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '"></label></div>';
        }
      }, {
        data: "codigo",
        name: 'codigo'
      },
      {
        data: "contenido",
        name: 'contenido'
      }],
      "drawCallback": function () {
        // $('#chk' + field).attr('checked', true);
      }
  });
  objVue.consignee_id_doc = consignee_id;
  $('#client-tracking').html(client);
  $('#modalCreateReceipt').modal('show');
}

var objVue = new Vue({
    el: '#tracking',
    created: function() {
        this.getConsignee();
        this.getShipper();
        /* CUSTOM MESSAGES VE-VALIDATOR*/
        const dict = {
            custom: {
                // consignee_id: {
                //     required: 'El cliente es obligatorio.'
                // },
                tracking: {
                    required: 'El tracking es obligatorio.'
                }
            }
        };
        this.$validator.localize('es', dict);
    },
    data: {
        consignee_id_doc: null,
        consignee_id: null,
        shipper_id: null,
        contenido: null,
        tracking: null,
        email: null,
        peso: null,
        piezas: 1,
        largo: 0,
        ancho: 0,
        alto: 0,
        instruccion: false,
        confirmedSend: false,
        editar: 0,
        consignees: [],
        shippers: [],
        ids_tracking: [],
        contenido_tracking: [],
        contenido_detail: null,
    },
    methods: {
        resetForm: function() {
            this.consignee_id = null;
            this.tracking = null;
            this.contenido = null;
            this.email = null;
            this.instruccion = false;
            this.confirmedSend = false;
            this.editar = 0;
        },
        createDocument: function(){
          let me = this;
          axios.post('documento/ajaxCreate/' + 1, {
              'tipo_documento_id': 1,
              'type_id': 1, //COURIER
              'created_at': this.getTime(),
              'shipper_id': (me.shipper_id.id) ? me.shipper_id.id : false,
              'consignee_id': me.consignee_id_doc,
          }).then(function(response) {
              var res = response.data;
              if (response.data['code'] == 200) {
                  toastr.success('Registro creado correctamente.');
                  me.createDocumentDetail(res.datos['id']);
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
        },
        createDocumentDetail: function(id_document) {
          let me = this;
          var datos = $("#formTrackingClient").serializeArray();
          me.ids_tracking = [];
          me.contenido_tracking = [];
          $.each(datos, function(i, field) {
              if (field.name === 'chk[]') {
                if($('#chk' + field.value).val() != ''){
                  me.ids_tracking.push($('#chk' + field.value).val());
                  me.contenido_tracking.push($('#chk' + field.value).data('contenido'));
                }
              }
          });
          if(me.contenido_tracking.length > 0){
            me.contenido_detail = me.contenido_tracking.toString();
          }
          axios.post('documento/insertDetail', {
            'documento_id': id_document,
            'contador': 1,
            'dimensiones': me.peso + ' Vol='+me.largo+'x'+me.ancho+'x'+me.alto,
            'peso': me.peso,
            'peso2': me.peso,
            'contenido': me.contenido_detail,
            'contenido2': me.contenido_detail,
            'largo': me.largo,
            'ancho': me.ancho,
            'alto': me.alto,
            'volumen': (me.largo * me.ancho * me.alto / 166).toFixed(2),
            'tipo_empaque_id': 3,
            'posicion_arancelaria_id': 234,
            'arancel_id2': 234,
            'created_at': this.getTime(),
            'ids_tracking': this.ids_tracking
          }).then(function(response) {
            var res = response.data;
            if (response.data['code'] == 200) {
              toastr.success('Registro creado correctamente.');
              me.updateTable();
            } else {
              toastr.warning(response.data['error']);
            }
          }).catch(function(error) {
            console.log(error);
            toastr.error("Error.", {
              timeOut: 50000
            });
          });
        },
        searchTracking: function() {
            let me = this;
            axios.get('tracking/searchTracking/' + me.tracking).then(response => {
                var datos = response.data;
                if (datos.data != null) {
                    if (datos.data['consignee_id']) {
                        me.consignee_id = {
                            id: datos.data['consignee_id'],
                            name: datos.data['nombre_full']
                        };
                    } else {
                        me.consignee_id = null;
                    }
                    me.contenido = datos.data['contenido'];
                    me.instruccion = datos.data['instruccion'];
                    me.email = datos.data['correo'];
                    me.confirmedSend = (datos.data['despachar'] == 1) ? true : false;
                } else {
                    me.create();
                    // this.instruccion = false;
                    // this.contenido = null;
                    // this.email = null;
                    // this.consignee_id = null;
                    // this.confirmedSend = false;
                }
            });
        },
        getConsignee: function() {
            let me = this;
            axios.get('tracking/getAllShipperConsignee/consignee').then(response => {
                me.consignees = response.data.data;
            });
        },
        getShipper: function() {
            let me = this;
            axios.get('tracking/getAllShipperConsignee/shipper').then(response => {
                me.shippers = response.data.data;
            });
        },
        updateTable: function() {
            refreshTable('tbl-tracking');
            refreshTable('tbl-tracking-bodega');
            refreshTable('tbl-tracking-group');
        },
        delete: function(data) {
            swal({
                title: 'Seguro que desea eliminar este registro?',
                text: "No lo podras recuperar despues!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No, cancelar!'
            }).then((result) => {
                if (result.value) {
                    axios.delete('tracking/' + data.id).then(response => {
                        this.updateTable();
                        toastr.success('Registro eliminado correctamente.');
                        toastr.options.closeButton = true;
                    });
                }
            });
        },
        create: function() {
            const isUnique = (value) => {
                return axios.post('tracking/validar_tracking', {
                    'element': value
                }).then((response) => {
                    return {
                        valid: response.data.valid,
                        data: {
                            message: response.data.message
                        }
                    };
                });
            };
            // The messages getter may also accept a third parameter that includes the data we returned earlier.
            this.$validator.extend('unique', {
                validate: isUnique,
                getMessage: (field, params, data) => {
                    return data.message;
                }
            });
            let me = this;
            this.$validator.validateAll().then((result) => {
                if (result) {
                    axios.post('tracking', {
                        'consignee_id': (this.consignee_id != null) ? this.consignee_id.id : null,
                        'codigo': this.tracking,
                        'contenido': this.contenido,
                        'confirmed_send': this.confirmedSend,
                    }).then(function(response) {
                        if (response.data['code'] == 200) {
                            toastr.success('Registro creado correctamente.');
                            toastr.options.closeButton = true;
                        } else {
                            toastr.warning(response.data['error']);
                            toastr.options.closeButton = true;
                        }
                        me.resetForm();
                        me.updateTable();
                    }).catch(function(error) {
                        console.log(error);
                        toastr.warning("Error: porfavor veifica la informacion ingresada.", {
                            timeOut: 50000
                        });
                    });
                }
            }).catch(function(error) {
                toastr.warning('Error: ' + error);
            });
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});

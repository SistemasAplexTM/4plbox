$(document).ready(function() {
  $.fn.editable.defaults.mode = 'inline';
  $.fn.editable.defaults.params = function(params) {
      params._token = $('meta[name="csrf-token"]').attr('content');
      return params;
  };
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
        },
        "visible": bodega
      }, {
          "render": function(data, type, full, meta) {
            return '<a data-name="contenido" data-pk="'+full.id+'" class="td_edit" data-type="text" data-placement="right" data-title="Contenido">'+ ((full.contenido !== null) ? full.contenido : 'No hay datos') +'</a>';
          }
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
                  var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='text-danger' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
              }
              return btn_delete;
          }
      }],
      "drawCallback": function () {
        $(".td_edit").editable({
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            url: "tracking/updateTrackingReceipt",
            validate:function(value){
                if($.trim(value) == ''){
                    return 'Este campo es obligatorio!';
                }
            },
            success: function(response, newValue) {
                objVue.updateTable();
            }
        });
      },
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
              return '<label class="badge badge-success" style="font-size: 15px;">'+full.cantidad+'</label>';
          }
      },
      {
          sortable: false,
          "render": function(data, type, full, meta) {
              return " <a onclick=\"showDataToCreateReceipt(" + full.consignee_id + ", '" + full.cliente + "')\" class='btn btn-outline btn-success btn-xs'><i class='far fa-file-signature'></i> Crear recibo</a> ";
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
        "render": function(data, type, full, meta) {
          return '<a data-name="contenido" data-pk="'+full.id+'" class="td_edit" data-type="text" data-placement="right" data-title="Contenido">'+ ((full.contenido !== null) ? full.contenido : 'No hay datos') +'</a>';
        }
      }],
      "drawCallback": function () {
        $(".td_edit").editable({
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            url: "tracking/updateTrackingReceipt",
            validate:function(value){
                if($.trim(value) == ''){
                    return 'Este campo es obligatorio!';
                }
            },
            success: function(response, newValue) {
                objVue.updateTable();
                refreshTable('tbl-trackings-client');
            }
        });
      }
  });
  objVue.consignee_id_doc = consignee_id;
  $('#client-tracking').html(client.toUpperCase());
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
        validation: function(data) {
          if(data.contenido != ''){
            if(data.peso != '' && parseFloat(data.peso) > 0){
              if(data.shipper != null){
                return true;
              }else{
                toastr.options = {"positionClass": "toast-top-center"}
                toastr.error('Selecciona un shippper para continuar.');
                return false;
              }
            }else{
              toastr.options = {"positionClass": "toast-top-center"}
              toastr.error('Ingresa el peso del paquete.');
              return false;
            }
          }else{
            toastr.options = {"positionClass": "toast-top-center"}
            toastr.error('Ingresa el contenido en alguno de los registros de tracking seleccionados.');
            return false;
          }
        },
        createDocument: function(){
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
            var myStr = me.contenido_tracking.toString();
            me.contenido_detail = myStr.replace(/,/g, ', ');
          }
          var datosForm = {
            'contenido': me.contenido_detail,
            'peso': me.peso,
            'shipper': me.shipper_id
          }
          if(me.validation(datosForm)){
            axios.post('documento/ajaxCreate/' + 1, {
                'tipo_documento_id': 1,
                'type_id': 1, //COURIER
                'created_at': this.getTime(),
                'shipper_id': (me.shipper_id.id) ? me.shipper_id.id : false,
                'consignee_id': me.consignee_id_doc,
            }).then(function(response) {
                var res = response.data;
                if (response.data['code'] == 200) {
                    toastr.success('Registro creado correctamente. Recibo N°: ' + res.datos['num_warehouse']);
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
          }
        },
        createDocumentDetail: function(id_document) {
          let me = this;
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
            'ids_tracking': this.ids_tracking,
            'shipper_id': (me.shipper_id.id) ? me.shipper_id.id : null,
            'consignee_id': me.consignee_id_doc,
          }).then(function(response) {
            var res = response.data;
            if (response.data['code'] == 200) {
              $('#modalCreateReceipt').modal('hide');
              // toastr.success('Registro creado correctamente.');
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
                    if(me.instruccion !== null || me.confirmedSend){
                      $('.alert-dismissible').removeClass('alert-info').addClass('alert-danger');
                    }else{
                      $('.alert-dismissible').removeClass('alert-danger').addClass('alert-info');
                    }
                } else {
                    me.create();
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

var objVue = new Vue({
    el: '#documento',
    watch:{
        emailD:function(value){
            if(value != null && value != ''){
                this.enviarEmailDestinatario = true;
            }else{
                this.enviarEmailDestinatario = false;
            }
        },
        city_s:function(value){
         if (Object.keys(value).length === 0) {
           $('#msn_l1').css('display', 'inline-block');
         }else{
           $('#msn_l1').css('display', 'none');
         }
       },
       city_c:function(value){
         var puntos = null;
         if(puntos_config != null){
           puntos = JSON.parse(puntos_config);
         }
         if(puntos != null){
           if(objVue.city_c.pais_id == puntos.pais_id){
             objVue.show_btn_products = true;
           }else{
             objVue.show_btn_products = false;
           }
         }
         setTimeout(() => {
           if (Object.keys(value).length === 0) {
             $('#msn_l2').css('display', 'inline-block');
           }else{
             $('#msn_l2').css('display', 'none');
           }
         }, 2000);
       }
    },
    mounted: function() {
        $('#date').val(this.getTime());
        if (!this.mostrar.includes(24)) {
          this.searchShipperConsignee($('#shipper_id').val(), 'shipper');
          this.searchShipperConsignee($('#consignee_id').val(), 'consignee');
        }
        this.getSelectCity();
    },
    created: function() {
        this.liquidado = $('#document_type').data('liquidado');
        this.showHiddeFields();
        /* CUSTOM MESSAGES VE-VALIDATOR*/
        const dict = {
            custom: {
                nombreR: {
                    required: 'El nombre es obligatorio.'
                },
                direccionR: {
                    required: 'La direccion es obligatorio.'
                },
                nombreD: {
                    required: 'El nombre es obligatorio.'
                },
                direccionD: {
                    required: 'La direccion es obligatorio.'
                },
            }
        };
        this.$validator.localize('es', dict);
    },
    data: {
        citys: [],
        citys_c: [],
        mostrar: {},
        document_type: '',
        liquidado: null,
        emailD: null,
        nombreR: null,
        nombreD: null,
        direccionR: null,
        direccionD: null,
        showmodalAdd: false,
        showFieldsTotals: false,
        enviarEmailDestinatario: false,
        city_s: {},	        // localizacion_id: null,
        city_c: {},	        // localizacion_id_c: null,
        city_selected_s: null,
        city_selected_c: null,
        disabled_s: false,
        disabled_c: false,
        // localizacion_id: null,
        // localizacion_id_c: null,
        contactos: {}, //es para poder elegir los contactos de shippero o consignee en la modal de consolidado
        restoreShipperConsignee: {}, //es para poder restaurar los contactos de shippero o consignee en el detalle del consolidado
        datosAgrupar: {}, //es para poder agrupar guias en el consolidado
        removerAgrupado: {}, //es para poder remover guias agrupadas en el consolidado
        permissions: {}, //es para poder pasar los permisos al consolidado
        refreshBoxes: false, //variable para refrescar las cajas del consolidado bodega
        cantidad_detalle: true, //para mostrar u ocultar el boton de agregar (funcionalidad para courier)
        pais_id_config: 0, //para validar si muestro guia o warehouse en el consolidado
        tracking_number: null,
        id_detalle: null,
        close: false,
        ids_tracking: [],
        contenido_tracking: [],
        points_id_detail: null,
        show_btn_products: false,
        total_points: 0,
        data_points: [],
        disabled_client: false, //desabilita el boton de agregar en el detalle si el cliente es jyg
        loading_save_ship:false,
        loading_save_cons:false,
        dataSelectShipper: [],
        dataSelectConsignee: [],
        detail_edit_id : null,
        shipper: [],
        consignee: [],
        shipper_id: null,
        consignee_id: null,
        saveOption: null,
        showPay: false,
        tipo_pago_id: null,
        forma_pago_id: null,
        paymentMethod: [],
        payments: [],
    },
    methods: {
      saveChange(op){
        if (op === 'shipper') {
          this.loading_save_ship = true;
          axios.get('/documento/updateShipperConsignee/' + this.detail_edit_id + '/' + this.shipper_id + '/shipper').then(response => {
            if (response.data['code'] == 200) {
                toastr.success('Actualizacion exitosa');
                toastr.options.closeButton = true;
            } else {
                toastr.warning(response.data['error']);
                toastr.options.closeButton = true;
            }
            this.loading_save_ship = false;
          });
        }else{
          this.loading_save_cons = true;
          axios.get('/documento/updateShipperConsignee/' + this.detail_edit_id + '/' + this.consignee_id + '/consignee').then(response => {
            if (response.data['code'] == 200) {
                toastr.success('Actualizacion exitosa');
                toastr.options.closeButton = true;
            } else {
                toastr.warning(response.data['error']);
                toastr.options.closeButton = true;
            }
            this.loading_save_cons = false;
          });
        }
      },
      setDataShipperConsignee(data, op){
        if(op){
          this.shipper = data;
          this.shipper_id = data.id;
        }else{
          this.consignee = data;
          this.consignee_id = data.id;
        }
        console.log('data: ',data);
      },
      changueShipperConsigneeDetail(id, shipper_id, consignee_id){
        this.detail_edit_id = id;
        this.shipper_id = shipper_id;
        this.consignee_id = consignee_id;

        $('#modalChangeShipperConsignee').modal('show');
        if(this.dataSelectShipper.length == 0){
          // axios.get('/shipper/all').then(response => {
          //   this.dataSelectShipper = response.data.data;
          // });
          // axios.get('/consignee/all').then(response => {
          //   this.dataSelectConsignee = response.data.data;
          // });
        }
      },
      getProductsCuba(data){
        let me = this;
        me.data_points = data;
        me.total_points = 0;
        var cont = '';
        for (var i = 0; i < data.length; i++) {
          me.total_points += data[i].total_puntos;
          if(i === 0){
            cont = data[i].articulo;
          }else{
            cont = cont + ', ' + data[i].articulo;
          }
        }
        $('#contiene').val(cont);
      },
      modalSearchProducts(){
        $('#modalAddPointsToDetail').modal('show');
      },
      setCity(data, option){
          if(option){
            this.city_s = data;
            $('#localizacion_id').val(data.id);
          }else{
            this.city_c = data;
            $('#localizacion_id_c').val(data.id);
            refreshTable('whgTable');
          }
        },
      addTrackingsToDocument: function(){
        let me = this;
        var datos = $("#formSearchTracking").serializeArray();
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
              $('#contiene').val(me.contenido_tracking.toString());
            }
      },
      modalSearchTracking: function() {
        let me = this;
        if ($.fn.DataTable.isDataTable('#tbl-trackings')) {
            $('#tbl-trackings' + ' tbody').empty();
            $('#tbl-trackings').dataTable().fnDestroy();
        }
        var table = $('#tbl-trackings').DataTable({
            ajax: '../../tracking/all/' + false + '/' + $('#consignee_id').val(),
            columns: [{
                "render": function (data, type, full, meta) {
                return '<div class="checkbox checkbox-success"><input type="checkbox" data-numguia="' + full.codigo + '" data-contenido="' + full.contenido + '" id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '"></label></div>';
              }
            }, {
                data: "codigo",
                name: 'codigo'
            }, {
                data: "contenido",
                name: 'contenido'
            }],
            'columnDefs': [{
                className: "text-center",
                "targets": [0],
            }],
            "drawCallback": function () {
              if(me.ids_tracking.length > 0){
                // setTimeout(function(){
                  $.each(me.ids_tracking, function(i, field) {
                    $('#chk' + field).attr('checked', true);
                  });
                // }, 2000);
              }
            }
        });
        $('#modalTrackingsAdd').modal('show');
      },
      closeDocument: function() {
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
                axios.get('./closeDocument').then(response => {
                  me.close = true;
                  toastr.success("Documento cerrado exitosamente.");
                });
            }
        });
      },
      refreshTableDetail: function(){
          var table = $('#whgTable').DataTable();
          table.ajax.reload();
      },
      totalizeDocument: function(){
          setTimeout(function(){
              totalizeDocument();
          },500);
      },
      showTotals(value) {
          this.showFieldsTotals = value;
          // if(value){
          //   if(!this.mostrar.includes(16)){
          //     this.mostrar.push(16);
          //   }
          // }else{
          //   this.mostrar.pop();
          // }
          // this.refreshTableDetail();
      },
      addTrackings(id) {
          this.id_detalle = id;
              /* TBL-TRACKING-USED */
              if ($.fn.DataTable.isDataTable('#tbl-trackings-used')) {
                  $('#tbl-trackings-used' + ' tbody').empty();
                  $('#tbl-trackings-used').dataTable().fnDestroy();
              }
              var table = $('#tbl-trackings-used').DataTable({
                  ajax: '../../tracking/all/' + false + '/' + null + '/' + id + '/' + true,
                  columns: [{
                      data: "codigo",
                      name: 'codigo'
                  }, {
                      data: "contenido",
                      name: 'contenido'
                  }, {
                      sortable: false,
                      "render": function(data, type, full, meta) {
                          var btn_delete = '';
                          btn_delete = '<a class="btn btn-danger btn-xs" type="button" id="btn_remove_t'+full.id+'" onclick="addTrackingToDocument(\''+full.codigo+'\', \'delete\')" data-toggle="tooltip" title="Retirar"><i class="fa fa-times"></i></a> ';
                          return btn_delete;
                      }
                  }],
                  'columnDefs': [{
                      className: "text-center",
                      "targets": [0],
                  }]
              });
              $('#modalTrackingsAdd2').modal('show');
      },
      addTrackingToDocument(option, codigo) {
          let me = this;
          $('#window-load').show();
          axios.post('../../tracking/addOrDeleteDocument', {
              'option': option,
              'tracking': (codigo) ? codigo : me.tracking_number,
              'id_detail': me.id_detalle,
              'consignee_id': $('#consignee_id').val(),
          }).then(response => {
              if(response.data.code == 200){
                  me.tracking_number = null;
                  me.addTrackings(me.id_detalle)
                  refreshTable('whgTable');
                  toastr.success(response.data.message);
                  toastr.options.closeButton = true;
                  $('#window-load').hide();
              }else{
                  if(response.data.code == 700){
                      me.createTracking();
                  }else{
                      toastr.warning('Error: -' + response.data.error);
                      $('#window-load').hide();
                  }
              }
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
              $('#window-load').hide();
          });
      },
      createTracking(){
          let me = this;
          axios.post('../../tracking', {
              'consignee_id': $('#consignee_id').val(),
              'codigo': me.tracking_number,
              'contenido': null,
              'confirmed_send': false,
          }).then(function(response) {
              if (response.data['code'] == 200) {
                  toastr.success('Registro creado correctamente.');
                  toastr.options.closeButton = true;
                  me.addTrackingToDocument('create');
              } else {
                  toastr.warning(response.data['error']);
                  toastr.options.closeButton = true;
                  $('#window-load').hide();
              }
          }).catch(function(error) {
              console.log(error);
              toastr.warning("Error: " + error, {
                  timeOut: 50000
              });
              $('#window-load').hide();
          });
      },
      /* FUNCION PARA ELIMINAR DETALLE DE CONSIOLIDADO */
      deleteDetailConsolidado: function(data) {
          let me = this;
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
                  axios.get('deleteDetailConsolidado/' + data.id + '/' + data.logical).then(response => {
                      toastr.success("Registro eliminado exitosamente.");
                      toastr.options.closeButton = true;
                      var table = $('#tbl-consolidado').DataTable();
                      table.ajax.reload();
                      me.refreshBoxes = !me.refreshBoxes;
                  });
              }
          });
      },
      showHiddeFields: function() {
          let me = this;
          var json = functionalities_doc;
          var arreglo = [];
          $.each(json, function(key, value) {
              arreglo.push(parseInt(value.id));
          });
          this.mostrar = arreglo;
          /* DEFINO QUE DOCUMENTO SE VA A IMPRIMIR */
          // if (arreglo.includes(22)) {
          if (this.liquidado == 0) {
              $('#printDocument').attr('href', '../../impresion-documento/' + $('#id_documento').val() + '/warehouse');
              $('#printLabel').attr('href', '../../impresion-documento-label/' + $('#id_documento').val() + '/warehouse');
              $('#invoice').hide();
              this.document_type = 'guia';
          }
          // if (arreglo.includes(23)) {
          if (this.liquidado == 1) {
              $('#invoice').show();
              $('#printDocument').attr('href', '../../impresion-documento/' + $('#id_documento').val() + '/guia');
              $('#printLabel').attr('href', '../../impresion-documento-label/' + $('#id_documento').val() + '/guia');
              $('#invoice').attr('href', '../../impresion-documento/' + $('#id_documento').val() + '/invoice');
              this.document_type = 'guia';
          }
          if (arreglo.includes(24)) {
              this.document_type = 'consolidado';
          }
          if ($('#show-totales').prop('checked') === true) {
              this.showFieldsTotals = true;
          }
          $('.form_doc').css('display', 'inline-block');
          setTimeout(function(){
              datatableDetail();
              permissions_f();
              if ($('#show-totales').prop('checked') === true) {
                  // me.getPositionById($('#pa_id').val());
                  llenarSelectServicio($('#tipo_embarque_id').val());
              }
          }, 500);
      },

      /* FUNCIONES ANTES DE GUARDAR */
      getAdminTable(op){
        return axios.get('/administracion/'+op+'/all').then(({data}) => {
          return data.data
        });
      },
      beforeSaveDocument(option){
        let me = this;
        me.saveOption = option;
        //obtener los metodos de pago
        me.getAdminTable(2).then(response => {
          me.paymentMethod = response;
          if($('#tipo_pago_id').val() != ''){
            me.tipo_pago_id = $('#tipo_pago_id').val()
            me.validatePayment(me.tipo_pago_id)
          }
        }).catch()
        $('#modalPaymentMethod').modal('show');
      },
      validatePayment(val){
        console.log('em');
        let me = this;
        $('#tipo_pago_id').val(val);
        me.getAdminTable(1).then(response => {
          me.payments = response;
          if($('#forma_pago_id').val() != ''){
            me.forma_pago_id = $('#forma_pago_id').val()
          }else{
            $('#forma_pago_id').val('');
          }
        }).catch()
        if(val == 4){
          me.showPay = false;
        }else{
          me.showPay = true;
        }
      },
      setPayment(val){
        $('#forma_pago_id').val(val);
      },
      saveDocument: function() {
        let me = this;
        let option = me.saveOption;
          $('#date').val(this.getTime());
          const isUnique = (value) => {
              if ($('#shipper_id').val() == '' || $('#shipper_id').val() == null) {
                  return axios.post('../../shipper/existEmail', {
                      'email': value,
                      'agencia_id': $('#agencia_id').val()
                  }).then((response) => {
                      return {
                          valid: response.data.valid,
                          data: {
                              message: response.data.message
                          }
                      };
                  });
              } else {
                  return {
                      valid: true,
                      data: {
                          message: ''
                      }
                  };
              }
          };
          // The messages getter may also accept a third parameter that includes the data we returned earlier.
          this.$validator.extend('unique_s', {
              validate: isUnique,
              getMessage: (field, params, data) => {
                  return data.message;
              }
          });
          const isUnique2 = (value) => {
              if ($('#consignee_id').val() == '' || $('#consignee_id').val() == null) {
                  return axios.post('../../consignee/existEmail', {
                      'email': value,
                      'agencia_id': $('#agencia_id').val()
                  }).then((response) => {
                      return {
                          valid: response.data.valid,
                          data: {
                              message: response.data.message
                          }
                      };
                  });
              } else {
                  return {
                      valid: true,
                      data: {
                          message: ''
                      }
                  };
              }
          };
          // The messages getter may also accept a third parameter that includes the data we returned earlier.
          this.$validator.extend('unique_c', {
              validate: isUnique2,
              getMessage: (field, params, data) => {
                  return data.message;
              }
          });
          this.$validator.validateAll().then((result) => {
              var msn = '';
              if (Object.keys(me.city_s).length === 0) {
                  $('#msn_l1').css('display', 'inline-block');
                  result = false;
                  msn = ' - Ciudad shipper';
              }
              if (Object.keys(me.city_c).length === 0) {
                  $('#msn_l2').css('display', 'inline-block');
                  result = false;
                  msn = ' - Ciudad consignee';
              }
              if ($('#show-totales').prop('checked') == true) {
                  if ($('#tipo_embarque_id').val() == null || $('#tipo_embarque_id').val() == '') {
                      $('#tipo_embarque_id').parent().addClass('has-error');
                      $('#tipo_embarque_id').siblings('small').css('display', 'inline-block');
                      result = false;
                      msn = ' - Tipo embarque';
                  }
                  if ($('#servicios_id').val() == null || $('#servicios_id').val() == '') {
                      $('#servicios_id').parent().addClass('has-error');
                      $('#servicios_id').siblings('small').css('display', 'inline-block');
                      result = false;
                      msn = ' - Servicios';
                  }
              }
              if (result) {
                  $('#option').val(option);
                  $('#formDocumento').submit();
              } else {
                  toastr.warning("Error. Porfavor verifica los datos ingresados.<br><br>" + msn);
              }
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
          });
      },
      getPositionById: function(id) {
          axios.get('../../arancel/getPositionById/' + id).then(response => {
              $('#pa').val(response.data['pa']);
              $('#arancel').val(response.data['arancel']);
              $('#iva').val(response.data['iva']);
          });
      },
      editFormsShipperConsignee: function(op) {
        let me = this;
          if (op == 1) {
              if ($('#opEditarCons').is(':checked')) {
                  $('#opEditarCons').prop('checked', false);
                  $('#msnEditarCons').css('display', 'none');
                  $('#nombreD').attr('readonly', true);
                  $('#direccionD').attr('readonly', true);
                  $('#emailD').attr('readonly', true);
                  $('#telD').attr('readonly', true);
                   me.disabled_c = true;
                  $('#zipD').attr('readonly', true);
                  $('#btnBuscarConsignee').attr('readonly', false);
              } else {
                  $('#opEditarCons').prop('checked', true);
                  $('#msnEditarCons').css('display', 'inline-block');
                  $('#nombreD').attr('readonly', false);
                  $('#direccionD').attr('readonly', false);
                  $('#emailD').attr('readonly', false);
                  $('#telD').attr('readonly', false);
                  me.disabled_c = false;
                  $('#zipD').attr('readonly', false);
                  $('#btnBuscarConsignee').attr('readonly', true);
              }
          } else {
              if ($('#opEditarShip').is(':checked')) {
                  $('#opEditarShip').prop('checked', false);
                  $('#msnEditarShip').css('display', 'none');
                  $('#nombreR').attr('readonly', true);
                  $('#direccionR').attr('readonly', true);
                  $('#emailR').attr('readonly', true);
                  $('#telR').attr('readonly', true);
                  me.disabled_s = true;
                  $('#zipR').attr('readonly', true);
                  $('#btnBuscarShipper').attr('readonly', false);
              } else {
                  $('#opEditarShip').prop('checked', true);
                  $('#msnEditarShip').css('display', 'inline-block');
                  $('#nombreR').attr('readonly', false);
                  $('#direccionR').attr('readonly', false);
                  $('#emailR').attr('readonly', false);
                  $('#telR').attr('readonly', false);
                  me.disabled_s = false;
                  $('#zipR').attr('readonly', false);
                  $('#btnBuscarShipper').attr('readonly', true);
              }
          }
      },
      placeShipperConsignee: function(data, table) {
        let me = this;
        if (table === 'shipper') {
          me.nombreR = data['nombre_full'];
          me.direccionR = data['direccion'];
          $('#nombreR').attr('readonly', true);
          $('#direccionR').attr('readonly', true);
          $('#emailR').val(data['correo']).attr('readonly', true);
          $('#telR').val(data['telefono']).attr('readonly', true);
          $('#localizacion_id').val(data['ciudad_id']);
          me.city_selected_s = data['ciudad'];
          me.city_s = {
            'id': data['ciudad_id'],
            'name': data['ciudad'],
            'pais_id': data['pais_id'],
          }
          me.disabled_s = true;
          $('#zipR').val(data['zip']).attr('readonly', true);
        } else {
          me.nombreD = data['nombre_full'];
          me.direccionD = data['direccion'];
          $('#nombreD').attr('readonly', true);
          $('#direccionD').attr('readonly', true);
          $('#emailD').val(data['correo']).attr('readonly', true);
          me.emailD = data['correo'];
          $('#telD').val(data['telefono']).attr('readonly', true);
          $('#localizacion_id_c').val(data['ciudad_id']);
          me.city_selected_c = data['ciudad'];
          me.city_c = {
            'id': data['ciudad_id'],
            'name': data['ciudad'],
            'pais_id': data['pais_id'],
          }
          me.disabled_c = true;
          $('#zipD').val(data['zip']).attr('readonly', true);
          $('#pais_id_D').val(data['pais_id']);
        }
      },
      searchShipperConsignee: function(id, table, selected) {
          var me = this;
          if (id != '') {
              if (table === 'shipper') {
                if(selected){
                  axios.get('../../' + table + '/getDataById/' + id).then(response => {
                      data = response.data;
                      me.placeShipperConsignee(data, table);
                      $('#shipper_id').val(id);
                      $('#modalShipper').modal('hide');
                  });
                }else{
                  me.placeShipperConsignee(shipper_data, table);
                }
              } else {
                if(selected){
                  axios.get('../../' + table + '/getDataById/' + id).then(response => {
                      data = response.data;
                      me.placeShipperConsignee(data, table);
                      $('#consignee_id').val(id);
                      $('#modalConsignee').modal('hide');
                  });
                }else{
                  me.placeShipperConsignee(consignee_data, table);
                }
              }
          }
      },
      resetFormsShipperConsignee: function(op) {
        let me = this;
          if (op == 1) {
              $('#consignee_id').val('');
              $('#poBoxD').val('');
              this.nombreD = null;
              this.direccionD = null;
              $('#nombreD').attr('readonly', false);
              $('#direccionD').attr('readonly', false);
              $('#direccionD').attr('readonly', false);
              $('#emailD').val('').attr('readonly', false);
              this.emailD = null;
              $('#telD').val('').attr('readonly', false);
              me.disabled_c = false;
               me.city_selected_c = null;
               $('#localizacion_id_c').val('');
              $('#zipD').val('').attr('readonly', false);
              $('#btnBuscarConsignee').attr('readonly', false);
          } else {
              $('#shipper_id').val('');
              this.nombreR = null;
              this.direccionR = null;
              $('#nombreR').attr('readonly', false);
              $('#direccionR').attr('readonly', false);
              $('#emailR').val('').attr('readonly', false);
              $('#telR').val('').attr('readonly', false);
              me.disabled_s = false;
              me.city_selected_s = null;
              $('#localizacion_id').val('');
              $('#zipR').val('').attr('readonly', false);
              $('#btnBuscarShipper').attr('readonly', false);
          }
      },
      rollBackDelete: function(data) {
          var me = this;
          axios.get('../restaurar/' + data.id + '/documento_detalle').then(response => {
              toastr.success('Registro restaurado.');
              me.refreshTableDetail();
          });
      },
      delete: function(data) {
          var me = this;
          // console.log(data);
          if (data.logical === true) {
              axios.get('../delete/' + data.id + '/' + data.logical + '/documento_detalle').then(response => {
                  toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>", '', {
                      timeOut: 15000
                  });
                  toastr.options.closeButton = true;
                  me.refreshTableDetail();
              });
          } else {
            swal({
            title: "<div><span style='color: rgb(212, 103, 82);'>Atención!</span><br><div>¿Desea eliminar este registro?</div></div>",
            text: "NO SE PODRA RECUPERAR",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Si",
            cancelButtonText: "No, Cancelar!",
            }).then((result) => {
                if (result.value) {
                  axios.get('../delete/' + data.id + '/' + data.logical + '/documento_detalle').then(response => {
                      toastr.success('Registro eliminado correctamente.');
                      toastr.options.closeButton = true;
                      me.refreshTableDetail();
                  });
                }
            });
          }
      },
      modalShipper: function(data_search) {
          var me = this;
          var nom = null;
          var id_data = null;
          if ($('#consignee_id').val() == '') {
              $('#show-all').bootstrapToggle('disable');
          } else {
              $('#show-all').bootstrapToggle('enable');
          }
          if (data_search) {
              if ($('#consignee_id').val() != '') {
                  id_data = $('#consignee_id').val();
              }
          }
          $('#modalShipper').modal('show');
          if ($.fn.DataTable.isDataTable('#tbl-modalshipper')) {
              $('#tbl-modalshipper tbody').empty();
              $('#tbl-modalshipper').dataTable().fnDestroy();
          }
          if ($('#nombreR').val() != '') {
              nom = $('#nombreR').val();
          }
          var table = $('#tbl-modalshipper').DataTable({
              ajax: '../../shipper/all/' + nom + '/' + id_data + '/' + $('#agencia_id').val(),
              columns: [{
                  sortable: false,
                  "render": function(data, type, full, meta) {
                      var btn_selet = "<div class='text-center'><button onclick=\"selectShipperConsignee(" + full.id + ", 'shipper', true)\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'><i class='fal fa-check'></i></button></div> ";
                      return btn_selet;
                  }
              }, {
                  data: 'nombre_full',
                  name: 'shipper.nombre_full',
              }, {
                  data: 'telefono',
                  name: 'shipper.telefono',
              }, {
                  data: 'ciudad',
                  name: 'localizacion.nombre'
              }, {
                  data: 'correo',
                  name: 'shipper.correo',
              }, {
                  data: 'agencia',
                  name: 'agencia.descripcion',
                  searchable: false,
              }]
          });
      },
      modalConsignee: function(data_search) {
          var me = this;
          var nom = null;
          var id_data = null;
          if ($('#shipper_id').val() == '') {
              $('#show-all-c').bootstrapToggle('disable');
          } else {
              $('#show-all-c').bootstrapToggle('enable');
          }
          if (data_search) {
              if ($('#shipper_id').val() != '') {
                  id_data = $('#shipper_id').val();
              }
          }
          $('#modalConsignee').modal('show');
          if ($.fn.DataTable.isDataTable('#tbl-modalconsignee')) {
              $('#tbl-modalconsignee tbody').empty();
              $('#tbl-modalconsignee').dataTable().fnDestroy();

          }
          if ($('#nombreD').val() != '') {
              nom = $('#nombreD').val();
          }
          var table = $('#tbl-modalconsignee').DataTable({
              ajax: '../../consignee/all/' + nom + '/' + id_data + '/' + $('#agencia_id').val(),
              columns: [{
                  sortable: false,
                  "render": function(data, type, full, meta) {
                      var btn_selet = "<div class='text-center'><button onclick=\"selectShipperConsignee(" + full.id + ", 'consignee', true)\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'><i class='fal fa-check'></i></button></div> ";
                      return btn_selet;
                  }
              }, {
                  data: 'nombre_full',
                  name: 'consignee.nombre_full'
              }, {
                  data: 'telefono',
                  name: 'consignee.telefono'
              }, {
                  data: 'ciudad',
                  name: 'localizacion.nombre'
              }, {
                  data: 'correo',
                  name: 'consignee.correo'
              }, {
                  data: 'agencia',
                  name: 'agencia.descripcion',
                  searchable: false,
              }]
          });
      },
      modalArancel: function(id, table_) {
          let me = this;
          $('#modalArancel').modal('show');
          if ($('#tbl-modalArancel tbody').length > 0) {
              var table = $('#tbl-modalArancel').DataTable().ajax.reload();
          } else {
              var table = $('#tbl-modalArancel').DataTable({
                  ajax: '../../arancel/all',
                  columns: [{
                      data: 'pa',
                      name: 'pa'
                  }, {
                      data: 'descripcion',
                      name: 'descripcion'
                  }, {
                      data: 'iva',
                      name: 'iva',
                      "render": $.fn.dataTable.render.number(',', '.', 2, '% ')
                  }, {
                      data: 'arancel',
                      name: 'arancel',
                      "render": $.fn.dataTable.render.number(',', '.', 2, '% ')
                  }, ]
              });
          }

          $('#tbl-modalArancel tbody').on('click', 'tr', function() {
              var data = table.row(this).data();
              if(id){
                  /* SE EJECUTA ESTA FUNCION CUANDO LA MODAL SE ABRE DESDE EL CONSOLIDADO */
                  me.updatePADetailConsolidado(id, data['id'], table_);
                  $( "#tbl-modalArancel tbody" ).off('click', 'tr');
              }else{
                  $('#pa_id').val(data['id']);
                  $('#pa').val(data['pa']);
                  $('#arancel').val(data['arancel']);
                  $('#iva').val(data['iva']);
                  $('#modalArancel').modal('hide');
              }
          });
      },
      modalAdditionalCharges: function() {
          // this.showmodalAdd = false;
          if (!this.showmodalAdd) {
              this.showmodalAdd = true;
          }
          $('#modalCargosAdd').modal('show');
      },
      addDetail: function(tipo) {
          this.disabled_client = true;
          var id_documento = $('#id_documento').val();
          var consignee_id = $('#consignee_id').val();
          var shipper_id = $('#shipper_id').val();
          var peso = $('#peso').val();
          var largo = $('#largo').val();
          var alto = $('#alto').val();
          var ancho = $('#ancho').val();
          var tracking = $('#tracking').val();
          var tipoEmpaque = $('#tipo_empaque_id').val();
          var tipoEmpaqueText = $('#tipo_empaque_id option:selected').text();
          var paId = $('#pa_id').val();
          var pa = $('#pa').val();
          var contiene = $('#contiene').val();
          var valDeclarado = ($('#valDeclarado').val() != '') ? parseFloat($('#valDeclarado').val()) : '';
          var resArancel = 0;
          var resIva = 0;
          var piezas = $('#valPiezas').val();
          var cont = 1;
          /* insercion del detalle */
          var me = this;
          if(typeof tipo != 'undefined'){
              cont = piezas;
              piezas = 1;
          }
          var datos = {
            'peso': peso,
            'tipoEmpaque': tipoEmpaque,
            'contiene': contiene,
            'declarado': valDeclarado,
          }
          if(me.validationDetail(datos)){
          // for (var i = 0; i < cont; i++) {
              axios.post('../insertDetail', {
                  'documento_id': id_documento,
                  'tipo_empaque_id': tipoEmpaque,
                  'posicion_arancelaria_id': paId,
                  'arancel_id2': paId,
                  'consignee_id': consignee_id,
                  'shipper_id': shipper_id,
                  'dimensiones': peso + ' Vol=' + largo + 'x' + ancho + 'x' + alto,
                  'largo': largo,
                  'ancho': ancho,
                  'alto': alto,
                  'contenido': contiene,
                  'contenido2': contiene,
                  'tracking': tracking,
                  'volumen': (largo * ancho * alto / 166).toFixed(2),
                  'valor': valDeclarado,
                  'declarado2': valDeclarado,
                  'peso': peso,
                  'peso2': peso,
                  'piezas': piezas,
                  'created_at': this.getTime(),
                  'contador': parseInt(cont),
                  'ids_tracking': me.ids_tracking,
                  'points': me.data_points
              }).then(function(response) {
                  if (response.data['code'] == 200) {
                      toastr.success('Registro creado correctamente.');
                      toastr.options.closeButton = true;
                      me.data_points = [];
                  } else {
                      this.disabled_client = false;
                      toastr.warning(response.data['error']);
                      toastr.options.closeButton = true;
                  }
                  $('#valPiezas').val(1);
                  $('#peso').val('');
                  $('#largo').val(0);
                  $('#ancho').val(0);
                  $('#alto').val(0);
                  $('#tracking').tagsinput('removeAll');
                  $('#contiene').val('');
                  $('#valDeclarado').val('');

                  me.refreshTableDetail();
              }).catch(function(error) {
                this.disabled_client = false;
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
          // }
        }
      },
      validationDetail: function(datos) {
        let me = this;
        console.log(datos, me.showFieldsTotals);
        if (datos.peso !== '') {
          if(datos.tipoEmpaque !== ''){
            if(datos.contiene !== ''){
              if(!me.showFieldsTotals){
                return true;
              }else{
                if(datos.declarado !== ''){
                  return true;
                }else{
                  toastr.warning('Ingrese el DECLARADO de la carga.');
                  return false;
                }
              }
            }else{
              toastr.warning('Ingrese el CONTENIDO de la carga.');
              return false;
            }
          }else{
            toastr.warning('Seleccione un TIPO DE EMPAQUE.');
            return false;
          }
        }else{
          $('#peso').css({"transition": "ripple .4s ease-in"});
          toastr.warning('Ingresa el PESO para continuar.');
  //         transition-property: text-decoration;
  // transition-duration: 0.8s;
  // transition-timing-function: linear;
  // transition-delay: 0.2s;
          return false;
        }
      },
      editTableDetail: function(data) {
          $('#pesoD' + data.id).attr('readonly', false);
          $('#contiene' + data.id).attr('readonly', false);
          $('#btn_edit' + data.id).css('display', 'none');
          $('#btn_confirm' + data.id).css('display', 'inline-block');
          $('#valorDeclarado' + data.id).attr('readonly', false);
          /* quitar readonly al campo tracking */
          $(".table #fila" + data.id + " .bootstrap-tagsinput .tag").each(function() {
              $(this).addClass('label-primary').css('color', 'white');
              $(this).append('<span data-role="remove"></span>');
          });
          $(".table #fila" + data.id + " .bootstrap-tagsinput").children('input').attr('readonly', false);
      },
      saveTableDetail: function(data) {
          /* edicion del detalle */
          var me = this;
          axios.post('../editDetail', {
              'id': data.id,
              'shipper_id': $('#shipper_id').val(),
              'consignee_id': $('#consignee_id').val(),
              'posicion_arancelaria_id': $('#pa' + data.id).val(),
              'arancel_id2': $('#id_pa' + data.id).val(),
              'dimensiones': $('#dimensiones' + data.id).val(),
              'contenido': $('#contiene' + data.id).val(),
              'contenido2': $('#contiene' + data.id).val(),
              'tracking': $('#tracking' + data.id).val(),
              'valor': parseFloat($('#valorDeclarado' + data.id).val()),
              'declarado2': parseFloat($('#valorDeclarado' + data.id).val()),
              'peso': $('#pesoD' + data.id).val(),
              'peso2': $('#pesoD' + data.id).val(),
              'type_document': $('#document_type').val(),
          }).then(function(response) {
              if (response.data['code'] == 200) {
                  toastr.success('Registro editado correctamente.');
                  toastr.options.closeButton = true;
              } else {
                  toastr.warning(response.data['error']);
                  toastr.options.closeButton = true;
              }
              // me.resetFieldsDetail();
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
          $('#pesoD' + data.id).attr('readonly', true);
          $('#contiene' + data.id).attr('readonly', true);
          $('#btn_edit' + data.id).css('display', 'inline-block');
          $('#btn_confirm' + data.id).css('display', 'none');
          $('#valorDeclarado' + data.id).attr('readonly', true);
          /* poner readonly al campo tracking */
          $(".table .bootstrap-tagsinput .tag").each(function() {
              $(this).removeClass('label-primary').css('color', '#555');
              $(this).children('span').remove();
          });
          $('.table .bootstrap-tagsinput').children('input').attr('readonly', true);
      },
      updateDataDetailConsolidado: function(rowData) {
          var me = this;
          axios.put('updateDetailConsolidado', {
              rowData
          }).then(function(response) {
              toastr.success('Registro actualizado correctamente.');
              toastr.options.closeButton = true;
              var table = $('#tbl-consolidado').DataTable();
              table.ajax.reload();
              if (rowData.option == 'shipper') {
                  $('#modalShipper').modal('hide');
              } else {
                  $('#modalConsignee').modal('hide');
              }
          }).catch(function(error) {
              toastr.success('Error.');
              toastr.options.closeButton = true;
          });
      },
      updatePADetailConsolidado: function(id_detalle, id_pa, table_) {
          var me = this;
          var rowData = {
              id_detalle: id_detalle,
              id_pa: id_pa,
              tabla: table_,
          }
          axios.put('updatePositionArancel', {
              rowData
          }).then(function(response) {
              toastr.success('Registro actualizado correctamente.');
              toastr.options.closeButton = true;
              var table = $('#'+table_).DataTable();
              table.ajax.reload();
              $('#modalArancel').modal('hide');
          }).catch(function(error) {
              toastr.success('Error.');
              toastr.options.closeButton = true;
          });
      },
      getSelectCity(){
        var me = this;
        // axios.get('/ciudad/getSelectCity').then(function(response) {
            me.citys = citys_data;
        // }).catch(function(error) {
        //     console.log(error);
        //     toastr.warning('Error: -' + error);
        // });
      },
    }
});

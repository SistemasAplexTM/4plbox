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
    },
    mounted: function() {
        $('#date').val(this.getTime());
    },
    created: function() {
        this.liquidado = $('#document_type').data('liquidado');
        this.showHiddeFields();
        this.searchShipperConsignee($('#shipper_id').val(), 'shipper');
        this.searchShipperConsignee($('#consignee_id').val(), 'consignee');
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
    },
    methods: {
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
        saveDocument: function(option) {
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
                if ($('#localizacion_id').val() == null) {
                    $('#localizacion_id').parent().addClass('has-error');
                    $('#msn_l1').css('display', 'inline-block');
                    result = false;
                    msn = ' - Ciudad shipper';
                }
                if ($('#localizacion_id_c').val() == null) {
                    $('#localizacion_id_c').parent().addClass('has-error');
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
            if (op == 1) {
                if ($('#opEditarCons').is(':checked')) {
                    $('#opEditarCons').prop('checked', false);
                    $('#msnEditarCons').css('display', 'none');
                    $('#direccionD').attr('readonly', true);
                    $('#emailD').attr('readonly', true);
                    $('#telD').attr('readonly', true);
                    $('#localizacion_id_c').select2({'disabled': true});
                    $('#zipD').attr('readonly', true);
                    $('#btnBuscarConsignee').attr('readonly', false);
                } else {
                    $('#opEditarCons').prop('checked', true);
                    $('#msnEditarCons').css('display', 'inline-block');
                    $('#direccionD').attr('readonly', false);
                    $('#emailD').attr('readonly', false);
                    $('#telD').attr('readonly', false);
                    $('#localizacion_id_c').select2({'disabled': false});
                    llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id_c', 2);
                    $('#zipD').attr('readonly', false);
                    $('#btnBuscarConsignee').attr('readonly', true);
                }
            } else {
                if ($('#opEditarShip').is(':checked')) {
                    $('#opEditarShip').prop('checked', false);
                    $('#msnEditarShip').css('display', 'none');
                    $('#direccionR').attr('readonly', true);
                    $('#emailR').attr('readonly', true);
                    $('#telR').attr('readonly', true);
                    $('#localizacion_id').select2({'disabled': true});
                    $('#zipR').attr('readonly', true);
                    $('#btnBuscarShipper').attr('readonly', false);
                } else {
                    $('#opEditarShip').prop('checked', true);
                    $('#msnEditarShip').css('display', 'inline-block');
                    $('#direccionR').attr('readonly', false);
                    $('#emailR').attr('readonly', false);
                    $('#telR').attr('readonly', false);
                    $('#localizacion_id').select2({'disabled': false});
                    llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id', 2);
                    $('#zipR').attr('readonly', false);
                    $('#btnBuscarShipper').attr('readonly', true);
                }
            }
        },
        resetFormsShipperConsignee: function(op) {
            if (op == 1) {
                $('#consignee_id').val('');
                $('#poBoxD').val('');
                this.nombreD = null;
                this.direccionD = null;
                $('#direccionD').attr('readonly', false);
                $('#emailD').val('').attr('readonly', false);
                this.emailD = null;
                $('#telD').val('').attr('readonly', false);
                $('#localizacion_id_c').select2({'disabled': false});
                $('#localizacion_id_c').select2('destroy').empty();
                llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id_c', 2); // module, tableName, id_campo
                $('#zipD').val('').attr('readonly', false);
                $('#btnBuscarConsignee').attr('readonly', false);
            } else {
                $('#shipper_id').val('');
                this.nombreR = null;
                this.direccionR = null;
                $('#direccionR').attr('readonly', false);
                $('#emailR').val('').attr('readonly', false);
                $('#telR').val('').attr('readonly', false);
                $('#localizacion_id').select2({'disabled': false});
                $('#localizacion_id').select2('destroy').empty();
                llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
                $('#zipR').val('').attr('readonly', false);
                $('#btnBuscarShipper').attr('readonly', false);
            }
        },
        rollBackDelete: function(data) {
            var me = this;
            axios.get('../restaurar/' + data.id + '/documento_detalle').then(response => {
                toastr.success('Registro restaurado.');
                // refreshTable('whgTable');
                // me.totalizeDocument();
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
                    // refreshTable('whgTable');
                    // me.totalizeDocument();
                    // $('#fila' + data.id).remove();
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
                        // refreshTable('whgTable');
                        // me.totalizeDocument();
                        // $('#fila' + data.id).remove();
                    });
                  }
              });
            }
        },
        placeShipperConsignee: function(data, table) {
            let me = this;
            if (table === 'shipper') {
                me.nombreR = data['nombre_full'];
                me.direccionR = data['direccion'];
                $('#direccionR').attr('readonly', true);
                $('#emailR').val(data['correo']).attr('readonly', true);
                $('#telR').val(data['telefono']).attr('readonly', true);
                $('#localizacion_id').append('<option value="' + data['ciudad_id'] + '" selected="selected">' + data['ciudad'] + '</option>').val([data['ciudad_id']]).trigger('change');
                // $('#localizacion_id').select2({'disabled': true});
                $('#zipR').val(data['zip']).attr('readonly', true);
            } else {
                me.nombreD = data['nombre_full'];
                me.direccionD = data['direccion'];
                $('#direccionD').attr('readonly', true);
                $('#emailD').val(data['correo']).attr('readonly', true);
                me.emailD = data['correo'];
                $('#telD').val(data['telefono']).attr('readonly', true);
                $('#localizacion_id_c').append('<option value="' + data['ciudad_id'] + '" selected="selected">' + data['ciudad'] + '</option>').val([data['ciudad_id']]).trigger('change');
                // $('#localizacion_id_c').select2({'disabled': true});
                $('#zipD').val(data['zip']).attr('readonly', true);
                $('#pais_id_D').val(data['pais_id']);
            }
        },
        searchShipperConsignee: function(id, table) {
            var me = this;
            if (id != '') {
                if (table === 'shipper') {
                    axios.get('../../' + table + '/getDataById/' + id).then(response => {
                        data = response.data;
                        me.placeShipperConsignee(data, table);
                        $('#shipper_id').val(id);
                        $('#modalShipper').modal('hide');
                    });
                } else {
                    axios.get('../../' + table + '/getDataById/' + id).then(response => {
                        data = response.data;
                        me.placeShipperConsignee(data, table);
                        $('#consignee_id').val(id);
                        $('#modalConsignee').modal('hide');
                    });
                }
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
                        var btn_selet = "<button onclick=\"selectShipperConsignee(" + full.id + ", 'shipper')\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fa fa-check'></i></button> ";
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
                    data: 'zip',
                    name: 'shipper.zip',
                }, {
                    data: 'agencia',
                    name: 'agencia.descripcion'
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
                        var btn_selet = "<button onclick=\"selectShipperConsignee(" + full.id + ", 'consignee')\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fa fa-check'></i></button> ";
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
                    data: 'zip',
                    name: 'consignee.zip'
                }, {
                    data: 'agencia',
                    name: 'agencia.descripcion'
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
                    'ids_tracking': me.ids_tracking
                }).then(function(response) {
                    if (response.data['code'] == 200) {
                        toastr.success('Registro creado correctamente.');
                        toastr.options.closeButton = true;
                    } else {
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
                    // refreshTable('whgTable');

                    me.refreshTableDetail();
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
        onSearchCity(search, loading) {
            loading(true);
            this.searchCity(loading, search, this);
        },
        searchCity: _.debounce((loading, search, vm) => {
            fetch(`../vueSelectGeneral/localizacion/${escape(search)}`).then(res => {
                res.json().then(json => (vm.citys = json.items));
                loading(false);
            });
        }, 350),
        onSearchCityC(search, loading) {
            loading(true);
            this.searchCityC(loading, search, this);
        },
        searchCityC: _.debounce((loading, search, vm) => {
            fetch(`../vueSelectGeneral/localizacion/${escape(search)}`).then(res => {
                res.json().then(json => (vm.citys_c = json.items));
                loading(false);
            });
        }, 350),
    }
});

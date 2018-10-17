// @class address;
// @extends abstractinput;
// @final;
// @example;

$(document).ready(function() {
    $('#tracking').tagsinput();
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.params = function(params) {
        params._token = $('meta[name="csrf-token"]').attr('content');
        return params;
    };
    $('#agencia_id').on('change', function() {
        objVue.resetFormsShipperConsignee(0);
        objVue.resetFormsShipperConsignee(1);
    });
    /* LIMPIAR MODALES */
    $('#modalShipper').on('hidden.bs.modal', function() {
        var table = $('#tbl-modalshipper').DataTable();
        table.clear();
    });
    $('#modalConsignee').on('hidden.bs.modal', function() {
        var table = $('#tbl-modalconsignee').DataTable();
        table.clear();
    });
    $('#modalShipperConsigneeConsolidado').on('hidden.bs.modal', function() {
        objVue.contactos = {};
    });
    $('#modalagrupar').on('hidden.bs.modal', function() {
        var table = $('#tbl-modalagrupar').DataTable();
        table.clear();
    });
    if ($('#shipper_id').val() == '') {
        $('#show-all-c').bootstrapToggle('disable');
    }
    if ($('#consignee_id').val() == '') {
        $('#show-all').bootstrapToggle('disable');
    }
    $('.track_guia').tagsinput({
        tagClass: 'label label-primary'
    });
    $('.table .bootstrap-tagsinput').children('input').attr('readonly', true);
    /* poner readonly al campo tracking */
    $(".table .bootstrap-tagsinput .tag").each(function() {
        $(this).removeClass('label-info').css('color', '#555');
        $(this).children('span').remove();
    });
    llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    llenarSelectPersonalizado('documento', 'localizacion', 'localizacion_id_c', 2); // module, tableName, id_campo
    
});
$(function() {
    //aparecer botones de accion en las bolsas del consolidado
    jQuery('.list-group').
    on('mouseover', 'li', function() {
        jQuery(this).find('.boxEdit, .boxDelete').show();
    }).
    on('mouseout', 'li', function() {
        jQuery(this).find('.boxEdit, .boxDelete').hide();
    });

    jQuery('#tbl-consolidado').
    on('mouseover', 'tr', function() {
        jQuery(this).find('.edit, .delete').show();
    }).
    on('mouseout', 'tr', function() {
        jQuery(this).find('.edit, .delete').hide();
    });

    jQuery('#whgTable').
    on('mouseover', 'tr', function() {
        jQuery(this).find('.edit').show();
    }).
    on('mouseout', 'tr', function() {
        jQuery(this).find('.edit').hide();
    });

    $('#show-all-c').change(function() {
        if ($(this).prop('checked') === true) {
            objVue.modalConsignee(false);
        } else {
            objVue.modalConsignee($('#shipper_id').val());
        }
    });
    $('#show-all').change(function() {
        if ($(this).prop('checked') === true) {
            objVue.modalShipper(false);
        } else {
            objVue.modalShipper($('#consignee_id').val());
        }
    });
    $('#show-totales').change(function() {
        if ($(this).prop('checked') === true) {
            objVue.showTotals(true);
            setTimeout(function() {
                llenarSelectServicio($('#tipo_embarque_id').val());
            }, 500);
        } else {
            objVue.showTotals(false);
        }
    });
});

function datatableDetail(){
    $('#whgTable').DataTable({
        ajax: 'getDataDetailDocument',
        "paging":   false,
        "info":     false,
        "searching": false,
        columns: [{
            data: 'num_warehouse',
            name: 'num_warehouse'
        }, {
            "render": function (data, type, full, meta) {
                return '<a data-name="piezas" data-pk="'+full.id+'" data-value="'+full.piezas+'" class="td_edit" data-type="text" data-placement="right" data-title="Piezas">'+full.piezas+'</a>';
            }
        },  {
            "render": function (data, type, full, meta) {
                var cadena  = full.dimensiones;
                var dimensiones = cadena.split(" ");
                var arr1 = cadena.split("=");
                var arrF = arr1[1].split("x");
                return '<a data-name="peso" data-pk="'+full.id+'" class="td_edit" data-type="text" data-placement="right" data-title="Peso">'+full.peso+'</a> ' + 
                ' <a data-name="dimensiones" data-pk="'+full.id+'" data-value="'+arrF+'" class="td_edit_d" data-type="address" data-placement="right" data-title="Dimensiones">'+dimensiones[1]+'</a>';;
            }
        }, {
            "render": function (data, type, full, meta) {
                return '<a data-name="contenido" data-pk="'+full.id+'" data-value="'+full.contenido+'" class="td_edit" data-type="text" data-placement="right" data-title="Contenido">'+full.contenido+'</a>';
            }
        }, {
            "render": function (data, type, full, meta) {
                var pa = full.nom_pa;
                return pa + '<a  data-toggle="tooltip" title="Canbiar" class="edit" style="float:right;color:#FFC107;" onclick="showModalArancel('+full.id+', \'whgTable\')"><i class="material-icons">&#xE254;</i></a>';
            },
            visible: (objVue.mostrar.includes(16)) ? true : false
        }, 
        // {
        //     data: 'nom_pa',
        //     name: 'nom_pa',
        //     visible: (objVue.mostrar.includes(16)) ? true : false
        // }, 
        {
            "render": function (data, type, full, meta) {
                return '<a data-name="declarado" data-pk="'+full.id+'" class="td_edit" data-type="text" data-placement="left" data-title="Declarado">'+full.valor+'</a>';
            }
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_addTracking = '';
                var btn_edit = '';
                var btn_save = '';
                var btn_delete = '';
                var display = 'inline-block';
                if(full.consolidado == 1){
                    display = 'none';
                }

                btn_addTracking = '<a class="btn btn-info btn-xs btn-actions addTrackings" type="button" id="btn_addtracking'+full.id+'" data-toggle="tooltip" title="Agregar tracking" onclick="addTrackings('+full.id+')"><i class="fa fa-barcode"></i> <span id="cant_tracking'+full.id+'">'+full.cantidad+'</span></a> ';

                btn_save = '<a class="btn btn-primary btn-xs btn-actions" type="button" id="btn_confirm'+full.id+'" onclick="saveTableDetail('+full.id+')" data-toggle="tooltip" title="Guardar" style="display:none;"><i class="fa fa-check"></i></a> ';

                btn_edit = '<a class="btn btn-success btn-xs btn-actions" type="button" id="btn_edit'+full.id+'" onclick="editTableDetail('+full.id+')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></a> ';

                btn_delete = '<a class="btn btn-danger btn-xs btn-actions" type="button" id="btn_remove'+full.id+'" onclick="eliminar('+full.id+', true)" data-toggle="tooltip" title="Eliminar" style="display: '+display+'"><i class="fa fa-times"></i></a> ';

                return btn_addTracking + btn_delete;
            }
        }, {
            data: 'volumen',
            name: 'volumen',
            visible: false
        }, {
            data: 'piezas',
            name: 'piezas',
            visible: false
        }, {
            data: 'peso',
            name: 'peso',
            visible: false
        }, {
            data: 'valor',
            name: 'valor',
            visible: false
        },],
        "drawCallback": function () {
            /* EDITABLE FIELD */
            // if (me.permissions.editDetail) {
                $(".td_edit").editable({
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    url: "updateDetailDocument",
                    validate:function(value){
                        if($.trim(value) == ''){
                            return 'Este campo es obligatorio!';
                        }
                    },
                    success: function(response, newValue) {
                        refreshTable('whgTable');
                        objVue.totalizeDocument();
                    }
                });

                $('.td_edit_d').editable({
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    mode: 'popup',
                    url: 'updateDetailDocument',
                    validate:function(value){
                        if($.trim(value.largo) == '' || $.trim(value.ancho) == '' || $.trim(value.alto) == ''){
                            return 'Los campos no pueden ir vacios!';
                        }
                    },
                    success: function(response, newValue) {
                        refreshTable('whgTable');
                        objVue.totalizeDocument();
                    }
                });
            // }
        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            /*Remove the formatting to get integer data for summation*/
            var intVal = function (i) {
                return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
            };
            /*Total over all pages*/
            var vol = api
                    .column(7)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
            var piezas = api
                    .column(8)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
            var peso = api
                    .column(9)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
            var dec = api
                    .column(10)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

            /*Update footer formatCurrency()*/
            $('#piezas').val(parseFloat(isInteger(piezas)));
            $('#volumen').val(Math.ceil(parseFloat(isInteger(vol))));
            $('#pie_ft').val(Math.ceil(parseFloat(isInteger(vol * 166 / 1728))));
            $('#pesoDim').val(parseFloat(isInteger(peso)));
            $('#valor_declarado_tbl').val(parseFloat(isInteger(dec)));
        },
    }).on('xhr.dt', function ( e, settings, json, xhr ) {
        if(app_type === 'courier'){
            if(json.data.length === 0){
                objVue.cantidad_detalle = true;
            }else{
                objVue.cantidad_detalle = false;
            }
        }
        // console.log(json.data);
    });
}
function llenarSelectServicio(id_embarque) {
    var url = '../../servicios/getAllServiciosAgencia/' + id_embarque;
    var pa_id = 1;// POSICION ARANCELARIA POR DEFECTO
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        success: function(data) {
            /* llenar select */
            $("#servicios_id").empty();
            $('#impuesto').val(0);
            $('#valor_libra2').val(0);
            if (Object.keys(data.data).length === 0) {
                $("#servicios_id").attr('readonly', true);
            } else {
                $("#servicios_id").attr('readonly', false);
                $(data.data).each(function(index, value) {
                    $("#servicios_id").append('<option value="' + value.id + '" data-tarifa="' + value.tarifa + '" data-seguro="' + value.seguro + '"\n\
                        data-cobvol="' + value.cobro_peso_volumen + '"\n\
                         data-tarifamin="' + value.peso_minimo + '"\n\
                          data-tarifa="' + value.tarifa + '"\n\
                           data-seguro="' + value.seguro + '"\n\
                            data-c_opcional="' + value.cobro_opcional + '"\n\
                             data-t_age="' + value.tarifa_agencia + '"\n\
                              data-seg_age="' + value.seguro_agencia + '"\n\
                               data-impuesto_age="' + value.impuesto + '"\n\
                               data-pa_id="' + value.pa_id + '">' + value.nombre + '</option>');
                });
            }
            objVue.totalizeDocument();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            data = {
                error: jqXHR + ' - ' + textStatus + ' - ' + errorThrown
            }
            $('#modal' + 1).modal('toggle');
            $('body').append('<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title" id="myModalLabel">ERROR EN TRANSACCIÓN</h4></div><div class="modal-body">' + data.error + '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div></div></div>');
            $('#modalError').modal({
                show: true
            });
        },
        complete: function(){
            if($('#servicios_id option:selected').data('pa_id') != null){
                pa_id = $('#servicios_id option:selected').data('pa_id');
            }
            $('#pa_id').val(pa_id);
            objVue.getPositionById(pa_id);
        }
    });
}
/*-- Función para llenar select PERSONALIZADO --*/
function llenarSelectPersonalizado(module, tableName, idSelect, length) {
    var url = '../selectInput/' + tableName;
    $('#' + idSelect).select2({
        placeholder: "Seleccionar",
        tokenSeparators: [','],
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: false
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        minimumInputLength: length,
    }).on("change", function(e) {
        $('.select2-selection').css('border-color', '');
        $('#' + idSelect).siblings('small').css('display', 'none');
    });
}

function formatRepo(repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fa fa-map-marker'></i> " + repo.text + " / " + repo.deptos + " / " + repo.pais + "</strong></div>";
    return markup;
}

function formatRepoSelection(repo) {
    $('#deptoD').val(repo.deptos);
    $('#paisD').val(repo.pais);
    return repo.text || repo.id + ' - ' + repo.text;
}

function editTableDetail(id_fila) {
    var data = {
        id: id_fila
    };
    objVue.editTableDetail(data);
}

function saveTableDetail(id_fila) {
    var data = {
        id: id_fila
    };
    objVue.saveTableDetail(data);
}

function selectShipperConsignee(id, table) {
    objVue.searchShipperConsignee(id, table);
}

function eliminarConsolidado(id, logical) {
    var data = {
        id: id,
        logical: logical,
    };
    objVue.deleteDetailConsolidado(data);
}

function updateShipperConsigneeConsolidado(id, documento_detalle_id, option) {
    var data = {
        id: id,
        documento_detalle_id: documento_detalle_id,
        option: option,
    };
    objVue.updateDataDetailConsolidado(data);
}

function addTrackings(id) {
    objVue.addTrackings(id);
}

function restoreShipperConsignee(id, table) {
    objVue.restoreShipperConsignee = {
        id: id,
        table: table
    };;
}

function addTrackingToDocument(idTracking, idDocument) {
    var data = {
        idTracking: idTracking,
        idDocument: idDocument,
    }
    objVue.addTrackingToDocument(data);
}

function showModalShipperConsigneeConsolidado(id, idShipCons, opcion) {
    objVue.contactos = {
        id: id,
        idShipCons: idShipCons,
        opcion: opcion,
    };
}

function agruparGuias(id) {
    objVue.datosAgrupar = {
        id: id
    };
}

function removerGuiaAgrupada(id, id_guia_detalle) {
    objVue.removerAgrupado = {
        id: id,
        id_guia_detalle: id_guia_detalle,
    };
}

function showModalArancel(id, table) {
    objVue.modalArancel(id, table);
}

function permissions_f() {
    objVue.permissions = {
        deleteDetailConsolidado: permission_deleteDetailConsolidado,
        insertDetail:                       permission_insertDetail,
        editDetail:                         permission_editDetail,
        removerGuiaAgrupada:                permission_removerGuiaAgrupada,
        pdfContrato:                        permission_pdfContrato,
        pdfTsa:                             permission_pdfTsa,
        pdf:                                permission_pdf,
        pdfLabel:                           permission_pdfLabel
    };
}

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
    },
    methods: {
        totalizeDocument: function(){
            setTimeout(function(){
                totalizeDocument();
            },500);
        },
        showTotals(value) {
            this.showFieldsTotals = value;
        },
        addTrackings(id) {
            // if ($('#consignee_id').val() != '') {
                var consig_id = ($('#consignee_id').val() != '') ? $('#consignee_id').val() : null;
                if ($.fn.DataTable.isDataTable('#tbl-trackings')) {
                    $('#tbl-trackings' + ' tbody').empty();
                    $('#tbl-trackings').dataTable().fnDestroy();
                }
                var table = $('#tbl-trackings').DataTable({
                    ajax: '../../tracking/all/' + false + '/' + consig_id,
                    columns: [{
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var checked = '';
                            if (full.documento_detalle_id == id) {
                                checked = "checked='checked'"
                            }
                            return '<div class="checkbox checkbox-success" data-toggle="tooltip" title="Agregar"><input type="checkbox" ' + checked + ' id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '" onclick="addTrackingToDocument(' + full.id + ', ' + id + ')"></label></div>';
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
                        width: 10,
                    }]
                });
                /* TBL-TRACKING-USED */
                if ($.fn.DataTable.isDataTable('#tbl-trackings-used')) {
                    $('#tbl-trackings-used' + ' tbody').empty();
                    $('#tbl-trackings-used').dataTable().fnDestroy();
                }
                var table = $('#tbl-trackings-used').DataTable({
                    ajax: '../../tracking/all/' + false + '/' + null + '/' + id + '/' + true,
                    columns: [{
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var checked = '';
                            if (full.documento_detalle_id == id) {
                                checked = "checked='checked'"
                            }
                            if(full.consignee_id == null){
                                return null;
                            }else{
                                return '<div class="checkbox checkbox-danger" data-toggle="tooltip" title="Quitar"><input type="checkbox" ' + checked + ' id="chk' + full.id + '" name="chk[]" value="' + full.id + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + full.id + '" onclick="addTrackingToDocument(' + full.id + ', ' + id + ')"></label></div>';
                            }
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
                        width: 10,
                    }]
                });
                $('#modalTrackingsAdd').modal('show');
            // } else {
            //     swal('Atención!', 'Necesita seleccionar un Consignee para continuar.', 'warning');
            // }
        },
        addTrackingToDocument(data) {
            let me = this;
            if (!$('#chk' + data.idTracking).is(':checked')) {
                axios.post('../../tracking/addOrDeleteDocument', {
                    'option': 'create',
                    'id_tracking': data.idTracking,
                    'id_document': data.idDocument
                }).then(response => {
                    refreshTable('whgTable');
                    // var cant = $('#cant_tracking' + data.idDocument).html();
                    // $('#cant_tracking' + data.idDocument).html(parseInt(cant) + 1);
                    toastr.success('Tracking agregado a este documento.');
                    toastr.options.closeButton = true;
                });
            } else {
                axios.post('../../tracking/addOrDeleteDocument', {
                    'option': 'delete',
                    'id_tracking': data.idTracking,
                    'id_document': data.idDocument
                }).then(response => {
                    refreshTable('whgTable');
                    // var cant = $('#cant_tracking' + data.idDocument).html();
                    // $('#cant_tracking' + data.idDocument).html(parseInt(cant) - 1);
                    toastr.warning('Tracking retirado de este documento.');
                    toastr.options.closeButton = true;
                });
            }
            setTimeout(function() {
                me.addTrackings(data.idDocument);
            }, 200);
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
        saveDocument: function() {
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
                refreshTable('whgTable');
                me.totalizeDocument();
                // me.refreshTableDetail(response.data.datos);
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
                    refreshTable('whgTable');
                    me.totalizeDocument();
                    // $('#fila' + data.id).remove();
                });
            } else {
                axios.delete('../delete/' + data.id).then(response => {
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                    refreshTable('whgTable');
                    me.totalizeDocument();
                    // $('#fila' + data.id).remove();
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
            var valDeclarado = parseFloat($('#valDeclarado').val());
            var resArancel = 0;
            var resIva = 0;
            var piezas = $('#valPiezas').val();
            var cont = 1;
            /* insercion del detalle */
            var me = this;
            // alert('registrar '+ tipo);
            if(typeof tipo != 'undefined'){
                cont = piezas;
                piezas = 1;
            }
            for (var i = 0; i < cont; i++) {
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
                    'created_at': this.getTime()
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
                    refreshTable('whgTable');
                    me.totalizeDocument();
                    // me.refreshTableDetail(response.data.datos);
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
        refreshTableDetail: function(data) {
            var me = this;
            var campo_pa = '';
            var campo_declarado = '';
            if (me.document_type === 'guia') {
                campo_pa = '<td><input type="text" id="numGuia' + data['id'] + '" name="numGuia[]" value="' + data['num_warehouse'] + '" class="form-control" readonly style="font-size: small;"></td>';
                campo_declarado = '<td>\n\
                <input onkeyup="totalizeDocument(this);"  type="text" id="valorDeclarado' + data['id'] + '" name="valorDeclarado[]" class="form-control cp_declarado" value="' + data['valor'] + '" readonly="readonly">\n\
                <input type="hidden" id="arancel' + data['id'] + '" name="arancel[]" class="form-control" value="' + data['id'] + '" readonly>\n\
                <input type="hidden" id="iva' + data['id'] + '" name="iva[]" class="form-control" value="' + data['id'] + '" readonly></td>';
            }
            if (data['tracking'] == null || data['tracking'] == '') {
                data['tracking'] = '';
            }
            var fila = '<tr id="fila' + data['id'] + '">\n\
            ' + campo_pa + '\n\
            <td>\n\
            <input type="text" onkeyup="totalizeDocument(this);" id="pesoD' + data['id'] + '" name="pesoD[]" class="form-control cp_peso" value="' + data['peso'] + '" readonly="readonly">\n\
            <input type="hidden" id="volumen' + data['id'] + '" name="volumen[]" class="form-control cp_volumen" value="' + data['volumen'].toFixed(2) + '">\n\
            <input type="hidden" style="font-size: small;" id="dimensiones' + data['id'] + '" name="dimensiones[]" class="form-control" value="' + data['dimensiones'] + '" readonly></td>\n\
            <td>\n\
            <input type="text" style="font-size: small;" id="contiene' + data['id'] + '" name="contiene[]" class="form-control" value="' + data['contenido'] + '" readonly="readonly">\n\
            <input type="hidden" id="tempaque' + data['id'] + '" name="tempaque[]" class="form-control" value="' + data['tipo_empaque_id'] + '" readonly>\n\
            <td>\n\
            <input type="text" style="font-size: small;" id="pa' + data['id'] + '" name="pa[]" class="form-control" value="' + data['nom_pa'] + '" readonly="readonly">\n\
            <input type="hidden" id="id_pa' + data['id'] + '" name="id_pa[]" value="' + data['id_pa'] + '" class="form-control" readonly >\n\
            </td>\n\
            ' + campo_declarado + '\n\
            <td>\n\
            <a class="btn btn-info btn-xs btn-actions addTrackings" type="button" id="btn_addtracking' + data['id'] + '" data-toggle="tooltip" title="Agregar tracking" onclick="addTrackings(' + data['id'] + ')"><i class="fa fa-barcode"></i> <span id="cant_tracking' + data['id'] + '">' + data['cantidad'] + '</span></a>\n\
            <a class="btn btn-primary btn-xs" type="button" id="btn_confirm' + data['id'] + '" onclick="saveTableDetail(' + data['id'] + ')" data-toggle="tooltip" title="Guardar" style="display:none;padding:5px;">\n\
            <i class="fa fa-check"></i></a>\n\
            <a class="btn btn-success btn-xs" type="button" id="btn_edit' + data['id'] + '" onclick="editTableDetail(' + data['id'] + ')" data-toggle="tooltip" title="Editar" style="padding:5px;">\n\
            <i class="fa fa-edit"></i></a>\n\
            <a class="btn btn-danger btn-xs" type="button" id="btn_remove' + data['id'] + '" onclick="eliminar(' + data['id'] + ', true)" data-toggle="tooltip" title="Eliminar" style="padding:5px;">\n\
            <i class="fa fa-times"></i></a>\n\
            </td></tr>';
            //coloca una nueva fila en la tabla
            $('#whgTable').append(fila);
            $(".tracking_guia").each(function(index, value) {
                $(this).tagsinput({
                    tagClass: 'label label-primary'
                });
            });
            /* poner readonly al campo tracking */
            $(".table .bootstrap-tagsinput .tag").each(function() {
                $(this).removeClass('label-primary').css('color', '#555');
                $(this).children('span').remove();
            });
            $('.table .bootstrap-tagsinput').children('input').attr('readonly', true);
            me.totalizeDocument();
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

(function ($) {
    "use strict";
    
    var Address = function (options) {
        this.init('address', options, Address.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Address, $.fn.editabletypes.abstractinput);

    $.extend(Address.prototype, {
        /**
        Renders input from tpl

        @method render() 
        **/        
        render: function() {
           this.$input = this.$tpl.find('input');
        },
        
        /**
        Default method to show value in element. Can be overwritten by display option.
        
        @method value2html(value, element) 
        **/
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return; 
            }
            var html = 'Vol=' + $('<div>').text(value.largo).html() + 'x' + $('<div>').text(value.ancho).html() + 'x' + $('<div>').text(value.alto).html();
            $(element).html(html); 
        },
        
        /**
        Gets value from element's html
        
        @method html2value(html) 
        **/        
        html2value: function(html) {        
          /*
            you may write parsing method to get value by element's html
            e.g. "Moscow, st. Lenina, bld. 15" => {city: "Moscow", street: "Lenina", building: "15"}
            but for complex structures it's not recommended.
            Better set value directly via javascript, e.g. 
            editable({
                value: {
                    city: "Moscow", 
                    street: "Lenina", 
                    building: "15"
                }
            });
          */ 
          // console.log('asdf: '+ html);
          return null;  
        },
      
       /**
        Converts value to string. 
        It is used in internal comparing (not for sending to server).
        
        @method value2str(value)  
       **/
       value2str: function(value) {
           var str = '';
           if(value) {
               for(var k in value) {
                   str = str + k + ':' + value[k] + ';';  
               }
           }
           return str;
       }, 
       
       /*
        Converts string to value. Used for reading value from 'data-value' attribute.
        
        @method str2value(str)  
       */
       str2value: function(str) {
           /*
           this is mainly for parsing value defined in data-value attribute. 
           If you will always set value by javascript, no need to overwrite it
           */
           return str;
       },                
       
       /**
        Sets value of input.
        
        @method value2input(value) 
        @param {mixed} value
       **/         
       value2input: function(value) {
           if(!value) {
             return;
           }else{
            value = value.split(',');
           }
           this.$input.filter('[name="largo"]').val(value[0]);
           this.$input.filter('[name="ancho"]').val(value[1]);
           this.$input.filter('[name="alto"]').val(value[2]);
       },       
       
       /**
        Returns value of input.
        
        @method input2value() 
       **/          
       input2value: function() { 
           return {
              largo: this.$input.filter('[name="largo"]').val(), 
              ancho: this.$input.filter('[name="ancho"]').val(), 
              alto: this.$input.filter('[name="alto"]').val()
           };
       },        
       
        /**
        Activates input: sets focus on the first field.
        
        @method activate() 
       **/        
       activate: function() {
            this.$input.filter('[name="largo"]').focus();
       },  
       
       /**
        Attaches handler to submit form in case of 'showbuttons=false' mode
        
        @method autosubmit() 
       **/       
       autosubmit: function() {
           this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
           });
       }       
    });

    Address.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-address"><label><span>Length: </span><input type="number" name="largo" class="input-small form-control" autocomplete="off"></label></div>'+
             '<div class="editable-address"><label><span>Width:  </span><input type="number" name="ancho" class="input-small form-control" autocomplete="off"></label></div>'+
             '<div class="editable-address"><label><span>Heigth: </span><input type="number" name="alto" class="input-small form-control" autocomplete="off"></label></div>',
             
        inputclass: ''
    });

    $.fn.editabletypes.address = Address;

}(window.jQuery));
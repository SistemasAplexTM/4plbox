let modalName = '';

$(document).ready(function () {
    $('#modalChangeShipperConsignee').on('hidden.bs.modal', function () {
        objVue.loading_save_ship = false;
        objVue.loading_save_cons = false;
    });

    setIdPaisConfig();
    // setTimeout(function() {
    //   var table2 = $('#whgTable').DataTable();
    //   $('#whgTable tbody').on( 'click', 'tr', function () {
    //       if ( $(this).hasClass('selected') ) {
    //           $(this).removeClass('selected');
    //           // $('.edit_document').hide();
    //           // $('.tags_document').hide();
    //           // $('.delete_document').hide();
    //           // $('.print_document').hide();
    //       }
    //       else {
    //           table2.$('tr.selected').removeClass('selected');
    //           $(this).addClass('selected');
    //           var tbl_data = table2.row('.selected').data();
    //           // $('.edit_document').show().attr('href', 'documento/'+tbl_data.id+'/edit');
    //           // $('.tags_document').show().attr('onclick',
    //           // "openModalTagsDocument("+tbl_data.id+", '"+tbl_data.num_warehouse+"','"+tbl_data.cons_nomfull+"', '"+tbl_data.email_cons+"', '0', "+tbl_data.liquidado+", "+tbl_data.piezas+", '"+tbl_data.estatus_color+"')");
    //           // $('.delete_document').show().attr('onclick', 'modalEliminar('+tbl_data.id+')');
    //           // $('.print_document').show();
    //       }
    //   });
    // }, 1000);
    $('#tracking').tagsinput();
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';
    $.fn.editable.defaults.params = function (params) {
        params._token = $('meta[name="csrf-token"]').attr('content');
        return params;
    };

    $('#agencia_id').on('change', function () {
        objVue.resetFormsShipperConsignee(0);
        objVue.resetFormsShipperConsignee(1);
    });
    $('#modalTrackingsAdd2').on('show.bs.modal', function () {
        setTimeout(function () {
            $('#tracking_number').focus();
        }, 700);
    });
    /* LIMPIAR MODALES */
    $('#modalShipper').on('hidden.bs.modal', function () {
        var table = $('#tbl-modalshipper').DataTable();
        table.clear();
    });
    $('#modalConsignee').on('hidden.bs.modal', function () {
        var table = $('#tbl-modalconsignee').DataTable();
        table.clear();
    });
    $('#modalShipperConsigneeConsolidado').on('hidden.bs.modal', function () {
        objVue.contactos = {};
    });
    $('#modalagrupar').on('hidden.bs.modal', function () {
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
    $(".table .bootstrap-tagsinput .tag").each(function () {
        $(this).removeClass('label-info').css('color', '#555');
        $(this).children('span').remove();
    });
});
$(function () {
    //aparecer botones de accion en las bolsas del consolidado
    jQuery('.list-group').
        on('mouseover', 'li', function () {
            jQuery(this).find('.boxEdit, .boxDelete').show();
        }).
        on('mouseout', 'li', function () {
            jQuery(this).find('.boxEdit, .boxDelete').hide();
        });

    jQuery('#tbl-consolidado').
        on('mouseover', 'tr', function () {
            jQuery(this).find('.edit, .delete').css('opacity', '1');
        }).
        on('mouseout', 'tr', function () {
            jQuery(this).find('.edit, .delete').css('opacity', '0');
        });

    jQuery('#whgTable').
        on('mouseover', 'tr', function () {
            jQuery(this).find('.edit').show();
        }).
        on('mouseout', 'tr', function () {
            jQuery(this).find('.edit').hide();
        });

    $('#show-all-c').change(function () {
        if ($(this).prop('checked') === true) {
            objVue.modalConsignee(false);
        } else {
            objVue.modalConsignee($('#shipper_id').val());
        }
    });
    $('#show-all').change(function () {
        if ($(this).prop('checked') === true) {
            objVue.modalShipper(false);
        } else {
            objVue.modalShipper($('#consignee_id').val());
        }
    });
    $('#show-totales').change(function () {
        if ($(this).prop('checked') === true) {
            objVue.showTotals(true);
            setTimeout(function () {
                llenarSelectServicio($('#tipo_embarque_id').val());
            }, 500);
        } else {
            objVue.showTotals(false);
        }
    });
});

function datatableDetail() {
    if ($.fn.DataTable.isDataTable('#whgTable')) {
        var table = $('#whgTable').DataTable();
        table.clear();
        $('#whgTable tbody').empty();
        $('#whgTable').dataTable().fnDestroy();
    }
    var puntos = null;
    if (puntos_config != null) {
        puntos = JSON.parse(puntos_config);
    }
    var tbl = $('#whgTable').DataTable({
        "ajax": {
            "url": "getDataDetailDocument",
            "type": "GET"
        },
        // "paging":   false,
        // "info":     false,
        processing: false,
        serverSide: false,
        "searching": false,
        "order": [
            [0, "desc"]
        ],
        columns: [{
            "render": function (data, type, full, meta) {
                var str = full.paquete;
                return parseInt(str);
            },
            width: 30
        }, {
            data: 'num_warehouse',
            name: 'num_warehouse',
            "render": function (data, type, full, meta) {
                return '<strong>' + full.num_warehouse + '</strong>';
            },
            "orderable": false,
        }, {
            "render": function (data, type, full, meta) {
                return '<a data-name="piezas" data-pk="' + full.id + '" data-value="' + full.piezas + '" class="td_edit" data-type="text" data-placement="right" data-title="Piezas">' + full.piezas + '</a>';
            },
            class: 'text-center'
        }, {
            "render": function (data, type, full, meta) {
                var cadena = full.dimensiones;
                var dimensiones = cadena.split(" ");
                var arr1 = cadena.split("=");
                var arrF = arr1[1].split("x");
                return '<a data-name="peso" data-pk="' + full.id + '" class="td_edit" data-type="text" data-placement="right" data-title="Peso">' + full.peso + '</a>' +
                    ' <a data-name="dimensiones" data-pk="' + full.id + '" data-value="' + arrF + '" class="td_edit_d" data-type="address" data-placement="right" data-title="Dimensiones">' + dimensiones[1] + '</a>';;
            }
        }, {
            "render": function (data, type, full, meta) {
                return '<a data-name="contenido" data-pk="' + full.id + '" data-value="' + ((full.contenido === null) ? '' : full.contenido) + '" class="td_edit" data-type="textarea" data-placement="right" data-title="Contenido">' + ((full.contenido === null) ? '' : full.contenido) + '</a>';
            },
            width: 200
        }, {
            "render": function (data, type, full, meta) {
                var pa = full.nom_pa;
                return ((pa === null) ? '' : pa) + '<a  data-toggle="tooltip" title="Canbiar" class="edit" style="float:right;color:#FFC107;" onclick="showModalArancel(' + full.id + ', \'whgTable\')"><i class="fal fa-pencil"></i></a>';
            },
            // visible: ((objVue.mostrar.includes(16)) ? true : false),
            // visible: false,
            width: 100
        },
        {
            "render": function (data, type, full, meta) {
                return '<a data-name="declarado" data-pk="' + full.id + '" class="td_edit" data-type="text" data-placement="left" data-title="Declarado">' + full.valor + '</a>';
            }
        }, {
            data: 'puntos',
            name: 'puntos'
        }, {
            sortable: false,
            "render": function (data, type, full, meta) {
                var btn_addTracking = '';
                var btn_edit = '';
                var btn_save = '';
                var btn_delete = '';
                var btn_points = '';
                if (full.consolidado == 0) {
                    btn_delete = '<a class="btn-actions" type="button" id="btn_remove' + full.id + '" onclick="eliminar(' + full.id + ', false)" data-toggle="tooltip" title="Eliminar" style="color:#E34724"><i class="fal fa-trash-alt"></i></a> ';
                }

                // btn_ship_cons = '<a class="btn btn-primary btn-xs btn-actions" type="button" id="btn_ship_cons'+full.id+'" onclick="changueShipperConsignee('+full.id+', '+full.shipper_id+', '+full.consignee_id+')" data-toggle="tooltip" title="Camibar"><i class="fal fa-user"></i></a> ';
                btn_ship_cons = '';
                btn_addTracking = '<a class="btn btn-info btn-xs btn-actions addTrackings" type="button" id="btn_addtracking' + full.id + '" data-toggle="tooltip" title="Agregar tracking" onclick="addTrackings(' + full.id + ')"><i class="fal fa-truck"></i> <span id="cant_tracking' + full.id + '">' + full.cantidad + '</span></a> ';
                if (puntos != null) {
                    if (objVue.city_c.pais_id == puntos.pais_id) {
                        btn_points = ' <a class="btn btn-warning btn-xs btn-actions" type="button" id="btn_points' + full.id + '" onclick="insertPoints(' + full.id + ')" data-toggle="tooltip" title="Puntos"><i class="fal fa-map-pin"></i></a> ';
                    }
                }
                var btn_group = '<div class="btn-group" data-toggle="tooltip" title="Acciones">' +
                    '<button type="button" class="btn btn-default btn-outline dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '<i class="fal fa-ellipsis-v"></i>' +
                    '</button>' +
                    '<ul class="dropdown-menu dropdown-menu-right pull-right" style="font-size: 15px!important;">' +
                    '<li><a data-target="#modalAddPoints" data-toggle="modal"><i class="fal fa-map-pin"></i> Agregar Puntos</a></li>' +
                    btn_delete +
                    '</ul>' +
                    '</div>';

                return btn_addTracking + btn_ship_cons + btn_points + btn_delete;
            },
            width: 105
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
        },
        ],
        "drawCallback": function () {
            /* EDITABLE FIELD */
            // if (me.permissions.editDetail) {
            $(".td_edit").editable({
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                url: "updateDetailDocument",
                validate: function (value) {
                    if ($.trim(value) == '') {
                        return 'Este campo es obligatorio!';
                    }
                },
                success: function (response, newValue) {
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
                validate: function (value) {
                    if ($.trim(value.largo) == '' || $.trim(value.ancho) == '' || $.trim(value.alto) == '') {
                        return 'Los campos no pueden ir vacios!';
                    }
                },
                success: function (response, newValue) {
                    refreshTable('whgTable');
                    objVue.totalizeDocument();
                }
            });
            // }
        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(),
                data;
            /*Remove the formatting to get integer data for summation*/
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            /*Total over all pages*/
            var vol = api
                .column(9)
                .data()
                .reduce(function (a, b) {
                    return intVal(Math.ceil(a)) + intVal(Math.ceil(b));
                }, 0);
            var piezas = api
                .column(10)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            var peso = api
                .column(11)
                .data()
                .reduce(function (a, b) {
                    return intVal(Math.ceil(a)) + intVal(Math.ceil(b));
                }, 0);
            var dec = api
                .column(12)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            /*Update footer formatCurrency()*/
            $('#piezas').html(parseFloat(isInteger(piezas)));
            $('#volumen').html(parseFloat(isInteger(Math.ceil(vol))));
            $('#pie_ft').html(parseFloat(isInteger(Math.ceil(vol * 166 / 1728))));
            $('#pesoDim').html(parseFloat(isInteger(peso)));
            $('#valor_declarado_tbl').html(parseFloat(isInteger(dec)));

            $('#piezas1').val(parseFloat(isInteger(piezas)));
            $('#volumen1').val(parseFloat(isInteger(Math.ceil(vol))));
            $('#pie_ft1').val(parseFloat(isInteger(Math.ceil(vol * 166 / 1728))));
            $('#pesoDim1').val(parseFloat(isInteger(peso)));
            $('#valor_declarado_tbl1').val(parseFloat(isInteger(dec)));

        },
    }).on('xhr.dt', function (e, settings, json, xhr) {
        var datos = json.data;

        if (app_type === 'courier') {
            if (json.data.length === 0) {
                objVue.cantidad_detalle = true;
                // $('#btn_add').attr('disabled', false);
                // $('#btn_add').siblings('button').attr('disabled', false);
            } else {
                objVue.cantidad_detalle = false;
                // $('#btn_add').attr('disabled', true);
                // $('#btn_add').siblings('button').attr('disabled', true);
            }
        }
        if (app_client === 'jyg') {
            if (json.data.length === 1) {
                objVue.disabled_client = true;
            } else {
                objVue.disabled_client = false;
            }
        }
        objVue.totalizeDocument();

        //VALIDA SI SE MUESTRAN LOS PUNTOS PARA EL PAIS CONFIGURADO
        if (puntos !== null) {
            if (objVue.city_c.pais_id === puntos.pais_id) {
                var columna = tbl.column(6);
                columna.visible(false);
                var columna = tbl.column(7);
                columna.visible(true);
            } else {
                var columna = tbl.column(6);
                columna.visible(true);
                var columna = tbl.column(7);
                columna.visible(false);
            }
        }
        // console.log(json.data);
    });
}

function minimaDetalle(id) {
    if ($('#minima_detalle' + id).is(":checked")) {
        console.log('chekeado ', id);

    } else {
        console.log('NO chekeado ', id);
    }

}

function llenarSelectServicio(id_embarque) {
    var url = '../../servicios/getAllServiciosAgencia/' + id_embarque;
    var pa_id = 1; // POSICION ARANCELARIA POR DEFECTO
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        success: function (data) {
            /* llenar select */
            $("#servicios_id").empty();
            if ($('#impuesto').val() === '0' || $('#impuesto').val() === '') {
                $('#impuesto').val(0);
            }
            if ($('#valor_libra2').val() === '0' || $('#valor_libra2').val() === '') {
                $('#valor_libra2').val(0);
            }
            // $('#valor_libra2').val(0);
            if (Object.keys(data.data).length === 0) {
                $("#servicios_id").attr('readonly', true);
            } else {
                $("#servicios_id").attr('readonly', false);
                $(data.data).each(function (index, value) {
                    $("#servicios_id").append('<option value="' + value.id + '" data-tarifa="' + value.tarifa + '" data-seguro="' + value.seguro + '" ' +
                        'data-cobvol="' + value.cobro_peso_volumen + '"' +
                        'data-tarifamin="' + value.tarifa_minima + '"' +
                        'data-tarifa="' + value.tarifa + '"' +
                        'data-seguro="' + value.seguro + '"' +
                        'data-c_opcional="' + value.cobro_opcional + '"' +
                        'data-t_age="' + value.tarifa_agencia + '"' +
                        'data-seg_age="' + value.seguro_agencia + '"' +
                        'data-impuesto_age="' + value.impuesto + '"' +
                        'data-pa_id="' + value.pa_id + '">' + value.nombre + '</option>');
                });
            }
            objVue.totalizeDocument();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            data = {
                error: jqXHR + ' - ' + textStatus + ' - ' + errorThrown
            }
            $('#modal' + 1).modal('toggle');
            $('body').append('<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title" id="myModalLabel">ERROR EN TRANSACCIÓN</h4></div><div class="modal-body">' + data.error + '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div></div></div>');
            $('#modalError').modal({
                show: true
            });
        },
        complete: function () {
            // ASIGNO EL SERVICIO REGISTRADO EN LA BD
            if ($('#servicios_id').data('servicio_id') != '') {
                $('#servicios_id').val($('#servicios_id').data('servicio_id'));
            }
            if ($('#servicios_id option:selected').data('pa_id') != null) {
                pa_id = $('#servicios_id option:selected').data('pa_id');
            }
            $('#pa_id').val(pa_id);
            objVue.getPositionById(pa_id);
        }
    });
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

function selectShipperConsignee(id, table, selected) {
    objVue.searchShipperConsignee(id, table, selected);
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

function addTrackingToDocument(tracking, option) {
    objVue.addTrackingToDocument(option, tracking);
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
        insertDetail: permission_insertDetail,
        editDetail: permission_editDetail,
        removerGuiaAgrupada: permission_removerGuiaAgrupada,
        pdfContrato: permission_pdfContrato,
        pdfTsa: permission_pdfTsa,
        pdf: permission_pdf,
        pdfLabel: permission_pdfLabel
    };
}

function closeDocument() {
    objVue.closeDocument();
}

function setIdPaisConfig() {
    objVue.pais_id_config = pais_id_config;
}

/* funciones para los puntos */
function insertPoints(id_detail) {
    objVue.points_id_detail = id_detail
    $('#modalAddPoints').modal('show');
}

function selectPoints() {
    var url = '../../administracion/9/selectInput';
    $('#points_id').select2({
        placeholder: "Seleccionar",
        tokenSeparators: [','],
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
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
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        templateResult: formatRepoPoints,
        templateSelection: formatRepoSelectionPoints,
        minimumInputLength: 0,
    });
}

function formatRepoPoints(repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fal fa-map-marker'></i> " + repo.text + "</strong></div>";
    return markup;
}

function formatRepoSelectionPoints(repo) {
    return repo.text || repo.id + ' - ' + repo.text;
}

function changueShipperConsignee(id, shipper_id, consignee_id) {
    objVue.changueShipperConsigneeDetail(id, shipper_id, consignee_id);
}
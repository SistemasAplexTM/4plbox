var label = '('+app_label+')';
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-success").fadeOut(1500);
    }, 3000);
    if (!permission_ajaxCreate) {
        $('#ajaxCreate').remove();
    }
});
var listDocument = function(tipo_doc_id, nom, icon, funcionalidades, reinitialite) {
    var href_print = '';
    var href_print_label = '';
    /* MOSTRAR LABELS DE ESTADOS SI ES WAREHOUSE */
    var labels = '';
    if(tipo_doc_id == '1'){
        labels =    '<label class="lb_status badge badge-default">Creado</label> '+
                    '<label class="lb_status badge badge-success">Pendiente</label> '+
                    '<label class="lb_status badge badge-primary">Liquidado</label> '+
                    '<label class="lb_status badge badge-warning">Consolidado</label> '+
                    '<label class="lb_status badge badge-danger">Anulado</label>';
    }
    if (reinitialite) {
        $('#tbl-documento').dataTable().fnDestroy();
    }
    $('#tbl-documento').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": 'documento/all/documento_detalle',
            "data": function(d) {
                d.id_tipo_doc = tipo_doc_id;
            }
        },
        columns: [{
            "render": function(data, type, full, meta) {
                var codigo = full.codigo;
                var color_badget = 'success';
                var cant = full.cantidad;
                if (full.cantidad == 0) {
                    if (full.tipo_documento_id != 3) {
                        codigo = full.num_warehouse;
                        cant = full.piezas;

                    }
                    color_badget = 'default';
                }else{
                    if (full.tipo_documento_id != 3) {
                        codigo = full.num_warehouse;
                        cant = full.piezas;
                        if (full.liquidado == 1) {
                            if(app_type === 'courier'){codigo = full.num_guia;}
                            color_badget = 'primary';
                        }
                    }
                    if(full.consolidado_status >= 1){
                        color_badget = 'warning';
                    }
                }
                return '<strong>' + ((codigo == null) ? '' : codigo) + '<strong> <span style="float: right;" class="badge badge-' + color_badget + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total piezas">' + cant + '</span>';
            }
        }, {
            data: 'fecha',
            name: 'documento.created_at'
        }, {
            data: 'cons_nomfull',
            name: 'consignee.nombre_full'
        }, {
            data: 'peso',
            name: 'documento.peso'
        }, {
            "render": function(data, type, full, meta) {
                if(full.volumen == null){
                    return 0;
                }else{
                    return isInteger(full.volumen);
                }
            }
        }, {
            data: 'agencia',
            name: 'agencia.descripcion'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var btn_edit = '<a href="documento/' + full.id + '/edit" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="material-icons">&#xE254;</i></a>';
                }
                if (permission_delete && (parseInt(full.consolidado_status) === 0) || full.consolidado_status == null) {
                    btn_delete = '<a onclick=\"modalEliminar(' + full.id + ')\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="material-icons">&#xE872;</i></a>';
                }


                if (full.tipo_documento_id == 3) { //consolidado = 3
                    btn_delete = '';
                    if (permission_delete && (parseInt(full.cantidad) === 0)) {
                        btn_delete = '<a onclick=\"modalEliminar(' + full.id + ')\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="material-icons">&#xE872;</i></a>';
                    }
                    var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='material-icons' style='vertical-align:  middle;'>print</i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
                    return btns + ' ' + btn_edit + btn_delete;
                } else {
                    var codigo = full.num_warehouse;
                    if (full.liquidado == 1) {
                        href_print = "impresion-documento/" + full.id + "/guia";
                        href_print_label = "impresion-documento-label/" + full.id + "/guia";
                        // codigo = full.num_guia;
                    } else {
                        href_print = "impresion-documento/" + full.id + "/warehouse";
                        href_print_label = "impresion-documento-label/" + full.id + "/warehouse";
                        // codigo = full.num_warehouse;
                    }
                    var btn_tags = ' <a onclick="openModalTagsDocument(' + full.id + ', \'' + codigo + '\', \'' + full.cons_nomfull + '\', \'' + full.email_cons + '\', \'' + full.cantidad + '\', \'' + full.liquidado + '\')" data-toggle="modal" data-target="#modalTagDocument" class="view"><i class="material-icons" data-toggle="tooltip" title="Tareas">&#xE5C8;</i></a>';
                    var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='material-icons' style='vertical-align:  middle;'>print</i> <span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu'><li><a href='" + href_print + "' target='_blank'> <spam class='fa fa-print'></spam> Imprimir</a></li>" + "<li><a href='" + href_print_label + "' target='_blank'> <spam class='fa fa-print'></spam> Labels "+label+"</a></li>" + "<li><a href='#' onclick=\"sendMail(" + full.id + ")\"> <spam class='fa fa-envelope'></spam> Enviar Mail</a></li>" + "</ul></div>";
                    return btn_edit + btns + ' ' + btn_tags + btn_delete;
                }
            }
        }],
        'columnDefs': [{
            // className: "text-center",
            "targets": [6],
            width: 180,
        }]
    });
    if (typeof tipo_doc_id == "undefined") {
        tipo_doc_id = 1;
    }
    $('#nombre_doc').html(nom + labels);
    var className = $('#icono_doc').attr('class');
    if (icon == null) {
        var icon = 'file-text-o';
    }
    $('#icono_doc').removeClass(className).addClass('fa fa-' + icon);
    $('#crearDoc').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\')');
}

function modalEliminar(id) {
    objVue.deleteDocument(id);
}

function sendMail(id) {
    objVue.sendMail(id);
}

function createNewDocument_(tipo_doc_id, name, functionalities) {
    var data = {
        tipo_doc_id: tipo_doc_id,
        name: name,
        functionalities: functionalities,
    };
    objVue.createNewDocument(data);
}

function openModalTagsDocument(id, codigo, cliente, correo, cantidad, liquidado) {
    if (correo == 'null') {
        correo = 'Sin correo';
    }
    if (cliente == 'null') {
        cliente = 'Sin cliente';
    }
    objVue.params = {
        'id': id,
        'codigo': codigo,
        'cliente': cliente,
        'correo': correo,
        'cantidad': cantidad,
        'liquidado': liquidado,
    }
}

function deleteStatusNota(id, table) {
    objVue.id_status = id;
    objVue.tableDelete = table;
}
/* objetos VUE index */
var objVue = new Vue({
    el: '#documentoIndex',
    mounted: function() {
        this.typeDocumentList();
        this.printDocument();
        $('#date').val(this.getTime());
    },
    data: {
        id_status: null,
        tableDelete: null,
        params: {},
    },
    methods: {
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
                            refreshTable('tbl-documento');
                            toastr.success('Documento eliminado exitosamente.');
                            toastr.options.closeButton = true;
                        }else{
                            toastr.warning('Atención! ha ocurrido un error.');
                        }
                    }).catch(function (error) {
                        console.log(error);
                        toastr.warning('Error.');
                        toastr.options.closeButton = true;
                    });
                }
            });
        },
        printDocument: function(){
            if( $('#documentoIndex').data('id_print') != '' && $('#documentoIndex').data('doc_print') != ''){
                window.open('impresion-documento/' + $('#documentoIndex').data('id_print') + '/'+$('#documentoIndex').data('doc_print'), '_blank');
                window.open('impresion-documento-label/' + $('#documentoIndex').data('id_print') + '/'+$('#documentoIndex').data('doc_print'), '_blank');
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
            axios.get('tipoDocumento/all').then(function(response) {
                $.each(response.data.data, function(key, value) {
                    var lista = '<button type="button" id="btn' + value.id + '" ' + ' onclick="listDocument(' + value.id + ',\'' + value.nombre + '\',\'' + value.icono + '\',\'' + value.funcionalidades + '\',\'' + true + '\')"' + ' class="btn btn-default btn-block" style="text-align:left;">' + ' <i class="fa fa-' + value.icono + '" aria-hidden="true"></i>  ' + value.nombre +'</button>';
                    if (value.id == 1) {
                        listDocument(value.id, value.nombre, value.icono, value.funcionalidades);
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
            swal({
                title: "<div>Se creará un(a) <span style='color: rgb(212, 103, 82);'>" + data.name + ".</span></div>",
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
        }
    },
});
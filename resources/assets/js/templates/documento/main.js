var label = '('+app_label+')';
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-success").fadeOut(1500);
    }, 3000);
    if (!permission_ajaxCreate) {
        $('#ajaxCreate').remove();
    }
});
var listDocument = function(tipo_doc_id, nom, icon, funcionalidades, reinitialite, filter) {
    objVue.type_document = tipo_doc_id;
    var href_print = '';
    var href_print_label = '';
    var status_id = '';
    /* MOSTRAR LABELS DE ESTADOS SI ES WAREHOUSE */
    var labels = '';
    if (reinitialite) {
        $('#tbl-documento').dataTable().fnDestroy();
    }
    if(typeof filter != 'undefined'){
        status_id = filter;
    }
    $('#tbl-documento').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": 'documento/all/documento_detalle',
            "data": function(d) {
                d.id_tipo_doc = tipo_doc_id;
                d.status_id = status_id;
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
                            // if(app_type === 'courier'){codigo = full.num_guia;}
                            color_badget = 'primary';
                        }
                    }
                    if(full.consolidado_status >= 1){
                        color_badget = 'warning';
                    }
                }
                if (full.tipo_documento_id != 3 && app_type === 'courier') {
                  var groupGuias = '';
                  group = '';
                  groupGuias = full.guias_agrupadas;
                  var btn_delete = "<a style='float: right;cursor:pointer;''><i class='material-icons'>clear</i></a>";
                  if(groupGuias != null && groupGuias != 'null' && groupGuias != ''){
                      groupGuias = groupGuias.replace(/,/g, "<br>");
                  }

                  if(full.consolidado_status == 0){
                    group = ' onclick="agruparGuiasIndex('+full.detalle_id+')"';
                  }
                  classText = color_badget;
                  var status = '<div style="color:'+full.estatus_color+'"><small>' + ((full.estatus == null) ? '' : full.estatus) + '</small></div>';
                  return '<span class=""><i class="fa fa-'+ ((full.agrupadas > 0) ? 'boxes' : 'box-open')+' fa-xs"></i> ' + ((codigo == null) ? '' : codigo )+ '</span><a style="float: right;cursor:pointer;" class="badge badge-'+ classText +' pop" role="button" data-html="true" data-toggle="popover" data-trigger="hover" title="<b>Documentos agrupadas</b>" data-content="'+((groupGuias == null) ? '' : groupGuias )+'" ' + group + '>'+ ((full.agrupadas == null) ? '' : full.agrupadas)+'</a>' + status;
                }else{
                  return '<strong>' + ((codigo == null) ? '' : codigo) + '<strong> <span style="float: right;" class="badge badge-' + color_badget + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total piezas">' + cant + '</span>';
                }
            }
        }, {
            data: 'fecha',
            name: 'documento.created_at',
            width: 80
        }, {
            data: 'cons_nomfull',
            name: 'consignee.nombre_full'
        },{
            data: 'valor',
            name: 'documento.valor',
            visible: (tipo_doc_id != 3) ? true : false
        },  {
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
            className: 'actions_btn',
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update && (parseInt(full.consolidado_status) === 0) || full.consolidado_status == null) {
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
                    if(app_type === 'courier'){
                      var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='material-icons' style='vertical-align:  middle;'>print</i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
                    }else{
                      var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='material-icons' style='vertical-align:  middle;'>print</i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
                    }
                    return btn_edit + ' ' + btns + ' ' +  btn_delete;
                } else {
                    var codigo = full.num_warehouse;
                    if (full.liquidado == 1) {
                        href_print = "impresion-documento/" + full.id + "/guia";
                        // href_print_label = "impresion-documento-label/" + full.id + "/guia";
                        var name = "Nitro PDF Creator (Pro 10)";
                        var format = "PDF";
                        href_print_label = 'onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=guia\')"';
                        // codigo = full.num_guia;
                    } else {
                        href_print = "impresion-documento/" + full.id + "/warehouse";
                        // href_print_label = "impresion-documento-label/" + full.id + "/warehouse";
                        var name = "Nitro PDF Creator (Pro 10)";
                        var format = "PDF";
                        href_print_label = 'onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=warehouse\')"';
                        // codigo = full.num_warehouse;
                    }
                    var btn_tags = ' <a onclick="openModalTagsDocument(' + full.id + ', \'' + codigo + '\', \'' + full.cons_nomfull + '\', \'' + full.email_cons + '\', \'' + full.cantidad + '\', \'' + full.liquidado + '\')" data-toggle="modal" data-target="#modalTagDocument" class="view"><i class="material-icons" data-toggle="tooltip" title="Tareas">&#xE5C8;</i></a>';
                    var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='material-icons' style='vertical-align:  middle;'>print</i> <span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='" + href_print + "' target='_blank'> <spam class='fa fa-print'></spam> Imprimir</a></li>"
                    + "<li><a " + href_print_label + " > <spam class='fa fa-print'></spam> Labels "+label+"</a></li>" + "<li><a href='#' onclick=\"sendMail(" + full.id + ")\"> <spam class='fa fa-envelope'></spam> Enviar Mail</a></li>" + "</ul></div>";
                    return btn_edit + btns + ' ' + btn_tags + btn_delete;
                }
            },
            width: 180
        }],
        'columnDefs': [{
            className: "text-center",
            "targets": [6],
            // width: 180,
        }],
        "drawCallback": function () {
          /* POPOVER PARA LAS GUIAS AGRUPADAS (BADGED) */
          $(".pop").popover({ trigger: "manual" , html: true})
              .on("mouseenter", function () {
                  var _this = this;
                  $(this).popover("show");
                  $(".popover").on("mouseleave", function () {
                      $(_this).popover('hide');
                  });
              }).on("mouseleave", function () {
                  var _this = this;
                  setTimeout(function () {
                      if (!$(".popover:hover").length) {
                          $(_this).popover("hide");
                      }
                  }, 300);
          });
        }
    });

    if(typeof filter == 'undefined'){
        if(tipo_doc_id == '1'){
            labels =    '<label for="creado" class="lb_status badge badge-default">Creado</label> ' +
                        '<label for="bodega" class="lb_status badge badge-success">En bodega</label> '+
                        '<label for="liquidado" class="lb_status badge badge-primary">Liquidado</label> '+
                        '<label for="consolidado" class="lb_status badge badge-warning">Consolidado</label> ' +
                        '<label for="anulado" class="lb_status badge badge-danger">Anulado</label> ';
        }
        if (typeof tipo_doc_id == "undefined") {
            tipo_doc_id = 1;
        }
        $('#nombre_doc').html(nom + ' ' + labels);
        var className = $('#icono_doc').attr('class');
        if (icon == null) {
            var icon = 'file-text-o';
        }
        // $('#icono_doc').removeClass(className).addClass(icon);
        $('#icono_doc').empty().append('<i class="fa '+icon+'"></i>');
        $('#crearDoc').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\')');
    }

}

function agruparGuiasIndex(id) {
    objVue.datosAgrupar = {
        id: id
    };
}

function removerDocumentoAgrupado(id) {
    objVue.removerAgrupado = {
        id: id
    };
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
              console.log(option);
              axios.get('documento/0/removerGuiaAgrupada/' + option.id + '/' + option.id + '/' + true).then(response => {
                  toastr.success('Registro quitado correctamente.');
                  refreshTable('tbl-documento');
              }).catch(function(error) {
                  console.log(error);
                  toastr.warning('Error: -' + error);
              });
          }
    },
    mounted: function() {
        this.typeDocumentList();
        this.printDocument();
        this.getStatus();
        $('#date').val(this.getTime());
    },
    data: {
        id_status: null,
        tableDelete: null,
        params: {},
        status: [],
        status_id: null,
        type_document: null,
        datosAgrupar: {},
        removerAgrupado: {}, //es para poder remover guias agrupadas en el consolidado
    },
    methods: {
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
              refreshTable('tbl-documento');
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
                    var lista = '<button type="button" id="btn' + value.id + '" ' + ' onclick="listDocument(' + value.id + ',\'' + value.nombre + '\',\'' + value.icono + '\',\'' + value.funcionalidades + '\',\'' + true + '\')"' + ' class="btn btn-default btn-block" style="text-align:left;">' + ' <i class="fa ' + value.icono + '" aria-hidden="true"></i>  ' + value.nombre +'</button>';
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

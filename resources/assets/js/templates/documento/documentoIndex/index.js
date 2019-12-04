var listDocument = function (tipo_doc_id, nom, icon, funcionalidades, reinitialite, filter, courier_carga) {
  objVue.type_document = tipo_doc_id;
  var filtro = '';
  /* MOSTRAR LABELS DE ESTADOS SI ES WAREHOUSE */
  var labels = '';
  if (parseInt(tipo_doc_id) === 4) {
    var t = '_agencia';
    $('#tbl3').css('display', 'inline-block');
    $('#tbl1').css('display', 'none');
    $('#tbl2').css('display', 'none');
    $('#crearDoc').css('display', 'inline-block');
    $('#btns_group').css('display', 'none');
    objVue.showFilter = false;
  } else {
    if (parseInt(tipo_doc_id) === 3) {
      var t = '';
      $('#tbl1').css('display', 'inline-block');
      $('#tbl2').css('display', 'none');
      $('#tbl3').css('display', 'none');
      $('#crearDoc').css('display', 'inline-block');
      $('#btns_group').css('display', 'none');
      objVue.showFilter = false;
    } else {
      var t = courier_carga;
      $('#tbl2').css('display', 'inline-block');
      $('#tbl1').css('display', 'none');
      $('#tbl3').css('display', 'none');
      $('#btns_group').css('display', 'inline-block');
      $('#crearDoc').css('display', 'none');
      objVue.showFilter = true;
    }
  }
  if (reinitialite) {
    if ($.fn.DataTable.isDataTable('#tbl-documento' + t)) {
      $('#tbl-documento' + t).dataTable().fnDestroy();
      $('#tbl-documento  tbody' + t).empty();
      if (t == 2) {
        $('#tbl-documento2  tbody').empty();
        if ($.fn.DataTable.isDataTable('#tbl-documento3')) {
          $('#tbl-documento3').dataTable().fnDestroy();
        }
        $('#tbl-documento3  tbody').empty();
        if ($.fn.DataTable.isDataTable('#tbl-documento4')) {
          $('#tbl-documento4').dataTable().fnDestroy();
        }
        $('#tbl-documento4  tbody').empty();
      }
    }
  }
  if (typeof filter != 'undefined' && filter != false) {
    filtro = filter;
  }
  // SI MUESTRO LOS WAREHOUSES ENTONCES LISTO LAS DOS GRILLAS DEL TAB
  if (t === 2) {
    for (var i = 2; i <= 2; i++) {
      datatableDocument(i, tipo_doc_id, filtro);
    }
  } else {
    datatableDocument(t, tipo_doc_id, filtro);
  }

  if (typeof filter == 'undefined' || filter === false) {
    if (typeof tipo_doc_id == "undefined") {
      tipo_doc_id = 1;
    }
    if (tipo_doc_id == '1') {
      labels = '<label for="creado" class="lb_status badge badge-default">Creado</label> ' +
        '<label for="bodega" class="lb_status badge badge-success">En bodega</label> ' +
        '<label for="liquidado" class="lb_status badge badge-primary">Liquidado</label> ' +
        '<label for="consolidado" class="lb_status badge badge-warning">Consolidado</label> ' +
        '<label for="anulado" class="lb_status badge badge-danger">Anulado</label> ';

    }
    $('#nombre_doc').html(nom + ' ' + labels);
    var className = $('#icono_doc').attr('class');
    if (icon == null) {
      var icon = 'file-text-o';
    }
    // $('#icono_doc').removeClass(className).addClass(icon);
    $('#icono_doc').empty().append('<i class="' + icon + '"></i>');
    if (t == 2 || t == 3) {
      $('#crearDoc2').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Courier\', 1)');
      $('#crearDoc3').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Carga\', 0)');
    } else {
      $('#crearDoc').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\')');
    }
  }

}

function datatableDocument(t, tipo_doc_id, filtro) {
  var table = $('#tbl-documento' + t).DataTable({
    processing: true,
    serverSide: true,
    lengthMenu: [
      [20, 40, 50, 80, 100, 200, 500],
      [20, 40, 50, 80, 100, 200, 500]
    ],
    order: [
      [0, "desc"]
    ],
    ajax: {
      "url": 'documento/all/documento_detalle',
      "data": function (d) {
        d.id_tipo_doc = tipo_doc_id;
        d.type = t;
        d.filter = filtro;
      }
    },
    columns: [{
      "render": numDocument,
      name: (tipo_doc_id != 3 && t != '_agencia') ? ((t === 3 || t === 4) ? 'b.num_warehouse' : 'a.num_warehouse') : 'b.id',
    }, {
      data: 'fecha',
      name: 'b.created_at',
      width: 80
    }, {
      data: (tipo_doc_id != 3 && t != '_agencia') ? 'cons_nomfull' : 'central_destino',
      name: (tipo_doc_id != 3 && t != '_agencia') ? ((t === 3) ? 'consignee.nombre_full' : 'c.nombre_full') : 'central_destino.nombre',
      "render": function (data, type, full, meta) {
        var nombre = ''
        if (tipo_doc_id != 3 && t != '_agencia') {
          nombre = full.cons_nomfull;
        } else {
          nombre = (full.central_destino != null) ? full.central_destino : '';
        }
        if (full.cliente !== null && tipo_doc_id != 3) {
          return '<div>' + nombre + '</div> <small style="font-size: 11px;color: #a9a7a7;"><i class="fal fa-user"></i> ' + full.cliente + '</small>'
        } else {
          return nombre;
        }
      },
    }, {
      data: 'ciudad',
      name: 'ciudad',
      searchable: false,
      visible: (tipo_doc_id != 3 && t != '_agencia') ? false : true
    }, {
      data: 'valor',
      name: 'b.valor',
      searchable: false,
      sortable: false,
      visible: (tipo_doc_id != 3 && t != '_agencia') ? true : false
    }, {
      data: 'peso',
      name: 'b.peso',
      "render": function (data, type, full, meta) {
        return '<div style="float: left;">' + ((full.peso != null) ? full.peso : 0) + ' lb </div> <div style="float: right;">' + ((full.piezas != null) ? full.piezas : 0) + '</div>';
      },
      searchable: false,
      sortable: false,
    }, {
      "render": showVolumen,
      searchable: false,
      sortable: false,
    }, {
      data: 'agencia',
      name: 'e.descripcion',
      searchable: false,
      sortable: false,
      visible: (app_client == 'jyg') ? false : true
    }, {
      sortable: false,
      className: '',
      "render": actionsButtons,
      searchable: false,
      width: 160
    }],
    'columnDefs': [{
      className: "text-center",
      "targets": [7]
    }],
    "drawCallback": function () {
      /* POPOVER PARA LAS GUIAS AGRUPADAS (BADGED) */
      $(".pop").popover({
        trigger: "manual",
        html: true
      })
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
      if (t === 4) {
        var table = $('#tbl-documento' + t).DataTable();
        // $('.pending').html(table.data().count());
      }
    }
  });
}

function actionsButtons(data, type, full, meta) {
  var btn_edit = '';
  var btn_delete = '';
  var btn_status = '';
  if (full.tipo_documento_id == 4) {
    var btn_edit = '<a href="#" onclick="openMenu(' + full.id + ', \'' + full.codigo + '\')" class="edit_btn" title="Editar" data-toggle="tooltip"><i class="fal fa-pencil fa-lg"></i></a>';
    var btn_print = '<a href="/reportDispatch/' + full.id + '/print" target="_blank" class="print_btn" title="Imprimir" data-toggle="tooltip"><i class="fal fa-print fa-lg"></i></a>';
    return btn_edit + btn_print;
  } else {
    if (permission_update) {
      if (parseInt(full.consolidado_status) === 0 || full.consolidado_status == null) {
        var btn_edit = '<a href="documento/' + full.id + '/edit" class="edit_btn" title="Editar" data-toggle="tooltip"><i class="fal fa-pencil fa-lg"></i></a>';
      } else {
        var btn_edit = '<a href="#" class="edit_btn" style="color:#b9b9b9; cursor:not-allowed" title="Editar" data-toggle="tooltip"><i class="fal fa-pencil fa-lg"></i></a>';
      }
      var btn_status = '<a onclick=\"modalChangeStatus(' + full.id + ')\" class="status_btn" title="Status" data-toggle="tooltip" style="color:#4caf50;"><i class="fal fa-clock fa-lg"></i></a>';
    }
    if (permission_delete && (parseInt(full.consolidado_status) === 0) || full.consolidado_status == null) {
      btn_delete = '<a onclick=\"modalEliminar(' + full.id + ')\" class="delete_btn" title="Eliminar" data-toggle="tooltip"><i class="fal fa-trash-alt fa-lg"></i></a>';
    }
    if (full.tipo_documento_id == 3) { //consolidado = 3
      btn_delete = '';
      if (permission_delete && (parseInt(full.cantidad) === 0)) {
        var btn_delete = '<li role="separator" class="divider"></li><li style="color:#E34724;"><a onclick=\"modalEliminar(' + full.id + ')\"><i class="fal fa-trash-alt fa-lg"></i> Eliminar</a></li>';
      }

      var btn_close = '<li><a onclick="closeDocument(' + full.id + ')"><i class="fal fa-lock"></i> Cerrar consolidado</a></li>';

      var btns = "<div class='btn-group'>" +
        "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
        "<i class='fal fa-print fa-lg'></i> <span class='caret'></span>" + "</button>" +
        "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
        "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fal fa-print'></spam> Imprimir manifiesto</a></li>" +
        "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fal fa-print'></spam> Imprimir Guias</a></li>" +
        "<li role='separator' class='divider'></li>" +
        "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fal fa-print'></spam> Imprimir contrato</a></li>" +
        "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fal fa-print'></spam> Imprimir TSA</a></li>" +
        "</ul></div>";

      var btn_actions = "<div class='btn-group'>" +
        "<button type='button' class='btn btn-success dropdown-toggle btn-xs btn-circle-sm' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
        "<i class='fal fa-ellipsis-v'></i>" +
        "</button>" +
        "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
        btn_close +
        btn_delete +
        "</ul></div>";
      return btn_edit + ' ' + btns + ' ' + btn_status + ' ' + btn_actions;
    } else {
      var codigo = full.num_warehouse;
      var saveLabelGuia = '';
      var saveLabelWH = '';
      var saveGuia = '';
      var saveWH = '';
      var proforma = '';
      var label_wcp = '';
      var label_guia_wcp = '';
      var wrh_wcp = '';
      var guia_wcp = '';
      var guia_invoice_wcp = '';

      /* CONFIGURAR IMPRESION CON WCP */
      if (print_labels != '') {
        label_wcp = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + print_labels + '&filetype=' + print_format + '&id=' + full.id + '&agency_id=' + agency_id + '&document=warehouse&label=true\')"> <spam class="fal fa-print"></spam> Label Warehouse</a></li>';
      }
      if (print_documents != '') {
        wrh_wcp = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + print_documents + '&filetype=' + print_format + '&id=' + full.id + '&agency_id=' + agency_id + '&document=warehouse\')"> <spam class="fal fa-print"></spam> Warehouse</a></li>';
        if (full.liquidado == 1) {
          guia_wcp = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + print_documents + '&filetype=' + print_format + '&id=' + full.id + '&agency_id=' + agency_id + '&document=guia\')"> <spam class="fal fa-print"></spam> Invoice</a></li>';
          label_guia_wcp = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + print_labels + '&filetype=' + print_format + '&id=' + full.id + '&agency_id=' + agency_id + '&document=guia&label=true\')"> <spam class="fal fa-print"></spam> Label Invoice</a></li>';
          guia_invoice_wcp = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + print_documents + '&filetype=' + print_format + '&id=' + full.id + '&agency_id=' + agency_id + '&document=invoice_guia\')"> <spam class="fal fa-print"></spam> Factura Proforma</a></li>';
        }
      }

      if (full.liquidado == 1) {
        saveGuia = "<li><a href='impresion-documento/" + full.id + "/guia' target='_blank'> <spam class='fal fa-file-pdf icon_lsave'></spam> Invoice</a></li>";
        saveLabelGuia = '<li><a href="impresion-documento-label/' + full.id + '/guia" target="_blank"> <spam class="fal fa-file-pdf icon_lsave"></spam> Label Invoice ' + label + '</a></li>';
        proforma = '<li><a href="impresion-documento/' + full.id + '/invoice_guia" target="_blank"> <spam class="fal fa-file-pdf icon_lsave"></spam> Factura Proforma</a></li>';
      }
      saveWH = "<li><a href='impresion-documento/" + full.id + "/warehouse' target='_blank'> <spam class='fal fa-file-pdf icon_lsave'></spam> Warehouse</a></li>";
      saveLabelWH = '<li><a href="impresion-documento-label/' + full.id + '/warehouse" target="_blank"> <spam class="fal fa-file-pdf icon_lsave"></spam> Labels Warehouse ' + label + '</a></li>';
      var btn_tags = ' <a onclick="openModalTagsDocument(' + full.id + ', \'' + codigo + '\', \'' + full.cons_nomfull + '\', \'' + full.email_cons + '\', \'' + full.cantidad + '\', \'' + full.liquidado + '\', \'' + full.piezas + '\', \'' + full.estatus_color + '\', \'' + full.detalle_id + '\', \'' + full.consignee_id + '\')" data-toggle="modal" data-target="#modalTagDocument" class="" style="font-size: 18px;"><i class="fal fa-arrow-square-right fa-lg" data-toggle="tooltip" title="Tareas"></i></a>';
      var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i> <span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
        saveGuia + " " +
        saveLabelGuia + " " +
        saveWH + " " +
        saveLabelWH + " " +
        proforma + " " +
        '<li role="separator" class="divider"></li>' +
        guia_wcp + " " +
        label_guia_wcp + " " +
        wrh_wcp + " " +
        label_wcp + " " +
        guia_invoice_wcp + " " +
        '<li role="separator" class="divider"></li>' +
        "<li><a href='#' onclick=\"sendMail(" + full.id + ")\"> <spam class='fal fa-envelope icon_lemail'></spam> Enviar Mail</a></li>" + "</ul></div>";

      return btn_edit + btns + ' ' + btn_tags + btn_delete;
    }
  }
}

function showVolumen(data, type, full, meta) {
  if (full.volumen == null) {
    return 0;
  } else {
    return isInteger(full.volumen);
  }
}

function numDocument(data, type, full, meta) {
  var codigo = full.codigo;
  var color_badget = 'success';
  var cant = full.cantidad;
  if (full.cantidad == 0) {
    if (full.tipo_documento_id != 3 && full.tipo_documento_id != 4) {
      codigo = full.num_warehouse;
      cant = full.piezas;

    }
    color_badget = 'default';
  } else {
    if (full.tipo_documento_id != 3 && full.tipo_documento_id != 4) {
      codigo = full.num_warehouse;
      if (full.num_warehouse === null) {
        codigo = full.warehouse;
      }
      cant = full.piezas;
      if (full.liquidado == 1) {
        // if(app_type === 'courier'){codigo = full.num_guia;}
        color_badget = 'primary';
      }
      var mintic = '';
      if (full.mintic && full.mintic != null) {
        color_badget = 'info';
      }
    }
    if (full.consolidado_status >= 1) {
      color_badget = 'warning';
    }
  }
  if (full.tipo_documento_id != 3 && full.tipo_documento_id != 4 && full.carga_courier == 1) {
    var groupGuias = '';
    group = '';
    // groupGuias = full.guias_agrupadas;
    groupG = full.guias_agrupadas;
    var btn_delete = "<a style='float: right;cursor:pointer;''><i class='material-icons'>clear</i></a>";
    if (groupG != null && groupG != 'null' && groupG != '') {
      // groupGuias = groupGuias.replace(/,/g, "<br>");
      groupG = groupG.split(",");
      if (groupG.length > 0) {
        for (var i = 0; i < groupG.length; i++) {
          var dat = groupG[i].split("@");
          groupGuias += "<label>- " + dat[0] + " (" + dat[1] + " lb) ($ " + dat[2] + ")</label><a style='float:right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerDocumentoAgrupado(" + dat[3] + ")'><i class='fal fa-times' style='font-size: 15px;'></i></a><br>";
        }
      }
    }
    if (full.consolidado_status === 0) {
      group = ' onclick="agruparGuiasIndex(' + full.detalle_id + ')"';
    }
    classText = color_badget;
    var status = '<div style="color:' + full.estatus_color + '"><small>' + ((full.estatus == null) ? '' : full.estatus) + '</small></div>';
    var st = ((full.estatus == null) ? '' : full.estatus);
    var mintic = '';
    if (full.mintic != '' && full.mintic != null) {
      mintic = '<div><small style="color: #23c6c8;">' + full.mintic + '</small></div>';
    } else {
      if (full.flag == 1) {
        var str = full.padre;
        mintic = '<div><small style="color: #23c6c8;padding-left:15px">' + str + '</small></div>';
      }
    }
    return '<span class="" data-toggle="tooltip" title="' + st + '"><i class="fal fa-' + ((full.estatus == null) ? 'box' : ((full.agrupadas > 0) ? 'boxes' : ((full.flag == 1) ? 'minus' : 'box-open'))) + ' " style="color:' + ((full.flag == 1) ? '#E34724' : full.estatus_color) + '"></i> ' + ((codigo == null) ? full.warehouse : codigo) + '</span><a style="float: right;cursor:pointer;" class="badge badge-' + classText + ' pop" role="button" data-html="true" data-toggle="popover" data-trigger="hover" title="<b>Documentos agrupadas</b>" data-content="' + ((groupGuias == null) ? '' : groupGuias) + '" ' + group + '>' + ((full.agrupadas == null) ? '' : full.agrupadas) + '</a>' + mintic;
  } else {
    icon = 'fal fa-boxes';
    if (full.transporte_id == 1) {
      icon = 'fal fa-plane';
    }
    if (full.transporte_id == 2) {
      icon = 'fal fa-ship';
    }
    if (full.num_warehouse === null) {
      icon = 'fal fa-box';
    }
    return '<i class="' + icon + '"></i> <strong>' + ((codigo == null) ? '' : codigo) + '<strong> <span style="float: right;" class="badge badge-' + color_badget + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total piezas">' + ((cant === null) ? 0 : cant) + '</span>';
  }
}

function modalChangeStatus(id) {
  objVue.id_consolidado_selected = id;
  objVue.getStatusDocument();
  $('#modalChangeStatus').modal('show');
}

function closeDocument(id) {
  objVue.closeDocument(id);
}

function openMenu(id, consecutive) {
  objVue.open(id, consecutive);
}
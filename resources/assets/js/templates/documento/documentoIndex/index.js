var listDocument = function(tipo_doc_id, nom, icon, funcionalidades, reinitialite, filter) {
    objVue.type_document = tipo_doc_id;
    var href_print = '';
    var href_print_label = '';
    var status_id = '';
    /* MOSTRAR LABELS DE ESTADOS SI ES WAREHOUSE */
    var labels = '';
    if(parseInt(tipo_doc_id) === 3){
      var t = '';
      $('#tbl1').css('display','inline-block');
      $('#tbl2').css('display','none');
      $('#crearDoc').css('display','inline-block');
      $('#btns_group').css('display','none');
    }else{
      var t = 2;
      $('#tbl2').css('display','inline-block');
      $('#tbl1').css('display','none');
      $('#btns_group').css('display','inline-block');
      $('#crearDoc').css('display','none');
    }
    if (reinitialite) {
      if ($.fn.DataTable.isDataTable('#tbl-documento' + t)) {
        $('#tbl-documento' + t).dataTable().fnDestroy();
        if(t == 2){
          $('#tbl-documento3').dataTable().fnDestroy();
          $('#tbl-documento4').dataTable().fnDestroy();
        }
      }
    }
    if(typeof filter != 'undefined'){
        status_id = filter;
    }
    // SI MUESTRO LOS WAREHOUSES ENTONCES LISTO LAS DOS GRILLAS DEL TAB
    if(t === 2){
      for (var i = 2; i <= 4; i++) {
        datatableDocument(i, tipo_doc_id, status_id);
      }
    }else{
      datatableDocument(t, tipo_doc_id, status_id);
    }

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
        $('#icono_doc').empty().append('<i class="'+icon+'"></i>');
        if(t == 2){
          $('#crearDoc2').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Courier\', 1)');
          $('#crearDoc3').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Carga\', 0)');
        }else{
          $('#crearDoc').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\')');
        }
    }

}

function datatableDocument(t, tipo_doc_id, status_id){
  var table = $('#tbl-documento' + t).DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [[20, 40, 50, 80, 100, 200, 500], [20, 40, 50, 80, 100, 200, 500]],
      // order: [[1, "desc"]],
      ajax: {
          "url": 'documento/all/documento_detalle',
          "data": function(d) {
              d.id_tipo_doc = tipo_doc_id;
              d.type = t;
              d.status_id = status_id;
          }
      },
      columns: [{
          "render": numDocument,
          name: (tipo_doc_id != 3) ? 'a.num_warehouse' : 'b.id',
      }, {
          data: 'fecha',
          name: 'b.created_at',
          width: 80
      }, {
          data: (tipo_doc_id != 3) ? 'cons_nomfull' : 'central_destino',
          name: (tipo_doc_id != 3) ? 'c.nombre_full' : 'central_destino.nombre'
      },{
          data: 'ciudad',
          name: 'ciudad',
          searchable: false,
          visible: (tipo_doc_id != 3) ? false : true
      },{
          data: 'valor',
          name: 'b.valor',
          searchable: false,
          visible: (tipo_doc_id != 3) ? true : false
      },  {
          data: 'peso',
          name: 'b.peso',
          searchable: false,
      }, {
          "render": showVolumen,
          searchable: false,
      }, {
          data: 'agencia',
          name: 'e.descripcion',
          searchable: false,
          visible: (app_client == 'jyg') ? false : true
          // visible: (show_agency != null && show_agency != 0) ? false : true
      }, {
          sortable: false,
          className: 'actions_btn',
          "render": actionsButtons,
          searchable: false,
          width: 150
      }],
      'columnDefs': [{
          className: "text-center",
          "targets": [7]
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
        if(t === 4){
          var table = $('#tbl-documento' + t).DataTable();
          $('.pending').html(table.data().count());
        }
      }
  });
}

function actionsButtons(data, type, full, meta) {
    var btn_edit = '';
    var btn_delete = '';
    var btn_status = '';
    if (permission_update) {
        var btn_edit = '<a href="documento/' + full.id + '/edit" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="fal fa-pencil fa-lg"></i></a>';
        var btn_status = '<a onclick=\"modalChangeStatus(' + full.id + ')\" class="edit" title="Status" data-toggle="tooltip" style="color:#4caf50;"><i class="fal fa-clock fa-lg"></i></a>';
    }
    if (permission_delete && (parseInt(full.consolidado_status) === 0) || full.consolidado_status == null) {
        btn_delete = '<a onclick=\"modalEliminar(' + full.id + ')\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="fal fa-trash-alt fa-lg"></i></a>';
    }
    if (full.tipo_documento_id == 3) { //consolidado = 3
        btn_delete = '';
        if (permission_delete && (parseInt(full.cantidad) === 0)) {
            btn_delete = '<a onclick=\"modalEliminar(' + full.id + ')\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="fal fa-trash-alt fa-lg"></i></a>';
        }
        if(app_type === 'courier'){
          var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-s' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
        }else{
          var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
        }
        return btn_edit + ' ' + btns + ' ' + btn_status + ' ' +  btn_delete;
    } else {
        var codigo = full.num_warehouse;
        var href_print_guia = '';
        var href_print_label_guia = '';
        var href_print_wrh = '';
        var href_print_label_wrh = '';
        var href_print_view_g = '';
        var href_print_view_w = '';
        var invoice = '';
        if (full.liquidado == 1) {
            href_print_view_g = "<li><a href='impresion-documento/" + full.id + "/guia' target='_blank'> <spam class='fal fa-print'></spam> Guia</a></li>";
            href_print_guia = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=guia\')"> <spam class="fal fa-print"></spam> Guia</a></li>';
            // href_print_label = "impresion-documento-label/" + full.id + "/guia";
            var name = "Nitro PDF Creator (Pro 10)";
            var format = "PDF";
            href_print_label_guia = '<li><a href="impresion-documento-label/' + full.id + '/guia" target="_blank"> <spam class="fal fa-print"></spam> Label Guia '+label+'</a></li>';
            invoice = '<li><a href="impresion-documento/' + full.id + '/invoice_guia" target="_blank"> <spam class="fal fa-print"></spam> Invoice</a></li>';
            // href_print_label_guia = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=guia&label=true\')"> <spam class="fa fa-print"></spam> Label Guia '+label+'</a></li>';
            // codigo = full.num_guia;
        }
        // else {
            href_print_view_w = "<li><a href='impresion-documento/" + full.id + "/warehouse' target='_blank'> <spam class='fal fa-print'></spam> Warehouse</a></li>";
            href_print_wrh = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=warehouse\')"> <spam class="fal fa-print"></spam> Warehouse</a></li>';
            // href_print_label = "impresion-documento-label/" + full.id + "/warehouse";
            var name = "Nitro PDF Creator (Pro 10)";
            var format = "PDF";
            href_print_label_wrh = '<li><a href="impresion-documento-label/' + full.id + '/warehouse" target="_blank"> <spam class="fal fa-print"></spam> Labels Warehouse '+label+'</a></li>';
            // href_print_label_wrh = '<li><a onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format +'&id=' + full.id + '&agency_id='+agency_id+'&document=warehouse&label=true\')"> <spam class="fa fa-print"></spam> Labels Warehouse '+label+'</a></li>';
            // codigo = full.num_warehouse;
        // }

        var btn_tags = ' <a onclick="openModalTagsDocument(' + full.id + ', \'' + codigo + '\', \'' + full.cons_nomfull + '\', \'' + full.email_cons + '\', \'' + full.cantidad + '\', \'' + full.liquidado + '\', \'' + full.piezas + '\', \'' + full.estatus_color + '\')" data-toggle="modal" data-target="#modalTagDocument" class="" style="font-size: 18px;"><i class="fal fa-arrow-square-right fa-lg" data-toggle="tooltip" title="Tareas"></i></a>';
        var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i> <span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>"
        // + href_print_wrh + " "
        + href_print_label_wrh + " "
        // + href_print_guia + " "
        + href_print_label_guia + " "
        // + '<li class="divider"></li> '
        + href_print_view_w + " "
        + href_print_view_g + " "
        + invoice + " "
        + "<li><a href='#' onclick=\"sendMail(" + full.id + ")\"> <spam class='fa fa-envelope'></spam> Enviar Mail</a></li>" + "</ul></div>";

        return btn_edit + btns + ' ' + btn_tags + btn_delete;
    }
}

function showVolumen(data, type, full, meta) {
    if(full.volumen == null){
        return 0;
    }else{
        return isInteger(full.volumen);
    }
}

function numDocument(data, type, full, meta) {
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
            var mintic = '';
            if(full.mintic && full.mintic != null){
              color_badget = 'info';
            }
        }
        if(full.consolidado_status >= 1){
            color_badget = 'warning';
        }
    }
    if (full.tipo_documento_id != 3 && full.carga_courier == 1) {
      var groupGuias = '';
      group = '';
      // groupGuias = full.guias_agrupadas;
      groupG = full.guias_agrupadas;
      var btn_delete = "<a style='float: right;cursor:pointer;''><i class='material-icons'>clear</i></a>";
      if(groupG != null && groupG != 'null' && groupG != ''){
          // groupGuias = groupGuias.replace(/,/g, "<br>");
          groupG = groupG.split(",");
          if(groupG.length > 0){
            for (var i = 0; i < groupG.length; i++) {
              var dat = groupG[i].split("@");
              groupGuias += "<label>- " + dat[0] + " (" + dat[1] + " lb) ($ " + dat[2] + ")</label><a style='float:right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerDocumentoAgrupado(" + dat[3] + ")'><i class='fa fa-times' style='font-size: 15px;'></i></a><br>";
            }
          }
      }

      if(full.consolidado_status == 0){
        group = ' onclick="agruparGuiasIndex('+full.detalle_id+')"';
      }
      classText = color_badget;
      var status = '<div style="color:'+full.estatus_color+'"><small>' + ((full.estatus == null) ? '' : full.estatus) + '</small></div>';
      var st = ((full.estatus == null) ? '' : full.estatus);
      var mintic = '';
      if(full.mintic != '' && full.mintic != null){
        mintic = '<div><small style="color: #23c6c8;">' + full.mintic + '</small></div>';
      }else{
        if(full.flag == 1){
          var str = full.padre;
          mintic = '<div><small style="color: #23c6c8;padding-left:15px">' + str + '</small></div>';
        }
      }
      return '<span class="" data-toggle="tooltip" title="'+st+'"><i class="fa fa-'+ ((full.estatus == null) ? 'box' : ((full.agrupadas > 0) ? 'boxes' : ((full.flag == 1) ? 'minus' : 'box-open')))+' fa-xs" style="color:'+ ((full.flag == 1) ? '#E34724' : full.estatus_color) +'"></i> ' + ((codigo == null) ? full.warehouse : codigo )+ '</span><a style="float: right;cursor:pointer;" class="badge badge-'+ classText +' pop" role="button" data-html="true" data-toggle="popover" data-trigger="hover" title="<b>Documentos agrupadas</b>" data-content="'+((groupGuias == null) ? '' : groupGuias )+'" ' + group + '>'+ ((full.agrupadas == null) ? '' : full.agrupadas)+'</a> ' + mintic;
    }else{
      icon = 'boxes';
      if(full.transporte_id == 7){
        icon = 'plane';
      }
      if(full.transporte_id == 8){
        icon = 'ship';
      }

      return '<strong>' + ((codigo == null) ? '' : codigo) + '<strong> <i class="fal fa-'+ icon +'"></i> <span style="float: right;" class="badge badge-' + color_badget + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total piezas">' + cant + '</span>';
    }
}

function modalChangeStatus(id) {
  objVue.id_consolidado_selected = id;
  objVue.getStatusDocument();
  $('#modalChangeStatus').modal('show');
}

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
        }
      }
    }
    if(typeof filter != 'undefined'){
        status_id = filter;
    }
    // SI MUESTRO LOS WAREHOUSES ENTONCES LISTO LAS DOS GRILLAS DEL TAB
    if(t === 2){
      for (var i = 2; i <= 3; i++) {
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
        $('#icono_doc').empty().append('<i class="fa '+icon+'"></i>');
        if(t == 2){
          $('#crearDoc2').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Courier\', 1)');
          $('#crearDoc3').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\', \'Carga\', 0)');
        }else{
          $('#crearDoc').attr('onclick', 'createNewDocument_(' + tipo_doc_id + ',\'' + nom + '\',\'' + funcionalidades + '\')');
        }
    }

}

function datatableDocument(t, tipo_doc_id, status_id){
  $('#tbl-documento' + t).DataTable({
      processing: true,
      serverSide: true,
      ajax: {
          "url": 'documento/all/documento_detalle',
          "data": function(d) {
              d.id_tipo_doc = tipo_doc_id;
              d.type = t;
              d.status_id = status_id;
          }
      },
      columns: [{
          "render": numDocument
      }, {
          data: 'fecha',
          name: 'b.created_at',
          width: 80
      }, {
          data: 'cons_nomfull',
          name: 'c.nombre_full'
      },{
          data: 'valor',
          name: 'b.valor',
          visible: (tipo_doc_id != 3) ? true : false
      },  {
          data: 'peso',
          name: 'b.peso'
      }, {
          "render": showVolumen
      }, {
          data: 'agencia',
          name: 'e.descripcion'
      }, {
          sortable: false,
          className: 'actions_btn',
          "render": actionsButtons,
          width: 180
      }],
      'columnDefs': [{
          className: "text-center",
          "targets": [6]
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
}

function actionsButtons(data, type, full, meta) {
    var btn_edit = '';
    var btn_delete = '';
    if (permission_update && (parseInt(full.consolidado_status) === 0) || full.consolidado_status == null) {
        var btn_edit = '<a href="documento/' + full.id + '/edit" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="fal fa-pencil fa-lg"></i></a>';
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
          var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
        }else{
          var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i><span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'>" + "<li><a href='impresion-documento/" + full.id + "/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Imprimir manifiesto</a></li>" + "<li><a href='impresion-documento/" + full.id + "/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Imprimir Guias</a></li>" + "<li role='separator' class='divider'></li> " + "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Imprimir contrato</a></li>" + "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> Imprimir TSA</a></li>" + "</ul></div>";
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

        var btn_tags = ' <a onclick="openModalTagsDocument(' + full.id + ', \'' + codigo + '\', \'' + full.cons_nomfull + '\', \'' + full.email_cons + '\', \'' + full.cantidad + '\', \'' + full.liquidado + '\', \'' + full.piezas + '\', \'' + full.estatus_color + '\')" data-toggle="modal" data-target="#modalTagDocument" class="" style="font-size: 18px;"><i class="fal fa-arrow-square-right fa-lg" data-toggle="tooltip" title="Tareas"></i></a>';
        var btns = "<div class='btn-group'>" + "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" + "<i class='fal fa-print fa-lg'></i> <span class='caret'></span>" + "</button>" + "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='" + href_print + "' target='_blank'> <spam class='fa fa-print'></spam> Imprimir</a></li>"
        + "<li><a " + href_print_label + " > <spam class='fa fa-print'></spam> Labels "+label+"</a></li>" + "<li><a href='#' onclick=\"sendMail(" + full.id + ")\"> <spam class='fa fa-envelope'></spam> Enviar Mail</a></li>" + "</ul></div>";
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
      return '<span class=""><i class="fa fa-'+ ((full.agrupadas > 0) ? 'boxes' : 'box-open')+' fa-xs"></i> ' + ((codigo == null) ? '' : codigo )+ '</span><a style="float: right;cursor:pointer;" class="badge badge-'+ classText +' pop" role="button" data-html="true" data-toggle="popover" data-trigger="hover" title="<b>Documentos agrupadas</b>" data-content="'+((groupGuias == null) ? '' : groupGuias )+'" ' + group + '>'+ ((full.agrupadas == null) ? '' : full.agrupadas)+'</a>' + status;
    }else{
      icon = 'boxes';
      if(full.transporte_id == 7){
        icon = 'plane';
      }
      if(full.transporte_id == 8){
        icon = 'ship';
      }
      return '<strong>' + ((codigo == null) ? '' : codigo) + '<strong> <i class="fa fa-'+ icon +'"></i> <span style="float: right;" class="badge badge-' + color_badget + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total piezas">' + cant + '</span>';
    }
}

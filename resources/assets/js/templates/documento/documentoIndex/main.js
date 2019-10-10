var label = '(' + app_label + ')';
$(document).ready(function () {
    setTimeout(function () {
        $(".alert-success").fadeOut(1500);
    }, 3000);
    if (!permission_ajaxCreate) {
        $('#ajaxCreate').remove();
    }
    // setTimeout(function() {
    //   var table2 = $('#tbl-documento2').DataTable();
    //   $('#tbl-documento2 tbody').on( 'click', 'tr', function () {
    //       if ( $(this).hasClass('selected') ) {
    //           $(this).removeClass('selected');
    //           $('.edit_document').hide();
    //           $('.tags_document').hide();
    //           $('.delete_document').hide();
    //           $('.print_document').hide();
    //       }
    //       else {
    //           table2.$('tr.selected').removeClass('selected');
    //           $(this).addClass('selected');
    //           var tbl_data = table2.row('.selected').data();
    //           $('.edit_document').show().attr('href', 'documento/'+tbl_data.id+'/edit');
    //           $('.tags_document').show().attr('onclick',
    //           "openModalTagsDocument("+tbl_data.id+", '"+tbl_data.num_warehouse+"','"+tbl_data.cons_nomfull+"', '"+tbl_data.email_cons+"', '0', "+tbl_data.liquidado+", "+tbl_data.piezas+", '"+tbl_data.estatus_color+"')");
    //           $('.delete_document').show().attr('onclick', 'modalEliminar('+tbl_data.id+')');
    //           $('.print_document').show();
    //           console.log(tbl_data);
    //       }
    //   });
    // }, 1000);
});

$(function () {
    // if($('#li-pending').hasClass('active')){
    //   console.log('remove');
    //   $('.pending').removeClass('ligth');
    // }else{
    //   console.log('add');
    //   $('.pending').addClass('ligth');
    // }
});

function agruparGuiasIndex(id) {
    objVue.datosAgrupar = id;
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

function createNewDocument_(tipo_doc_id, name, functionalities, type, type_id) {
    if (tipo_doc_id == 4) {
        objVue.open();
    } else {
        var data = {
            tipo_doc_id: tipo_doc_id,
            name: name,
            functionalities: functionalities,
            type: type,
            type_id: type_id,
        };
        objVue.createNewDocument(data);
    }
}

function openModalTagsDocument(id, codigo, cliente, correo, cantidad, liquidado, piezas, estatus_color, detalle_id, consignee_id) {
    if (correo == 'null') {
        correo = 'Sin correo';
    }
    if (cliente == 'null') {
        cliente = 'Sin cliente';
    }
    objVue.params = {
        'id': id,
        'detalle_id': detalle_id,
        'codigo': codigo,
        'consignee_id': consignee_id,
        'cliente': cliente,
        'correo': correo,
        'cantidad': cantidad,
        'liquidado': liquidado,
        'piezas': piezas,
        'estatus_color': estatus_color,
    }
}

function deleteStatusNota(id, table) {
    objVue.id_status = id;
    objVue.tableDelete = table;
}
var label = '('+app_label+')';
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-success").fadeOut(1500);
    }, 3000);
    if (!permission_ajaxCreate) {
        $('#ajaxCreate').remove();
    }
});

$(function() {
  // if($('#li-pending').hasClass('active')){
  //   console.log('remove');
  //   $('.pending').removeClass('ligth');
  // }else{
  //   console.log('add');
  //   $('.pending').addClass('ligth');
  // }
});

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

function createNewDocument_(tipo_doc_id, name, functionalities, type, type_id) {
    var data = {
        tipo_doc_id: tipo_doc_id,
        name: name,
        functionalities: functionalities,
        type: type,
        type_id: type_id,
    };
    objVue.createNewDocument(data);
}

function openModalTagsDocument(id, codigo, cliente, correo, cantidad, liquidado, piezas, estatus_color) {
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
        'piezas': piezas,
        'estatus_color': estatus_color,
    }
}

function deleteStatusNota(id, table) {
    objVue.id_status = id;
    objVue.tableDelete = table;
}
var label = '('+app_label+')';
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-success").fadeOut(1500);
    }, 3000);
    if (!permission_ajaxCreate) {
        $('#ajaxCreate').remove();
    }
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

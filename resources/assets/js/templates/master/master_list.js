$(document).ready(function () {
    $('#tbl-master').DataTable({
        ajax: 'master/all/reg',
        "order": [[ 1, "desc" ]],
        columns: [
            {data: 'num_master', name: 'num_master'},
            {data: 'aerolinea', name: 'aerolinea'},
            {data: 'created_at', name: 'created_at'},
            {data: 'tarifa', name: 'tarifa'},
            {data: 'peso', name: 'peso'},
            {data: 'peso_kl', name: 'peso_kl'},//peso lb
            {
                "render": function (data, type, full, meta) {
                    return full.consignee + '<div style="font-size:13px;color:#adadad;">Contacto: '+full.contacto+'</div>';
                }
            },
            {data: 'ciudad', name: 'ciudad'},
            {data: 'consecutivo', name: 'consecutivo'},
            {
                sortable: false,
                "render": function (data, type, full, meta) {
                    var btn_edit = '';
                    var btn_delete = '';
                    if (permission_update) {
                        var btn_edit = '<a href="master/create/' + full.id + '" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="material-icons">&#xE254;</i></a>';
                    }
                    if (permission_delete) {
                        var btn_delete = '<a onclick=\"modalEliminar()\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="material-icons">&#xE872;</i></a>';
                    }
                    var btns = "<div class='btn-group'>" +
                     "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                      "<i class='material-icons' style='vertical-align:  middle;'>print</i> <span class='caret'></span>" +
                       "</button>" + 
                       "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='master/imprimir/" +full.id + '/'+true +
                        "' target='_blank'> <spam class='fa fa-print'></spam> Master</a></li>" +
                         "<li><a href='master/imprimir/" +full.id +"' target='_blank'> <spam class='fa fa-print'></spam> Master simple</a></li>" + 
                         "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Contrato</a></li>" + 
                         "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> TSA</a></li>" + 
                         "</ul></div>";
                    return btn_edit + btns + btn_delete;
                }
            }
        ]
    });
});

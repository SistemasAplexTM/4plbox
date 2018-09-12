$(document).ready(function () {
    $('#tbl-bill').DataTable({
        ajax: 'bill/all',
        "order": [[ 1, "desc" ]],
        columns: [
            {data: 'num_bl', name: 'num_bl'},
            {data: 'date_document', name: 'date_document'},
            {data: 'point_origin', name: 'point_origin'},
            {data: 'loading_pier', name: 'loading_pier'},
            {data: 'foreign_port', name: 'foreign_port'},
            {data: 'peso_kl', name: 'peso_kl'},
            {
                sortable: false,
                "render": function (data, type, full, meta) {
                    var btn_edit = '';
                    var btn_delete = '';
                    // if (permission_update) {
                        var btn_edit = '<a href="bill/create/' + full.id + '" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="material-icons">&#xE254;</i></a>';
                    // }
                    // if (permission_delete) {
                        var btn_delete = '<a onclick=\"eliminar(' + full.id + ',' + true + ')\" class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="material-icons">&#xE872;</i></a>';
                    // }
                    // var btns = "<div class='btn-group'>" +
                    //  "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                    //   "<i class='material-icons' style='vertical-align:  middle;'>print</i> <span class='caret'></span>" +
                    //    "</button>" + 
                    //    "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='bill/imprimir/" +full.id + '/'+true +
                    //     "' target='_blank'> <spam class='fa fa-print'></spam> Bill of lading</a></li>" +
                    //      "</ul></div>";
                        var btn_print = '<a href="bill/imprimir/' + full.id + '/'+true + '" target="_blank" class="edit" title="Imprimir" data-toggle="tooltip" style="color:#676a6c;"><i class="material-icons">print</i></a>';
                    return btn_edit + btn_print + btn_delete;
                }
            }
        ]
    });
});


/* objetos VUE index */
var objVue = new Vue({
    el: '#bill',
    data: {
        //
    },
    methods: {
        delete: function(data) {
            axios.get('bill/delete/' + data.id + '/' + data.logical).then(response => {
                refreshTable('tbl-bill');
                toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                toastr.options.closeButton = true;
            });
        },
        rollBackDelete: function(data) {
            var urlRestaurar = 'bill/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                refreshTable('tbl-bill');
            });
        },
    }
})
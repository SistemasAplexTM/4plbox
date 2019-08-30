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
                        var btn_edit = '<a href="bill/create/' + full.id + '" class="edit_btn" title="Editar" data-toggle="tooltip"><i class="fal fa-pencil fa-lg"></i></a>';
                    // }
                    // if (permission_delete) {
                        var btn_delete = '<a onclick=\"eliminar(' + full.id + ',' + true + ')\" class="delete_btn" title="Eliminar" data-toggle="tooltip"><i class="fal fa-trash-alt fa-lg"></i></a>';
                    // }
                    // var btns = "<div class='btn-group'>" +
                    //  "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                    //   "<i class='material-icons' style='vertical-align:  middle;'>print</i> <span class='caret'></span>" +
                    //    "</button>" +
                    //    "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='bill/imprimir/" +full.id + '/'+true +
                    //     "' target='_blank'> <spam class='fal fa-print'></spam> Bill of lading</a></li>" +
                    //      "</ul></div>";
                        var btn_print = '<a href="bill/imprimir/' + full.id + '/'+true + '" target="_blank" class="edit" title="Imprimir" data-toggle="tooltip" style="color:#676a6c;"><i class="fal fa-print fa-lg"></i></a>';
                    return btn_edit + btn_print + btn_delete;
                }
            }
        ]
    });
});


/* objetos VUE index */
var objVue = new Vue({
    el: '#bill',
    mounted(){
      this.getData();
    },
    data: {
      options: [],
      consolidado_id: null,
      list: [],
      loading: false,
    },
    methods: {
        delete: function(data) {
            axios.get('bill/delete/' + data.id + '/' + data.logical).then(response => {
                refreshTable('tbl-bill');
                toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
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
        getData(){
          var me = this;
          axios.post('master/getDataConsolidados/1').then(function(response) {
              me.options = response.data;
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
          });
        },
        createBill(){
          var consolidado_id = null;
          if(this.consolidado_id != null){
            consolidado_id = this.consolidado_id.id;
          }
          location.href = "bill/create/" + null + '/' + consolidado_id;
        }
    }
})

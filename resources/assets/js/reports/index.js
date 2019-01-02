$(document).ready(function() {
    //
});
var listDocument = function() {
    $('#tbl-report').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": 'documento/all/documento_detalle',
            "data": function(d) {
                d.id_tipo_doc = 0;
                d.status_id = 0;
            }
        },
        columns: [{
            data: 'fecha',
            name: 'documento.created_at',
            width: 80
        },{
            data: 'fecha',
            name: 'documento.created_at',
            width: 80
        }, {
            data: 'cons_nomfull',
            name: 'consignee.nombre_full'
        },{
            data: 'fecha',
            name: 'documento.created_at',
            width: 80
        },{
            data: 'fecha',
            name: 'documento.created_at',
            width: 80
        }]
    });
}

/* objetos VUE index */
var objVue = new Vue({
    el: '#reports',
    mounted: function() {
      //
    },
    data: {
      //
    },
    methods: {
      //
    },
});

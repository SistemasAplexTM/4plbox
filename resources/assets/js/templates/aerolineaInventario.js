$(document).ready(function() {

});

function datatableCreate(table, used) {
  $('#tbl-' + table).DataTable({
      lengthMenu: [[20, 40, 50, 80, 100, 200, 500], [20, 40, 50, 80, 100, 200, 500]],
      ajax: {
          "url": 'aerolinea_inventario/all',
          "data": function(d) {
              d.used = used;
          }
      },
      columns: [{
          data: 'consecutivo_creacion',
          name: 'consecutivo_creacion'
      }, {
          data: 'aerolinea',
          name: 'aerolinea'
      }, {
          data: 'guia',
          name: 'guia',
          "render": function (data, type, full, meta) {
            return full.codigo + ' ' + full.guia;
          },
      }, {
          sortable: false,
          "render": function(data, type, full, meta) {
              var btn_edit = '';
              var btn_delete = '';
              var btns = '';
              // if (permission_update) {
                  var params = [
                      full.id,
                      full.aerolinea_id, "'" + full.aerolinea + "'", "'" + full.guia + "'"
                  ];
                  var btn_edit = "<a onclick=\"edit(" + params + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
              // }
              // if (permission_delete) {
                  if (full.usado == '0') {
                      btns += btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                  }
              // }
              return btns;
          }
      }],
      "drawCallback": function(settings) {
          var api = this.api();
          var rows = api.rows({
              page: 'current'
          }).nodes();
          var last = null;
          api.column(1, {
              page: 'current'
          }).data().each(function(group, i) {
              if (last !== group) {
                  // var btn_delete = " <a onclick=\"eliminar(" + i + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash'></i></a> ";
                  $(rows).eq(i).before('<tr class="group"><td colspan="3"><h3>' + group + '</h3></td></tr>');
                  last = group;
              }
          });
      },
      "columnDefs": [{
          "targets": 0,
          "width": "0%",
          "visible": false,
          "searchable": false
      },{
          "targets": 1,
          "width": "50%"
      }, {
          "targets": 2,
          "width": "40%"
      }, ]
  });
}

function edit(id) {
    var data = {
        id: id
    };
    objVue.edit(data);
}
/* objetos VUE index */
var objVue = new Vue({
    el: '#aerolineaInventario',
    data: {
        id: null,
        aerolinea_id: null,
        aerolinea: null,
        guia: null,
        cantidad: null,
        editar: false,
        aerolineas: [],
    },
    created() {
        this.getAerolinea();
    },
    mounted(){
      datatableCreate('aerolinea_inventario', 0);
      datatableCreate('aerolinea_inventario1', 1);
    },
    methods: {
        create: function(data) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    axios.post('aerolinea_inventario', {
                        'aerolinea_id': this.aerolinea_id.id,
                        'guia': this.guia,
                        'cantidad': this.cantidad
                    }).then(response => {
                        this.updateTable();
                        this.resetForm();
                        toastr.success("<div><p>Registro exitoso");
                        toastr.options.closeButton = true;
                    });
                }
            });
        },
        delete: function(data) {
            axios.get('aerolinea_inventario/delete/' + data.id).then(response => {
                toastr.success("<div><p>Registro eliminado");
                toastr.options.closeButton = true;
                this.updateTable();
            });
        },
        getAerolinea: function() {
            axios.get('transport/aerolineas/all').then(response => {
                this.aerolineas = response.data.data;
            });
        },
        update: function() {
            axios.put('aerolinea_inventario/' + this.id, {
                'aerolinea_id': this.aerolinea_id,
                'guia': this.guia,
                'cantidad': this.cantidad
            }).then(function(response) {
                var me = this;
                if (response.data['code'] == 200) {
                    toastr.success('Registro actualizado correctamente');
                    toastr.options.closeButton = true;
                    me.editar = 0;
                    me.resetForm();
                    me.updateTable();
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                    console.log(response.data);
                }
            }).catch(function(error) {
                toastr.error("Error.", {
                    timeOut: 50000
                });
            });
        },
        edit: function(data) {
            this.id = data['id'];
            this.aerolinea_id = data['aerolinea_id'];
            this.guia = data['guia'];
            this.cantidad = data['cantidad'];
            this.editar = 1;
        },
        updateTable: function() {
            refreshTable('tbl-aerolinea_inventario');
        },
        resetForm: function() {
            this.id = null;
            this.aerolinea_id = null;
            this.guia = null;
            this.cantidad = null;
            this.editar = 0;
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});

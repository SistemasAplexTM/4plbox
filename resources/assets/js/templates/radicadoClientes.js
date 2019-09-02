$(document).ready(function() {
    $('#tbl-clientes').DataTable({
        ajax: 'radicado_clientes/all',
        columns: [{
            data: 'documento',
            name: 'documento'
        }, {
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'direccion',
            name: 'direccion'
        }, {
            data: 'telefono',
            name: 'telefono'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                // if (permission_update) {
                    var params = [
                        full.id, "'" + full.documento + "'", "'" + full.nombre + "'",
                        "'" + full.direccion + "'",
                        "'" + full.telefono + "'",
                        "'" + full.correo + "'"
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-edit'></i></a> ";
                // }
                // if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash'></i></a> ";
                // }
                return btn_edit + btn_delete;
            }
        }]
    });
});

function edit(id, documento, nombre, direccion, telefono, correo) {
    var data = {
        id: id,
        documento: documento,
        nombre: nombre,
        direccion: direccion,
        telefono: telefono,
        correo: correo
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#clientes',
    mounted: function() {
      const dict = {
          custom: {
              documento: {
                  required: 'Campo obligatorio'
              },
              nombre: {
                  required: 'Campo obligatorio'
              },
              direccion: {
                  required: 'Campo obligatorio'
              },
              telefono: {
                  required: 'Campo obligatorio'
              }
          }
      };
      this.$validator.localize('es', dict);
    },
    data: {
        documento: null,
        nombre: null,
        direccion: null,
        telefono: null,
        correo: null,
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        resetForm: function() {
            this.id = null;
            this.documento = null;
            this.nombre = null;
            this.direccion = null;
            this.telefono = null;
            this.correo = null;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
        },
        /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
        deleteError: function(element) {
            let me = this;
            $.each(me.errors.items, function(key, value) {
                if (value.field === element) {
                    me.errors.items.splice(key, 1);
                }
            });
        },
        rollBackDelete: function(data) {
            var urlRestaurar = 'radicado_clientes/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-clientes');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('radicado_clientes/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('radicado_clientes/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            this.$validator.validateAll().then((result) => {
                if (result) {
                  axios.post('radicado_clientes', {
                      'documento': this.documento,
                      'nombre': this.nombre,
                      'direccion': this.direccion,
                      'telefono': this.telefono,
                      'correo': this.correo
                  }).then(function(response) {
                      if (response.data['code'] == 200) {
                          toastr.success('Registro creado correctamente.');
                          toastr.options.closeButton = true;
                      } else {
                          toastr.warning(response.data['error']);
                          toastr.options.closeButton = true;
                      }
                      me.resetForm();
                      me.updateTable();
                  }).catch(function(error) {
                      console.log(error);
                      toastr.error("Error." + error, {
                          timeOut: 50000
                      });
                  });
                } else {
                    toastr.warning("Error. Porfavor verifica los datos ingresados.<br>");
                }
            }).catch(function(error) {
                console.log(error);
                toastr.warning('Error: -' + error);
            });
        },
        update: function() {
            var me = this;
            axios.put('radicado_clientes/' + this.id, {
              'documento': this.documento,
              'nombre': this.nombre,
              'direccion': this.direccion,
              'telefono': this.telefono,
              'correo': this.correo
            }).then(function(response) {
                if (response.data['code'] == 200) {
                    toastr.success('Registro Actualizado correctamente');
                    toastr.options.closeButton = true;
                    me.editar = 0;
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                    console.log(response.data);
                }
                me.resetForm();
                me.updateTable();
            }).catch(function(error) {
              console.log(error);
                toastr.error("Error " + error, {
                    timeOut: 50000
                });
            });
        },
        edit: function(data) {
            this.id = data['id'];
            this.documento = data['documento'];
            this.nombre = data['nombre'];
            this.direccion = data['direccion'];
            this.telefono = data['telefono'];
            this.correo = data['correo'];
            this.editar = 1;
            this.formErrors = {};
            this.listErrors = {};
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});

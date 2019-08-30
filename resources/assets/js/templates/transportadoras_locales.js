$(document).ready(function() {
    $('#tbl-transportadoras_locales').DataTable({
        ajax: 'transportadoras_locales/all',
        columns: [{
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'url_rastreo',
            name: 'url_rastreo'
        }, {
            data: 'pais',
            name: 'b.descripcion'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                var params = [
                    full.id, +full.pais_id, "'" + full.nombre + "'", "'" + full.url_rastreo + "'"
                ];
                var btn_edit = "<a onclick=\"edit(" + params + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                return btn_edit + btn_delete;
            },
            width: 100
        }]
    });
});


function edit(id, pais_id, nombre, url_rastreo) {
    var data = {
        id: id,
        pais_id: pais_id,
        nombre: nombre,
        url_rastreo: url_rastreo
    };
    objVue.edit(data);
}

/* objeto VUE */
var objVue = new Vue({
    el: '#transportadoras_locales',
    data: {
      id: null,
      paises: [],
      pais_id: null,
      nombre: null,
      url_rastreo: null,
      editar: 0,
      formErrors: {},
      listErrors: {}
    },
    mounted: function() {
      this.getPaises()
    },
    methods: {
        resetForm: function() {
            this.id = null;
            this.pais_id = null;
            this.nombre = null;
            this.url_rastreo = null;
            this.editar = 0;
        },
        getPaises: function(){
          let me = this
          axios.get('transportadoras_locales/getPaises').then(response => {
              me.paises = response.data
          });
        },
        /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
        deleteError: function(element) {
            let me = this;
            $.each(me.listErrors, function(key, value) {
                if (key !== element) {
                    me.listErrors[key] = value;
                } else {
                    me.listErrors[key] = false;
                }
            });
        },
        rollBackDelete: function(data) {
            var urlRestaurar = 'transportadoras_locales/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-transportadoras_locales');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('transportadoras_locales/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('transportadoras_locales/' + data.id).then(response => {
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
                    axios.post('transportadoras_locales', {
                        'pais_id': this.pais_id,
                        'nombre': this.nombre,
                        'url_rastreo': this.url_rastreo
                    }).then(function(response) {
                        if (response.data['code'] == 200) {
                            toastr.success('Registro creado correctamente.');
                            toastr.options.closeButton = true;
                            me.resetForm();
                            me.updateTable();
                        } else {
                            toastr.warning(response.data['error']);
                            toastr.options.closeButton = true;
                        }
                    }).catch(function(error) {
                      console.log(error);
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
            axios.put('transportadoras_locales/' + this.id, {
              'pais_id': this.pais_id,
              'nombre': this.nombre,
              'url_rastreo': this.url_rastreo
            }).then(function(response) {
              console.log('error');
                if (response.data['code'] == 200) {
                    toastr.success('Registro Actualizado correctamente');
                    toastr.options.closeButton = true;
                    me.editar = 0;
                    me.resetForm();
                    me.updateTable();
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                }
            }).catch(function(error) {
                toastr.error("Porfavor completa los campos obligatorios.", {
                    timeOut: 50000
                });
            });
        },
        edit: function(data) {
            var me = this;
            me.resetForm();
            this.id = data['id'];
            this.nombre = data['nombre'];
            this.url_rastreo = data['url_rastreo'];
            this.pais_id = data['pais_id'];
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

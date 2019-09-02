$(document).ready(function() {
    llenarSelect('ciudad', 'pais', 'pais_id', 2); // module, tableName, id_campo
    $('#tbl-ciudad').DataTable({
        ajax: 'ciudad/all',
        columns: [{
            data: 'prefijo',
            name: 'prefijo'
        }, {
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'deptos',
            name: 'deptos.descripcion'
        }, {
            data: 'pais',
            name: 'pais.descripcion'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.prefijo + "'", "'" + full.nombre + "'", "'" + full.pais_id + "'", "'" + full.pais + "'", "'" + full.deptos_id + "'", "'" + full.deptos + "'",
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
});

function edit(id, prefijo, nombre, pais_id, pais, deptos_id, deptos) {
    var data = {
        id: id,
        prefijo: prefijo,
        nombre: nombre,
        pais_id: pais_id,
        pais: pais,
        deptos_id: deptos_id,
        deptos: deptos,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#ciudad',
    mounted: function() {
        //
    },
    data: {
        prefijo: '',
        nombre: '',
        pais_id: '',
        deptos_id: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.prefijo = '';
            this.nombre = '';
            this.pais_id = '';
            this.deptos_id = '';
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#pais_id').select2("val", "");
            $('#pais_id_input').val('');
            $('#deptos_id').select2("val", "");
            $('#deptos_id_input').val('');
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
            var urlRestaurar = 'ciudad/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-ciudad');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('ciudad/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('ciudad/' + data.id).then(response => {
                    if(response.data.code == 200){
                        this.updateTable();
                        toastr.success('Registro eliminado correctamente.');
                        toastr.options.closeButton = true;
                    }else{
                        toastr.error("Error: " + response.data.error, {timeOut: 50000});
                    }
                });
            }
        },
        create: function() {
            let me = this;
            axios.post('ciudad', {
                'created_at': new Date(),
                'nombre': this.nombre,
                'prefijo': this.prefijo,
                'deptos_id': $('#deptos_id_input').val(),
                'pais_id': $('#pais_id_input').val(),
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
                if (error.response.status === 422) {
                    me.formErrors = error.response.data; //guardo los errores
                    me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function(key, value) {
                    $('.result-' + key).html(value);
                });
                toastr.error("Porfavor completa los campos obligatorios.", {
                    timeOut: 50000
                });
            });
        },
        update: function() {
            var me = this;
            axios.put('ciudad/' + this.id, {
                'nombre': this.nombre,
                'prefijo': this.prefijo,
                'deptos_id': $('#deptos_id_input').val(),
                'pais_id': $('#pais_id_input').val(),
            }).then(function(response) {
                if (response.data['code'] == 200) {
                    toastr.success('Registro Actualizado correctamente');
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
                if (error.response.status === 422) {
                    me.formErrors = error.response.data;
                    me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function(key, value) {
                    $('.result-' + key).html(value);
                });
                toastr.error("Porfavor completa los campos obligatorios.", {
                    timeOut: 50000
                });
            });
        },
        edit: function(data) {
            this.id = data['id'];
            this.nombre = data['nombre'];
            this.prefijo = data['prefijo'];
            $('#pais_id_input').val(data['pais_id']);
            $('#deptos_id_input').val(data['deptos_id']);
            /* ASIGNACION DE VALORES A LOS SELECTS */
            $('#pais_id').empty().append('<option value="' + data['pais_id'] + '" selected="selected">' + data['pais'] + '</option>').val([data['pais_id']]).trigger('change');
            $('#deptos_id').empty().append('<option value="' + data['deptos_id'] + '" selected="selected">' + data['deptos'] + '</option>').val([data['deptos_id']]).trigger('change');
            llenarSelect('ciudad', 'deptos', 'deptos_id', 0, $('#pais_id_input').val()); // module, tableName, id_campo, id_condition
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

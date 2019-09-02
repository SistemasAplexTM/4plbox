$(document).ready(function() {
    llenarSelect('departamento', 'pais', 'pais_id', 2); // module, tableName, id_campo
    $('#tbl-departamento').DataTable({
        ajax: 'departamento/all',
        columns: [{
            data: 'descripcion',
            name: 'descripcion'
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
                        full.id, "'" + full.descripcion + "'", "'" + full.pais_id + "'", "'" + full.pais + "'",
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

function edit(id, descripcion, pais_id, pais) {
    var data = {
        id: id,
        descripcion: descripcion,
        pais_id: pais_id,
        pais: pais,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#departamento',
    mounted: function() {
        //
    },
    data: {
        descripcion: '',
        pais_id: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.descripcion = '';
            this.pais_id = '';
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#pais_id').select2("val", "");
            $('#pais_id_input').val('');
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
            var urlRestaurar = 'departamento/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-departamento');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('departamento/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('departamento/' + data.id).then(response => {
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
            axios.post('departamento', {
                'created_at': new Date(),
                'descripcion': this.descripcion,
                'pais_id': $('#pais_id').val(),
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
            axios.put('departamento/' + this.id, {
                'descripcion': this.descripcion,
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
            console.log(data);
            this.id = data['id'];
            this.descripcion = data['descripcion'];
            $('#pais_id_input').val(data['pais_id']);
            /* ASIGNACION DE VALORES A LOS SELECTS */
            var data = {
                id: data['pais_id'],
                text: data['pais']
            };
            var newOption = new Option(data.text, data.id, true, true);
            $('#pais_id').append(newOption).trigger('change');
            // $('#pais_id').empty().append('<option value="' + data['pais_id'] + '" selected="selected">' + data['pais'] + '</option>').val([data['pais_id']]).trigger('change');
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

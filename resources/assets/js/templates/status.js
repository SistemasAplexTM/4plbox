$(document).ready(function() {
    $('#tbl-status').DataTable({
        ajax: 'status/all',
        columns: [{
            data: 'descripcion',
            name: 'descripcion'
        }, {
            data: 'color',
            name: 'color',
            "render": function(data, type, full, meta) {
                var color = full.color;
                return '<spam style="background-color:' + color + '; padding-right:50px; border-radius: 10px; color: #ffffff;">&nbsp;</spam> <li class="fa fa-arrow-right"></li> ' + color;
            }
        }, {
            data: 'email',
            name: 'email',
            "render": function(data, type, full, meta) {
                var mail = full.email;
                if (mail == 1) {
                    return '<i class="fa fa-envelope text-navy"></i>';
                } else {
                    return '<i class="fa fa-envelope text-danger"></i>';
                }
            }
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.descripcion + "'", "'" + full.color + "'", "'" + full.email + "'",
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
    $('.i-checks').iCheck({
        // checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

function edit(id, descripcion, color, email) {
    var data = {
        id: id,
        descripcion: descripcion,
        color: color,
        email: email,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#status',
    mounted: function() {
        //
    },
    data: {
        descripcion: '',
        color: '',
        email: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.descripcion = '';
            this.color = '';
            this.email = '';
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#email_s').iCheck('uncheck').prop('checked', false);
            $('#email_n').iCheck('check').prop('checked', true);
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
            var urlRestaurar = 'status/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-status');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('status/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('status/' + data.id).then(response => {
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
            if ($('#email_s').is(':checked')) {
                this.email = 1;
            } else {
                this.email = 0;
            }
            axios.post('status', {
                'created_at': new Date(),
                'descripcion': this.descripcion,
                'color': this.color,
                'email': this.email,
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
            if ($('#email_s').is(':checked')) {
                this.email = 1;
            } else {
                this.email = 0;
            }
            axios.put('status/' + this.id, {
                'descripcion': this.descripcion,
                'color': this.color,
                'email': this.email,
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
            this.descripcion = data['descripcion'];
            this.color = data['color'];
            /* Chekear los radios del campo email*/
            if (data['email'] == 1) {
                $('#email_s').iCheck('check').prop('checked', true);
            } else {
                $('#email_n').iCheck('check').prop('checked', true);
            }
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
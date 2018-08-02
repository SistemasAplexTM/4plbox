$(document).ready(function() {
    selectWhithFontAwesome();
    llenarSelect('administracion/7', 'maestra_multiple', 'funcionalidades', 0); // module, tableName, id_campo
    llenarSelect('tipoDocumento', 'credencial', 'credenciales', 0); // module, tableName, id_campo
    $('#tbl-tipoDocumento').DataTable({
        ajax: 'tipoDocumento/all',
        columns: [{
            data: 'prefijo',
            name: 'prefijo'
        }, {
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'name_plantilla',
            name: 'name_plantilla'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                var id_email = 'null'
                if (full.email_plantilla_id != null) {
                    id_email = full.email_plantilla_id;
                }
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.nombre + "'", "'" + full.prefijo + "'", "'" + full.icono + "'", "'" + full.consecutivo_inicial + "'", "'" + full.funcionalidades + "'", "'" + full.credenciales + "'",
                        id_email, "'" + full.descripcion_plantilla + "'", "'" + full.email_copia + "'", "'" + full.email_copia_oculta + "'",
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
});

function selectWhithFontAwesome() {
    $.getJSON('./json/fa-icons.json', function(data) {
        $(".ajaxLoadFontAwesome").append('<option value="">Seleccione</option>');
        $.each(data, function(key, value) {
            var val = key.substring(3);
            $(".ajaxLoadFontAwesome").append('<option value="' + val + '" data-icon="fa ' + key + '">' + value + '</option>');
        });
    });
    setTimeout(function() {
        $('.ajaxLoadFontAwesome').selectpicker();
    }, 2000);
}

function edit(id, nombre, prefijo, icono, consecutivo_inicial, funcionalidades, credenciales, email_plantilla_id, descripcion_plantilla, email_copia, email_copia_oculta) {
    var data = {
        id: id,
        nombre: nombre,
        prefijo: prefijo,
        icono: icono,
        consecutivo_inicial: consecutivo_inicial,
        funcionalidades: funcionalidades,
        credenciales: credenciales,
        email_plantilla_id: email_plantilla_id,
        descripcion_plantilla: descripcion_plantilla,
        email_copia: email_copia,
        email_copia_oculta: email_copia_oculta,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#tipoDocumento',
    mounted: function() {
        this.getPlantillasEmail();
    },
    data: {
        nombre: '',
        prefijo: '',
        icono: '',
        consecutivo_inicial: 1,
        funcionalidades: '',
        credenciales: '',
        mostrar_correos_add: false,
        editar: 0,
        formErrors: {},
        listErrors: {},
        email_copia: null,
        email_copia_oculta: null,
        email_plantilla_id: null,
        plantillas: [],
    },
    watch: {
        email_plantilla_id: function(newQuestion) {
            this.mostrar_correos_add = false;
            if (newQuestion != null) {
                this.mostrar_correos_add = true;
                setTimeout(function() {
                    $('.email_copia').tagsinput({
                        tagClass: 'label label-info'
                    });
                }, 100);
            }
        }
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.nombre = '';
            this.prefijo = '';
            this.consecutivo_inicial = 1;
            $('.ajaxLoadFontAwesome').selectpicker('val', '');
            $('#funcionalidades').val(''); // Select the option with a value of '1'
            $('#funcionalidades').trigger('change'); // Notify any JS components that the value changed
            $('#credenciales').val(''); // Select the option with a value of '1'
            $('#credenciales').trigger('change'); // Notify any JS components that the value changed
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            this.mostrar_correos_add = false;
            this.email_plantilla_id = null;
            this.email_copia = null;
            this.email_copia_oculta = null;
        },
        getPlantillasEmail: function() {
            let me = this;
            axios.get('tipoDocumento/getPlantillasEmail').then(response => {
                me.plantillas = response.data.data;
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
            var urlRestaurar = 'tipoDocumento/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-tipoDocumento');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('tipoDocumento/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('tipoDocumento/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            var plantilla_id = '';
            if(this.email_plantilla_id != null && this.email_plantilla_id != 'null'){
                plantilla_id = this.email_plantilla_id.id;
            }
            axios.post('tipoDocumento', {
                'created_at': new Date(),
                'nombre': this.nombre,
                'prefijo': this.prefijo,
                'consecutivo_inicial': this.consecutivo_inicial,
                'icono': $('.ajaxLoadFontAwesome').selectpicker('val'),
                'funcionalidades': $('#funcionalidades').select2('data'),
                'credenciales': $('#credenciales').select2('data'),
                'email_copia': $('#email_copia').val(),
                'email_copia_oculta': $('#email_copia_oculta').val(),
                'email_plantilla_id': plantilla_id,
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
            var plantilla_id = '';
            if(this.email_plantilla_id != null && this.email_plantilla_id != 'null'){
                plantilla_id = this.email_plantilla_id.id;
            }
            axios.put('tipoDocumento/' + this.id, {
                'nombre': this.nombre,
                'prefijo': this.prefijo,
                'consecutivo_inicial': this.consecutivo_inicial,
                'icono': $('.ajaxLoadFontAwesome').selectpicker('val'),
                'funcionalidades': $('#funcionalidades').select2('data'),
                'credenciales': $('#credenciales').select2('data'),
                'email_copia': $('#email_copia').val(),
                'email_copia_oculta': $('#email_copia_oculta').val(),
                'email_plantilla_id': plantilla_id,
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
            var me = this;
            me.resetForm();
            this.id = data['id'];
            this.nombre = data['nombre'];
            this.prefijo = data['prefijo'];
            this.consecutivo_inicial = data['consecutivo_inicial'];
            $('.ajaxLoadFontAwesome').selectpicker('val', data['icono']);
            /* ASIGNAR VALORES AL SELECT FUNCIONALIDADES */
            jsonFuncionalidades = JSON.parse(data['funcionalidades']);
            if (jsonFuncionalidades != null) {
                $.each(jsonFuncionalidades, function(i, item) {
                    $("#funcionalidades").append('<option value="' + item.id + '" selected="selected">' + item.name + '</option>');
                });
                $('#funcionalidades').trigger('change'); // Notify any JS components that the value changed
            }
            /* ASIGNAR VALORES AL SELECT CREDENCIALES */
            jsonCredenciales = JSON.parse(data['credenciales']);
            if (jsonCredenciales != null) {
                $.each(jsonCredenciales, function(i, item) {
                    $("#credenciales").append('<option value="' + item.id + '" selected="selected">' + item.name + '</option>');
                });
                $('#credenciales').trigger('change'); // Notify any JS components that the value changed
            }
            if (data['email_copia'] != 'null') {
                me.email_copia = data['email_copia'];
            } else {
                me.email_copia = null
            }
            if (data['email_copia_oculta'] != 'null') {
                me.email_copia_oculta = data['email_copia_oculta'];
            } else {
                me.email_copia_oculta = null;
            }
            if (data['email_plantilla_id'] != null) {
                me.email_plantilla_id = {
                    id: data['email_plantilla_id'],
                    name: data['descripcion_plantilla']
                };
                me.mostrar_correos_add = true;
            } else {
                me.email_plantilla_id = null;
                me.mostrar_correos_add = false;
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
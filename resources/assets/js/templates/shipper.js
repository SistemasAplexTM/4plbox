$(document).ready(function() {
    llenarSelectP('shipper', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    llenarSelect('shipper', 'agencia', 'agencia_id', 0); // module, tableName, id_campo
    $('#tbl-shipper').DataTable({
        ajax: 'shipper/all',
        columns: [{
            data: 'nombre_full',
            name: 'nombre_full'
        }, {
            data: 'telefono',
            name: 'telefono'
        }, {
            data: 'ciudad',
            name: 'localizacion.nombre'
        }, {
            data: 'zip',
            name: 'zip'
        }, {
            data: 'agencia',
            name: 'agencia.descripcion'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, +full.agencia_id, +full.localizacion_id, "'" + full.primer_nombre + "'", "'" + full.segundo_nombre + "'", "'" + full.primer_apellido + "'", "'" + full.segundo_apellido + "'", "'" + full.direccion + "'", "'" + full.telefono + "'", "'" + full.correo + "'", "'" + full.zip + "'", "'" + full.ciudad + "'", "'" + full.agencia + "'"
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                var btn_add_contact = "<button onclick=\"pasar_id(" + full.id + ")\" type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#mdl-contactos'><i data-toggle='tooltip' data-placement='top' title='Agregar contactos' class='fa fa-user-plus'></i></button> ";
                return btn_edit + btn_add_contact + btn_delete;
            }
        }]
    });
});
$(window).load(function() {
    $('#agencia_id').empty().append('<option value="' + data_agencia['id'] + '" selected="selected">' + data_agencia['descripcion'] + '</option>').val([data_agencia['id']]).trigger('change');
});

function pasar_id(id) {
    objVue.parametro = id;
}

function edit(id, agencia_id, localizacion_id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, direccion, telefono, correo, zip, ciudad, agencia) {
    var data = {
        id: id,
        agencia_id: agencia_id,
        localizacion_id: localizacion_id,
        primer_nombre: primer_nombre,
        segundo_nombre: segundo_nombre,
        primer_apellido: primer_apellido,
        segundo_apellido: segundo_apellido,
        direccion: direccion,
        telefono: telefono,
        correo: correo,
        zip: zip,
        ciudad: ciudad,
        agencia: agencia
    };
    objVue.edit(data);
}
/*-- Función para llenar select PERSONALIZADO --*/
function llenarSelectP(module, tableName, idSelect, length) {
    var url = module + '/selectInput/' + tableName;
    $('#' + idSelect).select2({
        // theme: "classic",
        placeholder: "Seleccionar",
        tokenSeparators: [','],
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                /*console.log(data.items);*/
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        minimumInputLength: length,
    }).on("change", function(e) {
        $('.select2-selection').css('border-color', '');
        $('#' + idSelect).siblings('small').css('display', 'none');
        $('#' + idSelect + '_input').val($('#' + idSelect).val());
    });
}

function formatRepo(repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fa fa-map-marker'></i> " + repo.text + " / " + repo.deptos + " / " + repo.pais + "</strong></div>";
    return markup;
}

function formatRepoSelection(repo) {
    return repo.text || repo.id + ' - ' + repo.text;
}
/* objeto VUE */
var objVue = new Vue({
    el: '#shipper',
    mounted: function() {
        //
    },
    data: {
        parametro: null,
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        direccion: '',
        telefono: '',
        correo: '',
        zip: '',
        localizacion_id: '',
        agencia_id: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
        existShipper: false,
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.localizacion_id = '';
            this.agencia_id = '';
            this.primer_nombre = '';
            this.segundo_nombre = '';
            this.primer_apellido = '';
            this.segundo_apellido = '';
            this.direccion = '';
            this.telefono = '';
            this.correo = '';
            this.zip = '';
            this.editar = 0;
            this.existShipper = false;
            this.formErrors = {};
            this.listErrors = {};
            $('#localizacion_id').select2("val", "");
            $('#localizacion_id_input').val('');
            // $('#agencia_id').select2("val", "");
            // $('#agencia_id_input').val('');
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
            var urlRestaurar = 'shipper/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-shipper');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('shipper/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('shipper/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            const isUnique = (value) => {
                return axios.post('shipper/existEmail', {
                    'email': value,
                    'agencia_id': $('#agencia_id_input').val()
                }).then((response) => {
                    return {
                        valid: response.data.valid,
                        data: {
                            message: response.data.message
                        }
                    };
                });
            }
            // The messages getter may also accept a third parameter that includes the data we returned earlier.
            this.$validator.extend('unique', {
                validate: isUnique,
                getMessage: (field, params, data) => {
                    return data.message;
                }
            });
            this.$validator.validateAll().then((result) => {
                if (result) {
                    axios.post('shipper', {
                        'created_at': new Date(),
                        'agencia_id': $('#agencia_id_input').val(),
                        'localizacion_id': $('#localizacion_id_input').val(),
                        'primer_nombre': this.primer_nombre,
                        'segundo_nombre': this.segundo_nombre,
                        'primer_apellido': this.primer_apellido,
                        'segundo_apellido': this.segundo_apellido,
                        'direccion': this.direccion,
                        'telefono': this.telefono,
                        'correo': this.correo,
                        'zip': this.zip,
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
                        /*console.log(error);*/
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
            axios.put('shipper/' + this.id, {
                'agencia_id': $('#agencia_id_input').val(),
                'localizacion_id': $('#localizacion_id_input').val(),
                'primer_nombre': this.primer_nombre,
                'segundo_nombre': this.segundo_nombre,
                'primer_apellido': this.primer_apellido,
                'segundo_apellido': this.segundo_apellido,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'correo': this.correo,
                'zip': this.zip,
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
                    /* console.log(response.data);*/
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
            /*console.log(data);*/
            this.id = data['id'];
            $('#localizacion_id_input').val(data['localizacion_id']);
            $('#agencia_id_input').val(data['agencia_id']);
            /* ASIGNACION DE VALORES A LOS SELECTS */
            $('#localizacion_id').empty().append('<option value="' + data['localizacion_id'] + '" selected="selected">' + data['ciudad'] + '</option>').val([data['localizacion_id']]).trigger('change');
            $('#agencia_id').empty().append('<option value="' + data['agencia_id'] + '" selected="selected">' + data['agencia'] + '</option>').val([data['agencia_id']]).trigger('change');
            this.primer_nombre = data['primer_nombre'];
            if (data['segundo_nombre'] != 'null' && data['segundo_nombre'] != '' && data['segundo_nombre'] != null) {
                this.segundo_nombre = data['segundo_nombre'];
            }
            this.primer_apellido = data['primer_apellido'];
            if (data['segundo_apellido'] != 'null' && data['segundo_apellido'] != '' && data['segundo_apellido'] != null) {
                this.segundo_apellido = data['segundo_apellido'];
            }
            this.direccion = data['direccion'];
            if (data['telefono'] != 'null' && data['telefono'] != '' && data['telefono'] != null) {
                this.telefono = data['telefono'];
            }
            if (data['correo'] != 'null' && data['correo'] != '' && data['correo'] != null) {
                this.correo = data['correo'];
            }
            if (data['zip'] != 'null' && data['zip'] != '' && data['zip'] != null) {
                this.zip = data['zip'];
            }
            this.editar = 1;
            this.existShipper = true;
            this.formErrors = {};
            this.listErrors = {};
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});
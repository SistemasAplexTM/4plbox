$(document).ready(function () {
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
            "render": function (data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var btn_edit = "<a onclick=\"edit(" + full.id + ")\" class='edit_btn'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = "<li><a onclick=\"eliminar(" + full.id + "," + true + ")\" style='color:red'><i class='fal fa-trash'></i> Eliminar</a></li>";
                }
                var btn = '<div class="btn-group">' +
                    '<button type="button" class="btn btn-success dropdown-toggle btn-xs btn-circle-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '<i class="fal fa-ellipsis-v"></i>' +
                    '</button>' +
                    '<ul class="dropdown-menu dropdown-menu-right pull-right">' +
                    "<li><a onclick=\"pasar_id(" + full.id + ", '" + full.nombre_full + "')\"><i class='fal fa-user-plus'></i> Agregar Contactos</a></li>" +
                    btn_delete + '</ul>' +
                    '</div>';
                return btn_edit + btn;
            }
        }]
    });
});
$(window).load(function () {
    $('#agencia_id').empty().append('<option value="' + data_agencia['id'] + '" selected="selected">' + data_agencia['descripcion'] + '</option>').val([data_agencia['id']]).trigger('change');
});

function pasar_id(id, name) {
    // objVue.parametro = id;s
    objVue.openRigthBar(id, name);
}

function edit(id) {
    objVue.edit(id);
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
            data: function (params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
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
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        minimumInputLength: length,
    }).on("change", function (e) {
        $('.select2-selection').css('border-color', '');
        $('#' + idSelect).siblings('small').css('display', 'none');
        $('#' + idSelect + '_input').val($('#' + idSelect).val());
    });
}

function formatRepo(repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fal fa-map-marker'></i> " + repo.text + " / " + repo.deptos + " / " + repo.pais + "</strong></div>";
    return markup;
}

function formatRepoSelection(repo) {
    return repo.text || repo.id + ' - ' + repo.text;
}
/* objeto VUE */
var objVue = new Vue({
    el: '#shipper',
    mounted: function () {
        //
    },
    data: {
        agency_data: data_agencia,
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
        shipper_id: null,
        payload: {
            field_id: this.shipper_id,
            table: 'shipper',
            agency: data_agencia
        }
    },
    methods: {
        openRigthBar(param, name) {
            var data = {
                component: 'add-contact',
                title: 'Contactos',
                icon: 'fal fa-users',
                id_c: param,
                name: name,
                table: 'shipper',
                hidden_btn: true,
                edit: false,
                agency: data_agencia
            }
            bus.$emit('open', data)
        },
        resetForm: function () {
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
        },
        /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
        deleteError: function (element) {
            let me = this;
            $.each(me.listErrors, function (key, value) {
                if (key !== element) {
                    me.listErrors[key] = value;
                } else {
                    me.listErrors[key] = false;
                }
            });
        },
        rollBackDelete: function (data) {
            var urlRestaurar = 'shipper/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function () {
            this.shipper_id = null;
            this.payload.field_id = null;
            refreshTable('tbl-shipper');
        },
        delete: function (data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('shipper/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
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
        create: function () {
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
                    }).then(function (response) {
                        if (response.data['code'] == 200) {
                            toastr.success('Registro creado correctamente.');
                            toastr.options.closeButton = true;
                            me.resetForm();
                            me.updateTable();
                        } else {
                            toastr.warning(response.data['error']);
                            toastr.options.closeButton = true;
                        }
                    }).catch(function (error) {
                        /*console.log(error);*/
                        if (error.response.status === 422) {
                            me.formErrors = error.response.data; //guardo los errores
                            me.listErrors = me.formErrors.errors; //genero lista de errores
                        }
                        $.each(me.formErrors.errors, function (key, value) {
                            $('.result-' + key).html(value);
                        });
                        toastr.error("Porfavor completa los campos obligatorios.", {
                            timeOut: 50000
                        });
                    });
                } else {
                    toastr.warning("Error. Porfavor verifica los datos ingresados.<br>");
                }
            }).catch(function (error) {
                console.log(error);
                toastr.warning('Error: -' + error);
            });
        },
        update: function () {
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
            }).then(function (response) {
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
            }).catch(function (error) {
                if (error.response.status === 422) {
                    me.formErrors = error.response.data;
                    me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function (key, value) {
                    $('.result-' + key).html(value);
                });
                toastr.error("Porfavor completa los campos obligatorios.", {
                    timeOut: 50000
                });
            });
        },
        edit: function (id) {
            var me = this;
            this.shipper_id = id;
            this.payload.field_id = id;
            this.editar = 1;
            this.existShipper = true;
            this.formErrors = {};
            this.listErrors = {};
        },
        cancel: function () {
            var me = this;
            me.resetForm();
        },
    },
});
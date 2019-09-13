$(document).ready(function() {
    llenarSelectP('clientes', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    $('#tbl-clientes').DataTable({
        ajax: 'clientes/all',
        columns: [{
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'telefono',
            name: 'telefono'
        }, {
            data: 'direccion',
            name: 'direccion'
        }, {
            data: 'ciudad',
            name: 'b.nombre'
        }, {
            data: 'zona',
            name: 'zona'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var btn_edit = "<a onclick=\"edit(" + full.id + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
});


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
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fal fa-map-marker'></i> " + repo.text + " / " + repo.deptos + " / " + repo.pais + "</strong></div>";
    return markup;
}

function formatRepoSelection(repo) {
    $('#depto').val(repo.deptos);
    $('#pais').val(repo.pais);
    return repo.text || repo.id + ' - ' + repo.text;
}
/* objeto VUE */
var objVue = new Vue({
    el: '#clientes',
    mounted: function() {
        //
    },
    data: {
        agency_data: data_agencia,
        cliente_id: null,
        localizacion_id: '',
        nombre: '',
        direccion: '',
        telefono: '',
        correo: '',
        zona: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
        payload: {field_id: this.cliente_id, table: 'clientes', agency: data_agencia }
    },
    methods: {
        resetForm: function() {
            this.id = null;
            this.localizacion_id = null;
            this.nombre = null;
            this.direccion = null;
            this.telefono = null;
            this.correo = null;
            this.zona = null;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#localizacion_id').select2("val", "");
            $('#localizacion_id_input').val('');
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
            var urlRestaurar = 'clientes/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
          this.payload.field_id = null;
          this.cliente_id = null;
            refreshTable('tbl-clientes');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('clientes/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('clientes/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            const isUnique = (value) => {
                return axios.post('clientes/existEmail', {
                    'email': value
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
                    axios.post('clientes', {
                        'localizacion_id': $('#localizacion_id_input').val(),
                        'nombre': this.nombre,
                        'direccion': this.direccion,
                        'telefono': this.telefono,
                        'email': this.correo,
                        'zona': this.zona
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
            axios.put('clientes/' + this.id, {
                'localizacion_id': $('#localizacion_id_input').val(),
                'nombre': this.nombre,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.correo,
                'zona': this.zona
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
        edit: function(id) {
          var me = this;
          this.payload.field_id = id;
          this.cliente_id = id;
            // me.resetForm();
            // this.id = data['id'];

            // $('#localizacion_id_input').val(data['localizacion_id']);

            /* ASIGNACION DE VALORES A LOS SELECTS */
            // setTimeout(function(){
            //     $('#localizacion_id').empty()
            //     .append('<option value="' + data['localizacion_id'] + '" selected="selected">' + data['ciudad'] + '</option>')
            //     .val([data['localizacion_id']])
            //     .trigger('change');
            // },200);
            //
            //
            // this.nombre = data['nombre'];
            // this.direccion = data['direccion'];
            // if (data['telefono'] != 'null' && data['telefono'] != '' && data['telefono'] != null) {
            //     this.telefono = data['telefono'];
            // }
            // if (data['correo'] != 'null' && data['correo'] != '' && data['correo'] != null) {
            //     this.correo = data['correo'];
            // }
            // if (data['zona'] != 'null' && data['zona'] != '' && data['zona'] != null) {
            //     this.zona = data['zona'];
            // }
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

$(document).ready(function() {
    llenarSelectP('consignee', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    llenarSelect('consignee', 'agencia', 'agencia_id', 0); // module, tableName, id_campo
    // llenarSelect('consignee', 'tipo_identificacion', 'tipo_identificacion_id', 0); // module, tableName, id_campo
    $('#tbl-consignee').DataTable({
        ajax: 'consignee/all',
        columns: [{
            data: 'po_box',
            name: 'po_box'
        }, {
            data: 'nombre_full',
            name: 'nombre_full'
        }, {
            data: 'telefono',
            name: 'telefono'
        }, {
            data: 'ciudad',
            name: 'localizacion.nombre'
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
                        full.id, +full.agencia_id, +full.tipo_identificacion_id, +full.localizacion_id, "'" + full.documento + "'", "'" + full.primer_nombre + "'", "'" + full.segundo_nombre + "'", "'" + full.primer_apellido + "'", "'" + full.segundo_apellido + "'", "'" + full.direccion + "'", "'" + full.telefono + "'", "'" + full.correo + "'", "'" + full.zip + "'", "'" + full.tarifa + "'", "'" + full.ciudad + "'", "'" + full.agencia + "'", "'" + full.identificacion + "'",  + full.cliente_id, "'" + full.cliente + "'"
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                var btn_add_contact = "<button onclick=\"pasar_id(" + full.id + ")\" type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#mdl-contactos'><i data-toggle='tooltip' data-placement='top' title='Agregar contactos' class='fa fa-user-plus'></i></button> ";
                var btn_casillero = " <a onclick=\"generarCasillero(" + full.id + ")\" class='btn btn-outline btn-info btn-xs' data-toggle='tooltip' data-placement='top' title='Generar casillero'><i class='fa fa-address-card'></i></a> ";
                return btn_edit + btn_casillero + btn_add_contact + btn_delete;
            }
        }]
    });
});
$(window).load(function() {
    $('#agencia_id').empty().append('<option value="' + data_agencia['id'] + '" selected="selected">' + data_agencia['descripcion'] + '</option>').val([data_agencia['id']]).trigger('change');
});

function generarCasillero(id) {
    objVue.generarCasillero(id);
}

function pasar_id(id) {
    objVue.parametro = id;
}

function edit(id, agencia_id, tipo_identificacion_id, localizacion_id, documento, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, direccion, telefono, correo, zip, tarifa, ciudad, agencia, identificacion, cliente_id, cliente) {
    var data = {
        id: id,
        tipo_identificacion_id: tipo_identificacion_id,
        agencia_id: agencia_id,
        localizacion_id: localizacion_id,
        documento: documento,
        primer_nombre: primer_nombre,
        segundo_nombre: segundo_nombre,
        primer_apellido: primer_apellido,
        segundo_apellido: segundo_apellido,
        direccion: direccion,
        telefono: telefono,
        correo: correo,
        zip: zip,
        tarifa: tarifa,
        ciudad: ciudad,
        agencia: agencia,
        identificacion: identificacion,
        cliente_id : cliente_id,
        cliente : cliente,
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
    $('#depto').val(repo.deptos);
    $('#pais').val(repo.pais);
    return repo.text || repo.id + ' - ' + repo.text;
}
/* objeto VUE */
var objVue = new Vue({
    el: '#consignee',
    mounted: function() {
        //
    },
    data: {
        parametro: null,
        tipo_identificacion_id: '',
        agencia_id: '',
        cliente_id: null,
        clientes: [],
        localizacion_id: '',
        // documento: '',
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        direccion: '',
        telefono: '',
        correo: '',
        zip: '',
        emailsend: false,
        tarifa: 0,
        editar: 0,
        formErrors: {},
        listErrors: {},
        ident: false //recordar descomentar las variables tipo_identificacion_id y documento
    },
    methods: {
        generarCasillero: function(id) {
            axios.get('consignee/generarCasillero/' + id).then(response => {
                toastr.success('Registro exitoso.');
                this.updateTable();
            }).catch(function(error) {
                console.log(error);
                toastr.error("Error.", {
                    timeOut: 50000
                });
            });
        },
        getZipCode: function() {
            var address = this.direccion + ', ' + $('#localizacion_id').text() + ', ' + $('#depto').val() + ', ' + $('#pais').val();
            var inputZip = 'zip';
            console.log(address);
            setDataGeocode(address, inputZip);
        },
        resetForm: function() {
            this.id = '';
            // this.tipo_identificacion_id = '';
            this.localizacion_id = '';
            this.agencia_id = '';
            this.primer_nombre = '';
            // this.documento = '';
            this.segundo_nombre = '';
            this.primer_apellido = '';
            this.segundo_apellido = '';
            this.direccion = '';
            this.telefono = '';
            this.correo = '';
            this.zip = '';
            this.tarifa = 0;
            this.emailsend = false;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#localizacion_id').select2("val", "");
            $('#localizacion_id_input').val('');
            this.cliente_id = null;
            this.clientes = [];
            // $('#tipo_identificacion_id').select2("val", "");
            // $('#tipo_identificacion_id_input').val('');
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
            var urlRestaurar = 'consignee/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-consignee');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('consignee/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('consignee/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            const isUnique = (value) => {
                return axios.post('consignee/existEmail', {
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
                    axios.post('consignee', {
                        'created_at': new Date(),
                        'tipo_identificacion_id': $('#tipo_identificacion_id_input').val(),
                        'agencia_id': $('#agencia_id_input').val(),
                        'localizacion_id': $('#localizacion_id_input').val(),
                        'documento': this.documento,
                        'primer_nombre': this.primer_nombre,
                        'segundo_nombre': this.segundo_nombre,
                        'primer_apellido': this.primer_apellido,
                        'segundo_apellido': this.segundo_apellido,
                        'direccion': this.direccion,
                        'telefono': this.telefono,
                        'correo': this.correo,
                        'zip': this.zip,
                        'tarifa': this.tarifa,
                        'emailsend': this.emailsend,
                        'cliente_id': (this.cliente_id != null) ? this.cliente_id.id : null,
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
            axios.put('consignee/' + this.id, {
                'tipo_identificacion_id': $('#tipo_identificacion_id_input').val(),
                'agencia_id': $('#agencia_id_input').val(),
                'localizacion_id': $('#localizacion_id_input').val(),
                'documento': this.documento,
                'primer_nombre': this.primer_nombre,
                'segundo_nombre': this.segundo_nombre,
                'primer_apellido': this.primer_apellido,
                'segundo_apellido': this.segundo_apellido,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'correo': this.correo,
                'zip': this.zip,
                'tarifa': this.tarifa,
                'emailsend': this.emailsend,
                'cliente_id': (this.cliente_id != null) ? this.cliente_id.id : null,
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
            $('#tipo_identificacion_id_input').val(data['tipo_identificacion_id']);
            $('#localizacion_id_input').val(data['localizacion_id']);
            $('#agencia_id_input').val(data['agencia_id']);
            /* ASIGNACION DE VALORES A LOS SELECTS */
            // if (data['tipo_identificacion_id'] != 'null' && data['tipo_identificacion_id'] != '' && data['tipo_identificacion_id'] != null) {
            // $('#tipo_identificacion_id').empty().append('<option value="' + data['tipo_identificacion_id'] + '" selected="selected">' + data['identificacion'] + '</option>').val([data['tipo_identificacion_id']]).trigger('change');
            // }
            $('#localizacion_id').empty().append('<option value="' + data['localizacion_id'] + '" selected="selected">' + data['ciudad'] + '</option>').val([data['localizacion_id']]).trigger('change');
            $('#agencia_id').empty().append('<option value="' + data['agencia_id'] + '" selected="selected">' + data['agencia'] + '</option>').val([data['agencia_id']]).trigger('change');
            // if (data['documento'] != 'null' && data['documento'] != '' && data['documento'] != null) {
            // this.documento = data['documento'];
            // }
            this.primer_nombre = data['primer_nombre'];
            if (data['segundo_nombre'] != 'null' && data['segundo_nombre'] != '' && data['segundo_nombre'] != null) {
                this.segundo_nombre = data['segundo_nombre'];
            }
            if (data['primer_apellido'] != 'null' && data['primer_apellido'] != '' && data['primer_apellido'] != null) {
                this.primer_apellido = data['primer_apellido'];
            }
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
            if(data['cliente_id'] != null && data['cliente_id'] != ''){
                this.cliente_id = {id: data['cliente_id'], name: data['cliente']}
            }
            this.tarifa = data['tarifa'];
            this.editar = 1;
            this.formErrors = {};
            this.listErrors = {};
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
        onSearchClientes(search, loading) {
              loading(true);
              this.searchCliente(loading, search, this);
            },
            searchCliente: _.debounce((loading, search, vm) => {
              fetch(
                `consignee/vueSelectClientes/${escape(search)}`
              ).then(res => {
                res.json().then(json => (vm.clientes = json.items));
                loading(false);
              });
            }, 300),
    },
});
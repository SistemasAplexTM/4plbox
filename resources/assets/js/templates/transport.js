var type = $('#tipo').val();
$(document).ready(function() {
    llenarSelect('aerolineas', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    $('#tbl-aerolineas').DataTable({
        ajax: type + '/all',
        columns: [{
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'direccion',
            name: 'direccion'
        }, {
            data: 'ciudad',
            name: 'localizacion.nombre'
        }, {
            data: 'estado',
            name: 'deptos.descripcion'
        }, {
            data: 'pais',
            name: 'pais.descripcion'
        }, {
            data: 'zip',
            name: 'zip'
        }, {
            data: 'codigo',
            name: 'codigo'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.nombre + "'", "'" + full.direccion + "'", "'" + full.zip + "'", "'" + full.ciudad_id + "'", "'" + full.ciudad + "'", "'" + full.estado_id + "'", "'" + full.estado + "'", "'" + full.pais_id + "'", "'" + full.pais + "'", "'" + full.tipo + "'", "'" + full.codigo + "'",
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
});

function edit(id, nombre, direccion, zip, ciudad_id, ciudad, estado_id, estado, pais_id, pais, $tipo, codigo) {
    var data = {
        id: id,
        nombre: nombre,
        direccion: direccion,
        zip: zip,
        ciudad_id: ciudad_id,
        ciudad: ciudad,
        tipo: tipo,
        codigo: codigo,
    };
    objVue.edit(data);
}
/*-- Función para llenar select PERSONALIZADO --*/
function llenarSelect(module, tableName, idSelect, length) {
    var url = '../transport/selectInput/' + tableName;
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
                console.log(data.items);
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
    el: '#transport',
    mounted: function() {
        //
    },
    data: {
        nombre: '',
        direccion: '',
        zip: '',
        localizacion_id: '',
        tipo: type,
        codigo: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        resetForm: function() {
            this.id = '';
            this.nombre = '';
            this.direccion = '';
            this.zip = '';
            this.localizacion_id = '';
            this.codigo = '';
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
            var urlRestaurar = 'transport/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-aerolineas');
        },
        delete: function(data) {
            this.resetForm();
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('../transport/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                }).catch(function(error) {
                    console.log(error);
                    toastr.error("Error: "+ error, {
                        timeOut: 50000
                    });
                });
            } else {
                axios.delete('../transport/' + data.id).then(response => {
                    if(response.data.code == 200){
                        this.updateTable();
                        toastr.success('Registro eliminado correctamente.');
                        toastr.options.closeButton = true;
                    }else{
                        toastr.error("Error: " + response.data.error, {timeOut: 50000});
                    }
                }).catch(function(error) {
                    console.log(error);
                    toastr.error("Error: "+ error, {
                        timeOut: 50000
                    });
                });
            }
        },
        create: function() {
            let me = this;
            axios.post('transport', {
                'created_at': new Date(),
                'nombre': this.nombre,
                'direccion': this.direccion,
                'zip': this.zip,
                'tipo': this.tipo,
                'codigo': this.codigo,
                'localizacion_id': $('#localizacion_id_input').val(),
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
            axios.put('transport/' + this.id, {
                'nombre': this.nombre,
                'direccion': this.direccion,
                'zip': this.zip,
                'tipo': type,
                'codigo': this.codigo,
                'localizacion_id': $('#localizacion_id_input').val()
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
            this.direccion = '';
            if (data['direccion'] != 'null') {
                this.direccion = data['direccion'];
            }
            this.zip = '';
            if (data['zip'] != 'null') {
                this.zip = data['zip'];
            }
            this.tipo = data['tipo'];
            this.codigo = '';
            if (data['codigo'] != 'null') {
                this.codigo = data['codigo'];
            }
            $('#localizacion_id_input').val(data['ciudad_id']);
            /* ASIGNACION DE VALORES A LOS SELECTS */
            $('#localizacion_id').empty().append('<option value="' + data['ciudad_id'] + '" selected="selected">' + data['ciudad'] + '</option>').val([data['ciudad_id']]).trigger('change');
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
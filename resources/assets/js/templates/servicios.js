$(document).ready(function() {
    llenarSelect('administracion', 'maestra_multiple', 'tipo_embarque_id', 0); // module, tableName, id_campo
    $('#tbl-servicios').DataTable({
        ajax: 'servicios/all',
        columns: [{
            data: 'tipo_embarque',
            name: 'tipo_embarque'
        }, {
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'tarifa',
            name: 'tarifa',
            "render": $.fn.dataTable.render.number(',', '.', 2, '$ ')
        }, {
            data: 'peso_minimo',
            name: 'peso_minimo',
            "render": $.fn.dataTable.render.number(',', '.', 2, '$ ')
        }, {
            data: 'cobro_opcional',
            name: 'cobro_opcional',
            "render": $.fn.dataTable.render.number(',', '.', 2, '$ ')
        }, {
            data: 'seguro',
            name: 'seguro',
            "render": $.fn.dataTable.render.number(',', '.', 2, '$ ')
        }, {
            data: 'impuesto',
            name: 'impuesto',
            "render": $.fn.dataTable.render.number('', '', 0, '% ')
        }, {
            data: 'cobro_peso_volumen',
            name: 'cobro_peso_volumen',
            "render": function(data, type, full, meta) {
                if (full.cobro_peso_volumen == 1) {
                    return '<span class="badge badge-primary">Peso</span>';
                } else {
                    return '<span class="badge badge-warning">Volumen</span>';
                }
            }
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.nombre + "'",
                        full.tarifa,
                        full.peso_minimo,
                        full.cobro_opcional,
                        full.seguro,
                        full.impuesto,
                        full.cobro_peso_volumen,
                        full.tipo_embarque_id,
                        full.pa_id,
                        "'" + full.pa + "'"
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

function llenarSelect(tableModule, tableName, idSelect, length) {
    var url = tableModule + '/5/selectInput/' + tableName;
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        success: function(data) {
            if (data.code === 200) {
                /* llenar select */
                $(data.items).each(function(index, value) {
                    $("#" + idSelect).append('<option value="' + value.id + '">' + value.text + '</option>');
                });
            } else {
                alert('error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            data = {
                error: jqXHR + ' - ' + textStatus + ' - ' + errorThrown
            }
            $('#modal' + 1).modal('toggle');
            $('body').append('<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title" id="myModalLabel">ERROR EN TRANSACCIÓN</h4></div><div class="modal-body">' + data.error + '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div></div></div>');
            $('#modalError').modal({
                show: true
            });
        }
    });
}

function edit(id, nombre, tarifa, peso_minimo, cobro_opcional, seguro, impuesto, cobro_peso_volumen, tipo_embarque_id, pa_id, pa) {
    var data = {
        id:                 id,
        nombre:             nombre,
        tarifa:             tarifa,
        peso_minimo:        peso_minimo,
        cobro_opcional:     cobro_opcional,
        seguro:             seguro,
        impuesto:           impuesto,
        cobro_peso_volumen: cobro_peso_volumen,
        tipo_embarque_id:   tipo_embarque_id,
        pa_id:              pa_id,
        pa:                 pa
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#servicios',
    mounted: function() {
        //
    },
    data: {
        nombre: '',
        tarifa: 0,
        peso_minimo: 0,
        cobro_opcional: 0,
        seguro: 0,
        impuesto: 0,
        cobro_peso_volumen: 0,
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        modalArancel: function(id, table_) {
            let me = this;
            $('#modalArancel').modal('show');
            if ($('#tbl-modalArancel tbody').length > 0) {
                var table = $('#tbl-modalArancel').DataTable().ajax.reload();
            } else {
                var table = $('#tbl-modalArancel').DataTable({
                    ajax: 'arancel/all',
                    columns: [{
                        data: 'pa',
                        name: 'pa'
                    }, {
                        data: 'descripcion',
                        name: 'descripcion'
                    }, {
                        data: 'iva',
                        name: 'iva',
                        "render": $.fn.dataTable.render.number(',', '.', 2, '% ')
                    }, {
                        data: 'arancel',
                        name: 'arancel',
                        "render": $.fn.dataTable.render.number(',', '.', 2, '% ')
                    }, ]
                });
            }

            $('#tbl-modalArancel tbody').on('click', 'tr', function() {
                var data = table.row(this).data();
                if(id){
                    /* SE EJECUTA ESTA FUNCION CUANDO LA MODAL SE ABRE DESDE EL CONSOLIDADO */
                    me.updatePADetailConsolidado(id, data['id'], table_);
                    $( "#tbl-modalArancel tbody" ).off('click', 'tr');
                }else{
                    $('#pa_id').val(data['id']);
                    $('#pa').val(data['pa']);
                    $('#arancel').val(data['arancel']);
                    $('#iva').val(data['iva']);
                    $('#modalArancel').modal('hide');
                }
            });
        },
        checkbox: function() {
            $('#cobro_peso_volumen').change(function() {
                alert($(this).prop('checked'));
            });
        },
        resetForm: function() {
            this.id = '';
            this.nombre = '';
            this.tarifa = 0;
            this.peso_minimo = 0;
            this.cobro_opcional = 0;
            this.seguro = 0;
            this.impuesto = 0;
            this.cobro_peso_volumen = 0;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
            $('#cobro_peso_volumen').bootstrapToggle('off');
            $('#pa_id').val('');
            $('#pa').val('');
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
            var urlRestaurar = 'servicios/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-servicios');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('servicios/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('servicios/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            if ($('#cobro_peso_volumen').prop('checked') === true) {
                this.cobro_peso_volumen = 1;
            } else {
                this.cobro_peso_volumen = 0;
            }
            axios.post('servicios', {
                'created_at': new Date(),
                'nombre': this.nombre,
                'tarifa': this.tarifa,
                'peso_minimo': this.peso_minimo,
                'cobro_opcional': this.cobro_opcional,
                'seguro': this.seguro,
                'impuesto': this.impuesto,
                'cobro_peso_volumen': this.cobro_peso_volumen,
                'tipo_embarque_id': $('#tipo_embarque_id').val(),
                'posicion_arancel_id': $('#pa_id').val(),
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
            if ($('#cobro_peso_volumen').prop('checked') === true) {
                this.cobro_peso_volumen = 1;
            } else {
                this.cobro_peso_volumen = 0;
            }
            axios.put('servicios/' + this.id, {
                'nombre': this.nombre,
                'tarifa': this.tarifa,
                'peso_minimo': this.peso_minimo,
                'cobro_opcional': this.cobro_opcional,
                'seguro': this.seguro,
                'impuesto': this.impuesto,
                'cobro_peso_volumen': this.cobro_peso_volumen,
                'tipo_embarque_id': $('#tipo_embarque_id').val(),
                'posicion_arancel_id': $('#pa_id').val(),
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
            this.tarifa = data['tarifa'];
            this.peso_minimo = data['peso_minimo'];
            this.cobro_opcional = data['cobro_opcional'];
            this.seguro = data['seguro'];
            this.impuesto = data['impuesto'];
            $('#tipo_embarque_id').val(data['tipo_embarque_id']);
            if (data['cobro_peso_volumen'] === 0) {
                $('#cobro_peso_volumen').bootstrapToggle('off');
            } else {
                $('#cobro_peso_volumen').bootstrapToggle('on');
            }
            $('#pa_id').val('');
            $('#pa').val('');
            if(data['pa_id'] != ''){
                $('#pa_id').val(data['pa_id']);
            }
            if(data['pa'] != ''){
                $('#pa').val(data['pa']);
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
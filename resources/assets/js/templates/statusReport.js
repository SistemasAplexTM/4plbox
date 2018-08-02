$(document).ready(function() {
    $('#tbl-statusReport').DataTable({
        ajax: 'statusReport/all',
        columns: [{
            data: 'fecha_status',
            name: 'fecha_status'
        }, {
            data: 'codigo',
            name: 'codigo'
        }, {
            data: 'num_consolidado',
            name: 'num_consolidado'
        }, {
            data: 'observacion',
            name: 'observacion'
        }, {
            data: 'status_name',
            name: 'status_name'
        }, {
            data: 'name',
            name: 'name'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                return btn_delete;
            }
        }]
    });
});
var objVue = new Vue({
    el: '#statusReport',
    mounted: function() {
        //
    },
    data: {
        codigo: null,
        observacion: null,
        transportadora: null,
        num_transportadora: null,
        transport:false,
        editar: 0,
        status_id: null,
        status: [],
        formErrors: {},
        listErrors: {},
    },
    created() {
        this.getDataSelect('tipo_de_producto');
    },
    methods: {
        setTransport: function(val){
            this.transport = false;
            this.status_id = val;
            if (val.value == 12) {
                this.transport = true;
            }
        },
        resetForm: function() {
            this.codigo = null;
            this.observacion = null;
            this.transportadora = null;
            this.num_transportadora = null;
            this.status_id = null;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
        },
        getDataSelect: function(table_select, select) {
            var me = this;
            axios.get('status/getDataSelect').then(function(response) {
                if (response.data['code'] == 200) {
                    me.status = response.data.data;
                } else {
                    notifyMesagge('bg-red', 'Error: ' + response.data['error']);
                }
            }).catch(function(error) {
                notifyMesagge('bg-red', 'Error');
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
        updateTable: function() {
            refreshTable('tbl-statusReport');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            swal({
                title: "¿Desea eliminar el registro seleccionado?",
                text: "No podras restaurarlo despues!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si",
                cancelButtonText: "No, Cancelar!",
            }).then((result) => {
                if (result.value) {
                    if (data.logical === true) {
                        axios.get('statusReport/delete/' + data.id + '/' + data.logical).then(response => {
                            this.updateTable();
                            toastr.success("Registro eliminado exitosamente.");
                            toastr.options.closeButton = true;
                        });
                    } else {
                        axios.get('statusReport/delete/' + data.id).then(response => {
                            this.updateTable();
                            toastr.success('Registro eliminado correctamente.');
                            toastr.options.closeButton = true;
                        });
                    }
                }
            })
        },
        create: function() {
            let me = this;
            axios.post('statusReport', {
                'status_id': this.status_id.value,
                'codigo': this.codigo,
                'observacion': this.observacion,
                'transportadora': this.transportadora,
                'num_transportadora': this.num_transportadora,
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
        }
    },
});
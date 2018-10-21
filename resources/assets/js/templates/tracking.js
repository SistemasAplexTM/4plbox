$(document).ready(function() {
    var table = $('#tbl-tracking').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: 'tracking/all/' + true,
        columns: [{
            data: "fecha",
            name: 'fecha'
        }, {
            data: "cliente",
            name: 'cliente'
        }, {
            data: "codigo",
            name: 'codigo'
        }, {
            data: "num_warehouse",
            name: 'num_warehouse'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var color = '#ccc';
                var label = 'Sin acción';
                if (full.confirmed_send == 1) {
                    color = '#4caf50';
                    label = 'Despachar';
                }
                return '<div style="color:' + color + '" class="text-center" data-toggle="tooltip" title="' + label + '"><i class="fa fa-flag"></i></div>';
            }
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_delete = '';
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                return btn_delete;
            }
        }],
    });
});
var objVue = new Vue({
    el: '#tracking',
    created: function() {
        this.getShipper();
        /* CUSTOM MESSAGES VE-VALIDATOR*/
        const dict = {
            custom: {
                // consignee_id: {
                //     required: 'El cliente es obligatorio.'
                // },
                tracking: {
                    required: 'El tracking es obligatorio.'
                }
            }
        };
        this.$validator.localize('es', dict);
    },
    data: {
        consignee_id: null,
        contenido: null,
        tracking: null,
        email: null,
        instruccion: false,
        confirmedSend: false,
        editar: 0,
        consignees: []
    },
    methods: {
        resetForm: function() {
            this.consignee_id = null;
            this.tracking = null;
            this.contenido = null;
            this.email = null;
            this.instruccion = false;
            this.confirmedSend = false;
            this.editar = 0;
        },
        searchTracking: function() {
            let me = this;
            axios.get('tracking/searchTracking/' + me.tracking).then(response => {
                var datos = response.data;
                if (datos.data != null) {
                    if (datos.data['consignee_id']) {
                        me.consignee_id = {
                            id: datos.data['consignee_id'],
                            name: datos.data['nombre_full']
                        };
                    } else {
                        me.consignee_id = null;
                    }
                    me.contenido = datos.data['contenido'];
                    me.instruccion = datos.data['instruccion'];
                    me.email = datos.data['correo'];
                    me.confirmedSend = (datos.data['despachar'] == 1) ? true : false;
                } else {
                    me.create();
                    // this.instruccion = false;
                    // this.contenido = null;
                    // this.email = null;
                    // this.consignee_id = null;
                    // this.confirmedSend = false;
                }
            });
        },
        getShipper: function() {
            let me = this;
            axios.get('tracking/getAllShipperConsignee/consignee').then(response => {
                me.consignees = response.data.data;
            });
        },
        updateTable: function() {
            refreshTable('tbl-tracking');
        },
        delete: function(data) {
            swal({
                title: 'Seguro que desea eliminar este registro?',
                text: "No lo podras recuperar despues!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No, cancelar!'
            }).then((result) => {
                if (result.value) {
                    axios.delete('tracking/' + data.id).then(response => {
                        this.updateTable();
                        toastr.success('Registro eliminado correctamente.');
                        toastr.options.closeButton = true;
                    });
                }
            });
        },
        create: function() {
            const isUnique = (value) => {
                return axios.post('tracking/validar_tracking', {
                    'element': value
                }).then((response) => {
                    return {
                        valid: response.data.valid,
                        data: {
                            message: response.data.message
                        }
                    };
                });
            };
            // The messages getter may also accept a third parameter that includes the data we returned earlier.
            this.$validator.extend('unique', {
                validate: isUnique,
                getMessage: (field, params, data) => {
                    return data.message;
                }
            });
            let me = this;
            this.$validator.validateAll().then((result) => {
                if (result) {
                    axios.post('tracking', {
                        'consignee_id': (this.consignee_id != null) ? this.consignee_id.id : null,
                        'codigo': this.tracking,
                        'contenido': this.contenido,
                        'confirmed_send': this.confirmedSend,
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
                        console.log(error);
                        toastr.warning("Error: porfavor veifica la informacion ingresada.", {
                            timeOut: 50000
                        });
                    });
                }
            }).catch(function(error) {
                toastr.warning('Error: ' + error);
            });
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});
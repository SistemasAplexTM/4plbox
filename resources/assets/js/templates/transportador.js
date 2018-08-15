$(document).ready(function() {
    $('#tbl-transportador').DataTable({
        ajax: 'transportador/all',
        columns: [{
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'direccion',
            name: 'direccion'
        }, {
            data: 'ciudad',
            name: 'ciudad'
        }, {
            data: 'zip',
            name: 'zip'
        }, {
            data: 'contacto',
            name: 'contacto'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.nombre + "'", "'" + full.direccion + "'", "'" + full.telefono + "'", "'" + full.email + "'", "'" + full.contacto + "'", "'" + full.ciudad + "'", "'" + full.estado + "'", "'" + full.pais + "'", "'" + full.zip + "'",
                        full.shipper,
                        full.consignee,
                        full.carrier,
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

function edit(id, nombre, direccion, telefono, email, contacto, ciudad, estado, pais, zip, shipper, consignee, carrier) {
    var data = {
        id: id,
        nombre: nombre,
        direccion: direccion,
        telefono: telefono,
        email: email,
        contacto: contacto,
        ciudad: ciudad,
        estado: estado,
        pais: pais,
        zip: zip,
        shipper: shipper,
        consignee: consignee,
        carrier: carrier
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#transportador',
    mounted: function() {
        //
    },
    data: {
        nombre: '',
        direccion: '',
        telefono: '',
        email: '',
        contacto: '',
        ciudad: '',
        estado: '',
        pais: '',
        zip: '',
        shipper: false,
        consignee: false,
        carrier: false,
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        checkbox: function() {
            $('#zip').change(function() {
                alert($(this).prop('checked'));
            });
        },
        resetForm: function() {
            this.id = '';
            this.nombre = '';
            this.direccion = '';
            this.telefono = '';
            this.email = '';
            this.contacto = '';
            this.ciudad = '';
            this.estado = '';
            this.pais = '';
            this.zip = '';
            this.shipper = false;
            this.consignee = false;
            this.carrier = false;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
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
            var urlRestaurar = 'transportador/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-transportador');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('transportador/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('transportador/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            axios.post('transportador', {
                'created_at': new Date(),
                'nombre': this.nombre,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.email,
                'contacto': this.contacto,
                'ciudad': this.ciudad,
                'estado': this.estado,
                'pais': this.pais,
                'zip': this.zip,
                'shipper': this.shipper,
                'consignee': this.consignee,
                'carrier': this.carrier,
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
            let me = this;
            axios.put('transportador/' + this.id, {
                'nombre': this.nombre,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.email,
                'contacto': this.contacto,
                'ciudad': this.ciudad,
                'estado': this.estado,
                'pais': this.pais,
                'zip': this.zip,
                'shipper': this.shipper,
                'consignee': this.consignee,
                'carrier': this.carrier,
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
                console.log(error);
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
            this.resetForm();
            this.id = data['id'];
            this.nombre = data['nombre'];
            this.direccion = data['direccion'];
            this.telefono = data['telefono'];
            if(data['email'] != 'null'){
                this.email = data['email'];
            }
            this.contacto = data['contacto'];
            this.ciudad = data['ciudad'];
            this.pais = data['pais'];
            this.estado = data['estado'];
            this.zip = data['zip'];
            this.shipper = data['shipper'];
            this.consignee = data['consignee'];
            this.carrier = data['carrier'];
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
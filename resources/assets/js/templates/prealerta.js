$(document).ready(function() {
    var id_agencia = $('#prealerta').data('id_agencia');
    $('#tbl-prealerta').DataTable({
        ajax: 'prealerta/'+id_agencia+'/all',
        columns: [{
            data: 'tracking',
            name: 'tracking'
        }, {
            data: 'despachar',
            name: 'despachar',
            "render": function(data, type, full, meta) {
                if (full.despachar == 1) {
                    return '<span class="badge badge-primary">Despachar</span>';
                } else {
                    return '<span class="badge badge-warning">Esperar</span>';
                }
            }
        },{
            data: 'consignee',
            name: 'consignee_id'
        }, {
            data: 'agencia',
            name: 'agencia_id'
        }, {
            data: 'contenido',
            name: 'contenido'
        }, {
            data: 'instruccion',
            name: 'instruccion'
        }, {
            data: 'correo',
            name: 'correo'
        }, {
            data: 'telefono',
            name: 'telefono'
        }]
    });
    $('#despachar').change(function() {
        objVue.msn();
    });
});
var objVue = new Vue({
    el: '#prealerta',
    mounted: function(){
    },
    data:{
        email: null,
        instruccion: null,
        tracking: null,
        telefono: null,
        existConsignee: false,
        despachar: false,
    },
    methods:{
        msn: function(){
            this.despachar = !this.despachar;
        },
        resetForm: function(){
            this.tracking = null;
            this.errors.clear();
        },
        create: function(){
            const isUnique = (value) => {
                return axios.post($('#formPrealerta').data('id_agencia')+'/validar_tracking',{'element' : value}).then((response) => {
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
            this.$validator.validateAll().then((result) => {
                if (result) {
                    let me = this;
                    axios.post($('#formPrealerta').data('id_agencia'),{
                        'email' : this.email,
                        'instruccion' : this.instruccion,
                        'tracking' : this.tracking,
                        'despachar' : $('#despachar').prop('checked'),
                    }).then(function(response){
                        if(response.data['code'] == 200){
                            toastr.success('Registro creado correctamente.');
                            toastr.options.closeButton = true;
                            me.resetForm();
                        }else{
                            console.log(response);
                            toastr.warning('Error: '+response.data['error']);
                            toastr.options.closeButton = true;
                        }
                    }).catch(function(error){
                        console.log(error);
                        toastr.error("Porfavor completa los campos obligatorios.", {timeOut: 30000});
                    });
                }
            }).catch(function(error) {
                console.log(error);
                toastr.warning('Error: Completa los campos.');
            });
        }
    },
});
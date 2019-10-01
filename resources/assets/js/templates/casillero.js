$(document).ready( () => {
    var pathname = window.location.pathname;
    var data = pathname.split('/');
    objVue.agencia_id = data.pop();
    $("#corporativo").change(() => {
        objVue.corporativo = (objVue.corporativo) ? false : true;
    });
});

var objVue = new Vue({
    el: '#casillero',
    watch: {
        agencia_id:function(value){
            // this.configuration('agency_mc');
        },
        'email' : function(v) {
            this.email = v.toLowerCase().trim();
        },
        'email_confirmation' : function(v) {
            this.email_confirmation = v.toLowerCase().trim();
        },
        'primer_nombre' : function(v) {
            this.primer_nombre = v.toUpperCase();
        },
        'primer_apellido' : function(v) {
            this.primer_apellido = v.toUpperCase();
        },
        'direccion' : function(v) {
            this.direccion = v.toUpperCase();
        }
    },
    mounted: function(){

        let me=this;
        /* CUSTOM MESSAGES VE-VALIDATOR*/
            const dict = {
              custom: {
                primer_nombre: {
                  required: 'El primer nombre es obligatorio.'
                },
                // primer_apellido: {
                //   required: 'El primer apellido es obligatorio.'
                // },
                password: {
                  required: 'La contraseña es obligatoria.'
                },
                password_confirmation: {
                  required: 'La confirmación de la contraseña es obligatoria.'
                },
                email_confirmation: {
                  required: 'La confirmación de email es obligatoria.',
                  confirmed: 'El email ingresado no coincide.'
                },
                email: {
                  required: 'El email es obligatorio.'
                },
                direccion: {
                  required: 'La dirección es obligatoria.'
                },
                localizacion_id: {
                  required: 'La ciudad es obligatoria.'
                },
                zip: {
                  required: 'El código postal es obligatorio.'
                },
                celular: {
                  required: 'El celular es obligatorio.'
                },
                acepta_condiciones: {
                  required: 'Debe aceptar las condiciones para poder continuar.'
                },
              }
            };
            this.$validator.localize('es', dict);
            const isUnique = (value) => {
                return axios.post('validar/validar_email',{
                    'element' : value,
                    'agencia_id' : me.agencia_id
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
    },
    data:{
        id: null,
        tipo_identificacion_id: null,
        agencia_id: null,
        localizacion_id: null,
        documento: null,
        primer_nombre: null,
        segundo_nombre: null,
        primer_apellido: null,
        segundo_apellido: null,
        direccion: null,
        telefono: null,
        celular: null,
        email: null,
        email_confirmation: null,
        zip: null,
        tarifa: 0,
        po_box: null,
        estatus: 1,
        nombre_full: null,
        casillero: 0,
        password: null,
        direccion2: null,
        acepta_condiciones: null,
        recibir_info: null,
        phone_code: null,
        success: false,
        corporativo: false,
        ciudades: [],
        listId: null
    },
    methods:{
        // configuration: function(key){
        //     let me = this;
        //     axios.get('../aplexConfig/config/'+key).then(response => {
        //         let config = JSON.parse(response.data.value);
        //         me.listId = config.list.find(function(element){
        //             return element.id_agency == me.agencia_id
        //         });
        //     });
        //     console.log(me.agencia_id);
        //     axios.get('../aplexConfig/getDataAgencyById/'+me.agencia_id).then(response => {
        //         me.recibir_info = (response.data.usar_mail_chimp == 1) ? true : false;
        //     });
        // },
        resetForm: function(){
            this.id = '';
        },
        /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
        deleteError: function(element){
            let me = this;
            $.each(me.listErrors, function (key, value) {
                if(key !== element){
                   me.listErrors[key] = value;
               }else{
                me.listErrors[key] = false;
               }
            });
        },
        create: function(){
            let me=this;
            this.$validator.validateAll().then((result) => {
                if (result) {
                  var l = Ladda.create(document.querySelector('.ladda-button'));
                  l.start();
                    let me = this;
                    axios.post('../registro',{
                        'agencia_id': this.agencia_id,
                        // 'listId': this.listId.id_list,
                        'localizacion_id': this.localizacion_id,
                        'documento': this.documento,
                        'primer_nombre': this.primer_nombre,
                        'segundo_nombre': this.segundo_nombre,
                        'primer_apellido': this.primer_apellido,
                        'segundo_apellido': this.segundo_apellido,
                        'direccion': this.direccion,
                        'telefono': this.celular,
                        'celular': this.celular,
                        'correo': this.email,
                        'zip': this.zip,
                        'tarifa': this.tarifa,
                        'estatus': this.estatus,
                        'casillero': this.casillero,
                        'password': this.password,
                        'direccion2': this.direccion2,
                        'acepta_condiciones': this.acepta_condiciones,
                        'recibir_info': this.recibir_info
                    }).then(function(response){
                      l.stop();
                        if(response.data['code'] == 200){
                            toastr.success('Registro creado correctamente.');
                            toastr.options.closeButton = true;
                            window.location = response.data['url'];
                        }else{
                            toastr.warning(response.data['error']);
                            toastr.options.closeButton = true;
                        }
                        me.resetForm();
                    }).catch(function(error){
                        console.log(error);
                    });
                }else{
                  console.log(result);
                }
            });

        },
        cancel: function(){
            var me = this;
            me.resetForm();
        },
        setPhoneCode: function(val){
            var me = this;
            me.localizacion_id = null;
            me.phone_code = '';
            if (val) {
                me.localizacion_id = val;
                me.phone_code = val.phone_code;
            }
        },
        setZip: function(val){
            var me = this;
            var zip = 'zip';
            var address = me.direccion+' '+ me.localizacion_id['name'] + ' '+ me.localizacion_id['depto'] + ' '+ me.localizacion_id['pais'];
            var inputZip = 'zip';
            console.log(address);
            setDataGeocode(address, inputZip);
        },
        onSearch(search, loading) {
          loading(true);
          this.search(loading, search, this);
        },
        search: _.debounce((loading, search, vm) => {
          fetch(
            `vueSelectCiudad/${escape(search)}`
          ).then(res => {
            res.json().then(json => (vm.ciudades = json.items));
            loading(false);
          });
        }, 1000),
    }
});

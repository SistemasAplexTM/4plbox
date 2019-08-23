var objVue = new Vue({
    el: '#rastreo',
    mounted: function() {
        // this.getData();
    },
    data: {
        codigo: null,
        codigo_label: null,
        peso_label: null,
        tracking_label: null,
        fecha_entrega: null,
        no_data: null,
        replace: [{
            'img': 'img/imagesRastreo/casillero.png',
            'descripcion': 'Hemos recibido su paquete en su casillero procedente de ',
        }, {
            'img': 'img/imagesRastreo/consolidado.png',
            'descripcion': 'Su envío ha sido consolidado, próximo a despacharse a ',
        }, {
            'img': 'img/imagesRastreo/llegada.png',
            'descripcion': 'Su paquete ha llegado a bodega destino en proceso de desconsolidación.',
        }, {
            'img': 'img/imagesRastreo/reparto.png',
            'descripcion': 'Su paquete se encuentra en reparto con Coordinadora realice el seguimiento a la guía número 21200004563.',
        }, {
            'img': 'img/imagesRastreo/fin.png',
            'descripcion': 'El paquete ha sido entregado!!',
        }],
        datos: [],
    },
    methods: {
        resetDatas: function(){
            this.codigo_label= null;
            this.peso_label= null;
            this.tracking_label= null;
            this.fecha_entrega= null;
            this.no_data = null;
            this.datos = [];
        },
        getData: function() {
            this.resetDatas();
            this.$validator.validateAll().then((result) => {
                if(result){
                    axios.get('rastreo/getStatusReport/' + this.codigo).then(response => {
                        if (response.data.code === 200) {
                            this.datos = response.data.data;
                            this.codigo_label = this.codigo;
                            if(this.datos.length > 0){
                                this.peso_label = this.datos[0].peso;
                                str = this.datos[0].tracking;
                                this.tracking_label = '';
                                if(str != null){
                                  this.tracking_label = str.split(",");
                                }
                            }else{
                                this.no_data = 'No hay datos con el numero ingresado. :(';
                            }
                            for (var i in this.datos) {
                                if (this.datos.hasOwnProperty(i)) {
                                    this.datos[i].mont_data = this.findMontToDate(this.datos[i].mont_data);
                                    // this.datos[i].img = this.replace[i].img;
                                    this.datos[i].img = this.datos[i].icon + ' fa-5x';
                                    this.datos[i].color = this.datos[i].color;
                                    // this.datos[i].descripcion = this.replace[i].descripcion;
                                    this.datos[i].descripcion = this.datos[i].descripcion_general;
                                    // if(i != 0){
                                    //     this.datos[i].procedencia = '';
                                    // }
                                    // if(i == 1){
                                    //     this.datos[i].procedencia = this.datos[i].ciudad + ' / ' + this.datos[i].depto + ' / ' + this.datos[i].pais;
                                    // }
                                    // if(i == 2){
                                    //     this.datos[i].descripcion = 'Su paquete ha llegado a '+ this.datos[i].pais +' en proceso de desconsolidación.';
                                    // }
                                    // if(i == 4){
                                    //     this.fecha_entrega = this.datos[i].fecha_status;
                                    // }SELECT * FROM `detalle` where documento_id = 325246
                                }
                            }
                            console.log(this.datos);
                        } else {
                            if (response.data.code === 600) {
                                toastr.warning(response.data.data);
                            }
                        }
                    }).catch(function(error) {
                        console.log(error);
                        toastr.error("Error.", {
                            timeOut: 50000
                        });
                    });
                }
            }).catch(function(error) {
                toastr.warning('Error: Completa los campos.');
            });
        },
        findMontToDate: function(mont) {
            var monthsShort = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
            return monthsShort[parseInt(mont) - 1];
        }
    }
});

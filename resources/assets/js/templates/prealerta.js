$(document).ready(function () {
    var id_agencia = $('#prealerta').data('id_agencia');
    $('#tbl-prealerta').DataTable({
        ajax: 'prealerta/' + id_agencia + '/all',
        columns: [{
            data: 'tracking',
            name: 'tracking'
        }, {
            data: 'despachar',
            name: 'despachar',
            "render": function (data, type, full, meta) {
                if (full.despachar == 1) {
                    return '<span class="badge badge-primary">Despachar</span>';
                } else {
                    return '<span class="badge badge-warning">Esperar</span>';
                }
            }
        }, {
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
    $('#despachar').change(function () {
        objVue.msn();
    });
});

// function findPrinters() {
//   qz.printers.find().then(function(data) {
//     var list = '';
//     for(var i = 0; i < data.length; i++) {
//        list += "&nbsp; " + data[i] + "<br/>";
//     }
//     $('#prealerta').append("<strong>Available printers:</strong><br/>" + list);
//   }).catch(function(e) { console.error(e); });
// }

var objVue = new Vue({
    el: '#prealerta',
    mounted: function () {
        let me = this;
        // IMPRIMIENDO CON QZ
        // Compruebo la conexion
        // qz.websocket.connect().then(function() {
        //   alert("Connected!");
        //
        //   // listando todas las impresoras del cliente
        //   findPrinters();
        //
        //   // Busqueda de impresora por nombre
        //   qz.printers.find("Nitro PDF Creator (Pro 12)").then(function(found) {
        //     alert("Printer: " + found);
        //
        //     // impresion directa
        //     var config = qz.configs.create(found);
        //     var data = [{
        //           type: 'pdf',
        //           data: '/files/dumaFile.pdf'
        //     }];
        //     qz.print(config, data).catch(function(e) { console.error(e); });
        //     // fin
        //   });
        // });

        // JSPM.JSPrintManager.auto_reconnect = true;
        // JSPM.JSPrintManager.start();
        // JSPM.JSPrintManager.WS.onStatusChanged = function () {
        //     if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
        //       // Listado de impresoras simple
        //       JSPM.JSPrintManager.getPrinters().then(function (e) {
        //         e.forEach(function(el, index) {
        //           // me.printers_options.push({ id: index, name: el });
        //         });
        //       });
        //
        //       // Impresion Multiple
        //       var cpj = new JSPM.ClientPrintJob();
        //       // impresora por defecto
        //       // cpj.clientPrinter = new JSPM.DefaultPrinter();
        //
        //       // muestra dialog con listado de impresoras
        //       // cpj.clientPrinter = new JSPM.UserSelectedPrinter();
        //
        //       // imprime con una impresora seleccionada
        //       cpj.clientPrinter = new JSPM.InstalledPrinter('Nitro PDF Creator (Pro 12)');
        //
        //       var my_file1 = new JSPM.PrintFilePDF('/files/dumaFile.pdf', JSPM.FileSourceType.URL, 'archivo1.pdf', 1);
        //       var my_file2 = new JSPM.PrintFilePDF('/files/file.pdf', JSPM.FileSourceType.URL, 'archivo2.pdf', 1);
        //       cpj.files.push(my_file1);
        //       cpj.files.push(my_file2);
        //       cpj.sendToClient();
        //     }else {
        //       if(JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed){
        //         console.log('JSPM is not installed or not running!');
        //       }else{
        //         if(JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.BlackListed){
        //           console.log('JSPM has blacklisted this website!');
        //         }
        //       }
        //     }
        // };
    },
    data: {
        email: null,
        instruccion: null,
        tracking: null,
        contenido: null,
        telefono: null,
        existConsignee: false,
        despachar: false,
    },
    methods: {
        msn: function () {
            this.despachar = !this.despachar;
        },
        resetForm: function () {
            this.tracking = null;
            this.contenido = null;
            this.instruccion = null;
            this.errors.clear();
        },
        create: function () {
            const isUnique = (value) => {
                return axios.post($('#formPrealerta').data('id_agencia') + '/validar_tracking', {
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
            this.$validator.validateAll().then((result) => {
                if (result) {
                    var l = Ladda.create(document.querySelector('.ladda-button'));
                    l.start();
                    let me = this;
                    axios.post($('#formPrealerta').data('id_agencia'), {
                        'email': this.email,
                        'instruccion': this.instruccion,
                        'tracking': this.tracking,
                        'contenido': this.contenido,
                        'despachar': $('#despachar').prop('checked'),
                    }).then(function (response) {
                        l.stop();
                        if (response.data['code'] == 200) {
                            toastr.success('Su paquete ha sido prealertado');
                            toastr.options.closeButton = true;
                            me.resetForm();
                        } else {
                            console.log(response);
                            toastr.warning('Error: ' + response.data['error']);
                            toastr.options.closeButton = true;
                        }
                    }).catch(function (error) {
                        l.stop();
                        console.log(error);
                        toastr.error("Error al prealertar.", {
                            timeOut: 30000
                        });
                    });
                }
            }).catch(function (error) {
                console.log(error);
                toastr.warning('Error: Completa los campos.');
            });
        }
    },
});
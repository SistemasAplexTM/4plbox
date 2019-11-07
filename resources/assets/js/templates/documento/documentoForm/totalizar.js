/*DECLARACIÓN DE VARIABLES PARA LOS TOTALES DEL DOCUMENTO*/
var piezas;
var peso;
var volumen;
var impuesto;
var pa_aduana;
var flete = 0;
var seguro = 0;
var descuento = 0;
var total = 0;
$(window).load(function () {
    setTimeout(function () {
        totalizeDocument();
    }, 1000);
});
$(document).ready(function () {});

function totalizeDocument(elemento) {
    // setTimeout(function(){
    var cont = 1;
    flete = $('#servicios_id option:selected').data('tarifamin');
    if (typeof flete === 'undefined') {
        flete = 0;
    }
    if (parseFloat($('#tarifa_minima').val()) === 0) {
        $('#tarifa_minima').val(flete);
        $('#flete').val(flete);
    } else {
        flete = parseFloat($('#tarifa_minima').val());
        $('#flete').val(parseFloat($('#tarifa_minima').val()));
    }
    $(elemento).css('border-color', '');
    peso = $('#pesoDim').html();
    piezas = $('#piezas').html();
    volumen = $('#volumen').html();
    declarado = $('#valor_declarado_tbl').html();
    var cargos_add = 0;
    cargos_add = $('#cargos_add').val();
    if ($('#valor_libra2').val() == '0') {
        $('#valor_libra').val($('#servicios_id option:selected').data('tarifa'));
        $('#valor_libra2').val($('#servicios_id option:selected').data('tarifa'));
        $('#valorLibra').html($('#servicios_id option:selected').data('tarifa')); // *********  label
    }
    if ($('#impuesto').val() === '0' || $('#impuesto').val() === '') {
        if ($('#servicios_id option:selected').data('impuesto_age')) {
            $('#impuesto').val($('#servicios_id option:selected').data('impuesto_age'));
        }
    }
    $('#peso_total').val(parseFloat(peso));

    if ($('#servicios_id option:selected').data('cobvol') == 1) {
        $('#peso_cobrado').val(parseFloat(peso));
    } else {
        if (parseFloat(peso) > parseFloat(volumen)) {
            $('#peso_cobrado').val(parseFloat(peso));
        } else {
            $('#peso_cobrado').val(parseFloat(volumen));
        }
    }

    if ($('#impuesto').val() == '') {
        impuesto = $('#servicios_id option:selected').data('impuesto_age');
        $('#impuesto').val(impuesto);
    } else {
        impuesto = $('#impuesto').val();
    }
    $('#valor_declarado').val(parseFloat(declarado));
    // $('#valor_declarado_tbl').val(parseFloat(declarado));
    pa_aduana = isInteger((parseFloat(declarado) * parseFloat(impuesto) / 100));
    $('#pa_aduana').val(pa_aduana);
    // flete = Math.ceil(parseFloat(calculateFlete(flete)));
    flete = parseFloat(calculateFlete(flete));
    $('#flete').val(isInteger(flete));

    seguro = calculateInsurance(seguro);
    $('#seguro').val(seguro);
    if ($('#descuento').val() === '') {
        descuento = 0;
    } else {
        descuento = $('#descuento').val();
    }
    total = parseFloat(pa_aduana) + parseFloat(flete) + parseFloat(seguro) + parseFloat(cargos_add) - parseFloat(descuento);
    $('#total').val(isInteger(total));
    $('.total_lb').html(isInteger(total));
    // },1000);
}

function calculateFlete(flete) {
    var tarifa = parseFloat($('#valor_libra2').val());
    var pie = parseFloat($('#pie_ft').val());
    var metro = parseFloat(pie / 35.315);
    // console.log(metro);
    /* VALIDA QUE NO SOBREPASE LA TARIFA DEL SERVICIO */
    // if (parseFloat($('#valor_libra2').val()) < parseFloat($('#valor_libra').val())) {
    if ($('#valor_libra').val() === '0' || $('#valor_libra').val() === '') {
        tarifa = 0;
    } else {
        tarifa = parseFloat($('#valor_libra').val());
    }
    // }
    var cOpcional = $('#servicios_id option:selected').data('c_opcional');

    /* DEFINO SI ES AEREO O MARITIMO 2 = MARITIMO*/
    if ($('#tipo_embarque_id').val() == app_cuft) {
        /* SE COBRA POR PIE 3 SI LA POSICION ARANCELARIA ES CERO = 0 O SIN POSICION */
        if ($('#pa_id').val() == 1) {
            $('#cobrarPor').text('cuft');
            var tot = parseFloat(pie) * parseFloat(tarifa);
        } else {
            $('#cobrarPor').text('cbm');
            var tot = parseFloat(metro) * parseFloat(tarifa);
        }
        if (parseFloat(tot) <= parseFloat(flete)) {
            return flete;
        } else {
            return tot;
        }
    } else {
        /* SE EVALUA SI SE COBRARA POR PESO O VOLUMEN (PESO = 1 - VOLUMEN = 0)*/
        if ($('#servicios_id option:selected').data('cobvol') == 0 && app_client != 'worldcargo') {
            if ((parseFloat(peso) * parseFloat(tarifa)) >= 0 && ((parseFloat(peso) * parseFloat(tarifa)) <= $('#servicios_id option:selected').data('tarifamin'))) {
                $('#cobrarPor').text('Pes');
                return flete;
            }
            if ((parseFloat(peso) * parseFloat(tarifa)) > $('#servicios_id option:selected').data('tarifamin')) {
                /*PESO * LA TARIFA */
                $('#cobrarPor').text('Pes');

                var flete_c = parseFloat(peso) * parseFloat(tarifa);
                var diferen = parseFloat(volumen) - parseFloat(peso);
                if (parseFloat(diferen) > 0) {
                    /* SE COBRA POR VOLUMEN */
                    res = parseFloat(cOpcional) * parseFloat(diferen);
                    flete_c = parseFloat(flete_c) + parseFloat(res);
                    $('#cobrarPor').text('Vol');
                }
                if (parseFloat(flete) < parseFloat(flete_c)) {
                    return flete_c;
                } else {
                    return flete;
                }
            }
        } else {
            if (parseFloat(peso) > parseFloat(volumen)) {
                $('#cobrarPor').text('Pes');
                valor = parseFloat(peso);
            } else {
                valor = parseFloat(volumen);
                $('#cobrarPor').text('Vol');
            }
            if (parseFloat(valor) * parseFloat(tarifa) <= parseFloat(flete)) {
                return flete;
            } else {
                return parseFloat(valor) * parseFloat(tarifa);
                // if (parseFloat(peso) >= 0 && parseFloat(peso) <= 8) {
                //     return flete;
                // } else {
                //     if (parseFloat(peso) > 8) {
                //         flete = isInteger((parseFloat(valor) * parseFloat(tarifa)));
                //         return flete;
                //     }
                // }
            }
        }
    }
}

function calculateInsurance() {
    /* calcular valor asegurado*/
    var condicionSeg = 100;
    var seguroUsu = $.trim($('#seguro_valor').val());
    var valorAsegurado = $.trim($('#servicios_id option:selected').data('seguro'));
    if ((seguroUsu === '') || (seguroUsu === 0)) {
        return 0;
    } else {
        /*tomamos el valor que hay en el campo seguro para saber cuantas veces el valor indicado supera 100 USD ej: 5/5=1;*/
        var calculoS = parseFloat($('#seguro').val()) / valorAsegurado;
        if (calculoS > 0) {
            /*multiplicamos condicionSeg * calculoS para determinar el valor que se evaluara con el dato inresado por el usuario Ej: 100*1=100;*/
            cantAsegu = condicionSeg * calculoS;
        }
        if (parseFloat(seguroUsu)) {
            /*el seguro: Por cada 100 USD que ingrese el usuario en el valor asegurado se incrementara de 5 USD en 5 USD el seguro a pagar*/
            seguro = (valorAsegurado * (Math.ceil(seguroUsu / 100)));
        } else {
            if (parseFloat(seguroUsu) < condicionSeg) {
                seguro = 0;
            }
        }
        return seguro;
    }
}

function calculateServiceType() {
    $('#valor_libra').val($('#servicios_id option:selected').data('tarifa'));
    $('#valor_libra2').val($('#servicios_id option:selected').data('tarifa'));
    $('#valorLibra').html($('#servicios_id option:selected').data('tarifa')); // *********  label
    $('#pa_id').val($('#servicios_id option:selected').data('pa_id'));
    $('#impuesto').val($('#servicios_id option:selected').data('impuesto_age'));
    objVue.getPositionById($('#servicios_id option:selected').data('pa_id'));
    totalizeDocument();
}
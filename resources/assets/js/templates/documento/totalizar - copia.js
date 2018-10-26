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
$(window).load(function() {
    setTimeout(function(){
        totalizeDocument();
    },1000);
});
$(document).ready(function() {});

function totalizeDocument(elemento) {
    var cont = 1;
        flete = $('#servicios_id option:selected').data('tarifamin');
        $('#flete').val(flete);
        $(elemento).css('border-color', '');
        peso = 0;
        piezas = 0;
        volumen = 0;
        declarado = 0;
        var cargos_add = 0;
        $(".cp_peso").each(function(index, value) {
            // console.log('peso='+$(this).html());
            // peso = parseFloat(peso) + parseFloat($(this).html());
            peso = parseFloat(peso) + parseFloat($(this).val());
            piezas = piezas + 1;
        });
        $(".cp_volumen").each(function(index, value) {
            volumen = parseFloat(volumen) + parseFloat($(this).val());
        });
        $(".cp_declarado").each(function(index, value) {
            valD = $(this).val();
            if (valD == '') {
                valD = 0;
            }
            declarado = parseFloat(declarado) + parseFloat(valD);
        });
        // $(".td_total").each(function(index, value) {
        //     cargos_add = cargos_add + parseFloat($(this).text());
        // });
        cargos_add = $('#cargos_add').val();
        if ($('#valor_libra2').val() == '0') {
            $('#valor_libra').val($('#servicios_id option:selected').data('tarifa'));
            $('#valor_libra2').val($('#servicios_id option:selected').data('tarifa'));
            $('#valorLibra').html($('#servicios_id option:selected').data('tarifa')); // *********  label
        }
        if ($('#impuesto').val() == '0') {
            if ($('#servicios_id option:selected').data('impuesto_age')) {
                $('#impuesto').val($('#servicios_id option:selected').data('impuesto_age'));
            }
        }
        $('#piezas').val(parseFloat(piezas));
        $('#pesoDim').val(parseFloat(peso));
        $('#volumen').val(parseFloat(isInteger(volumen)));
        $('#peso_total').val(parseFloat(peso));
        $('#peso_cobrado').val(parseFloat(peso));
        if ($('#impuesto').val() == '') {
            impuesto = $('#servicios_id option:selected').data('impuesto_age');
            $('#impuesto').val(impuesto);
        } else {
            impuesto = $('#impuesto').val();
        }
        $('#valor_declarado').val(parseFloat(declarado));
        $('#valor_declarado_tbl').val(parseFloat(declarado));
        pa_aduana = isInteger((parseFloat(declarado) * parseFloat(impuesto) / 100));
        $('#pa_aduana').val(pa_aduana);
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
}

function calculateFlete(flete) {
    var tarifa = parseFloat($('#valor_libra2').val());
    /* VALIDA QUE NO SOBREPASE LA TARIFA DEL SERVICIO */
    // if (parseFloat($('#valor_libra2').val()) < parseFloat($('#valor_libra').val())) {
        if($('#valor_libra').val() == ''){
            tarifa =  0;
        }else{
            tarifa = parseFloat($('#valor_libra').val());
        }
    // }
    var cOpcional = $('#servicios_id option:selected').data('c_opcional');
    /* SE EVALUA SI SE COBRARA POR PESO O VOLUMEN (PESO = 1 - VOLUMEN = 0)*/
    if ($('#servicios_id option:selected').data('cobvol') == 0) {
        if (parseFloat(peso) >= 0 && parseFloat(peso) <= 8) {
            $('#cobrarPor').text('Pes');
            return flete;
        }
        if (parseFloat(peso) > 8) {
            /*PESO * LA TARIFA */
            $('#cobrarPor').text('Pes');
            var flete = parseFloat(peso) * parseFloat(tarifa);
            var diferen = parseFloat(volumen) - parseFloat(peso);
            if (parseFloat(diferen) > 0) {
                /* SE COBRA POR VOLUMEN */
                res = parseFloat(cOpcional) * parseFloat(diferen);
                flete = parseFloat(flete) + parseFloat(res);
                $('#cobrarPor').text('Vol');
            }
            return flete;
        }
    } else {
        if (parseFloat(peso) > parseFloat(volumen)) {
            $('#cobrarPor').text('Pes');
            valor = parseFloat(peso);
        } else {
            valor = parseFloat(volumen);
            $('#cobrarPor').text('Vol');
        }
        if (parseFloat(valor) * parseFloat(tarifa) <= 10) {
            return flete;
        } else {
            if (parseFloat(peso) >= 0 && parseFloat(peso) <= 8) {
                return flete;
            } else {
                if (parseFloat(peso) > 8) {
                    flete = isInteger((parseFloat(valor) * parseFloat(tarifa)));
                    return flete;
                }
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
    totalizeDocument();
}
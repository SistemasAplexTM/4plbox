/*-- Acá van las funciones que se ejecutan al cargar el documento --*/
/* funcion para los tooltips de los botones */
Number.prototype.padLeft = function(base, chr) {
    var len = (String(base || 10).length - String(this).length) + 1;
    return len > 0 ? new Array(len).join(chr || '0') + this : this;
}
$(function() {
    $('body').tooltip({
        selector: 'a[rel="tooltip"], [data-toggle="tooltip"]'
    });
});
$(document).ready(function() {
    //-->
    // $('.ladda-button').ladda('bind', {
    //     timeout: 5000
    // });
    $.fn.select2.defaults.set("theme", "bootstrap");

    if (typeof lang != 'undefined' && lang == 'es') {
        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                },
                /*"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",*/
                "info": "Registros del _START_ al _END_  de un total de _TOTAL_",
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ Registros",
                "infoEmpty": "Mostrando registros del 0 al 0",
                "emptyTable": "No hay datos disponibles en la tabla",
                "infoFiltered": "(Filtrando para _MAX_ Registros totales)",
                "zeroRecords": "No se encontraron registros coincidentes",
            },
            processing: true,
            serverSide: true,
            searching: true,
            LengthChange: false
        });
    } else {
        $.extend(true, $.fn.dataTable.defaults, {
            processing: true,
            serverSide: true,
            searching: true,
            LengthChange: false
            
        });
    }
    /* ESTILO CHOSEN SELECT2 A UN SELECT */
    $('.select2').select2();
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {
            allow_single_deselect: true
        },
        '.chosen-select-no-single': {
            disable_search_threshold: 10
        },
        '.chosen-select-no-results': {
            no_results_text: 'Oops, nothing found!'
        },
        '.chosen-select-width': {
            width: "100%"
        }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
});
/*-- Función para llenar select --*/
/*el idCondition se utiliza para mandar el id del pais para buscar los deptos de ese pais*/
function llenarSelect(module, tableName, idSelect, length, idCondition) {
    var condition = '';
    if (idCondition) {
        condition = '/' + idCondition;
    }
    var url = module + '/selectInput/' + tableName + condition;
    $('#' + idSelect).select2({
        // theme: "classic",
        placeholder: "Seleccionar",
        tokenSeparators: [','],
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: length,
    }).on("change", function(e) {
        // console.log(e);
        $('.select2-selection').css('border-color', '');
        $('#' + idSelect).siblings('small').css('display', 'none');
        $('#' + idSelect + '_input').val($('#' + idSelect).val());
        if (tableName === 'pais') {
            if ($('#deptos_id_input').length) {
                // $('#deptos_id').select2("val", "");
                llenarSelect('ciudad', 'deptos', 'deptos_id', 0, $('#pais_id_input').val()); // module, tableName, id_campo, id_condition
            }
        }
    });
}
/*-- Función para recargar datatables --*/
function refreshTable(tabla) {
    $('#' + tabla).dataTable()._fnAjaxUpdate();
};
/*-- Función para pasar el id de jQuery  a vue para eliminarlo --*/
function eliminar(id, logical) {
    var data = {
        id: id,
        logical: logical
    };
    objVue.delete(data);
}
/*-- Función para pasar el id de jQuery  a vue para deshacer el eliminado --*/
function deshacerEliminar(id) {
    var data = {
        id: id
    };
    objVue.rollBackDelete(data);
}

function deleteError(padre) {
    //valida si tiene elemento abuelo si el padre es igual a -> input-group
    if (padre.attr('class') == 'input-group') {
        padre.parent().removeClass('has-error');
        padre.parent().children('small').css('display', 'none');
    } else {
        padre.removeClass('has-error');
        padre.children('small').css('display', 'none');
    }
}
/* COMPROBAR SI UN NUMERO ES ENTERO */
function isInteger(numero) {
    numero = parseFloat(numero);
    if (numero == 0) {
        return numero;
    } else {
        if (numero % 1 == 0) {
            return numero;
        } else {
            return numero.toFixed(2);
        }
    }
}

function show5() {
    if (typeof lang != 'undefined') {
        if (!document.layers && !document.all && !document.getElementById) return
        var Digital = new Date()
        var hours = Digital.getHours()
        var minutes = Digital.getMinutes()
        var seconds = Digital.getSeconds()
        var dn = "PM"
        if (hours < 12) dn = "AM"
        if (hours > 12) hours = hours - 12
        if (hours == 0) hours = 12
        if (minutes <= 9) minutes = "0" + minutes
        if (seconds <= 9) seconds = "0" + seconds
        //change font size here to your desire
        myclock = hours + ":" + minutes + ":" + seconds + " " + dn
        if (document.layers) {
            document.layers.liveclock.document.write(myclock)
            document.layers.liveclock.document.close()
        } else if (document.all) liveclock.innerHTML = myclock
        else if (document.getElementById) document.getElementById("liveclock").innerHTML = myclock
        setTimeout("show5()", 1000)
    }
}

function number_format(amount) {
  var decimals = 0;
  var separator = '.';
  var separator_decimals = ',';
  // var data = getFormatNumber();
  // console.log('local ', decimals);
  // var decimals = data.decimals;
  // var separator = data.separator;
  // var separator_decimals = data.separator_decimals;

  amount += ''; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

  decimals = decimals || 0; // por si la variable no fue fue pasada

  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0)
      return parseFloat(0).toFixed(decimals);

  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = '' + amount.toFixed(decimals);

  var amount_parts = amount.split('.'),
      regexp = /(\d+)(\d{3})/;

  while (regexp.test(amount_parts[0]))
      amount_parts[0] = amount_parts[0].replace(regexp, '$1' + separator + '$2');

  return amount_parts.join(separator_decimals);
}
// function getFormatNumber(){
//   $.ajax({
//     url: 'formatNumber',
//   }).done(function(data){
//      response = JSON.parse(data.data);
//   });
//   return response;
// }

window.onload = show5
/*Funciones globales para vue js*/
Vue.mixin({
    data: function() {
        return {
            get required() {
                return 0;
            }
        }
    },
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        formatNumber(data){
          return new Intl.NumberFormat("en-IN").format(data);
        },
        getTime() {
            var d = new Date,
                dformat = [d.getFullYear(), (d.getMonth() + 1).padLeft(),
                    d.getDate().padLeft()
                ].join('-') + ' ' + [d.getHours().padLeft(),
                    d.getMinutes().padLeft(),
                    d.getSeconds().padLeft()
                ].join(':');
            return dformat;
        }
    }
})

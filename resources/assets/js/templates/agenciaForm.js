$(document).ready(function() {
    llenarSelectPersonalizado('agencia', 'localizacion', 'localizacion_id', 2); // module, tableName, id_campo
    llenarSelect('agencia', 'servicios', 'servicios_id', 0); // module, tableName, id_campo
    $('#servicios_id').on("change", function(e) {
        var tarifa = $('#servicios_id option:selected').data("tarifa");
        var seguro = $('#servicios_id option:selected').data("seguro");
        var tarifa_minima = $('#servicios_id option:selected').data("tarifa_minima");
        $('#tarifaP').val(tarifa);
        $('#seguroP').val(seguro);
        $('#min').val(tarifa_minima);
    });
    $('#saveForm').on('click', function() {
        if ($('#detalleAgencia tbody tr').length > 0) {
            $("#formaagencia").submit();
        } else {
            $('#noEnviar').css('display', 'inline-block');
        }
    });
    if ($('#paypal').is(':checked')) {
        objVue.paypal = true;
    }
    if ($('#mail').is(':checked')) {
        objVue.mailchimp = true;
    }
    if ($('#zopim').is(':checked')) {
        objVue.zopim = true;
    }
});

function addRow() {
    var cont = $('#detalleAgencia tbody tr').length + 1;
    var serviId = $('#servicios_id option:selected').val();
    var servi = $('#servicios_id option:selected').text();
    var tariM = $('#servicios_id option:selected').data('tarifa_minima');
    var tariMinimaP = $('#servicios_id option:selected').data('minima_a');
    var tariP = $('#tarifaP').val();
    var segup = $('#seguroP').val();
    var tariA = $('#tarifaA').val();
    var segu = $('#seguro').val();
    var min_agency = $('#min_agency').val();
    var fila = '<tr id="fila' + cont + '" class="rowServices">';
    var td1 = '<td><input type="hidden" id="servi" name="servicios_id[]" value="' + serviId + '" class="form-control" readonly><input type="text" id="serviN" name="serviN[]" value="' + servi + '" class="form-control" readonly></td>';
    var td2 = '<td><input type="text" id="tariP" name="tarifa_principal[]" value="' + tariP + '" class="form-control" readonly></td>';
    var td3 = '<td><input type="text" id="tariM" name="tarifa_minima[]" value="' + tariM + '" class="form-control" readonly></td>';
    var td4 = '<td><input type="text" id="segup" name="seguro_principal[]" value="' + segup + '" class="form-control" readonly></td>';
    var td5 = '<td><input type="text" id="tariA" name="tarifa_agencia[]" value="' + tariA + '" class="form-control" readonly></td>';
    var td6 = '<td><input type="text" id="segu" name="seguro[]" value="' + segu + '" class="form-control" readonly></td>';
    var td7 = '<td><input type="text" id="min_a" name="min_a[]" value="' + min_agency + '" class="form-control" readonly></td>';
    var td8 = '<td><button class="btn btn-danger btn-xs btn_remove" type="button" onclick="removeRowServices(' + cont + ')"><i class="fal fa-times" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></button></td>';
    fila += td1 + td2 + td3 + td4 + td5 + td6 + td7 + td8 + '</tr>';
    $('#noEnviar').css('display', 'none');
    $('#detalleAgencia').append(fila);
    $('#tarifaA').val('');
    $('#seguro').val(0);
}

function llenarSelect(module, tableName, idSelect, length) {
    if ($('#edit').val() == 'edit') {
        var url = '../selectInput/' + tableName;
    } else {
        var url = 'selectInput/' + tableName;
    }
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        success: function(data) {
            if (data.code === 200) {
                /* llenar select */
                $(data.items).each(function(index, value) {
                    $("#" + idSelect).append('<option value="' + value.id + '" data-tarifa="' + value.tarifa + '" data-tarifa_minima="' + value.peso_minimo + '"  data-seguro="' + value.seguro + '" data-impuesto="' + value.impuesto + '">' + value.tipo_embarque + ' - ' + value.text + '</option>');
                });
            } else {
                alert('error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            data = {
                error: jqXHR + ' - ' + textStatus + ' - ' + errorThrown
            }
            $('#modal' + 1).modal('toggle');
            $('body').append('<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title" id="myModalLabel">ERROR EN TRANSACCIÓN</h4></div><div class="modal-body">' + data.error + '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div></div></div>');
            $('#modalError').modal({
                show: true
            });
        }
    });
}

/*-- Función para llenar select PERSONALIZADO --*/
function llenarSelectPersonalizado(module, tableName, idSelect, length) {
    if ($('#edit').val() == 'edit') {
        var url = '../selectInput/' + tableName;
    } else {
        var url = 'selectInput/' + tableName;
    }
    $('#' + idSelect).select2({
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
                console.log(data.items);
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
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        minimumInputLength: length,
    }).on("change", function(e) {
        $('.select2-selection').css('border-color', '');
        $('#' + idSelect).siblings('small').css('display', 'none');
    });
}

function formatRepo(repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup = "<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'><strong><i class='fal fa-map-marker'></i> " + repo.text + " / " + repo.deptos + " / " + repo.pais + "</strong></div>";
    return markup;
}

function formatRepoSelection(repo) {
    return repo.text || repo.id + ' - ' + repo.text;
}

function removeRowServices(index, idDetail) {
    swal({
        title: 'Atención!',
        text: "Desea eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.value) {
            if (idDetail) {
                var data = {
                    id: idDetail,
                    index: index,
                    logical: true
                };
                objVue.delete(data);
            } else {
                executeRemoveRow(index, idDetail);
            }
        }
    });
}

function editRowServices(index, idDetail) {
    if ($('#btn_edit' + idDetail).attr('class') === 'btn_edit edit_btn') {
        $('#tariA' + idDetail).removeAttr('readonly');
        $('#segu' + idDetail).removeAttr('readonly');
        $('#min' + idDetail).removeAttr('readonly');
        $('#btn_edit' + idDetail).removeClass('edit_btn');
        $('#btn_edit' + idDetail).addClass('create_btn');
        $('#btn_edit' + idDetail).children().removeClass('fa-pencil');
        $('#btn_edit' + idDetail).children().addClass('fa-check');
        $('#tariA' + idDetail).focus();
    } else {
        if ($('#btn_edit' + idDetail).attr('class') === 'btn_edit create_btn') {
            var id = idDetail;
            var tariA = $('#tariA' + idDetail).val();
            var segu = $('#segu' + idDetail).val();
            var min = $('#min' + idDetail).val();
            var idAgencia = $('#agencia_id').val();
            var data = {
                id: idDetail,
                tarifa: tariA,
                seguro: segu,
                minima: min,
            };
            objVue.editService(data);
            $('#tariA' + id).removeAttr('style');
            $('#tariA' + id).attr('readonly', 'readonly');
            $('#segu' + id).removeAttr('style');
            $('#segu' + id).attr('readonly', 'readonly');
            $('#min' + id).removeAttr('style');
            $('#min' + id).attr('readonly', 'readonly');
            $('#btn_edit' + id).removeClass('create_btn');
            $('#btn_edit' + id).addClass('edit_btn');
            $('#btn_edit' + id).children().removeClass('fa-check');
            $('#btn_edit' + id).children().addClass('fa-pencil');
        }
    }
}

function executeRemoveRow(index, id) {
    console.log(index);
    $('#fila' + index).remove();
    var item = 1;
    /* indexar nuevamente las filas */
    $(".rowServices").each(function(index, value) {
        $(value).attr('id', 'fila' + (item++));
    });
    item = 1;
    $(".btn_remove").each(function(index, value) {
        if (id != '') {
            var id = $(value).attr('id');
        }
        $(value).attr('onclick', 'removeRowServices(' + (item++) + ', ' + id + ')');
    });
}
// /* objetos VUE formulario */
var objVue = new Vue({
    el: '#agenciaform',
    data: {
        email: null,
        descripcion: null,
        responsable: null,
        direccion: null,
        telefono: null,
        zip: null,
        email: null,
        formErrors: {},
        listErrors: {},
        paypal: false,
        mailchimp: false,
        zopim: false,
    },
    methods: {
      savePrint: function() {
        axios.post('savePrint', { print: $('#installedPrinterName').val() }).then(response => {
          toastr.success("<div><p>Registrado exitosamente.</p></div>");
          toastr.options.closeButton = true;
        }).catch(error => console.log(error))
      },
        functionalities: function(op, id) {
            let me = this;
            setTimeout(function() {
                if (op === 'pp') {
                    if ($('#paypal').is(':checked')) {
                        me.paypal = true;
                    } else {
                        me.paypal = false;
                    }
                }
                if (op === 'mc') {
                    if ($('#mail').is(':checked')) {
                        me.mailchimp = true;
                    } else {
                        me.mailchimp = false;
                    }
                }
                if (op === 'zp') {
                    if ($('#zopim').is(':checked')) {
                        me.zopim = true;
                    } else {
                        me.zopim = false;
                    }
                }
            }, 200);
        },
        resetForm: function() {
            this.id = '';
            this.formErrors = {};
            this.listErrors = {};
        },
        saveDocument: function() {},
        delete: function(data) {
            if (data.logical === true) {
                axios.get('../delete/' + data.id + '/' + data.logical + '/detalle').then(response => {
                    toastr.success("<div><p>Registro eliminado exitosamente.</p></div>");
                    toastr.options.closeButton = true;
                    executeRemoveRow(data.index);
                });
            } else {
                /*axios.delete('agencia/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });*/
            }
        },
        editService: function(data) {
            var me = this;
            axios.put('../updateDetail/' + data.id, {
                'tarifa_agencia': data.tarifa,
                'seguro': data.seguro,
                'tarifa_minima': data.minima,
            }).then(function(response) {
                if (response.data['code'] == 200) {
                    toastr.success('Registro Actualizado correctamente');
                    toastr.options.closeButton = true;
                    me.resetForm();
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                    console.log(response.data);
                }
            }).catch(function(error) {
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
        }
    },
});

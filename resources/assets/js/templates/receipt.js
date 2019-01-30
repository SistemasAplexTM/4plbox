$(document).ready(function () {
  var config = {
      '.chosen-select': {},
      '.chosen-select-deselect': {allow_single_deselect: true},
      '.chosen-select-no-single': {disable_search_threshold: 10},
      '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
      '.chosen-select-width': {width: "95%"}
  }
  for (var selector in config) {
      $(selector).chosen(config[selector]);
  }
  $('.chosen-container').css('width', '100%');
  $('.chosen-single').css('border', '1px solid #e5e6e7');
  $('.chosen-single').css('border-radius', '0px');
  $('.chosen-single').css('background', '0px');
  $('.chosen-single').css('box-shadow', 'none');
  $('.chosen-drop').css('border', '1px solid #e5e6e7');

  $('.chosen-search input').autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "receipt/getConsignee/"+request.term,
        dataType: "json",
        success: function( data ) {
          $('.chosen-select').empty();
          response( $.map( data, function( item ) {
            $('.chosen-select').append('<option value="">Seleccione</option>');
            $.each(item, function(key, value) {
              $('.chosen-select').append(
                '<option value="'+value.id+'" data-direccion="'+value.direccion+'" data-telefono="'+value.telefono+'" data-ciudad="'+value.ciudad+'">' + value.name + '</option>'
              );
            });
          }));
          $(".chosen-select").trigger("chosen:updated");
        }
      });
    }
  });

  $('#consignee_id').on('change', function () {
      var dir = $('#consignee_id option:selected').data('direccion');
      var tel = $('#consignee_id option:selected').data('telefono');
      var ciu = $('#consignee_id option:selected').data('ciudad');
      $('#direccion').val(dir);
      $('#telefono').val(tel);
      $('#ciudad').val(ciu);
  });

  $('#tbl-receipt').DataTable({
      ajax: 'receipt/all',
      columns: [{
          data: 'numero_recibo',
          name: 'numero_recibo'
      }, {
          data: 'consignee',
          name: 'consignee'
      }, {
          data: 'direccion',
          name: 'direccion',
          "render": function(data, type, full, meta) {
            return '<div>' + full.direccion +'</div><small>' + full.ciudad + '</small>'
          }
      }, {
          data: 'telefono',
          name: 'telefono'
      }, {
          sortable: false,
          "render": function(data, type, full, meta) {
              var params = [
                  full.id,
                  "'" + full.consignee + "'",
                  full.documento_detalle_id
              ];
              var btn_view = "<a onclick=\"view(" + params + ")\" class='' data-toggle='tooltip' data-placement='top' title='Ver'><i class='fa fa-eye'></i></a> ";
              var btn_print = "<a href='receipt/printReceipt/" + full.id + "' target='_blank' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Imprimir'><i class='fa fa-print'></i></a> ";

              return btn_view + btn_print;
          }
      }]
  });

  $('#input_name').on('click', function () {
      $('#consignee_id').parent().toggle();
      $('#cliente').toggle();
      $('#consignee_id').val('').prop("disabled", false).trigger('chosen:updated');
    });
    $('#entregado').change(function () {
      if ($(this).prop('checked') == true) {
        if (objVue.id != null) {
          $('#div_wrh_guia_r').slideDown(200);
          $('#div_status').slideDown(200);
        }
      } else {
        $('#div_wrh_guia_r').slideUp(200);
        $('#div_status').slideUp(200);
      }
    });

});
function view(id, consignee, id_doc_detail){
  // $('#consignee_id').select2({'disabled': true});
  $('#consignee_id').append('<option value="' + id + '" selected="selected">' + consignee + '</option>').val(id).trigger('chosen:updated');
  $('#consignee_id').prop('disabled', true).trigger("chosen:updated");
  $('#direccion').prop('disabled', true);
  $('#telefono').prop('disabled', true);
  $('#ciudad').prop('disabled', true);
  $('#transportador').prop('disabled', true);
  $('#warehouse').prop('disabled', true);
  objVue.view(id, id_doc_detail);
}

function view(id, consignee, id_doc_detail){
  objVue.view(id, id_doc_detail);
}

var objVue = new Vue({
    el: '#receipt',
    data: {
      warehouse: null,
      num_warehouse_guia_r: null,
      document: {},
      detail: [],
      transportador: '',
      entregado: false,
      status: null,
      id: null,
      documento_detalle_id: null,
      editar: 0,
    },
    methods:{
      checkDocument(id){
        if (this.num_warehouse_guia_r != null) {
          var row = this.detail.filter(result => result.warehouse == this.num_warehouse_guia_r);
          if (row.length > 0) {
            axios.post('receipt/checkReceipt', {'id_doc': this.documento_detalle_id, 'id': row[0].id, 'warehouse': row[0].warehouse, 'status': this.status}).then(({data}) => {
              this.num_warehouse_guia_r = null
              this.status = null
              $('#' + row[0].id).removeClass('badge-warning').addClass('badge-primary').html('Revisado');
            })
          }else{
            toastr.warning('Debe ingresar un número de warehouse válido para la revisión.');
          }
        }else{
          toastr.warning('Debe ingresar un número de warehouse para la revisión.');
        }
      },
      addDocumentToReceipt(){
        let me = this;
        axios.get('receipt/searchDocument/' + me.warehouse).then(({data}) => {
          if (data.data != null) {
            me.document = data.data[0]
            if (me.detail.length <= 0) {
              //me.saveDocument();
            }
            me.detail.push(data.data[0])
            this.warehouse = null
          }
        });
      },
      getDocument(id){
        axios.get('receipt/getDocument/' + id).then(response => {
          var datos = response.data;
          if (datos != null) {
            var cliente = JSON.parse(datos['cliente_datos'])
            $('#direccion').val(cliente.direccion);
            $('#telefono').val(cliente.telefono);
            $('#ciudad').val(cliente.ciudad);
            this.transportador = datos['transportador']
          }
        });
      },
      getDetail(id){
        axios.get('receipt/searchReceiptDetail/' + id).then(response => {
          var datos = response.data;
          if (datos.data != null) {
            // console.log(datos.data)
            this.detail = datos.data
          }
        });
      },
      saveDocument(){
        var data = {
          id_client: $('#consignee_id').val(),
          data_client: {
            direccion: $('#direccion').val(),
            telefono: $('#telefono').val(),
            ciudad: $('#ciudad').val()
          },
          transportador: this.transportador,
          document: this.document
        }
        let me = this
        axios.post('receipt', {data}).then(response => {
          var datos = response.data;
          me.id = datos.id
        });
      },
      saveDetail(){
        this.saveDocument();
        var data = {
          id_client: $('#consignee_id').val(),
          data_client: {
            direccion: $('#direccion').val(),
            telefono: $('#telefono').val(),
            ciudad: $('#ciudad').val()
          },
          transportador: this.transportador,
          entregado: $("#entregado").prop('checked'),
          document: this.document
        }
        axios.post('receipt/saveDetail', {factura_id: this.id, detalle: this.detail, head: data}).then(response => {
          var datos = response.data;
          if (datos.data != null) {
          }
        });
        refreshTable('tbl-receipt');
        this.cancel()
      },
      view(params,id_doc_detail){
        this.id = params
        this.documento_detalle_id = id_doc_detail
        this.editar = 1
        this.getDocument(params)
        this.getDetail(params)
      },
      cancel(){
        this.id = null
        this.documento_detalle_id = null
        this.editar = 0
        this. warehouse = null
        this.document = {}
        this.detail = []
        this.transportador = ''
        this.entregado = 0
        $('#direccion').val('')
        $('#telefono').val('')
        $('#ciudad').val('')
        $('#consignee_id').val('')
        $('#consignee_id').prop('disabled', false).trigger("chosen:updated");
        $('#direccion').prop('disabled', false);
        $('#telefono').prop('disabled', false);
        $('#ciudad').prop('disabled', false);
        $('#transportador').prop('disabled', false);
        $('#warehouse').prop('disabled', false);
      }
    }
});

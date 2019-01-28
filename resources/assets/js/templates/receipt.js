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
                  full.id
              ];
              var btn_view = "<a onclick=\"view(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Ver'><i class='fa fa-eye'></i></a> ";
              var btn_print = " <a onclick=\"print(" + params + ")\" class='btn btn-outline btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Imprimir'><i class='fa fa-print'></i></a> ";

              return btn_view + btn_print;
          }
      }]
  });
});

var objVue = new Vue({
    el: '#receipt',
    mounted: function() {
        //
    },
    data: {
      warehouse: null,
      editar: 0,
    },
    methods:{
      addDocumentToReceipt(){
        let me = this;
        axios.get('receipt/searchDocument/' + me.warehouse).then(response => {
          var datos = response.data;
          if (datos.data != null) {
            console.log(datos.data);
          }
        });
      },
      getDetail(id){
        axios.get('receipt/searchReceiptDetail/' + id).then(response => {
          var datos = response.data;
          if (datos.data != null) {
            console.log(datos.data);
          }
        });
      }
    }
});

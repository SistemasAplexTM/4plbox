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
        url: "ajaxpro.php?name="+request.term,
        dataType: "json",
        success: function( data ) {
          $('.select-box').empty();
          response( $.map( data, function( item ) {
            $('.select-box').append('<option value="'+item.id+'">' + item.name + '</option>');
          }));
          $(".select-box").trigger("chosen:updated");
        }
      });
    }
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

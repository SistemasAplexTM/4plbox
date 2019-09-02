<template lang="html">
  <div class="contentc">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Facturas</h5>
            <div class="ibox-tools">
                <a class="btn btn-primary" data-toggle="tooltip" title="Crear Factura"><span>Nueva <i class="fal fa-plus" style="font-size: small;"></i></span></a>
            </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="tbl-invoice" class="table table-striped table-hover table-bordered" style="width: 100%;">
              <thead>
                <tr>
                  <th><i class="fal fa-file-invoice"></i> Factura</th>
                  <th><i class="fal fa-calendar-alt"></i> Fecha</th>
                  <th><i class="fal fa-user"></i> Cliente</th>
                  <th><i class="fal fa-user"></i> Destinatario</th>
                  <th><i class="fal fa-dollar-sign"></i> Monto Total</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      id: 0
    }
  },
  mounted(){
    this.getAll();
  },
  methods:{
    getAll(){
      $('#tbl-invoice').DataTable({
          processing: true,
          serverSide: true,
          responsive: true,
          lengthMenu: [[40, 50, 80, 100, 200, -1], [40, 50, 80, 100, 200, "All"]],
          ajax: '/invoice/getAll',
          "order": [[ 0, "desc" ]],
          columns: [
              {data: 'consecutive', name: 'consecutive'},
              {data: 'date_document', name: 'date_document'},
              {data: 'shipper', name: 'shipper'},
              {data: 'consignee', name: 'consignee'},
              {data: 'id', name: 'id'},
              {
                  sortable: false,
                  "render": function (data, type, full, meta) {
                      var btn_edit = '';
                      var btn_delete = '';
                      var btn_edit = '<a href="#" class="edit" title="Editar" data-toggle="tooltip" style="color:#FFC107;"><i class="fal fa-pencil fa-lg"></i></a> ';
                      var btn_print = ' <a href="invoice/pdfLabels/'+full.id+'" target="_blank" class="edit" style="color:#4e5451;"><i class="fal fa-print fa-lg"></i> Labels</a> ';
                      var btn_pdf = ' <a href="#" class="edit" style="color:#4e5451;"><i class="fal fa-file-pdf fa-lg"></i> Descargar PDF</a> ';
                      var btn_email = ' <a href="#" class="edit" style="color:#4e5451;"><i class="fal fa-envelope-open fa-lg"></i> Enviar Email</a> ';
                      var btn_delete = ' <a class="delete" title="Eliminar" data-toggle="tooltip" style="color:#E34724;"><i class="fal fa-trash-alt fa-lg"></i></a>';
                      var btn_group = '<div class="btn-group" data-toggle="tooltip" title="Acciones">'+
                              '<button type="button" class="btn btn-default btn-outline dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                '<i class="fal fa-ellipsis-v"></i>'+
                              '</button>'+
                              '<ul class="dropdown-menu dropdown-menu-right pull-right" style="font-size: 15px!important;">'+
                                '<li>'+btn_print+'</li>'+
                                '<li role="separator" class="divider"></li>'+
                                '<li>'+ btn_pdf +'</li>'+
                                '<li>'+btn_email+'</li>'+
                              '</ul>'+
                            '</div>';
                      return btn_edit + btn_group + btn_delete;
                  },
                  width:100
              }
          ]
      });
    }
  }
}
</script>

<style lang="css" scoped>

</style>

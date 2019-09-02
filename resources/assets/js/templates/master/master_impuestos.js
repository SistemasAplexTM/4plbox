$(document).ready(function () {
    // $('#tbl-master_impuestos').DataTable({
    //     ajax: 'master/all/reg',
    //     "order": [[ 2, "desc" ]],
    //     columns: [
    //         {data: 'num_master', name: 'num_master', "render": function (data, type, full, meta) {
    //           var house = '';
    //           if(full.master_id != null){
    //             house =  '<div><label for="bodega" class="lb_status badge badge-success">House</label></div>';
    //           }
    //           return full.num_master + house;
    //         }},
    //         {data: 'aerolinea', name: 'aerolinea'},
    //         {data: 'created_at', name: 'created_at'},
    //         {data: 'tarifa', name: 'tarifa'},
    //         {data: 'peso', name: 'peso'},
    //         {data: 'peso_kl', name: 'peso_kl'},//peso lb
    //         {
    //             "render": function (data, type, full, meta) {
    //                 return full.consignee + '<div style="font-size:13px;color:#adadad;">Contacto: '+full.contacto+'</div>';
    //             }
    //         },
    //         {data: 'ciudad', name: 'ciudad'},
    //         {data: 'consecutivo', name: 'consecutivo'},
    //         {
    //             sortable: false,
    //             "render": function (data, type, full, meta) {
    //                 var btn_edit = '';
    //                 var btn_delete = '';
    //                 var btn_consolidado = '';
    //                 var btn_hawb = '';
    //                 var btn_label = '<li><a onclick="createLabel('+ full.id +', \''+ full.num_master +'\')"><i class="fal fa-tags fa-lg"></i> Labels bolsas</a></li>';
    //                 if(full.master_id == null){
    //                   var btn_hawb = '<li><a onclick="createHouse('+ full.id +', \''+ full.num_master +'\')"><i class="fal fa-copy fa-lg"></i> Crear House</a></li>';
    //                 }
    //                 if (permission_update) {
    //                     var btn_edit = '<li><a href="master/create/' + full.id + '"><i class="fal fa-pencil fa-lg"></i> Editar</a></li>';
    //                 }
    //                 if (permission_delete) {
    //                     var btn_delete = '<li style="color:#E34724;"><a onclick=\"modalEliminar()\"><i class="fal fa-trash-alt fa-lg"></i> Eliminar</a></li>';
    //                 }
    //                 var btn_cost = '<li><a onclick="createCost('+ full.id +', \''+ full.num_master +'\', \''+ full.peso +'\', \''+ full.peso_kl +'\', \''+ full.tarifa +'\', \''+ full.trm +'\', \''+ full.fecha_liquidacion +'\')"><i class="fal fa-file-invoice-dollar fa-lg"></i> Crear Costos</a></li>';
    //                 if(full.consolidado_id != null){
    //                   btn_consolidado = "<li class='divider'></li>" +
    //                      "<li><a href='impresion-documento/" +full.consolidado_id +"/consolidado' target='_blank'> <spam class='fal fa-print'></spam> Consolidado</a></li>" +
    //                      "<li><a href='impresion-documento/" +full.consolidado_id +"/consolidado_guias' target='_blank'> <spam class='fal fa-print'></spam> Guias hijas</a></li>" +
    //                      "<li><a href='master/imprimirGuias/" +full.consolidado_id +"/labels' target='_blank'> <spam class='fal fa-print'></spam> Labels guias hijas</a></li>";
    //                 }
    //                 var btns = "<div class='btn-group'>" +
    //                  "<button type='button' class='btn btn-success dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
    //                   "<i class='fal fa-ellipsis-v'></i>" +
    //                    "</button>" +
    //                    "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
    //                     btn_edit +
    //                     btn_hawb +
    //                     btn_cost +
    //                     '<li role="separator" class="divider"></li>' +
    //                     btn_delete +
    //                      "</ul></div>";
    //
    //                 var btns_print = "<div class='btn-group'>" +
    //                  "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
    //                   "<i class='fal fa-print fa-lg'></i>  <span class='caret'></span>" +
    //                    "</button>" +
    //                    "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='master/imprimir/" +full.id + '/'+true +
    //                     "' target='_blank'> <spam class='fal fa-print'></spam> Master</a></li>" +
    //                      "<li><a href='master/imprimir/" +full.id +"' target='_blank'> <spam class='fal fa-print'></spam> Master simple</a></li>" +
    //                      "<li><a onclick=\"createLabel("+ full.id +", '"+ full.num_master + "')\"> <spam class='fal fa-print'></spam> Labels</a></li>" +
    //                      // "<li><a href='master/imprimirLabel/" +full.id +"' target='_blank'> <spam class='fal fa-print'></spam> Labels 2</a></li>" +
    //                      "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fal fa-print'></spam> Contrato</a></li>" +
    //                      "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fal fa-print'></spam> TSA</a></li>" +
    //                      btn_consolidado +
    //                      "</ul></div>";
    //                 return btns + ' ' + btns_print;
    //             }
    //         }
    //     ]
    // });
});

var objVue = new Vue({
  el: '#master_hijas',
  mounted(){

  },
  data:{

  },
  methods: {
    
  }
});

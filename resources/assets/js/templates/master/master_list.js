$(document).ready(function () {
    $('#tbl-master').DataTable({
        ajax: 'master/all/reg',
        "order": [[ 2, "desc" ]],
        columns: [
            {data: 'num_master', name: 'num_master', "render": function (data, type, full, meta) {
              var house = '';
              if(full.master_id != null){
                house =  '<div><label for="bodega" class="lb_status badge badge-success">House</label></div>';
              }
              return full.num_master + house;
            }},
            {data: 'aerolinea', name: 'aerolinea'},
            {data: 'created_at', name: 'created_at'},
            {data: 'tarifa', name: 'tarifa'},
            {data: 'peso', name: 'peso'},
            {data: 'peso_kl', name: 'peso_kl'},//peso lb
            {
                "render": function (data, type, full, meta) {
                    return full.consignee + '<div style="font-size:13px;color:#adadad;">Contacto: '+full.contacto+'</div>';
                }
            },
            {data: 'ciudad', name: 'ciudad'},
            {data: 'consecutivo', name: 'consecutivo'},
            {
                sortable: false,
                "render": function (data, type, full, meta) {
                    var btn_edit = '';
                    var btn_delete = '';
                    var btn_consolidado = '';
                    var btn_hawb = '';
                    var btn_label = '<li><a onclick="createLabel('+ full.id +', \''+ full.num_master +'\')"><i class="fal fa-tags fa-lg"></i> Labels bolsas</a></li>';
                    if(full.master_id == null){
                      var btn_hawb = '<li><a onclick="createHouse('+ full.id +', \''+ full.num_master +'\')"><i class="fal fa-copy fa-lg"></i> Crear House</a></li>';
                    }
                    if (permission_update) {
                        var btn_edit = '<li><a href="master/create/' + full.id + '"><i class="fal fa-pencil fa-lg"></i> Editar</a></li>';
                    }
                    if (permission_delete) {
                        var btn_delete = '<li style="color:#E34724;"><a onclick=\"modalEliminar()\"><i class="fal fa-trash-alt fa-lg"></i> Eliminar</a></li>';
                    }
                    var btn_cost = '<li><a onclick="createCost('+ full.id +', \''+ full.num_master +'\', \''+ full.peso +'\', \''+ full.peso_kl +'\', \''+ full.tarifa +'\', \''+ full.trm +'\', \''+ full.fecha_liquidacion +'\')"><i class="fal fa-file-invoice-dollar fa-lg"></i> Crear Costos</a></li>';
                    if(full.consolidado_id != null){
                      btn_consolidado = "<li class='divider'></li>" +
                         "<li><a href='impresion-documento/" +full.consolidado_id +"/consolidado' target='_blank'> <spam class='fa fa-print'></spam> Consolidado</a></li>" +
                         "<li><a href='impresion-documento/" +full.consolidado_id +"/consolidado_guias' target='_blank'> <spam class='fa fa-print'></spam> Guias hijas</a></li>" +
                         "<li><a href='master/imprimirGuias/" +full.consolidado_id +"/labels' target='_blank'> <spam class='fa fa-print'></spam> Labels guias hijas</a></li>";
                    }
                    var btns = "<div class='btn-group'>" +
                     "<button type='button' class='btn btn-success dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                      "<i class='fa fa-ellipsis-v'></i>" +
                       "</button>" +
                       "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
                        btn_edit +
                        btn_hawb +
                        btn_cost +
                        '<li role="separator" class="divider"></li>' +
                        btn_delete +
                         "</ul></div>";

                    var btns_print = "<div class='btn-group'>" +
                     "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                      "<i class='fal fa-print fa-lg'></i>  <span class='caret'></span>" +
                       "</button>" +
                       "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='master/imprimir/" +full.id + '/'+true +
                        "' target='_blank'> <spam class='fa fa-print'></spam> Master</a></li>" +
                         "<li><a href='master/imprimir/" +full.id +"' target='_blank'> <spam class='fa fa-print'></spam> Master simple</a></li>" +
                         "<li><a onclick=\"createLabel("+ full.id +", '"+ full.num_master + "')\"> <spam class='fa fa-print'></spam> Labels</a></li>" +
                         // "<li><a href='master/imprimirLabel/" +full.id +"' target='_blank'> <spam class='fa fa-print'></spam> Labels 2</a></li>" +
                         "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fa fa-print'></spam> Contrato</a></li>" +
                         "<li><a href='impresion-documento/pdfTsa' target='_blank'> <spam class='fa fa-print'></spam> TSA</a></li>" +
                         btn_consolidado +
                         "</ul></div>";
                    return btns + ' ' + btns_print;
                }
            }
        ]
    });
});

function createHouse(id, master) {
    objVue.createHouseAwb(id, master);
}

function createCost(id, master, peso, peso_kl, tarifa, trm, fecha_liquidacion) {
    objVue.id_master = id;
    objVue.master = master;
    objVue.cost_weight_lb = peso;
    objVue.cost_weight = peso_kl;
    objVue.cost_rate = tarifa;
    objVue.cost_trm = (trm !== null && trm !== "null" && trm !== '') ? trm : null;
    objVue.cost_date = (fecha_liquidacion !== null && fecha_liquidacion !== "null" && fecha_liquidacion !== '') ? fecha_liquidacion : objVue.getTime();
    objVue.cost_edit = false;
    if (trm !== null && trm !== "null" && trm !== '') {
      objVue.cost_edit = true;
    }
    $('#modalMasterCost').modal('show');
}

function createLabel(id, master) {
    objVue.id_master = id;
    objVue.master = master;
    $('#modalPrintLabelsMaster').modal('show');
}

var objVue = new Vue({
 el: '#master_list',
 mounted(){
   this.getData();
   this.cost_date = this.getTime();
 },
 data:{
   options: [],
   consolidado_id: null,
   list: [],
   id_master: null,
   master: null,
   type: 'COURIER',
   loading: false,
   cost_date: null,
   cost_trm: null,
   cost_rate: 0,
   cost_weight: 0,
   cost_weight_lb: 0,
   cost_loading: false,
   cost_edit: false,// PARA SABER SI EDITO O CREO EL COSTO DE LA MASTER
   cost_text_save: 'Continuar',
 },
 methods: {
   saveCost(){
     let me = this;
     var data = {
       master_id : this.id_master,
       cost_date : this.cost_date,
       cost_trm : this.cost_trm,
       cost_rate : this.cost_rate,
       cost_weight : this.cost_weight,
       cost_edit : this.cost_edit,
     }
     me.cost_loading = true;
     me.cost_text_save = 'Guardando...';
     axios.post('master/saveCostMaster', data).then(function(response) {
         if (response.data['code'] == 200) {
            me.cost_loading = false;
            me.cost_text_save = 'Continuar';
            toastr.success('Registro guardado correctamente');
            location.href = "master/"+ me.id_master +"/impuestosMaster";
         }else{
           me.cost_loading = false;
           me.cost_text_save = 'Continuar';
         }
     }).catch(function(error) {
         console.log(error);
         me.cost_loading = false;
         me.cost_text_save = 'Continuar';
         toastr.error("Error." + error, {
             timeOut: 50000
         });
     });
   },
   createLabelBags(){
     window.open('master/' + this.id_master + '/getDataPrintBagsConsolidate/' + this.type);
   },
   createHouseAwb(id, master){
     swal({
         title: "<div>¿Desea crear un House de la master '"+ master +"'?</div>",
         text: "",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: "Si, Crear",
         cancelButtonText: "No, Cancelar!",
     }).then((result) => {
         if (result.value) {
             axios.get('master/hawb/' + id).then(function(response) {
                 if (response.data['code'] == 200) {
                     toastr.success('House creado correctamente.');
                     refreshTable('tbl-master');
                 }
             }).catch(function(error) {
                 console.log(error);
                 toastr.error("Error." + error, {
                     timeOut: 50000
                 });
             });
         }
     })
   },
   getData(){
     var me = this;
     axios.post('master/getDataConsolidados/0').then(function(response) {
         me.options = response.data;
     }).catch(function(error) {
         console.log(error);
         toastr.warning('Error: -' + error);
     });
   },
   createMaster(){
     var consolidado_id = null;
     if(this.consolidado_id != null){
       consolidado_id = this.consolidado_id.id;
     }
     location.href = "master/create/" + null + '/' + consolidado_id;
   }
	}
});

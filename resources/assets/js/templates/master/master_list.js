$(document).ready(function () {
  $('#modalMasterCost').on('show.bs.modal', function () {
    objVue.getDataSelectCost();
  });
  $('#tbl-master').DataTable({
    ajax: 'master/all/reg',
    "order": [
      [2, "desc"]
    ],
    columns: [{
        data: 'num_master',
        name: 'num_master',
        "render": function (data, type, full, meta) {
          var house = '';
          if (full.master_id != null) {
            house = '<div><label for="bodega" class="lb_status badge badge-success">House</label></div>';
          }
          return full.num_master + house;
        }
      },
      {
        data: 'aerolinea',
        name: 'aerolinea'
      },
      {
        data: 'created_at',
        name: 'created_at'
      },
      {
        data: 'tarifa',
        name: 'tarifa'
      },
      {
        data: 'peso',
        name: 'peso',
        filterable: false
      },
      {
        data: 'peso_kl',
        name: 'peso_kl',
        filterable: false
      }, //peso lb
      {
        name: 'consignee',
        "render": function (data, type, full, meta) {
          return full.consignee + '<div style="font-size:13px;color:#adadad;">Contacto: ' + full.contacto + '</div>';
        }
      },
      {
        data: 'ciudad',
        name: 'ciudad'
      },
      {
        data: 'consecutivo',
        name: 'consecutivo',
        sortable: false,
        filterable: false
      },
      {
        sortable: false,
        "render": function (data, type, full, meta) {
          var btn_edit = '';
          var btn_delete = '';
          var btn_consolidado = '';
          var btn_hawb = '';
          var btn_label = '<li><a onclick="createLabel(' + full.id + ', \'' + full.num_master + '\')"><i class="fal fa-tags fa-lg"></i> Labels bolsas</a></li>';
          if (full.master_id == null) {
            var btn_hawb = '<li><a onclick="createHouse(' + full.id + ', \'' + full.num_master + '\')"><i class="fal fa-copy fa-lg"></i> Crear House</a></li>';
          }
          if (permission_update) {
            var btn_edit = '<li><a href="master/create/' + full.id + '"><i class="fal fa-pencil fa-lg"></i> Editar</a></li>';
          }
          if (permission_delete) {
            var btn_delete = '<li style="color:#E34724;"><a onclick=\"modalEliminar()\"><i class="fal fa-trash-alt fa-lg"></i> Eliminar</a></li>';
          }
          var btn_cost = '<li><a onclick="createCost(' + full.id + ', \'' + full.num_master + '\', \'' + full.peso + '\', \'' + full.peso_kl + '\', \'' + full.tarifa + '\')"><i class="fal fa-file-invoice-dollar fa-lg"></i> Crear Costos</a></li>';
          var btn_xml = '<li><a href="master/generateXml/' + full.id + '" target="_blank"><i class="fal fa-file-export fa-lg"></i> Generar XML</a></li>';
          if (full.consolidado_id != null) {
            btn_consolidado = "<li class='divider'></li>" +
              "<li><a href='impresion-documento/" + full.consolidado_id + "/consolidado' target='_blank'> <spam class='fal fa-print'></spam> Consolidado</a></li> " +
              "<li><a href='impresion-documento/" + full.consolidado_id + "/consolidado_guias' target='_blank'> <spam class='fal fa-print'></spam> Guias hijas</a></li>" +
              "<li><a href='master/imprimirGuias/" + full.consolidado_id + "/labels' target='_blank'> <spam class='fal fa-print'></spam> Labels guias hijas</a></li> " +
              "<li><a href='exportInternalManifest/" + full.consolidado_id + "' target='_blank'> <spam class='fal fa-print'></spam> Manifiesto Interno</a></li>";
          }
          var btns = "<div class='btn-group'>" +
            "<button type='button' class='btn btn-success dropdown-toggle btn-xs btn-circle-sm' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "<i class='fal fa-ellipsis-v'></i>" +
            "</button>" +
            "<ul class='dropdown-menu dropdown-menu-right pull-right'>" +
            btn_edit +
            btn_hawb +
            '<li role="separator" class="divider"></li>' +
            btn_cost +
            // btn_xml +
            '<li role="separator" class="divider"></li>' +
            btn_delete +
            "</ul></div>";

          var btns_print = "<div class='btn-group'>" +
            "<button type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "<i class='fal fa-print fa-lg'></i>  <span class='caret'></span>" +
            "</button>" +
            "<ul class='dropdown-menu dropdown-menu-right pull-right'><li><a href='master/imprimir/" + full.id + '/' + true +
            "' target='_blank'> <spam class='fal fa-print'></spam> Master</a></li>" +
            "<li><a href='master/imprimir/" + full.id + "' target='_blank'> <spam class='fal fa-print'></spam> Master simple</a></li>" +
            "<li><a onclick=\"createLabel(" + full.id + ", '" + full.num_master + "')\"> <spam class='fal fa-print'></spam> Labels</a></li>" +
            // "<li><a href='master/imprimirLabel/" +full.id +"' target='_blank'> <spam class='fal fa-print'></spam> Labels 2</a></li>" +
            "<li><a href='impresion-documento/pdfContrato' target='_blank'> <spam class='fal fa-print'></spam> Contrato</a></li>" +
            "<li><a href='impresion-documento-tsa/pdfTsa/" + full.carrier_id + "' target='_blank'> <spam class='fal fa-print'></spam> TSA</a></li>" +
            btn_consolidado +
            "</ul></div>";
          return btns_print + ' ' + btns;
        }
      }
    ]
  });
});

function createHouse(id, master) {
  objVue.createHouseAwb(id, master);
}

function createTax(id, master, peso, peso_kl, tarifa, trm, fecha_liquidacion) {
  objVue.id_master = id;
  objVue.master = master;
  objVue.tax_weight_lb = peso;
  objVue.tax_weight = peso_kl;
  objVue.tax_rate = tarifa;
  objVue.tax_trm = (trm !== null && trm !== "null" && trm !== '') ? trm : null;
  objVue.tax_date = (fecha_liquidacion !== null && fecha_liquidacion !== "null" && fecha_liquidacion !== '') ? fecha_liquidacion : objVue.getTime();
  objVue.tax_edit = false;
  if (trm !== null && trm !== "null" && trm !== '') {
    objVue.tax_edit = true;
  }
  $('#modalMasterTax').modal('show');
}

function createCost(id, master, peso, peso_kl, tarifa) {
  objVue.id_master = id;
  $('#modalMasterCost').modal('show');
}

function createLabel(id, master) {
  objVue.id_master = id;
  objVue.master = master;
  $('#modalPrintLabelsMaster').modal('show');
}

var objVue = new Vue({
  el: '#master_list',
  mounted() {
    this.getData();
    this.tax_date = this.getTime();
  },
  watch: {
    write: function (value) {
      if (value) {
        this.icon_cost = 'fal fa-hand-pointer';
        this.icon_title = 'Seleccionar';
        this.text_cost = 'Descripción';
      } else {
        this.icon_cost = 'fal fa-user-edit';
        this.icon_title = 'Escribir';
        this.text_cost = 'Seleccionar Costo o Gasto';
      }
    },
  },
  data: {
    options: [],
    consolidado_id: null,
    list: [],
    id_master: null,
    master: null,
    type: 'COURIER',
    text_loading: false,
    text_save: 'Continuar',
    // VARIALBES PARA TAX
    loading: false,
    tax_date: null,
    tax_trm: null,
    tax_rate: 0,
    tax_weight: 0,
    tax_weight_lb: 0,
    tax_edit: false, // PARA SABER SI EDITO O CREO EL COSTO DE LA MASTER
    //VARIABLES PARA COST
    costo_gasto: '0',
    cost_trm: null,
    cost_valor: null,
    cost_descripcion: null,
    cost_moneda_id: null,
    cost_costo_id: null,
    costos: [],
    monedas: [],
    moneda: null,
    costs: [],
    write: false,
    icon_cost: 'fal fa-user-edit',
    icon_title: 'Escribir',
    text_cost: 'Seleccionar Costo o Gasto',
  },
  methods: {
    deleteCost(id) {
      let me = this;
      axios.delete('master/deleteCost/' + id).then(function (response) {
        me.getCosts();
        toastr.success('Registro eliminado correctamente');
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });
    },
    getCosts() {
      let me = this;
      axios.get('master/getCosts/' + this.id_master).then(function (response) {
        me.costs = response.data.data;
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });
    },
    saveCost() {
      let me = this;
      var data = {
        master_id: this.id_master,
        trm: this.cost_trm,
        valor: this.cost_valor,
        moneda_id: this.cost_moneda_id.id,
        costos_id: this.cost_costo_id,
        descripcion: this.cost_descripcion,
        costo_gasto: this.costo_gasto,
      }
      me.text_loading = true;
      me.text_save = 'Guardando...';
      axios.post('master/saveCostMaster', data).then(function (response) {
        if (response.data['code'] == 200) {
          me.text_loading = false;
          me.text_save = 'Continuar';
          me.cost_trm = null;
          me.cost_valor = null;
          me.cost_moneda_id = null;
          me.cost_costo_id = null;
          me.cost_descripcion = null;
          me.costo_gasto = '0';
          me.getCosts();
          toastr.success('Registro guardado correctamente');
        } else {
          me.text_loading = false;
          me.text_save = 'Continuar';
        }
      }).catch(function (error) {
        console.log(error);
        me.text_loading = false;
        me.text_save = 'Continuar';
        toastr.error("Error." + error, {
          timeOut: 50000
        });
      });
    },
    setMoneda(data) {
      this.moneda = null;
      if (data != '') {
        this.moneda = '(' + data.moneda + ' ' + data.simbolo + ')';
      }
    },
    getDataSelectCost() {
      let me = this;
      axios.get('administracion/10/all').then(function (response) {
        me.costos = response.data.data;
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });

      axios.get('moneda/all').then(function (response) {
        me.monedas = response.data.data;
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });

      this.getCosts();
    },
    saveTax() {
      let me = this;
      var data = {
        master_id: this.id_master,
        tax_date: this.tax_date,
        tax_trm: this.tax_trm,
        tax_rate: this.tax_rate,
        tax_weight: this.tax_weight,
        tax_edit: this.tax_edit,
      }
      me.tax_loading = true;
      me.tax_text_save = 'Guardando...';
      axios.post('master/saveTaxMaster', data).then(function (response) {
        if (response.data['code'] == 200) {
          me.tax_loading = false;
          me.tax_text_save = 'Continuar';
          toastr.success('Registro guardado correctamente');
          location.href = "master/" + me.id_master + "/impuestosMaster";
        } else {
          me.tax_loading = false;
          me.tax_text_save = 'Continuar';
        }
      }).catch(function (error) {
        console.log(error);
        me.tax_loading = false;
        me.tax_text_save = 'Continuar';
        toastr.error("Error." + error, {
          timeOut: 50000
        });
      });
    },
    createLabelBags() {
      window.open('master/' + this.id_master + '/getDataPrintBagsConsolidate/' + this.type);
    },
    createHouseAwb(id, master) {
      swal({
        title: "<div>¿Desea crear un House de la master '" + master + "'?</div>",
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, Crear",
        cancelButtonText: "No, Cancelar!",
      }).then((result) => {
        if (result.value) {
          axios.get('master/hawb/' + id).then(function (response) {
            if (response.data['code'] == 200) {
              toastr.success('House creado correctamente.');
              refreshTable('tbl-master');
            }
          }).catch(function (error) {
            console.log(error);
            toastr.error("Error." + error, {
              timeOut: 50000
            });
          });
        }
      })
    },
    getData() {
      var me = this;
      axios.post('master/getDataConsolidados/0').then(function (response) {
        me.options = response.data;
      }).catch(function (error) {
        console.log(error);
        toastr.warning('Error: -' + error);
      });
    },
    createMaster() {
      var consolidado_id = null;
      if (this.consolidado_id != null) {
        consolidado_id = this.consolidado_id.id;
      }
      location.href = "master/create/" + null + '/' + consolidado_id;
    }
  }
});
var objVue = new Vue({
    el: '#configDocument',
    data: {
        nombreR: null,
        dataShipper: {},
        nombreD: null,
        dataConsignee: {},
        observDefault: null
    },
    created(){
      this.getDefault();
    },
    methods:{
      modalShipper: function(data_search) {
        var me = this;
        var nom = null;
        var id_data = null;
        if ($('#consignee_id').val() == '') {
            $('#show-all').bootstrapToggle('disable');
        } else {
            $('#show-all').bootstrapToggle('enable');
        }
        if (data_search) {
            if ($('#consignee_id').val() != '') {
                id_data = $('#consignee_id').val();
            }
        }
        $('#modalShipper').modal('show');
        if ($.fn.DataTable.isDataTable('#tbl-modalshipper')) {
            $('#tbl-modalshipper tbody').empty();
            $('#tbl-modalshipper').dataTable().fnDestroy();
        }
        if ($('#nombreR').val() != '') {
            nom = $('#nombreR').val();
        }
        var table = $('#tbl-modalshipper').DataTable({
            ajax: '../../shipper/all/' + nom + '/' + null + '/' + 1,
            columns: [{
                sortable: false,
                "render": function(data, type, full, meta) {
                  var dataShipper = [
                    "'" + full.nombre_full + "'",
                    full.id
                  ]
                    var btn_selet = "<button onclick=\"selectShipperConsignee(" + full.id + ", 'shipper')\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fal fa-check'></i></button> ";
                    return btn_selet;
                }
            }, {
                data: 'nombre_full',
                name: 'shipper.nombre_full',
            }, {
                data: 'telefono',
                name: 'shipper.telefono',
            }, {
                data: 'ciudad',
                name: 'localizacion.nombre'
            }, {
                data: 'zip',
                name: 'shipper.zip',
            }, {
                data: 'agencia',
                name: 'agencia.descripcion'
            }]
        });
      },
      modalConsignee: function(data_search) {
          var me = this;
          var nom = null;
          var id_data = null;
          if ($('#shipper_id').val() == '') {
              $('#show-all-c').bootstrapToggle('disable');
          } else {
              $('#show-all-c').bootstrapToggle('enable');
          }
          if (data_search) {
              if ($('#shipper_id').val() != '') {
                  id_data = $('#shipper_id').val();
              }
          }
          $('#modalConsignee').modal('show');
          if ($.fn.DataTable.isDataTable('#tbl-modalconsignee')) {
              $('#tbl-modalconsignee tbody').empty();
              $('#tbl-modalconsignee').dataTable().fnDestroy();

          }
          if ($('#nombreD').val() != '') {
              nom = $('#nombreD').val();
          }
          var table = $('#tbl-modalconsignee').DataTable({
              ajax: '../../consignee/all/' + nom + '/' + id_data + '/' + $('#agencia_id').val(),
              columns: [{
                  sortable: false,
                  "render": function(data, type, full, meta) {
                      var btn_selet = "<button onclick=\"selectShipperConsignee(" + full.id + ", 'consignee')\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fal fa-check'></i></button> ";
                      return btn_selet;
                  }
              }, {
                  data: 'nombre_full',
                  name: 'consignee.nombre_full'
              }, {
                  data: 'telefono',
                  name: 'consignee.telefono'
              }, {
                  data: 'ciudad',
                  name: 'localizacion.nombre'
              }, {
                  data: 'zip',
                  name: 'consignee.zip'
              }, {
                  data: 'agencia',
                  name: 'agencia.descripcion'
              }]
          });
      },
      saveDefaultS: function(id){
        axios.post('../config/shipperDefault/'+id+'/true', {data: id}).then(response => {
          $('#modalShipper').modal('hide');
          this.getShipperById(id);
        });
      },
      saveDefaultC: function(id){
        axios.post('../config/consigneeDefault/'+id+'/true', {data: id}).then(response => {
          $('#modalConsignee').modal('hide');
          this.getConsigneeById(id);
          toastr.success('Registro exitoso.');
        });
      },
      saveDefaultObserv: function(){
        axios.post('../config/observDefault/'+this.observDefault+'/true', {data: ''}).then(response => {
          toastr.success('Registro exitoso.');
        });
      },
      getDefault: function(){
        axios.get('../getConfig/shipperDefault').then(({data}) => {
          this.getShipperById(data.value);
        });
        axios.get('../getConfig/consigneeDefault').then(({data}) => {
          this.getConsigneeById(data.value);
        });
        axios.get('../getConfig/observDefault').then(({data}) => {
          this.observDefault = data.value;
        });
      },
      getShipperById: function(id){
        axios.get('../shipper/getDataById/' + id).then(({data}) => {
          this.dataShipper = data
        });
      },
      getConsigneeById: function(id){
        axios.get('../consignee/getDataById/' + id).then(({data}) => {
          this.dataConsignee = data
        });
      }
    }
})

function selectShipperConsignee(id, type) {
  if (type == 'shipper') {
    objVue.saveDefaultS(id);
  }else{
    objVue.saveDefaultC(id);
  }
}

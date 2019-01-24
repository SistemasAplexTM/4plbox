var objVue = new Vue({
    el: '#configDocument',
    data: {
        nombreR: null,
        dataShipper: {}
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
                    var btn_selet = "<button onclick=\"selectShipperConsignee(" + full.id + ", '" + full.nombre_full +"', "+ full.telefono+", '"+full.ciudad+"')\" class='btn-primary btn-xs' data-toggle='tooltip' title='Seleccionar'>Seleccionar <i class='fa fa-check'></i></button> ";
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
      saveDefault: function(id){
        axios.post('../config/shipperDefault/'+id+'/true', {data: id}).then(response => {
          $('#modalShipper').modal('hide');
          this.getShipperById(id);
        });
      },
      getDefault: function(){
        axios.get('../getConfig/shipperDefault').then(({data}) => {
          this.getShipperById(data.value);
        });
      },
      getShipperById: function(id){
        axios.get('../shipper/getDataById/' + id).then(({data}) => {
          this.dataShipper = data
        });
      }
    }
})

function selectShipperConsignee(id) {
  objVue.saveDefault(id);
}

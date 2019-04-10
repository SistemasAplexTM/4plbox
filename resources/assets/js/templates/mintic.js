$(document).ready(function() {
  all();
});

function all(){
  var d = new Date();
  var month = d.getMonth()+1;
  var day = d.getDate();
  var output = (month<10 ? '0' : '') + month + '/' + (day<10 ? '0' : '') + day + '/' + d.getFullYear();
  var fechas = output + ' - ' + output;

  $('#tbl-mintic').DataTable({
    ajax: {
      "url": 'mintic/all',
      "data": function(d) {
        d.fechas = fechas;
      }
    },
    columns: [
      {
        data: 'num_warehouse',
        name: 'num_warehouse'
      },
      {
        data: 'mintic',
        name: 'mintic'
      },
      {
        data: 'created_at',
        name: 'created_at'
      }
    ]
  }).on('xhr.dt', function ( e, settings, json, xhr ) {
    objVue.desc = 'Mintic ' + (json.data.length + 1)
  });
}

var objVue = new Vue({
    el: '#mintic',
    data: {
      warehouse: null,
      disabled: false,
      desc: 'Mintic 1',
      detail: [],
      cantidad: 0
    },
    methods:{
      addWarehouse(){
        let me = this;
        me.disabled = true
        axios.get('mintic/searchDocument/' + me.warehouse).then(({data}) => {
          if (data.code == 200) {
            var row = this.detail.filter(result => result.warehouse == me.warehouse);
            if (row.length > 0) {
              toastr.warning('EL warehouse ya se encuentra en esta mintic');
              me.disabled = false
              this.warehouse = null
              setTimeout(function() {
                me.$refs.wrh.focus();
              },200)
              return;
            }else{
              me.detail.push(data.data)
              me.cantidad = this.detail.length;
            }
            me.disabled = false
            this.warehouse = null
            console.log(this.$refs.wrh);
            setTimeout(function() {
              me.$refs.wrh.focus();
            },200)
          }else{
            me.disabled = false
            this.warehouse = null
            setTimeout(function() {
              me.$refs.wrh.focus();
            },200)
            toastr.warning(data.msg);
          }
        });
      },
      create(){
        let me = this
        axios.post('documento/ajaxCreate/1', {
          'tipo_documento_id': 1,
          'type_id': 1,
          'created_at': this.getTime()
        }).then(function(response) {
          var res = response.data;
          if (response.data['code'] == 200) {
            me.createdDetail(response.data['datos'].id)
          } else {
            toastr.warning(response.data['error']);
          }
        }).catch(function(error) {
          console.log(error);
          toastr.error("Error.", {
            timeOut: 50000
          });
        });
      },
      createdDetail(id){
        let me = this
        var datos = this.getPesoTotal(this.detail)
        axios.post('documento/insertDetail', {
          'documento_id': id,
          'contador': 1,
          'minitc': this.desc,
          'dimensiones': datos[0] + ' Vol=0x0x0',
          'peso': datos[0],
          'peso2': datos[0],
          'contenido': datos[1],
          'contenido2': datos[1],
          'largo': 0,
          'ancho': 0,
          'alto': 0,
          'tipo_empaque_id': 3,
          'posicion_arancelaria_id': 234,
          'arancel_id2': 234,
          'detail': this.detail
        }).then(function(response) {
          var res = response.data;
          if (response.data['code'] == 200) {
            toastr.success('Registro creado correctamente.');
            me.detail = []
            me.warehouse = null
            refreshTable('tbl-mintic')
          } else {
            toastr.warning(response.data['error']);
          }
        }).catch(function(error) {
          console.log(error);
          toastr.error("Error.", {
            timeOut: 50000
          });
        });
      },
      getPesoTotal(detail){
        var total = 0
        var contenido = ''
        var flag = true
        for (var i = 0; i < detail.length; i++) {
          total += parseFloat(detail[i].peso)
          if (detail[i].contenido != null) {
            if (flag) {
              contenido += detail[i].contenido
              flag = false
            }else{
              contenido += ', ' + detail[i].contenido
            }
          }
        }
        return [total,contenido]
      }
    }
});

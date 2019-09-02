$(document).ready(function() {
    $('#tbl-radicado').DataTable({
        ajax: 'radicado/all',
        order: [[0, "desc"]],
        columns: [{
            data: 'id',
            name: 'id'
        }, {
            data: 'fecha',
            name: 'fecha'
        }, {
            data: 'cliente.nombre',
            name: 'cliente_id'
        }, {
            data: 'empleado.nombre',
            name: 'empleado_id'
        },  {
            sortable: false,
            "render": function(data, type, full, meta) {
              var btn_print = " <a href='radicado/imprimir/"+full.id+"' target='_blank' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Imprimir'><i class='fal fa-print'></i></a> ";
              var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash'></i></a> ";
              return btn_print + btn_delete;
            }
        }]
    });
});

function edit(id, documento, nombre, direccion, telefono, correo) {
    var data = {
        id: id,
        documento: documento,
        nombre: nombre,
        direccion: direccion,
        telefono: telefono,
        correo: correo
    };
    objVue.edit(data);
}

var objVue = new Vue({
    el: '#radicado',
    mounted: function() {
      const dict = {
          custom: {
              fecha: {
                  required: 'Campo obligatorio'
              },
              cliente_id: {
                  required: 'Campo obligatorio'
              },
              empleado_id: {
                  required: 'Campo obligatorio'
              }
          }
      };
      this.$validator.localize('es', dict);
      this.getClients();
      this.getEmployees();
    },
    data: {
        clientes: [],
        cliente_id: null,
        empleados: [],
        empleado_id: null,
        fecha: null,
        cantidad: 1,
        descripcion: null,
        detalle: [],
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
      agregarDetalle(){
        this.detalle.push({
          cantidad: this.cantidad,
          descripcion: this.descripcion
        });
        this.cantidad = 1;
        this.descripcion = null;
      },
      getClients(){
        axios.get('radicado/getClientes').then(response => {
          this.clientes = response.data;
        });
      },
      getEmployees(){
        axios.get('radicado/getEmpleados').then(response => {
          this.empleados = response.data;
        });
      },
      resetForm: function() {
          this.id = null;
          this.cliente_id = null;
          this.empleado_id = null;
          this.detalle= [];
          this.editar = 0;
          this.formErrors = {};
          this.listErrors = {};
      },
      /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
      deleteError: function(element) {
          let me = this;
          console.log(me.errors.items);
          $.each(me.errors.items, function(key, value) {
              if (value.field === element) {
                  me.errors.items.splice(key, 1);
              }
          });
      },
      updateTable: function() {
          refreshTable('tbl-radicado');
      },
      rollBackDelete: function(data) {
          var urlRestaurar = 'radicado/restaurar/' + data.id;
          axios.get(urlRestaurar).then(response => {
              toastr.success('Registro restaurado.');
              this.updateTable();
          });
      },
      delete: function(data) {
          this.formErrors = {};
          this.listErrors = {};
          if (data.logical === true) {
              axios.get('radicado/delete/' + data.id + '/' + data.logical).then(response => {
                  this.updateTable();
                  toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                  toastr.options.closeButton = true;
              });
          } else {
              axios.delete('radicado/' + data.id).then(response => {
                  this.updateTable();
                  toastr.success('Registro eliminado correctamente.');
                  toastr.options.closeButton = true;
              });
          }
      },
      create: function() {
          let me = this;
          this.$validator.validateAll().then((result) => {
              if (result) {
                axios.post('radicado', {
                    'cliente_id': this.cliente_id,
                    'empleado_id': this.empleado_id,
                    'fecha': this.fecha,
                    'detalle': this.detalle
                }).then(function(response) {
                    if (response.data['code'] == 200) {
                        toastr.success('Registro creado correctamente.');
                        toastr.options.closeButton = true;
                        me.resetForm();
                        me.updateTable();
                    } else {
                      console.log(response.data['error'].errorInfo);
                        toastr.warning(response.data['error'].errorInfo);
                        toastr.options.closeButton = true;
                    }
                }).catch(function(error) {
                    console.log(error);
                    toastr.error("Error." + error, {
                        timeOut: 50000
                    });
                });
              } else {
                  toastr.warning("Error. Porfavor verifica los datos ingresados.<br>");
              }
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
          });
      },
      update: function() {
          var me = this;
          axios.put('radicado/' + this.id, {
            'documento': this.documento,
            'nombre': this.nombre,
            'direccion': this.direccion,
            'telefono': this.telefono,
            'correo': this.correo
          }).then(function(response) {
              if (response.data['code'] == 200) {
                  toastr.success('Registro Actualizado correctamente');
                  toastr.options.closeButton = true;
                  me.editar = 0;
              } else {
                  toastr.warning(response.data['error']);
                  toastr.options.closeButton = true;
                  console.log(response.data);
              }
              me.resetForm();
              me.updateTable();
          }).catch(function(error) {
            console.log(error);
              toastr.error("Error " + error, {
                  timeOut: 50000
              });
          });
      },
      edit: function(data) {
          this.id = data['id'];
          this.documento = data['documento'];
          this.nombre = data['nombre'];
          this.direccion = data['direccion'];
          this.telefono = data['telefono'];
          this.correo = data['correo'];
          this.editar = 1;
          this.formErrors = {};
          this.listErrors = {};
      },
      cancel: function() {
          var me = this;
          me.resetForm();
      },
    },
});

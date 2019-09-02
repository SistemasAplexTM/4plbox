$(document).ready(function() {
    $('#tbl-status').DataTable({
        ajax: 'status/all',
        lengthMenu: [[20, 40, 50, 80, 100, 200, 500], [20, 40, 50, 80, 100, 200, 500]],
        columns: [{
            data: 'descripcion',
            name: 'descripcion',
            "render": function(data, type, full, meta) {
              return '<i class="'+ full.icon +'" style="font-size: 20px;"></i> ' + full.descripcion;
            }
        }, {
            data: 'color',
            name: 'color',
            "render": function(data, type, full, meta) {
                var color = full.color;
                return '<spam style="background-color:' + color + '; padding-right:50px; border-radius: 10px; color: #ffffff;">&nbsp;</spam> <li class="fal fa-arrow-right"></li> ' + color;
            }
        }, {
            data: 'email',
            name: 'email',
            "render": function(data, type, full, meta) {
                var icon_mail = '<i class="fal fa-envelope text-danger"></i>';
                if (full.email == 1) {
                    icon_mail = '<i class="fal fa-envelope text-navy"></i>';
                }
                return icon_mail;
            }
        }, {
            data: 'view_client',
            name: 'view_client',
            "render": function(data, type, full, meta) {
                var icon_user = '<i class="fal fa-user text-danger"></i> ';
                if (full.view_client == 1) {
                    icon_user = '<i class="fal fa-user text-navy"></i> ';
                }
                var desc = full.descripcion_general;
                if(full.descripcion_general == null){
                  desc = '';
                }
                return icon_user + desc;
            }
        },{
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.descripcion + "'", "'" + full.descripcion_general + "'", "'" + full.color + "'", "'" + full.email + "'", "'" + full.view_client + "'", "'" + full.icon + "'", "'" + full.json_data + "'"
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + false + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
    $('.i-checks').iCheck({
        // checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

$(function(){
  $('input').on('ifChecked', function(event){
    objVue.showEmailTemplate();
  });
})

function edit(id, descripcion, descripcion_general, color, email, view_client, icon, json_data) {
    var data = {
        id: id,
        descripcion: descripcion,
        descripcion_general: descripcion_general,
        color: color,
        email: email,
        view_client: view_client,
        icon: icon,
        json_data: json_data,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#status',
    mounted: function() {
        this.getPlantillasEmail();
        this.getIcons();
        const dict = {
            custom: {
                descripcion: {
                    required: 'El nombre es obligatoria.'
                },
                descripcion_general: {
                    required: 'La descripcion es obligatoria.'
                }
            }
        };
        this.$validator.localize('es', dict);
    },
    data: {
        descripcion: null,
        descripcion_general: null,
        color: '#020202',
        email: '',
        view_client: false,
        editar: 0,
        formErrors: {},
        listErrors: {},
        email_plantilla_id: null,
        plantillas: [],
        showTemplate: false,
        options4: [],
        value9: [],
        list: [],
        loading: false,
        icon_selected: null
    },
    methods: {
        setIconPrefix(data){
          this.icon_selected = data.value;
        },
        getIcons(){
          let me = this;
          $.getJSON('./json/fa-icons-lite.json', function(data) {
              $(".ajaxLoadFontAwesome").append('<option value="">Seleccione</option>');
              $.each(data, function(key, value) {
                  var val = key.substring(4);
                  me.list.push({value: key, label: value});
              });
          });
        },
        remoteMethod(query) {
          if (query !== '') {
            this.loading = true;
            setTimeout(() => {
              this.loading = false;
              this.options4 = this.list.filter(item => {
                return item.label.toLowerCase()
                  .indexOf(query.toLowerCase()) > -1;
              });
            }, 200);
          } else {
            this.options4 = [];
          }
        },
        resetForm: function() {
            this.id = '';
            this.descripcion = null;
            this.descripcion_general = null;
            this.color = '#020202';
            this.email = '';
            this.editar = 0;
            this.view_client= false;
            this.email_plantilla_id= null;
            this.formErrors = {};
            this.listErrors = {};
            this.value9 = null;
            this.icon_selected = null;
            $('#email_s').iCheck('uncheck').prop('checked', false);
            $('#email_n').iCheck('check').prop('checked', true);
            $('#view_client_s').iCheck('uncheck').prop('checked', false);
            $('#view_client_n').iCheck('check').prop('checked', true);
        },
        showEmailTemplate: function() {
          let me = this;
          if ($('#email_s').is(':checked')) {
              me.showTemplate = true;
          } else {
              me.showTemplate = false;
          }
        },
        getPlantillasEmail: function() {
            let me = this;
            axios.get('tipoDocumento/getPlantillasEmail').then(response => {
                me.plantillas = response.data.data;
            });
        },
        /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
        deleteError: function(element) {
            let me = this;
            $.each(me.listErrors, function(key, value) {
                if (key !== element) {
                    me.listErrors[key] = value;
                } else {
                    me.listErrors[key] = false;
                }
            });
        },
        rollBackDelete: function(data) {
            var urlRestaurar = 'status/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-status');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('status/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('status/' + data.id).then(response => {
                    if(response.data.code == 200){
                        this.updateTable();
                        toastr.success('Registro eliminado correctamente.');
                        toastr.options.closeButton = true;
                    }else{
                        toastr.error("Error: " + response.data.error, {timeOut: 50000});
                    }
                });
            }
        },
        create: function() {
            let me = this;
            if ($('#email_s').is(':checked')) {
                this.email = 1;
            } else {
                this.email = 0;
            }
            if ($('#view_client_s').is(':checked')) {
                this.view_client = 1;
            } else {
                this.view_client = 0;
            }
            this.$validator.validateAll().then((result) => {
                if (result) {
                  axios.post('status', {
                      'created_at': new Date(),
                      'descripcion': this.descripcion,
                      'descripcion_general': this.descripcion_general,
                      'color': this.color,
                      'email': this.email,
                      'view_client': this.view_client,
                      'icon': this.value9.value,
                      'email_plantilla_id': (this.email_plantilla_id !== null) ? this.email_plantilla_id : null,
                  }).then(function(response) {
                      if (response.data['code'] == 200) {
                          toastr.success('Registro creado correctamente.');
                          toastr.options.closeButton = true;
                      } else {
                          toastr.warning(response.data['error']);
                          toastr.options.closeButton = true;
                      }
                      me.resetForm();
                      me.updateTable();
                  }).catch(function(error) {
                      if (error.response.status === 422) {
                          me.formErrors = error.response.data; //guardo los errores
                          me.listErrors = me.formErrors.errors; //genero lista de errores
                      }
                      $.each(me.formErrors.errors, function(key, value) {
                          $('.result-' + key).html(value);
                      });
                      toastr.error("Porfavor completa los campos obligatorios.", {
                          timeOut: 50000
                      });
                  });
              }
          });
        },
        update: function() {
            var me = this;
            if ($('#email_s').is(':checked')) {
                this.email = 1;
            } else {
                this.email = 0;
            }
            if ($('#view_client_s').is(':checked')) {
                this.view_client = 1;
            } else {
                this.view_client = 0;
            }
            console.log(this.value9, 'sdfa');
            axios.put('status/' + this.id, {
                'descripcion': this.descripcion,
                'descripcion_general': this.descripcion_general,
                'color': this.color,
                'email': this.email,
                'view_client': this.view_client,
                'icon': this.value9.value,
                'email_plantilla_id': (this.email_plantilla_id !== null) ? this.email_plantilla_id : null,
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
                if (error.response.status === 422) {
                    me.formErrors = error.response.data;
                    me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function(key, value) {
                    $('.result-' + key).html(value);
                });
                toastr.error("Porfavor completa los campos obligatorios.", {
                    timeOut: 50000
                });
            });
        },
        edit: function(data) {
            this.id = data['id'];
            var plant = null;
            this.email_plantilla_id = null;
            if(data['json_data'] != 'null'){
              plant = JSON.parse(data['json_data']);
              this.email_plantilla_id = plant.email_template_id;
            }
            this.descripcion = data['descripcion'];
            if(data['descripcion_general'] != 'null'){
              this.descripcion_general = data['descripcion_general'];
            }else{
              this.descripcion_general = '';
            }
            this.color = data['color'];
            /* Chekear los radios del campo email*/
            if (data['email'] == 1) {
                $('#email_s').iCheck('check').prop('checked', true);
            } else {
                $('#email_n').iCheck('check').prop('checked', true);
            }
            /* Chekear los radios del campo view_client*/
            if (data['view_client'] == 1) {
                $('#view_client_s').iCheck('check').prop('checked', true);
            } else {
                $('#view_client_n').iCheck('check').prop('checked', true);
            }
            this.value9 = null;
            this.icon_selected = null;
            let icono = _.filter(this.list, function(q){return q.value === data['icon']});
            if(icono.length > 0){
              this.value9 = icono[0].label;
              this.icon_selected = icono[0].value;
            }
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

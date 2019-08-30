$(document).ready(function() {
    $('.summernote').summernote();
    $('.note-editable').attr('onkeyup', 'llenarTextarea()');
    $('#tbl-emailTemplate').DataTable({
        ajax: 'emailTemplate/all',
        columns: [{
            data: 'nombre',
            name: 'nombre'
        }, {
            data: 'descripcion_plantilla',
            name: 'descripcion_plantilla'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, +full.agencia_id, "'" + full.nombre + "'", "'" + full.subject + "'", "'" + full.descripcion_plantilla + "'", "'" + full.otros_destinatarios + "'", full.enviar_archivo,
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
});

function edit(id, agencia_id, nombre, subject, descripcion_plantilla, otros_destinatarios, enviar_archivo) {
    var data = {
        id: id,
        agencia_id: agencia_id,
        nombre: nombre,
        subject: subject,
        descripcion_plantilla: descripcion_plantilla,
        otros_destinatarios: otros_destinatarios,
        enviar_archivo: enviar_archivo
    };
    objVue.edit(data);
}

function llenarTextarea() {
    // var cont = $('.note-editable').html();
    // $('#mensaje').html(cont);
}
/* objeto VUE */
var objVue = new Vue({
    el: '#emailTemplate',
    mounted: function() {
        //
    },
    data: {
        agencia_id: '',
        nombre: '',
        subject: '',
        mensaje: '',
        descripcion_plantilla: '',
        otros_destinatarios: '',
        email_file: false,
        editar: 0,
        formErrors: {},
        listErrors: {},
        testingCode: '',
    },
    methods: {
        copyTestingCode (val) {
          this.testingCode = val;
          setTimeout(function () {
            let testingCodeToCopy = document.querySelector('#testing-code')
            testingCodeToCopy.setAttribute('type', 'text')    // 不是 hidden 才能複製
            testingCodeToCopy.select()

            try {
              var successful = document.execCommand('copy');
              var msg = successful ? 'successful' : 'unsuccessful';
              toastr.success('Copiado correctamente');
            } catch (err) {
              toastr.warning('Ops! No se copio correctamente.');
            }

            /* unselect the range */
            testingCodeToCopy.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
          }, 100);
        },
        resetForm: function() {
            this.id = '';
            this.agencia_id = '';
            this.nombre = '';
            this.subject = '';
            // $('#mensaje').html('');
            // $('.note-editable').html('');
            tinymce.activeEditor.setContent('');
            this.descripcion_plantilla = '';
            this.otros_destinatarios = '';
            this.email_file = false;
            this.editar = 0;
            this.formErrors = {};
            this.listErrors = {};
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
            var urlRestaurar = 'emailTemplate/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-emailTemplate');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('emailTemplate/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('emailTemplate/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        create: function() {
            let me = this;
            axios.post('emailTemplate', {
                'created_at': new Date(),
                'agencia_id': $('#agencia_id').val(),
                'nombre': this.nombre,
                'subject': this.subject,
                'mensaje': tinymce.get('mensaje').getContent(),
                'descripcion_plantilla': this.descripcion_plantilla,
                'otros_destinatarios': this.otros_destinatarios,
                'enviar_archivo': this.email_file,
            }).then(function(response) {
                if (response.data['code'] == 200) {
                    toastr.success('Registro creado correctamente.');
                    toastr.options.closeButton = true;
                    me.resetForm();
                    me.updateTable();
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                }
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
        },
        update: function() {
            var me = this;
            axios.put('emailTemplate/' + this.id, {
                'agencia_id': $('#agencia_id').val(),
                'nombre': this.nombre,
                'subject': this.subject,
                'mensaje': tinymce.get('mensaje').getContent(),
                'descripcion_plantilla': this.descripcion_plantilla,
                'otros_destinatarios': this.otros_destinatarios,
                'enviar_archivo': this.email_file,
            }).then(function(response) {
                if (response.data['code'] == 200) {
                    toastr.success('Registro Actualizado correctamente');
                    toastr.options.closeButton = true;
                    me.editar = 0;
                    me.resetForm();
                    me.updateTable();
                } else {
                    toastr.warning(response.data['error']);
                    toastr.options.closeButton = true;
                }
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
            this.agencia_id = data['agencia_id'];
            this.nombre = data['nombre'];
            this.subject = data['subject'];
            this.email_file = data['enviar_archivo'];
            this.descripcion_plantilla = data['descripcion_plantilla'];
            if (data['otros_destinatarios'] == 'null') {
                data['otros_destinatarios'] = '';
            }
            this.otros_destinatarios = data['otros_destinatarios'];
            // $('#mensaje').html(data['mensaje']);
            // $('.note-editable').html(data['mensaje']);
            this.getContent(data['id']);
            this.editar = 1;
            this.formErrors = {};
            this.listErrors = {};
        },
        getContent(id){
          axios.get('emailTemplate/getContent/'+id).then(response => {
            tinymce.activeEditor.setContent('');
            tinymce.activeEditor.selection.setContent(response.data.content);
          });
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});

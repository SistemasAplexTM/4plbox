$(document).ready(function() {
    $('#tbl-rol').DataTable({
        ajax: 'rol/all',
        columns: [{
            data: 'name',
            name: 'name'
        }, {
            sortable: false,
            "render": function(data, type, full, meta) {
                var btn_edit = '';
                var btn_delete = '';
                if (permission_update) {
                    var params = [
                        full.id, "'" + full.name + "'", "'" + full.slug + "'", "'" + full.description + "'", "'" + full.special + "'"
                    ];
                    var btn_edit = "<a onclick=\"edit(" + params + ")\" class='btn btn-outline btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                }
                if (permission_delete) {
                    var btn_delete = " <a onclick=\"eliminar(" + full.id + "," + true + ")\" class='btn btn-outline btn-danger btn-xs' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                }
                return btn_edit + btn_delete;
            }
        }]
    });
    $('#tbl-permissions').DataTable({
        processing: false,
        serverSide: false,
        ajax: 'rol/allPermissions',
        columns: [{
            sortable: false,
            "render": function(data, type, full, meta) {
                var idReg = full.id;
                return '<div class="checkbox checkbox-success" style="width: 100%;text-align: center;"><input class="permissions_chk" type="checkbox" id="chk' + idReg + '" name="chk[]" value="' + idReg + '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' + idReg + '"></label></div>';
            }
        }, {
            data: 'name',
            name: 'name'
        }, {
            data: 'description',
            name: 'description'
        }]
    });
});

function edit(id, name, slug, description, special) {
    var data = {
        id: id,
        name: name,
        slug: slug,
        description: description,
        special: special,
    };
    objVue.edit(data);
}
var objVue = new Vue({
    el: '#roles',
    mounted: function() {
        //
    },
    data: {
        name: null,
        slug: null,
        description: null,
        special: null,
        editar: 0,
        formErrors: {},
        listErrors: {},
    },
    methods: {
        slugGenerate: function() {
            var cadena = this.name;
            cadena = cadena.toLowerCase();
            this.slug = cadena.replace(/ /g, "_");
        },
        resetForm: function() {
            this.id = null;
            this.name = null;
            this.slug = null;
            this.description = null;
            this.special = null,
                // $('#special').bootstrapToggle('off');
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
            var urlRestaurar = 'rol/restaurar/' + data.id;
            axios.get(urlRestaurar).then(response => {
                toastr.success('Registro restaurado.');
                this.updateTable();
            });
        },
        updateTable: function() {
            refreshTable('tbl-rol');
        },
        delete: function(data) {
            this.formErrors = {};
            this.listErrors = {};
            if (data.logical === true) {
                axios.get('rol/delete/' + data.id + '/' + data.logical).then(response => {
                    this.updateTable();
                    toastr.success("<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" + data.id + ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fa fa-reply'></i> Restaurar</button></div>");
                    toastr.options.closeButton = true;
                });
            } else {
                axios.delete('rol/' + data.id).then(response => {
                    this.updateTable();
                    toastr.success('Registro eliminado correctamente.');
                    toastr.options.closeButton = true;
                });
            }
        },
        getPermissions: function(){
        	var parametros=[];
        	var chk = $('#permissions').serializeArray();
            $.each(chk, function(i,e){
                if(e.name == 'chk[]'){
            	   parametros.push(parseInt(e.value)); 
                }
                   
            });
            return parametros;
        },
        create: function() {
            let me = this;
            axios.post('rol', {
                'name': this.name,
                'slug': this.slug,
                'description': this.description,
                'special': this.special,
                'permissions': this.getPermissions(),
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
                console.log(error);
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
            axios.put('rol/' + this.id, {
                'name': this.name,
                'slug': this.slug,
                'description': this.description,
                'special': this.special,
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
                    console.log(response.data);
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
            this.name = data['name'];
            this.slug = data['slug'];
            this.description = data['description'];
            this.special = data['special'];
            if (data['special'] == 'null' || data['special'] == '') {
                this.special = null;
            }
            this.checkPermissionsRole();
            this.editar = 1;
            this.formErrors = {};
            this.listErrors = {};
        },
        checkPermissionsRole: function(){
        	axios.get('rol/getPermissions/' + this.id).then(response => {
                console.log(response.data.data);
            });
        },
        cancel: function() {
            var me = this;
            me.resetForm();
        },
    },
});
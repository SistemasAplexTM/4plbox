$(document).ready(function() {

});

var objVue = new Vue({
    el: '#access_control',
    watch: {
        role_id: function(values) {
            this.reset();
            if (values != null) {
                this.special = values.special;
                if(this.role_id != null && this.special == null){
                    this.getPermisionsRole(this.role_id.id);
                }
            }
        },
        special: function(value){
            if(value === null){
                if(this.role_id != null){
                    this.getPermisionsRole(this.role_id.id);
                }
            }else{
                this.permisionsRoles = [];
            }
        }
    },
    mounted: function() {
        this.getRoles();
    },
    data: {
        editar: 0,
        checkAll_c: false,
        checkAll_r: false,
        checkAll_u: false,
        checkAll_d: false,
        roles: [],
        permisionsRoles: [],
        selected_permissions: [],
        chk_c: [],
        chk_r: [],
        chk_u: [],
        chk_d: [],
        role_id: null,
        special: null,
        specialPermisions: [],
        chk_special: [],
        name_module: null
    },
    methods: {
        reset:function(){
            this.checkAll_c = false;
            this.checkAll_r = false;
            this.checkAll_u = false;
            this.checkAll_d = false;
            this.permisionsRoles = [];
            this.chk_c = [];
            this.chk_r = [];
            this.chk_u = [];
            this.chk_d = [];
            this.special = null;
        },
        getRoles: function() {
            axios.get('rol/all').then(response => {
                this.roles = response.data.data;
            });
        },
        getPermisionsRole: function(role_id) {
            axios.get('accessControl/getPermisionsRole/' + role_id).then(response => {
                this.permisionsRoles = response.data.data;
                this.selected_permissions = response.data.permissions;
                for (var key in this.permisionsRoles) {
                    for(var sel in this.selected_permissions) {
                        if(this.permisionsRoles[key]['c'] == this.selected_permissions[sel].id){
                            this.chk_c.push(this.selected_permissions[sel].id);
                            this.updateCheckall('c');
                        }
                        if(this.permisionsRoles[key]['r'] == this.selected_permissions[sel].id){
                            this.chk_r.push(this.selected_permissions[sel].id);
                            this.updateCheckall('r');
                        }
                        if(this.permisionsRoles[key]['u'] == this.selected_permissions[sel].id){
                            this.chk_u.push(this.selected_permissions[sel].id);
                            this.updateCheckall('u');
                        }
                        if(this.permisionsRoles[key]['d'] == this.selected_permissions[sel].id){
                            this.chk_d.push(this.selected_permissions[sel].id);
                            this.updateCheckall('d');
                        }
                    }
                }
            }).catch(function(error) {
                toastr.success('Error. ' + error);
                toastr.options.closeButton = true;
            });
        },
        savePermissions: function() {
            let me = this;
            axios.post('accessControl',{
                'role_id': this.role_id.id,
                'special': this.special,
                'datos': $('#form_permissions').serializeArray()
            }).then(function (response) {
                if(response.data.code == 200){
                    toastr.success('Registro exitoso.');
                    me.reset();
                    this.role_id = null;
                }else{
                    var error = response.data.error;
                    if(Array.isArray(error)){
                        error = response.data.error['errorInfo'];
                    }
                    toastr.warning(error);
                    toastr.options.closeButton = true;
                }
            }).catch(function (error) {
                console.log(error);
                toastr.warning('Error. ' + error);
                toastr.options.closeButton = true;
            });
        },
        checkAll: function(op) {
            if (op == 'c') {
                this.checkAll_c = !this.checkAll_c;
                this.chk_c = [];
                if (this.checkAll_c) { // Check all
                    for (var key in this.permisionsRoles) {
                        this.chk_c.push(this.permisionsRoles[key]['c']);
                    }
                }
            } else {
                if (op == 'r') {
                    this.checkAll_r = !this.checkAll_r;
                    this.chk_r = [];
                    if (this.checkAll_r) { // Check all
                        for (var key in this.permisionsRoles) {
                            this.chk_r.push(this.permisionsRoles[key]['r']);
                        }
                    }
                } else {
                    if (op == 'u') {
                        this.checkAll_u = !this.checkAll_u;
                        this.chk_u = [];
                        if (this.checkAll_u) { // Check all
                            for (var key in this.permisionsRoles) {
                                this.chk_u.push(this.permisionsRoles[key]['u']);
                            }
                        }
                    } else {
                        if (op == 'd') {
                            this.checkAll_d = !this.checkAll_d;
                            this.chk_d = [];
                            if (this.checkAll_d) { // Check all
                                for (var key in this.permisionsRoles) {
                                    this.chk_d.push(this.permisionsRoles[key]['d']);
                                }
                            }
                        }
                    }
                }
            }
        },
        updateCheckall: function(op) {
            if (op == 'c') {
                if (this.chk_c.length == this.permisionsRoles.length) {
                    this.checkAll_c = true;
                } else {
                    this.checkAll_c = false;
                }
            } else {
                if (op == 'r') {
                    if (this.chk_r.length == this.permisionsRoles.length) {
                        this.checkAll_r = true;
                    } else {
                        this.checkAll_r = false;
                    }
                } else {
                    if (op == 'u') {
                        if (this.chk_u.length == this.permisionsRoles.length) {
                            this.checkAll_u = true;
                        } else {
                            this.checkAll_u = false;
                        }
                    } else {
                        if (op == 'd') {
                            if (this.chk_d.length == this.permisionsRoles.length) {
                                this.checkAll_d = true;
                            } else {
                                this.checkAll_d = false;
                            }
                        }
                    }
                }
            }
        },
        specialAction: function(module_name){
            this.name_module = module_name;
            axios.get('accessControl/getSpecialPermisions/' + module_name + '/'+this.role_id.id).then(response => {
                this.specialPermisions = response.data.data;
                this.selected_permissions = response.data.permissions;
                for (var key in this.specialPermisions) {
                    for(var sel in this.selected_permissions) {
                        if(this.specialPermisions[key].id == this.selected_permissions[sel].id){
                            this.chk_special.push(this.selected_permissions[sel].id);
                        }
                    }
                }

            }).catch(function(error) {
                toastr.success('Error. ' + error);
                toastr.options.closeButton = true;
            });
        },
        saveSpecialPermissions: function() {
            let me = this;
            axios.post('accessControl/saveSpecialPermissions',{
                'role_id': this.role_id.id,
                'name_module': this.name_module,
                'datos': $('#form_permissions_special').serializeArray()
            }).then(function (response) {
                if(response.data.code == 200){
                    toastr.success('Registro exitoso.');
                }else{
                    var error = response.data.error;
                    if(Array.isArray(error)){
                        error = response.data.error['errorInfo'];
                    }
                    toastr.warning(error);
                    toastr.options.closeButton = true;
                }
            }).catch(function (error) {
                console.log(error);
                toastr.warning('Error. ' + error);
                toastr.options.closeButton = true;
            });
        },
    }
});
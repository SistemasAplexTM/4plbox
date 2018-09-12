$(document).ready(function() {
    $('#modalParties').on('hidden.bs.modal', function() {
        if($('#collapseOne').hasClass('in')){
            $('#open_collapse').click();
            $('#open_collapse').html('<i class="fa fa-plus"></i> Crear nuevo');
        }
        objVue.cancelPartie();
    });
});

function seleccionarPartie(partie, id, display_name, account_number, zip, text_exporter) {
    var data = {
        id: id,
        display_name: display_name,
        account_number: account_number,
        zip: zip,
        text_exporter: text_exporter
    };
    objVue.seleccionarPartie(partie, data);
}

function editPartieForm(id, display_name, account_number, zip, text_exporter) {
    var data = {
        id: id,
        display_name: display_name,
        account_number: account_number,
        zip: zip,
        text_exporter: text_exporter
    };
    objVue.editPartieForm(data);
}

function deletePartie(id) {
    var data = {
        id: id
    };
    objVue.deletePartie(data);
}

function confirmDeletePartie(id){
    $('#delete_ok'+id).css('display', 'none');
    $('#delete_'+id).css('display', 'inline-block');
    $('#delete_c'+id).css('display', 'inline-block');
}

function cancelDeletePartie(id){
    $('#delete_'+id).css('display', 'none');
    $('#delete_c'+id).css('display', 'none');
    $('#delete_ok'+id).css('display', 'inline-block');
}


/* objetos VUE index */
var objVue = new Vue({
    el: '#billForm',
    mounted: function() {
        //
    },
    created: function() {
        if ($('#bill_id').val() != null && $('#bill_id').val() != '') {
            this.bill_id = $('#bill_id').val();
            this.editar = true;
            this.edit(this.bill_id);
        }
    },
    data: {
        bill_id: null,
        zip: null,
        document_number: null,
        num_bl: null,
        export_references: null,
        exporter: null,
        exporter_id: null,
        exporter_zip: null,
        consignee: null,
        consignee_id: null,
        notify_party: null,
        notify_party_id: null,
        forwarding_agent: null,
        point_origin: null,
        pre_carriage_by: null,
        place_of_receipt: null,
        domestic_routing: null,
        exporting_carrier: null,
        port_loading: null,
        loading_pier: null,
        foreign_port: null,
        placce_delivery: null,
        type_move: null,
        containered: 1,
        agent_for_carrier: null,
        detail: [{
            marks_numbers: null,
            number_packages: null,
            description: null,
            gross_weight: 0,
            measurement: 0
        }],
        other: [{
            description: null,
            ammount_pp: null,
            ammount_cll: null
        }],
        oc_total_pp: 0,
        oc_total_cll: 0,
        editar: false,
        //modal
        name_partie: null,
        id_partie: null,
        display_name: null,
        account_number: null,
        zip_partie: null,
        text_exporter: null,
        edit_p: false
    },
    methods: {
        print(){
            window.open("../imprimir/" + this.bill_id + '/' + true, '_blank');
        },
        addDetail: function() {
            this.detail.push({
                marks_numbers: null,
                number_packages: null,
                description: null,
                gross_weight: 0,
                measurement: 0,
            });
        },
        deleteRow(index) {
            this.detail.splice(index, 1);
        },
        addDetailOther: function() {
            this.other.push({
                description: null,
                ammount_pp: null,
                ammount_cll: null
            });
        },
        deleteRowOther(index) {
            this.other.splice(index, 1);
        },
        sumar(){
            let me = this;
            me.oc_total_pp = 0;
            me.oc_total_cll = 0;
            for (p in me.other) {
                me.oc_total_pp += isInteger((me.other[p].ammount_pp == null) ? 0 : me.other[p].ammount_pp)
                me.oc_total_cll += isInteger((me.other[p].ammount_cll == null) ? 0 : me.other[p].ammount_cll)
            }
        },
        SearchPartie(partie) {
            $('#modalParties').modal('show');
            this.name_partie = partie;
            this.getParties(partie);
        },
        getParties(partie){
            if ($.fn.DataTable.isDataTable('#tbl-modalParties')) {
                $('#tbl-modalParties tbody').empty();
                $('#tbl-modalParties').dataTable().fnDestroy();
            }
            if(this.editar){
                var url = '../getParties';
            }else{
                var url = '../bill/getParties';
            }
            $('#tbl-modalParties').DataTable({
                ajax: url,
                columns: [{
                    data: 'display_name',
                    name: 'display_name'
                }, {
                    data: 'account_number',
                    name: 'account_number'
                }, {
                    data: 'zip',
                    name: 'zip'
                }, {
                    sortable: false,
                    "render": function(data, type, full, meta) {
                        var btn_edit = '';
                        var btn_delete = '';
                        var texto = full.text_exporter;
                        if(texto != 'null' && texto != '' && texto != null){
                            texto = texto.replace(/\n/g, "-");
                        }
                        // if (permission_update) {
                            var params = [
                                full.id, "'" + full.display_name + "'", "'" + full.account_number + "'", "'" + full.zip + "'", "'" + texto + "'"
                            ];
                            var btn_edit = "<a onclick=\"editPartieForm(" + params + ")\" class='btn btn-outline btn-success btn-xs edit' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-edit'></i></a> ";
                        // }
                        // if (permission_delete) {
                            var btn_delete = " <a onclick=\"deletePartie(" + full.id + ")\" id='delete_"+full.id+"' class='btn btn-outline btn-danger btn-xs delete_' data-toggle='tooltip' data-placement='top' title='Confirmar eliminado'><i class='fa fa-check'></i></a> ";
                            var btn_cancel = " <a onclick=\"cancelDeletePartie(" + full.id + ")\" id='delete_c"+full.id+"' class='btn btn-outline btn-default btn-xs delete_c' data-toggle='tooltip' data-placement='top' title='Cancelar'><i class='fa fa-times'></i></a> ";
                            var btn_confirm_delete = " <a onclick=\"confirmDeletePartie(" + full.id + ")\" id='delete_ok"+full.id+"' class='btn btn-outline btn-danger btn-xs delete_ok' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash'></i></a> ";
                        // }
                            var btn_selected = " <a onclick=\"seleccionarPartie('" + partie +"', " + params +")\" class='btn btn-outline btn-primary btn-xs selected' data-toggle='tooltip' data-placement='top' title='Seleccionar'><i class='fa fa-check'></i></a> ";
                        return btn_selected + btn_edit + btn_delete + btn_confirm_delete + btn_cancel;
                    },
                    width: 150,
                }]
            });
        },
        store: function() {
            axios.post('../bill', {
                'document_number'   : this.document_number,
                'num_bl'            : this.num_bl,
                'zip'               : this.zip,
                'export_references' : this.export_references,
                'exporter'          : this.exporter,
                'exporter_id'       : this.exporter_id,
                'exporter_zip'      : this.exporter_zip,
                'consignee'         : this.consignee,
                'consignee_id'      : this.consignee_id,
                'notify_party'      : this.notify_party,
                'notify_party_id'   : this.notify_party_id,
                'forwarding_agent'  : this.forwarding_agent,
                'point_origin'      : this.point_origin,
                'pre_carriage_by'   : this.pre_carriage_by,
                'place_of_receipt'  : this.place_of_receipt,
                'domestic_routing'  : this.domestic_routing,
                'exporting_carrier' : this.exporting_carrier,
                'port_loading'      : this.port_loading,
                'loading_pier'      : this.loading_pier,
                'foreign_port'      : this.foreign_port,
                'placce_delivery'   : this.placce_delivery,
                'containered'       : this.containered,
                'type_move'         : this.type_move,
                'agent_for_carrier' : this.agent_for_carrier,
                'detail'            : this.detail,
                'other'             : this.other,
                'created_at'        : this.getTime()
            }).then(response => {
                toastr.success('Registro exitoso.');
                location.reload(true);
                window.open("imprimir/" + response.data.id_bill + '/' + true,'_blank');
            });
        },
        update: function(){
            axios.put('../' + this.bill_id, {
                'document_number'   : this.document_number,
                'num_bl'            : this.num_bl,
                'zip'               : this.zip,
                'export_references' : this.export_references,
                'exporter'          : this.exporter,
                'exporter_id'       : this.exporter_id,
                'exporter_zip'      : this.exporter_zip,
                'consignee'         : this.consignee,
                'consignee_id'      : this.consignee_id,
                'notify_party'      : this.notify_party,
                'notify_party_id'   : this.notify_party_id,
                'forwarding_agent'  : this.forwarding_agent,
                'point_origin'      : this.point_origin,
                'pre_carriage_by'   : this.pre_carriage_by,
                'place_of_receipt'  : this.place_of_receipt,
                'domestic_routing'  : this.domestic_routing,
                'exporting_carrier' : this.exporting_carrier,
                'port_loading'      : this.port_loading,
                'loading_pier'      : this.loading_pier,
                'foreign_port'      : this.foreign_port,
                'placce_delivery'   : this.placce_delivery,
                'containered'       : this.containered,
                'type_move'         : this.type_move,
                'agent_for_carrier' : this.agent_for_carrier,
                'detail'            : this.detail,
                'other'             : this.other,
                'updated_at'        : this.getTime()
            }).then(response => {
                toastr.success('Actualización exitosa.');
                // this.editar = false;
                // location.reload(true);
                window.open("../imprimir/" + response.data.id_bill + '/' + true, '_blank');
            });
        },
        edit(id){
            axios.get('../' + id + '/edit').then(response => {
                this.document_number    = response.data.data.document_number;
                this.num_bl             = response.data.data.num_bl;
                this.zip                = response.data.data.zip;
                this.zip                = response.data.data.zip;
                this.export_references  = response.data.data.export_references;
                this.exporter           = response.data.data.exporter;
                this.exporter_id        = response.data.data.exporter_id;
                this.exporter_zip       = response.data.data.exporter_zip;
                this.consignee          = response.data.data.consignee;
                this.consignee_id       = response.data.data.consignee_id;
                this.notify_party       = response.data.data.notify_party;
                this.notify_party_id    = response.data.data.notify_party_id;
                this.forwarding_agent   = response.data.data.forwarding_agent;
                this.point_origin       = response.data.data.point_origin;
                this.pre_carriage_by    = response.data.data.pre_carriage_by;
                this.place_of_receipt   = response.data.data.place_of_receipt;
                this.domestic_routing   = response.data.data.domestic_routing;
                this.exporting_carrier  = response.data.data.exporting_carrier;
                this.port_loading       = response.data.data.port_loading;
                this.loading_pier       = response.data.data.loading_pier;
                this.foreign_port       = response.data.data.foreign_port;
                this.placce_delivery    = response.data.data.placce_delivery;
                this.containered        = response.data.data.containered;
                this.type_move          = response.data.data.type_move;
                this.agent_for_carrier  = response.data.data.agent_for_carrier;
                this.detail             = response.data.detalle;
                this.other              = response.data.other;
                this.sumar();
            });
        },
        cancel: function() {
            if(this.editar){
                window.location.href = '../';
            }else{
                window.location.href = '../bill';
            }
        },
        seleccionarPartie(partie, data){
            var texto = data.text_exporter;
            texto = texto.replace(/-/g, "\n");
            if(partie == 'Shipper'){
                this.exporter_id = data.id;
                this.exporter = texto;
                this.exporter_zip = data.zip;
            }else{
                if(partie == 'Consignee'){
                    this.consignee_id = data.id;
                    this.consignee = texto;
                }
            }
            if($('#collapseOne').hasClass('in')){
                $('#open_collapse').click();
                $('#open_collapse').html('<i class="fa fa-plus"></i> Crear nuevo');
            }
            $('#modalParties').modal('hide');
        },
        editPartieForm(data){
            var texto = data.text_exporter;
            texto = texto.replace(/-/g, "\n");
            this.display_name   = (data.display_name == 'null') ? null : data.display_name;
            this.account_number = (data.account_number == 'null') ? null : data.account_number;
            this.zip_partie     = (data.zip == 'null') ? null : data.zip;
            this.text_exporter  = (texto == 'null') ? null : texto;
            this.id_partie      = data.id;
            this.edit_p         = true;
            if(!$('#collapseOne').hasClass('in')){
                $('#open_collapse').click();
            }
            $('#open_collapse').html('<i class="fa fa-edit"></i> Editar');
        },
        addPartie(){
            let me = this;
            if(this.editar){
                var url = '../createPartie';
            }else{
                var url = '../bill/createPartie';
            }
            axios.post(url, {
                'display_name'   : this.display_name,
                'account_number' : this.account_number,
                'zip'            : this.zip_partie,
                'text_exporter'  : this.text_exporter,
                'created_at'     : this.getTime()
            }).then(response => {
                toastr.success('Registro exitoso.');
                me.cancelPartie();
                me.getParties(this.name_partie);
            });
        },
        editPartie(){
            let me = this;
            if(this.editar){
                var url = '../editPartie';
            }else{
                var url = '../bill/editPartie';
            }
            axios.put(url + '/'+ this.id_partie, {
                'display_name'   : this.display_name,
                'account_number' : this.account_number,
                'zip'            : this.zip_partie,
                'text_exporter'  : this.text_exporter,
                'updated_at'     : this.getTime()
            }).then(response => {
                toastr.success('Actualización exitosa.');
                me.cancelPartie();
                me.getParties(this.name_partie);
            });
        },
        cancelPartie(){
            this.display_name   = null;
            this.account_number = null;
            this.zip_partie     = null;
            this.text_exporter  = null;
            this.edit_p = false;
        },
        deletePartie: function(data) {
            let me = this;
            if(this.editar){
                var url = '../destroyPartie';
            }else{
                var url = '../bill/destroyPartie';
            }
            axios.delete(url + '/' + data.id).then(response => {
                toastr.success('Registro eliminado correctamente.');
                toastr.options.closeButton = true;
                me.getParties(this.name_partie);
            });
        },
    }
});
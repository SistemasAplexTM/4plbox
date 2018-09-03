$(document).ready(function() {
    //
});
/* objetos VUE index */
var objVue = new Vue({
    el: '#billForm',
    mounted: function() {
        //
    },
    created: function() {
        console.log('bill = ' + $('#bill_id').val());
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
        editar: false
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
        SearchShipper() {},
        SearchConsignee() {},
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
    }
});
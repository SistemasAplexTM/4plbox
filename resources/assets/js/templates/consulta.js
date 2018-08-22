$(document).ready(function() {
    $('.rango_fecha').daterangepicker({
        "locale": {
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Custom",
            "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            "firstDay": 1
        }
    });
});
/* objeto VUE */
var objVue = new Vue({
    el: '#consulta',
    mounted: function() {
        this.getStatus();
    },
    data: {
        shippers: [],
        shipper_id: null,
        consignees: [],
        consignee_id: null,
        status: [],
        status_id: null,
    },
    methods: {
        resetForm: function() {
            this.shipper_id = null;
        },
        updateTable: function() {
            refreshTable('tbl-consulta');
        },
        cancel: function() {
            this.resetForm();
        },
        getStatus: function() {
            let me = this;
            axios.get('status/all').then(function(response) {
                me.status = response.data.data;
            }).catch(function(error) {
                console.log(error);
                toastr.warning('Error.');
                toastr.options.closeButton = true;
            });
        },
        search: function() {
            if ($.fn.DataTable.isDataTable('#tbl-consulta')) {
                $('#tbl-consulta tbody').empty();
                $('#tbl-consulta').dataTable().fnDestroy();
            }
            $('#tbl-consulta').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    "url": "consulta/all",
                    "data": {
                        'shipper_id': (this.shipper_id != null) ? this.shipper_id.id : null,
                        'consignee_id': (this.consignee_id != null) ? this.consignee_id.id : null,
                        'status_id': (this.status_id != null) ? this.status_id.id : null,
                        'fechas': $('#fechas').val()
                    }
                },
                columns: [{
                    data: 'num_warehouse',
                    name: 'num_warehouse'
                }, {
                    data: 'estado',
                    name: 'estado'
                }, {
                    data: 'fecha',
                    name: 'fecha'
                }, {
                    data: 'shipper',
                    name: 'shipper'
                }, {
                    data: 'consignee',
                    name: 'consignee'
                }, {
                    data: 'piezas',
                    name: 'piezas'
                }, {
                    data: 'peso',
                    name: 'peso'
                }, {
                    data: 'volumen',
                    name: 'volumen'
                }]
            });
        },
        onSearchShippers(search, loading) {
            loading(true);
            this.searchShippers(loading, search, this);
        },
        searchShippers: _.debounce((loading, search, vm) => {
            fetch(`shipper/vueSelect/${escape(search)}`).then(res => {
                res.json().then(json => (vm.shippers = json.items));
                loading(false);
            });
        }, 350),
        onSearchConsignees(search, loading) {
            loading(true);
            this.searchConsignees(loading, search, this);
        },
        searchConsignees: _.debounce((loading, search, vm) => {
            fetch(`consignee/vueSelect/${escape(search)}`).then(res => {
                res.json().then(json => (vm.consignees = json.items));
                loading(false);
            });
        }, 350),
    },
});
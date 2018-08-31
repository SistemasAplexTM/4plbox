$(document).ready(function () {
    alert('asd');
});

/* objetos VUE index */
var objVue = new Vue({
    el: '#billForm',
    mounted: function () {
        //
    },
    created: function(){
        //
    },
    data:{
        zip: null,
        document_number: null,
        num_bl: null,
        export_references: null,
        detail: [{
          marks: null,
          number: null,
          description: null,
          gross: 0,
          measurement: 0,
        }],
    },
    methods:{
        showHiddeFields: function() {
            var json = functionalities_doc;
            var arreglo = [];
            $.each(json, function(key, value) {
                arreglo.push(parseInt(value.id));
            });
            this.mostrar = arreglo;
        },
        addDetail: function(){
            this.detail.push({
                marks: null,
                number: null,
                description: null,
                gross: 0,
                measurement: 0,
            });
        },
        deleteRow(index) {
            this.detail.splice(index,1);
        },
    },
});
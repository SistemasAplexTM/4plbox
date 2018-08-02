$(document).ready(function () {
    //
});

/* objetos VUE index */
var objVue = new Vue({
    el: '#viewdocumento',
    mounted: function () {
        //
    },
    created: function(){
        this.showHiddeFields();
    },
    data:{
        mostrar: {},
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
    },
});
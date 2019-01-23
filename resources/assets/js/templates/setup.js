var objVue = new Vue({
    el: '#setup',
    data:{

    },
    created(){
      this.create()
    },
    methods:{
      create: function(){
        axios.get('formatNumber').then(({data}) => {
          localStorage.setItem("decimals", data.data)
        });
      }
    },
});

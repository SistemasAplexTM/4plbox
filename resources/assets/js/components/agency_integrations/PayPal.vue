<template lang="html">
  <div class="" style="margin-top: 20px">
    <div class="">
      <div class="panel-body">
        <div class="col-lg-12">
            <div class="col-sm-4 text-center">
              <h2>
                Configuración de PayPal
              </h2>
                <i class="fab fa-paypal fa-7x"></i>
            </div>
            <div class="col-lg-8">
                <div class="checkbox checkbox-success checkbox-inline" @click="">
                    <input v-model="actived" type="checkbox" id="mail" name="usar_mail_chimp">
                    <label for="mail"> Activar</label>
                </div>
                <div class="form-group">
                  <label>Ingrese correo</label>
                  <input v-model="email_p" type="email" name="email_p" id="email_p" class="form-control" placeholder="Correo">
                </div>
                <div class="form-group">
                    <button type="button" class="ladda-button btn btn-primary btn-sm" data-style="expand-right" name="button" @click="save">Guardar <span class="ladda-spinner"></span></button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PayPal',
  data(){
    return{
      email_p: '',
      actived: null
    }
  },
  props: ["agency_id"],
  methods:{
    save: function(){
      var l = $('.ladda-button').ladda();
      l.ladda( 'start' );
      axios.post('../../config/agency_paypal_' + this.agency_id +'/type/false', {
        email: this.email_p,
        actived: this.actived
      }).then(response => {
        toastr.success('Registro creado correctamente.');
        this.get();
        l.ladda('stop');
      }).catch(error => {
        l.ladda('stop');
        console.log(error);
      });
    },
    get: function(){
      axios.get('../../getConfig/agency_paypal_' + this.agency_id).then(({data}) => {
        if (data.value) {
          var data = JSON.parse(data.value)
          this.email_p = data.email
          this.actived = data.actived
        }
      });
    },
  },
  created(){
    this.get();
  },
}
</script>

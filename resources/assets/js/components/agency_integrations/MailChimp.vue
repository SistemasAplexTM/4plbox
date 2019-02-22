<template lang="html">
  <div class="" style="margin-top: 20px">
    <div class="">
      <div class="panel-body">
        <div class="col-lg-12">
            <div class="col-sm-4 text-center">
              <h2>
                Configuración de MailChimp
              </h2>
                <i class="fab fa-mailchimp fa-7x"></i>
            </div>
            <div class="col-lg-8">
                <div class="checkbox checkbox-success checkbox-inline" @click="">
                    <input v-model="actived" type="checkbox" id="mail" name="usar_mail_chimp">
                    <label for="mail"> Activar </label>
                </div>
                <div class="form-group">
                  <label>Ingrese MC_KEY de MailChimp</label>
                  <input v-model="key" type="tex" name="mc_key" id="mc_key" class="form-control" placeholder="MC_KEY">
                </div>
                <div class="form-group">
                    <label>Ingrese el ID de la lista</label>
                    <input v-model="id_list" type="tex" name="listId" id="listId" class="form-control" placeholder="ID Cliente">
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
  data(){
    return{
      key: '',
      id_list: '',
      actived: null
    }
  },
  props: ["agency_id"],
  methods:{
    save: function(){
      var l = $('.ladda-button').ladda();
      l.ladda( 'start' );
      axios.post('../../config/agency_mc_' + this.agency_id +'/type/false', {
        mc_key: this.key,
        id_list: this.id_list,
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
      axios.get('../../getConfig/agency_mc_' + this.agency_id).then(({data}) => {
        if (data.value) {
          var data = JSON.parse(data.value)
          this.key = data.mc_key
          this.id_list = data.id_list
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

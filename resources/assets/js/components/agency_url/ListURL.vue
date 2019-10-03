<template lang="html">
  <table class="table table-striped table-hover table-bordered" id="tbl-url" style="width: 100%;">
    <thead>
      <tr>
        <th width="35%">Descripción</th>
        <th>URL</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Principal</td>
        <td>
          <a id="url_main" data-name="url_main" data-type="text" :data-pk="agency.id">{{ agency.url }}</a>
        </td>
      </tr>
      <tr>
        <td>Términos y condiciones</td>
        <td><a target="_blank" :href="agency.terms">{{ agency.url_terms }}</a></td>
      </tr>
    </tbody>
  </table>
</template>

<script>

export default {
  props: ['agency_id'],
  data(){
    return {
      agency: {}
    }
  },
  mounted(){
    let me = this
    me.get();
    
  },
  methods: {
    get(){
      if (!this.agency_id) {
        return false
      }
      axios.get('/agencia/getURL/' + this.agency_id,).then(({data}) => {
        this.agency = data
        console.log(data);
      }).catch(error => error)
    }
  }
}
</script>

<style lang="css" scoped>
</style>

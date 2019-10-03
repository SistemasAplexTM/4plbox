<template lang="html">
  <div class="col-lg-12">
      <div class="form-group">
        <label>URL Principal</label>
        <input v-model="main" type="tex" class="form-control" placeholder="http://">
      </div>
      <div class="form-group">
          <label>URL Términos</label>
          <input v-model="term" type="tex" class="form-control" placeholder="http://">
      </div>
      <!-- <div class="form-group">
          <label>Tracking</label>
          <input v-model="tracking" type="tex" class="form-control" placeholder="http://">
      </div>
      <div class="form-group">
          <label>Prealertar</label>
          <input v-model="prealert" type="tex" class="form-control" placeholder="http://">
      </div> -->
      <div class="form-group">
          <el-button type="success" @click="save" size="small">
            <i class="fal fa-save"> </i> Guardar
          </el-button>
      </div>
  </div>
</template>

<script>
export default {
  props: ['agency_id'],
  data(){
    return {
      main: '',
      term: '',
      prealert: '',
      tracking: ''
    }
  },
  methods: {
    save(){
      if (!this.agency_id) {
        return false
      }
      var data = {main: this.main, term: this.term, prealert: this.prealert, tracikng: this.tracking}
      axios.post('/agencia/saveURL/' + this.agency_id, data).then(({data}) => {
        bus.$emit('updateListURL')
      }).catch(error => error)
    }
  }
}
</script>

<style lang="css" scoped>
</style>

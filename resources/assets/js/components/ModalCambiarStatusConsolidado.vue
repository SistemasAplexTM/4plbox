<style lang="css" scoped>



</style>

<template lang="html">

<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
          <li role="trackings" class="active"><a href="#bodega" aria-controls="bodega" role="tab" data-toggle="tab"><i class="fal fa-clock"></i> Estados</a></li>
          <li role="trackings"><a href="#recibido" aria-controls="recibido" role="tab" data-toggle="tab"><i class="fal fa-barcode"></i> Recibido</a></li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade" id="recibido">
             <div class="row form-group" id="register">
                 <div class="col-lg-6" :class="{ 'has-error': errors.has('warehouse') }">
                     <el-input ref="warehouse_state" size="medium" placeholder="Código de warehouse" v-model="form.warehouse" @keyup.enter.native="setData"></el-input>
                     <small class="help-block">{{ errors.first('warehouse') }}</small>
                 </div>
                 <div class="col-lg-6" :class="{ 'has-error': errors.has('estatus') }">
                  <status-component :data="status" :default="1" @get="form.estatus_id = $event.id"/>
                 </div>
                 <transition name="fade">
                     <div class="col-lg-6 form-group" :class="{ 'has-error': errors.has('transportadora') }" v-if='show'>
                         <el-select name="transportadora" v-model="form.transportadora_id" filterable placeholder="Transportadoras locales" v-validate.disable="'required'" size="medium">
                             <el-option v-for="item in form.transportadora" :key="item.id" :label="item.name" :value="item.id">
                                 <span style="float: left">{{ item.name }}</span>
                                 <span style="float: right; color: #8492a6; font-size: 13px">{{ item.pais }}</span>
                             </el-option>
                         </el-select>
                         <small class="help-block">{{ errors.first('transportadora') }}</small>
                     </div>
                 </transition>
                 <transition name="fade">
                     <div class="col-lg-6 form-group" :class="{ 'has-error': errors.has('guia_transportadora') }" v-if='show'>
                         <el-input name="guia_transportadora" placeholder="Número de guia transportadora" prefix-icon="el-icon-edit-outlin" v-model="form.guia_transportadora" v-validate.disable="'required'" size="medium">
                         </el-input>
                         <small class="help-block">{{ errors.first('guia_transportadora') }}</small>
                     </div>
                 </transition>
                 <div class="col-lg-10">
                     <input type="text" class="form-control" v-model="form.observacion" placeholder="Observación">
                 </div>
                 <div class="col-lg-2">
                     <button class="btn btn-primary" data-toggle="tooltip" title="Agregar" @click="setData"><i class="fal fa-plus"></i></button>
                 </div>
             </div>
          </div>
          <div role="tabpanel" class="tab-pane fade active in" id="bodega">
           <form id="formGuiasAgrupar" style="margin-top: 10px">
               <p>Selecione el estatus que desea aplicar a este consolidado y sus documentos internos.</p>
               <div class="row">
                   <div class="col-sm-8">
                       <div class="form-group">
                           <label for="status_id">Estatus actual</label>
                           <status-component :data="status" :default="defaultStatus" @get="status_id = $event.id"/>
                       </div>
                   </div>
                   <div class="col-sm-4">
                       <div class="form-group">
                           <label for="status_id" style="width: 100%;">&nbsp;</label>
                           <button class="ladda-button btn btn-primary btn-sm" data-style="expand-right" type="button" data-toggle="tooltip" title="Agregar estatus a guias" @click="addStatusConsolidado()"><i class="fal fa-save"></i> Cambiar</button>
                       </div>
                   </div>
               </div>
           </form>
          </div>
        </div>
    </div>
</div>

</template>

<script>

export default {
  data(){
    return {
     form: {
      status_id: null,
      estatus_id: 1,
      observacion: null,
      warehouse: null,
      transportadora: [],
      transportadora_id: null,
     },
     show: false,
     defaultStatus: null,

    };
  },
  props: ["document_id", "status"],
  mounted(){
    let me = this;
    $('#modalChangeStatus').on('show.bs.modal', function() {
      setTimeout(function() {
        me.$refs.warehouse_state.focus();
        me.getStatusDocument()
      },500)
    });
  },
  methods:{
   setData(){
    axios.post('cambiarStatusConsolidado/'+ this.document_id, this.form).then(({data}) => {
     if (data.code == 200) {
      toastr.success('Registrado con éxito.');
      this.reset()
     }else{
      toastr.error('El warehouse no se encuentra en este consolidado.');
     }
    }).catch(error => { console.log(error) })
   },
   reset(){
    let me = this;
    this.form = {
     estatus_id: null,
     warehouse: null,
     transportadora: [],
     transportadora_id: null
    }
    me.$refs.warehouse_state.focus();
    this.show = false
   },
   addStatusConsolidado: function(){
     var l = Ladda.create(document.querySelector('.ladda-button'));
     // console.log(l);
     l.start();
     let me = this;
     axios.post('documento/' + me.document_id + '/addStatusToGuias',{
         'status_id': me.status_id
     }).then(function (response) {
         l.stop();
         toastr.success('Registro Exitoso.');
     }).catch(function (error) {
         l.stop();
         console.log(error);
         toastr.warning('Error.');
         toastr.options.closeButton = true;
     });
   },
   getStatusDocument: function(){
     let me = this;
     axios.post('documento/' + me.document_id + '/getStatusDocument').then(({data}) => {
       me.defaultStatus = data.estado_id
     }).catch(function (error) {
       console.log(error);
       toastr.warning('Error.');
       toastr.options.closeButton = true;
     });
   },
  }
}

</script>

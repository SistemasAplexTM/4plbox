<template lang="html">
  <div class="">
    <div class="">
      <div class="mail-box-header">
          <h2>
              Configuración de documentos
          </h2>
      </div>
      <div class="mail-box">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1"> Valores por defecto</a></li>
            <li class=""><a data-toggle="tab" href="#tab-2">Acciones por defecto</a></li>
          </ul>
          <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                <div class="row">
                  <div class="col-sm-6 b-r"><h3 class="m-t-none m-b">Shipper</h3>
                    <p>El shipper que seleccione, se cargará por defecto en todos los documetos que cree.</p>
                    <div class="form-group">
                      <div class="input-group"  style="margin-bottom: 5px;">
                        <input type="hidden" class="" id="shipper_id" name="shipper_id">
                        <input type="search" data-id="nomBuscarShipper" id="nombreR" name="nombreR"  class="form-control" v-model="nombreR" v-validate="'required'">
                        <span class="input-group-btn">
                          <button id="btnBuscarShipper" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Shipper">
                            <span class="fal fa-search"></span>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6"><h3 class="m-t-none m-b">Consignee</h3>
                    <p>El consignee que seleccione, se cargará por defecto en todos los documetos que cree.</p>
                    <div class="form-group">
                      <div class="input-group"  style="margin-bottom: 5px;">
                        <input type="search" data-id="nomBuscarConsignee" id="nombreD" name="nombreD" class="form-control" v-model="nombreD" v-validate="'required'">
                        <span class="input-group-btn">
                          <button id="btnBuscarConsignee"class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Consignee">
                            <span class="fal fa-search"></span>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <hr>
                <div class="row">
                  <div class="col-lg-12">
                    <h3>Observación por defecto</h3>
                    <div class="form-group">
                      <textarea name="name" rows="4" cols="20" class="form-control" v-model="observDefault"></textarea>
                    </div>
                    <button @click="saveDefaultObserv" class="btn btn-primary" type="button" data-toggle='tooltip' title="Guardar"><span class="fal fa-save"></span></button>
                  </div>
                </div>
              </div>
            </div>
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                <strong>Donec quam felis</strong>
                <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                    and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>
                <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                    sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
              </div>
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
      nombreR: null,
      dataShipper: {},
      nombreD: null,
      dataConsignee: {},
      observDefault: null
    }
  },
  // created(){
  //   this.getDefault();
  // },
  methods:{
    saveDefaultS: function(id){
      axios.post('../config/shipperDefault/'+id+'/true', {data: id}).then(response => {
        $('#modalShipper').modal('hide');
        this.getShipperById(id);
      });
    },
    saveDefaultC: function(id){
      axios.post('../config/consigneeDefault/'+id+'/true', {data: id}).then(response => {
        $('#modalConsignee').modal('hide');
        this.getConsigneeById(id);
        toastr.success('Registro exitoso.');
      });
    },
    saveDefaultObserv: function(){
      axios.post('../config/observDefault/'+this.observDefault+'/true', {data: ''}).then(response => {
        toastr.success('Registro exitoso.');
      });
    },
    getDefault: function(){
      axios.get('../getConfig/shipperDefault').then(({data}) => {
        this.getShipperById(data.value);
      });
      axios.get('../getConfig/consigneeDefault').then(({data}) => {
        this.getConsigneeById(data.value);
      });
      axios.get('../getConfig/observDefault').then(({data}) => {
        this.observDefault = data.value;
      });
    },
    getShipperById: function(id){
      axios.get('../shipper/getDataById/' + id).then(({data}) => {
        this.dataShipper = data
      });
    },
    getConsigneeById: function(id){
      axios.get('../consignee/getDataById/' + id).then(({data}) => {
        this.dataConsignee = data
      });
    }
  }
}
</script>

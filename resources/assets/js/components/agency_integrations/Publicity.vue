<template lang="html">
  <div style="margin-top: 20px">
   <el-row>
    <el-col :span="24">
     <el-button  @click="add" type="success"><i class="fal fa-plus"></i> Agregar Item</el-button>
     <el-button  @click="save" type="primary" class="fr"><i class="fal fa-save"></i> Guardar</el-button>
    </el-col>
   </el-row>
   <el-row class="row" :gutter="14">
    <el-col v-for="(column, indexColumn) in publicity" :key="column.id" :lg="column.width" :xs="24" class="column">
     <div :class="'bg-'+column.color" class="text-center mt-30">
      <button class="btn btn-danger btn-small fr"  data-toggle="modal" @click="remove(indexColumn)" type="button">
       <i class="fal fa-trash"></i>
      </button>
      <button class="btn btn-success btn-small fr"  data-toggle="modal" @click="edit(indexColumn)" type="button">
       <i class="fal fa-edit"></i>
      </button>
						<h1 v-html="column.text" style="max-width: 100%"></h1>
     </div>
    </el-col>
		</el-row>
 <!-- Modal -->
 <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Publicidad</h4>
      </div>
      <div class="modal-body">
       <el-select v-model="width" style="margin-bottom: 20px">
        <el-option
        v-for="item in 24"
        :label="item"
        :value="item"
        :key="item">
       </el-option>
      </el-select>
      <br>
       <textarea name="name" rows="8" cols="80" class="form-control" id="editValue" :value="this.columnEdit.text">
       </textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="update()">Actualizar</button>
      </div>
    </div>
  </div>
</div>
 <!-- Fin Midal -->
  </div>
</template>

<script>
export default {
 data(){
  return {
   width: null,
   idEdit: null,
   rowEdit: null,
   columnEdit: {},
   publicity: []
  }
 },
 props: ["agency_id"],
 created(){
  let me = this
  setTimeout(function () {
   me.getData()
  }, 300);
 },
 methods:{
  save(){
   axios.post('../../config/agency_publicity_' + this.agency_id +'/type/false', this.publicity).then(({data}) => {
    alert("Listo!")
   }).catch(error => error)
  },
  getData(){
   axios.get('../../getConfig/agency_publicity_' + this.agency_id).then(({data}) => {
    // this.publicity = []
    // if (data.value && data.value.length > 0) {
     var data = JSON.parse(data.value)
     this.publicity = data
    // }
   }).catch(error => error)
  },
  add(){
   var id = 1
   if (this.publicity) {
    id = this.publicity.length
   }
   this.publicity.push({id: id + 1, width: 4, color: 'info-light', text: 'Nuevo item'})
  },
  remove(column){
   var id = this.publicity.splice(column)
  },
  edit(column){
   this.columnEdit = this.publicity[column]
   this.width = this.publicity[column].width
   this.columnEdit.column = column
   $('#myModal').modal('show')
  },
  update(){
   this.publicity[this.columnEdit.column].text = $('#editValue').val()
   this.publicity[this.columnEdit.column].width = this.width
   $('#myModal').modal('hide')
  },
  filtrarPorID(obj) {
   if ('id' in obj && typeof(obj.id) === 'number' && !isNaN(obj.id) && obj.id == this.idEdit) {
    return true;
   } else {
    return false;
   }
  }
 }
}
</script>

<style lang="css" scoped>
.row{
 /* border: 1px solid gray !important; */
 border: 1px solid rgba(0,0,0,.2) !important;
 margin-top: 10px;
 padding: 10px;
}
.column{
 border: 1px dotted rgba(0,0,0,.2) !important;
}
.add{
 min-height: 100px;
 cursor: pointer;
 text-align: center;
}
.add:hover{
 border: 1px dotted black !important;
 color: black;
}
.add > .icon{
 margin: 100px;
}
.add > .icon:hover{
 color: black;
 -webkit-transition: all 60ms ease-in;
	-webkit-transform: scale(1.020, 1.020);
}

.fr{
 float: right;
}
</style>

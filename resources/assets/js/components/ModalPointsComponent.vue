<!-- estilos -->
<style type="text/css">
  .el-select-dropdown{
    z-index: 9999!important;
  }
  .el-input-number.is-controls-right .el-input-number__decrease, .el-input-number.is-controls-right .el-input-number__increase{
    height: 19px;
  }
  [class*=" el-icon-"], [class^=el-icon-]{
    margin-top: 2px;
  }
  .el-select .el-input__inner{
    width: 100%;
  }
  .el-table__empty-block{
    height: auto;
  }
</style>
<template>
  <div class="modal fade bs-example" id="modalAddPoints" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" id="points" style="width: 40%!important;">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                  <h2 class="modal-title" id="myModalLabel"><i class="fal fa-map-pin"></i> Agregar Puntos</h2>
              </div>
              <div class="modal-body">
                <form id="formAddPoints" action="">
                  <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                  <div class="row">
                      <div class="col-lg-12">
                          <h3>Seleccione la categoría y la cantidad del producto para registrar los puntos.</h3>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                          <el-select
                            clearable
                            v-model="point_id"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="Ingrese un dato"
                            :remote-method="remoteMethod"
                            :loading="loading"
                            loading-text="Cargando"
                            no-data-text="No hay datos"
                            value-key="id">
                            <el-option
                              v-for="item in options4"
                              :key="item.id"
                              :label="item.text"
                              :value="item">
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <el-input-number placeholder="Cantidad" controls-position="right" :min="1" v-model="cantidad"></el-input-number>
                        </div>
                      </div>
                      <div class="col-lg-3">
                          <div class="form-group">
                            <el-button type="success" :loading="loading_save" @click="save"><i class="fal fa-save"></i> Guardar</el-button>
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                            <el-table
                              :data="tableData"
                              style="width: 100%"
                              show-summary
                              sum-text="TOTAL"
                              empty-text="No hay datos">
                              <el-table-column
                                label="Categoría"
                                prop="category">
                              </el-table-column>
                              <el-table-column
                                label="Cantidad"
                                prop="quantity">
                              </el-table-column>
                              <el-table-column
                                label="Puntos"
                                prop="points_total">
                              </el-table-column>
                              <el-table-column
                                label="Total Puntos"
                                prop="total_puntos">
                              </el-table-column>
                              <el-table-column
                                align="right">
                                <template slot-scope="scope">
                                    <el-button data-toggle="tooltip" title="Eliminar" data-placement="left"
                                      size="mini"
                                      type="danger"
                                      @click="handleDelete(scope.$index, scope.row)"><i class="fal fa-times"></i>
                                    </el-button>
                                </template>
                              </el-table-column>
                            </el-table>
                          </div>
                      </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  export default {
    props:["id_detail"],
    watch:{
        id_detail:function(value){
            this.tableData= [];
            if(value != null && value != ''){
                this.getTableData();
            }
        },
    },
    data() {
      return {
        options4: [],
        point_id: [],
        list: [],
        loading: false,
        cantidad: 1,
        loading_save:false,
        tableData: []
      }
    },
    mounted() {
      this.getDataSelect();
    },
    methods: {
      resetForm(){
        this.point_id= null;
        this.cantidad= 1;
      },
      save(){
        let me = this;
        if(me.point_id != null && me.cantidad > 0){
        me.loading_save = true;
          axios.post('../../puntos', {
              'puntos_id': me.point_id.id,
              'documento_detalle_id': me.id_detail,
              'cantidad': me.cantidad,
              'total_puntos': me.cantidad * parseInt(me.point_id.descripcion),
          }).then(response => {
            me.getTableData();
            me.resetForm();
            refreshTable('whgTable');
            me.loading_save = false;
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
              me.loading_save = false;
          });
        }else{
          toastr.warning('Completa los campos requeridos');
        }
      },
      handleDelete(index, row) {
        let me = this;
        axios.delete('../../puntos/' + row.id).then(function(response) {
          me.getTableData();
          refreshTable('whgTable');
          toastr.success('Registro eliminado correctamente.');
        }).catch(function(error) {
            console.log(error);
            toastr.warning('Error: -' + error);
        });
      },
      getTableData(){
  				var me = this;
  				axios.get('../../puntos/' + me.id_detail).then(function(response) {
              me.tableData = response.data.data;
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
          });
			},
      getDataSelect(){
				var me = this;
				axios.get('../../administracion/10/selectInput').then(function(response) {
            me.list = response.data.items;
        }).catch(function(error) {
            console.log(error);
            toastr.warning('Error: -' + error);
        });
			},
      remoteMethod(query) {
        if (query !== '') {
          this.loading = true;
          setTimeout(() => {
            this.loading = false;
            this.options4 = this.list.filter(item => {
              return item.text.toLowerCase()
                .indexOf(query.toLowerCase()) > -1;
            });
          }, 200);
        } else {
          this.options4 = [];
        }
      }
    }
  }
</script>

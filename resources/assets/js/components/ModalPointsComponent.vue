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
</style>
<template>
  <div class="modal fade bs-example" id="modalAddPoints" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" id="points" style="width: 40%!important;">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                  <h2 class="modal-title" id="myModalLabel"><i class="far fa-map-pin"></i> Agregar Puntos</h2>
              </div>
              <div class="modal-body">
                <form id="formAddPoints" action="">
                  <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                  <div class="row">
                      <div class="col-lg-12">
                          <h3>Seleccione la categoria para y la cantidad del producto para registrar los puntos.</h3>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                          <el-select
                            clearable
                            v-model="value9"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="Ingrese un dato"
                            :remote-method="remoteMethod"
                            :loading="loading"
                            loading-text="Cargando"
                            no-data-text="No hay datos">
                            <el-option
                              v-for="item in options4"
                              :key="item.id"
                              :label="item.text"
                              :value="item.id">
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
                            <el-button type="success" :loading="loading" @click="save"><i class="fa fa-save"></i> Guardar</el-button>
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
                              sum-text="TOTAL">
                              <el-table-column
                                label="Categoria"
                                prop="category">
                              </el-table-column>
                              <el-table-column
                                label="Cantidad"
                                prop="quantity">
                              </el-table-column>
                              <el-table-column
                                label="Total Puntos"
                                prop="points_total">
                              </el-table-column>
                              <el-table-column
                                align="right">
                                <template slot-scope="scope">
                                  <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete(scope.$index, scope.row)"><i class="far fa-times"></i></el-button>
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
    data() {
      return {
        options4: [],
        value9: [],
        list: [],
        loading: false,
        cantidad: 1,
        loading:false,
        tableData: [{
          category: 'Camisas',
          quantity: 2,
          points_total: 6,
        }, {
          category: 'Celulares',
          quantity: 5,
          points_total: 100,
        }, {
          category: 'Televisores',
          quantity: 1,
          points_total: 50,
        }, {
          category: 'Pantalones',
          quantity: 3,
          points_total: 15,
        }]
      }
    },
    mounted() {
      this.getDataSelect();
    },
    methods: {
      save(){
        let me = this;
        me.loading = true;
        setTimeout(function() {
          me.loading = false;
        },2000);
      },
      handleDelete(index, row) {
        console.log(index, row);
      },
      getDataSelect(){
				var me = this;
				axios.get('../../administracion/9/selectInput').then(function(response) {
            console.log(response.data.items);
            me.list = response.data.items;
        }).catch(function(error) {
            console.log(error);
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

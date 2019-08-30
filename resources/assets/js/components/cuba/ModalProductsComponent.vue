<!-- estilos -->
<style type="text/css">
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
  <div class="modal fade bs-example" id="modalAddPointsToDetail" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" id="points" style="">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                  <h2 class="modal-title" id="myModalLabel"><i class="fal fa-map-pin"></i> Productos ingresados por el cliente</h2>
              </div>
              <div class="modal-body">
                <form id="formAddPoints" action="">
                  <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                  <div class="row">
                      <div class="col-lg-12">
                          <h3>Elija los productos para crear el recibo.</h3>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                            <el-table
                              ref="multipleTable"
                              :data="tableData.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
                              style="width: 100%"
                              empty-text="No hay datos"
                              @selection-change="handleSelectionChange">
                              <el-table-column
                                type="selection"
                                width="55">
                              </el-table-column>
                              <el-table-column
                                label="Categoría"
                                prop="categoria"
                                width="300">
                              </el-table-column>
                              <el-table-column
                                label="Articulo"
                                prop="articulo"
                                width="300">
                              </el-table-column>
                              <el-table-column
                                label="Cant."
                                prop="cantidad">
                              </el-table-column>
                              <el-table-column
                                label="Punt."
                                prop="puntos">
                              </el-table-column>
                              <el-table-column
                                label="Tot. Punt."
                                prop="total_puntos"
                                width="100">
                                <!-- <template slot="header" slot-scope="scope">
                                  <el-input
                                    v-model="search"
                                    size="mini"
                                    placeholder="Type to search"/>
                                </template> -->
                              </el-table-column>
                            </el-table>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                            <h2>PUNTOS SELECCIONADOS: 0</h2>
                          </div>
                      </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i> Cerrar</button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  export default {
    props:["id_document", "points", "data_p"],
    watch:{
      multipleSelection:function(val){
        // console.log(val);
      },
      data_p:function(val){
        if(val.length == 0){
          this.toggleSelection();
        }
      },
    },
    data() {
      return {
        tableData: [],
        multipleSelection: [],
        search: '',
      }
    },
    mounted() {
      this.getTableData();
    },
    methods: {
      toggleSelection(rows) {
        if (rows) {
          rows.forEach(row => {
            this.$refs.multipleTable.toggleRowSelection(row);
          });
        } else {
          this.$refs.multipleTable.clearSelection();
        }
      },
      handleSelectionChange(val){
        this.multipleSelection = val;
        this.$emit('get', val);
      },
      getTableData(){
  				var me = this;
  				axios.get('../../puntos/getProductsClient/' + me.id_document).then(function(response) {
              me.tableData = response.data;
          }).catch(function(error) {
              console.log(error);
              toastr.warning('Error: -' + error);
          });
			},
    }
  }
</script>

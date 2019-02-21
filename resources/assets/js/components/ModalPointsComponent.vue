<!-- estilos -->
<style type="text/css">
.el-select-dropdown{
  z-index: 9999!important;
}
</style>
<template>
  <div class="modal fade bs-example" id="modalAddPoints" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" id="points" style="width: 40%!important;">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                  <h2 class="modal-title" id="myModalLabel"><i class="fa fa-truck"></i> Agregar Puntos</h2>
              </div>
              <div class="modal-body">
                <form id="formAddPoints" action="">
                  <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                  <div class="row">
                      <div class="col-lg-8">
                          <h3>Seleccione la categoria para y la cantidad del producto para registrar los puntos.</h3>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
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
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover" id="tbl-trackings" style="width: 100%">
                                      <thead>
                                          <tr>
                                              <th></th>
                                              <th style="width: 50%">@lang('documents.tracking')</th>
                                              <th>@lang('documents.content')</th>
                                          </tr>
                                      </thead>
                                  </table>
                              </div>
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
      }
    },
    mounted() {
      this.getDataSelect();
    },
    methods: {
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

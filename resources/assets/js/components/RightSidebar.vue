<template lang="html">
    <div class="sidebar-container">
        <ul class="nav nav-tabs navs-3">
            <li class="active">
              <a data-toggle="tab" href="#tab-1">WHR</a>
            </li>
            <li>
              <a data-toggle="tab" href="#tab-2">Projects</a>
            </li>
            <li>
              <a data-toggle="tab">Otros</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade active in">
                <div class="sidebar-title">
                    <h3> <i class="fal fa-search"></i> Buscar</h3>
                    <el-select
                      size="small"
                      clearable
                      v-model="data_search"
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Wh o Guia"
                      :remote-method="remoteMethod"
                      :loading="loading"
                      loading-text="Cargando..."
                      no-data-text="No hay datos"
                      @change="handleSelect"
                      value-key="id">
                      <el-option
                        v-for="item in options"
                        :key="item.id"
                        :label="item.name"
                        :value="item">
                      </el-option>
                    </el-select>
                </div>
                <div>
                    <div class="sidebar-message">
                        <a href="#">
                            <div class="pull-left text-center">
                                <img alt="image" class="img-circle message-avatar" src="img/a1.jpg">

                                <div class="m-t-xs">
                                    <i class="fal fa-star text-warning"></i>
                                    <i class="fal fa-star text-warning"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                There are many variations of passages of Lorem Ipsum available.
                                <br>
                                <small class="text-muted">Today 4:21 pm</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane fade">
                <div class="sidebar-title">
                    <h3> <i class="fal fa-cube"></i> Latest projects</h3>
                    <small><i class="fal fa-tim"></i> You have 14 projects. 10 not completed.</small>
                </div>

                <ul class="sidebar-list">
                    <li>
                        <a href="#">
                            <div class="small pull-right m-t-xs">9 hours ago</div>
                            <h4>Business valuation</h4>
                            It is a long established fact that a reader will be distracted.

                            <div class="small">Completion with: 22%</div>
                            <div class="progress progress-mini">
                                <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                            </div>
                            <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="small pull-right m-t-xs">9 hours ago</div>
                            <h4>Contract with Company </h4>
                            Many desktop publishing packages and web page editors.

                            <div class="small">Completion with: 48%</div>
                            <div class="progress progress-mini">
                                <div style="width: 48%;" class="progress-bar"></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data () {
    return {
      options: [],
      data_search: [],
      list: [],
      loading: false,
    }
  },
  methods: {
    getData(){
      var me = this;
      axios.get('/ciudad/getSelectCity').then(function(response) {
          me.list = response.data.data;
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
          this.options = this.list.filter(item => {
            return item.name.toLowerCase()
              .indexOf(query.toLowerCase()) > -1;
          });
        }, 200);
      } else {
        this.options = [];
      }
    },
    handleSelect(item) {
      console.log(item);
    }
  },
  mounted() {
    this.getData();
  }
}
</script>

<style lang="css" scoped>
</style>

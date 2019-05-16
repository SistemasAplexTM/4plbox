<template lang="html">
  <div class="">
    <el-select
        v-model="value9"
        clearable
        filterable
        remote
        reserve-keyword
        placeholder="Tracking, Warehouse"
        size="small"
        :remote-method="remoteMethod"
        :loading="loading"
        value-key="id">
      <el-option
        v-for="item in options4"
        :key="item.id"
        :label="item.name"
        :value="item">
        <div class="content-select">
          <span style="">
            <i class="fa fa-user icon"></i> {{ item.consignee }}
          </span>
          <br>
          <span style="color: #8492a6;">
            <i class="fa fa-box-open icon"></i> {{ item.name }} &nbsp;&nbsp;
            <i class="fa fa-balance-scale icon"></i> {{ item.peso }} Lb &nbsp;&nbsp;
            ${{ item.peso }}
          </span>
          <br>
          <span style="color: #8492a6; font-size: 13px">
            <i class="fa fa-truck icon"></i> {{ item.tracking }}
            <br>
            <i class="fa fa-comment-edit icon"></i> {{ item.contenido }}
          </span>
        </div>
      </el-option>
    </el-select>
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
      this.getData();
    },
    methods: {
      getData(){
        var me = this;
        axios.get('/documento/getDataSearchDocument/' + false).then(function(response) {
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
            this.options4 = this.list.filter(item => {
              if(item.name.toLowerCase().indexOf(query.toLowerCase()) > -1){
                return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1;
              }else{
                if(item.tracking !== null){
                  return item.tracking.toLowerCase().indexOf(query.toLowerCase()) > -1;
                }else{
                  if(item.consignee !== null){
                    return item.consignee.toLowerCase().indexOf(query.toLowerCase()) > -1;
                  }
                }
              }
            });
          }, 200);
        } else {
          this.options4 = [];
        }
      }
    }
}
</script>

<style lang="css" scoped>
  .el-select-dropdown__item{
    height: 70px;
  }
  .icon{
    font-size: 11px;
  }
  .content-select{
    padding-top: 7px;
    line-height: 17px;
  }
</style>

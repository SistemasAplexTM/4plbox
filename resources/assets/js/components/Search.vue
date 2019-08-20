<template lang="html">
  <div class="">
    <el-autocomplete
      class="inline-input"
      v-model="datos.name"
      :fetch-suggestions="querySearch"
      :trigger-on-focus="false"
      placeholder="Tracking, Warehouse"
      @select="handleSelect"
      size="small"
    >
      <template slot-scope="{ item }">
        <div class="content-select">
          <div style="">
            <i class="fa fa-user icon"></i> {{ item.consignee }}
          </div>
          <div style="color: #8492a6;">
            <i class="fa fa-box-open icon"></i> {{ item.name }} &nbsp;&nbsp;
            <i class="fa fa-balance-scale icon"></i> {{ item.peso }} Lb &nbsp;&nbsp;
            ${{ item.peso }}
          </div>
          <div style="color: #8492a6; font-size: 13px">
            <div><i class="fa fa-truck icon"></i> {{ item.tracking }}</div>
            <div><i class="fa fa-comment-edit icon"></i> {{ item.contenido }}</div>
          </div>
        </div>
  		</template>
    </el-autocomplete>
  </div>
</template>

<script>
export default {
  data() {
      return {
        datos: {},
        list: [],
        loading: false,
        options: [],
      }
    },
    mounted() {
      //
    },
    methods: {
      querySearch(queryString, cb) {
        var me = this;
        axios.get('/documento/getDataSearchDocument/'+queryString).then(function(response) {
            me.options = response.data.data;
            cb(me.options);
        }).catch(function(error) {
            console.log(error);
            toastr.warning('Error: -' + error);
        });
      },
      handleSelect(item) {
        // this.datos = item;
        // this.$emit('get', item);
      }
    }
}
</script>

<style lang="css">
  .content-select{
    padding-top: 10px;
  }
  .el-autocomplete, .inline-input{
    width: 100%
  }
  .el-autocomplete-suggestion, .el-popper{
    width: max-content!important;
    z-index: 9999!important;
  }
  .icon{
    font-size: 11px;
  }
  .content-select{
    padding-top: 7px;
    line-height: 17px;
  }
  /* .el-select-dropdown__item{
    height: 70px;
  } */
</style>

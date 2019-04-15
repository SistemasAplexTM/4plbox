<template lang="html">
  <div class="">
    <el-select
        v-model="value9"
        filterable
        remote
        reserve-keyword
        placeholder="Tracking, Warehouse"
        size="small"
        :remote-method="remoteMethod"
        :loading="loading">
      <el-option
        v-for="item in options4"
        :key="item.value"
        :label="item.label"
        :value="item.value">
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
      // this.list = this.states.map(item => {
      //   return { value: item, label: item };
      // });
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
            this.options4 = this.list.filter(item => {
              return item.label.toLowerCase()
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

<style lang="css" scoped>

</style>

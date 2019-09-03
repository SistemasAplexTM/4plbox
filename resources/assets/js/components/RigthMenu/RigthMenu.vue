<template>
  <el-drawer
    title="I am the title"
    :visible.sync="drawer"
    :direction="direction">
    <span>Hi, there!</span>
    <form-consignee :table="table" :agency="agency_data" :field_id="field_id"></form-consignee>
  </el-drawer>
</template>
<script>
  export default {
    props:["table", "agency_data", "field_id"],
    data() {
      return {
        direction: 'rtl',
        drawer: false
      };
    },
    methods: {
      handleClose(done) {
        this.$confirm('Are you sure you want to close this?')
          .then(_ => {
            done();
          })
          .catch(_ => {});
      }
    }
  };
</script>

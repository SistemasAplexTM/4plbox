<template>
  <div class>
    <el-autocomplete
      class="inline-input"
      v-model="city.name"
      :fetch-suggestions="querySearch"
      :trigger-on-focus="false"
      :disabled="disabled"
      placeholder="Buscar Ciudad"
      @select="handleSelect"
      size="medium"
    >
      <template slot-scope="{ item }">
        <div>
          <label class="value_item">
            <i class="fal fa-map-marker"></i>
            {{ item.name }}
          </label>
          <div>
            <small>{{ item.deptos }} / {{ item.pais }}</small>
          </div>
        </div>
      </template>
    </el-autocomplete>
  </div>
</template>

<script>
export default {
  props: ["data", "disabled", "selected", "reset"],
  data() {
    return {
      options: [],
      city: {},
      list: [],
      loading: false
    };
  },
  watch: {
    selected: function(value) {
      this.city = { name: value };
    },
    data: function(value) {
      this.list = value;
    },
    reset: function(value) {
      if (value) {
        this.city = {};
        this.city = [];
        this.options = [];
      }
    }
  },
  methods: {
    querySearch(queryString, cb) {
      var me = this;
      axios
        .get("/ciudad/getSelectCity/" + queryString)
        .then(function(response) {
          me.options = response.data.data;
          cb(me.options);
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    handleSelect(item) {
      this.city = item;
      this.$emit("get", item);
    }
  }
};
</script>

<style lang="css" scoped>
.value_item {
  margin: 0;
  height: 15px;
}
.el-autocomplete {
  width: 100% !important;
}
</style>

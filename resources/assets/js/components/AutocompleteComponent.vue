<template>
  <!-- <v-select v-model="selectedj" label="name" :filterable="false" :options="findDatas" @search="onSearchData" placeholder="Buscar">
    	<template slot="option" slot-scope="option">
    		<div v-if="type == 'navbar'">
		        <span class="fal fa-barcode"></span>
		        <span>{{ option.name }}</span>
		        <div><i class="fal fa-cube"></i> {{ option.num_warehouse }} | {{ option.contenido }}</div>
		        <div><i class="fal fa-user"></i> S: {{ option.ship_nomfull }} <i class="fal fa-user-o"></i> C: {{ option.cons_nomfull }}</div>
    		</div>
    		<div v-else>
		        <span>{{ option.name }}</span>
    		</div>
	    </template>
	    <template slot="selected-option" slot-scope="option">
	        <span v-if="type == 'navbar'" class="fal fa-barcode"></span>
	        <span>{{ option.name }}</span>
	    </template>
  </v-select>-->
  <div class>
    <el-autocomplete
      v-model="datos.name"
      class="inline-input"
      clearable
      placeholder="Seleccione"
      :fetch-suggestions="querySearch"
      :trigger-on-focus="false"
      value-key="id"
      size="medium"
      @select="handleSelect"
    >
      <template slot-scope="{ item }">
        <div class="content-select">
          <i class="fal fa-user icon"></i>
          {{ item.name }}
        </div>
      </template>
    </el-autocomplete>
  </div>
</template>

<script>
export default {
  props: ["url", "type", "selection"],
  data() {
    return {
      datos: {},
      options: []
    };
  },
  watch: {
    // selectedj:function(value){
    // 	// this.changeSelect(value);
    // }
  },
  mounted() {
    let me = this;
    setTimeout(function() {
      if (me.selection.id != null) {
        me.datos = me.selection;
      } else {
        me.datos = {};
      }
    }, 1500);
  },
  methods: {
    querySearch(queryString, cb) {
      var me = this;
      axios
        .get("/" + me.url + "/" + queryString + "/" + me.type)
        .then(function(response) {
          me.options = response.data.items;
          cb(me.options);
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    handleSelect(item) {
      this.datos = item;
      if (item) {
        item["type"] = this.type;
      } else {
        item = this.type;
      }
      this.$emit("change-select", item);
    }
    // onSearchData(search, loading) {
    //   loading(true);
    //   this.searchData(loading, search, this, this.url);
    // },
    // searchData: _.debounce((loading, search, vm, url) => {
    //   fetch(
    //     `/${escape(url + '/' + search + '/' + vm.type)}`
    //   ).then(res => {
    //     res.json().then(json => (vm.findDatas = json.items));
    //     loading(false);
    //   });
    // }, 200),
    // changeSelect: function(data) {
    // 	if (data) {
    // 		data['type'] = this.type;
    // 	}else{
    // 		data = this.type;
    // 	}
    // 	this.$emit('change-select', data);
    // }
  }
};
</script>

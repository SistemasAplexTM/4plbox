<template>
    <v-select v-model="selected" label="name" :filterable="false" :options="findDatas" @search="onSearchData" placeholder="Buscar" :on-change="changeSelect">
    	<template slot="option" slot-scope="option">
    		<div v-if="type == 'navbar'">
		        <span class="fa fa-barcode"></span>
		        <span>{{ option.name }}</span>
		        <div><i class="fa fa-cube"></i> {{ option.num_warehouse }} | {{ option.contenido }}</div>
		        <div><i class="fa fa-user"></i> S: {{ option.ship_nomfull }} <i class="fa fa-user-o"></i> C: {{ option.cons_nomfull }}</div>
    		</div>
    		<div v-else>
		        <span>{{ option.name }}</span>
    		</div>
	    </template>
	    <template slot="selected-option" slot-scope="option">
	        <span v-if="type == 'navbar'" class="fa fa-barcode"></span>
	        <span>{{ option.name }}</span>
	    </template>
    </v-select>
</template>

<script>
    export default {
        data () {
	        return {
	            findDatas: []
		    }
		},
		props: ["url", "type", "selected"],
        methods:{
            onSearchData(search, loading) {
		      loading(true);
		      this.searchData(loading, search, this, this.url);
		    },
		    searchData: _.debounce((loading, search, vm, url) => {
		      fetch(
		        `/${escape(url + '/' + search + '/' + vm.type)}`
		      ).then(res => {
		        res.json().then(json => (vm.findDatas = json.items));
		        loading(false);
		      });
		    }, 200),
		    changeSelect: function(data) {
		    	if (data) {
		    		data['type'] = this.type;
		    	}else{
		    		data = this.type;
		    	}
	    		this.$emit('change-select', data);
	    	}
	    }
    }
</script>
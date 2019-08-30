<template>
	<div id="right-sidebar" :class="{'sidebar-open' : show}">

		<!-- <input type="hidden" id="urlGetStatus" value="/4plbox/public/statusReport/getStatusByIdDetalle/">
		<input type="hidden" id="urlHistoryConsignee" value="/4plbox/public/documento/getHistoryConsignee/">
		<input type="hidden" id="urlHistoryDocument" value="/4plbox/public/documento/getHistoryDocument/"> -->

		<!-- SERVER -->
		
		<input type="hidden" id="urlGetStatus" value="/statusReport/getStatusByIdDetalle/">
		<input type="hidden" id="urlHistoryConsignee" value="/documento/getHistoryConsignee/">
		<input type="hidden" id="urlHistoryDocument" value="/documento/getHistoryDocument/">

	    <div class="sidebar-container">
	        <ul class="nav nav-tabs navs-3">
	            <li :class="{'active' : !showDataTab}" @click="showDataTab=false" style="width: 55%;font-size:20px;" id="tab-1"><a data-toggle="tab" href="#tab-1">
	                    Consulta tracking
	                </a></li>
	            <li :class="{'active' : showDataTab}" @click="showDataTab=true" style="width: 45%;font-size:20px;" id="tab-2"><a data-toggle="tab" href="#tab-3">
	                    <i class="fal fa-commenting"></i>
	                </a></li>
	        </ul>
	        <div class="tab-content" style="font-size:18px">
	        	<!-- TAB 1 -->
	            <div id="tab-1" class="tab-pane" :class="{'active' : !showDataTab}">
	                <div class="sidebar-title">
	                	<template v-if="Object.keys(datos).length !== 0">
		                    <h3><i class="fal fa-barcode"></i> {{ datos.name }}</h3>
		                    <div><a href="#" @click="showData('warehouse')"><i class="fal fa-cube"></i> <strong>{{ datos.num_warehouse }}</strong></a></div>
		                    <div><i class="fal fa-tag"></i> {{ datos.contenido }}</div>
		                    <div style="font-size:16px"><i class="fal fa-user-o"></i> {{ datos.ship_nomfull }}</div>
		                    <div><a href="#" @click="showData('consignee')"><i class="fal fa-user"></i> <strong>{{ datos.cons_nomfull }}</strong></a></div>
	                	</template>
	                </div>
	                <div style="font-size:20px;">
	                    <div class="sidebar-message"  v-for="statusData in statusDatas">
	                        <a href="#">
	                            <div class="pull-left text-center" v-bind:style="{ color: statusData.status_color }">
	                                <i class="fal fa-clock-o fa-2x"></i>
	                            </div>
	                            <div class="media-body" style="font-size: 16px;">
	                            	<div class="m-t-xs">
	                                    <span><strong>{{ statusData.status_name }}</strong></span>
	                                </div>
	                                {{ statusData.observacion }}
	                                <br>
	                                <small class="text-muted">{{ statusData.fecha_status }}</small>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	            </div>
				<!-- TAB 2 -->
	            <div id="tab-2" class="tab-pane" :class="{'active' : showDataTab}">
	                <div class="sidebar-title" v-if="Object.keys(datosMesage).length !== 0">
	                    <h3><strong><i class="fa" :class="datosMesage.icon"></i> {{ datosMesage.title }}</strong></h3>
	                    <small>{{ datosMesage.content }}</small>
	                </div>
	                <ul class="sidebar-list">
	                    <li v-for="consigneeData in consigneeDatas">
	                        <a href="#">
	                            <div class="small pull-right m-t-xs">{{ consigneeData.created_at }}</div>
	                            <h4>{{ consigneeData.num_warehouse }} <small>Peso: {{ consigneeData.peso }} Lbs</small></h4>
	                            <div><i class="fal fa-user"></i> Shipper: {{ consigneeData.ship_nomfull }}</div>
	                            <div><i class="fal fa-barcode"></i> Trackings: {{ consigneeData.tracking }}</div>
								Contenido: {{ consigneeData.contenido }}
	                        </a>
	                    </li>
	                </ul>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
    export default {
    	props:[
    		'object'
    	],
        data () {
	        return {
	        	datos:[],
	        	datosMesage:[],
	          	statusDatas: [],
	          	consigneeDatas: [],
	          	warehouseDatas: [],
	          	show: false,
	          	showDataTab: false 
		    }
		},
		watch:{
			object:function(newObject){
                this.show = false;
				this.showSidebar(newObject);
			}
		},
		methods:{
			showData: function(data){
                this.showDataTab = true;
                if(data === 'consignee'){
                	this.datosMesage = {
                		'title': this.datos.cons_nomfull,
                		'content':null,
                		'icon':'fa-user',
                	}
                	this.getDataConsignee(this.datos.consignee_id);
                }else{
                	this.datosMesage = {
                		'title': this.datos.num_warehouse,
                		'content':this.datos.contenido,
                		'icon':'fa-cube',
                	}
                	this.getDataWarehouse(this.datos.num_warehouse);
                }
            },
			showSidebar: function(data){
                if(data != null && data != 'navbar'){
                	this.show = true;
                	this.datos = data;
                	this.getDataStatus(data.id);
                }
            },
			getDataStatus: function(id){
		    	var me = this;
				axios.get($('#urlGetStatus').val() + id).then(function(response) {
	                if (response.data['code'] == 200) {
	                	if(response.data.data != null){
	                    	me.statusDatas = response.data.data;
	                	}else{
	                		me.statusDatas = [];
	                	}
	                } else {
	                    toastr.warning(response.data['error']);
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                console.log(error);
	                if (error.response.status === 422) {
	                    me.formErrors = error.response.data; //guardo los errores
	                    me.listErrors = me.formErrors.errors; //genero lista de errores
	                }
	                $.each(me.formErrors.errors, function(key, value) {
	                    $('.result-' + key).html(value);
	                });
	                toastr.error("Error en transacción.", {
	                    timeOut: 50000
	                });
	            });
		    },
		    getDataConsignee: function(id){
		    	var me = this;
				axios.get($('#urlHistoryConsignee').val() + id).then(function(response) {
	                if (response.data['code'] == 200) {
	                	if(response.data.data != null){
	                    	me.consigneeDatas = response.data.data;
	                	}else{
	                		me.consigneeDatas = [];
	                	}
	                } else {
	                    toastr.warning(response.data['error']);
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                console.log(error);
	                if (error.response.status === 422) {
	                    me.formErrors = error.response.data; //guardo los errores
	                    me.listErrors = me.formErrors.errors; //genero lista de errores
	                }
	                $.each(me.formErrors.errors, function(key, value) {
	                    $('.result-' + key).html(value);
	                });
	                toastr.error("Error en transacción.", {
	                    timeOut: 50000
	                });
	            });
		    },
		    getDataWarehouse: function(warehouse){
		    	var me = this;
				axios.get($('#urlHistoryDocument').val() + warehouse).then(function(response) {
	                if (response.data['code'] == 200) {
	                	if(response.data.data != null){
	                    	me.warehouseDatas = response.data.data;
	                	}else{
	                		me.warehouseDatas = [];
	                	}
	                    console.log(me.warehouseDatas);
	                } else {
	                    toastr.warning(response.data['error']);
	                    toastr.options.closeButton = true;
	                }
	            }).catch(function(error) {
	                console.log(error);
	                if (error.response.status === 422) {
	                    me.formErrors = error.response.data; //guardo los errores
	                    me.listErrors = me.formErrors.errors; //genero lista de errores
	                }
	                $.each(me.formErrors.errors, function(key, value) {
	                    $('.result-' + key).html(value);
	                });
	                toastr.error("Error en transacción.", {
	                    timeOut: 50000
	                });
	            });
		    }
		}
	};
</script>

<style>
	#right-sidebar{
	    overflow: scroll;width: 25% !important;
	}
	#right-sidebar.sidebar-open.sidebar-top, #right-sidebar.sidebar-open{
	    width: 25% !important;
	}
	#right-sidebar {
	    background-color: #fff;
	    border-left: 1px solid #e7eaec;
	    border-top: 1px solid #e7eaec;
	    overflow: hidden;
	    position: fixed;
	    width: 25% !important;
	    z-index: 1009;
	    bottom: 0;
	    right: -25%;
	}
</style>
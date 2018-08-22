@extends('layouts.app')
@section('title', 'Consulta')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Consulta</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Consultar datos (Shipper - Consignee)</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="consulta">
        <form id="formconsignee" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Consultar datos (Shipper - Consignee)</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="consignee_id" class="control-label gcore-label-top">Desde-Hasta:</label>
                                    <div class="input-group">
	                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	                                    <input class="form-control rango_fecha" type="text" id="fechas" name="fechas" value="" placeholder="mm/dd/aaaa - mm/dd/aaaa" autocomplete="off" />
	                                </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="shipper_id" class="control-label gcore-label-top">Shipper:</label>
                                    <v-select name="shipper" v-model="shipper_id" label="name" :filterable="false" :options="shippers" @search="onSearchShippers" placeholder="Shipper"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="consignee_id" class="control-label gcore-label-top">Consignee:</label>
                                    <v-select name="consignee" v-model="consignee_id" label="name" :filterable="false" :options="consignees" @search="onSearchConsignees" placeholder="Consignee"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="status_id" class="control-label gcore-label-top">Estado:</label>
                                    <v-select name="status" v-model="status_id" label="descripcion" :options="status" placeholder="Estado"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label class="control-label gcore-label-top">&nbsp;</label>
                                    <a class="btn btn-primary" @click="search()"><i class="fa fa-search"></i> Buscar</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
		                        <div class="table-responsive">
		                            <table id="tbl-consulta" class="table table-striped table-hover table-bordered" style="width: 100%;">
		                                <thead>
		                                    <tr>
		                                        <th>Recibo</th>
		                                        <th>Estado</th> 
		                                        <th>Fecha</th>
		                                        <th>Shipper</th>
		                                        <th>Consignee</th>
		                                        <th># Cajas</th>
		                                        <th>Peso</th>
		                                        <th>Volumen</th>
		                                    </tr>
		                                </thead>
		                            </table>
		                        </div>  
		                    </div>  
		                </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/consulta.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', 'Consulta')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.consult')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.consult_data_shipper-consignee')</strong>
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
                        <h5>@lang('general.consult_data_shipper-consignee')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="consignee_id" class="control-label gcore-label-top">@lang('general.since_until'):</label>
                                    <div class="input-group">
	                                    <span class="input-group-addon"><i class="fal fa-calendar"></i></span>
	                                    <input class="form-control rango_fecha" type="text" id="fechas" name="fechas" value="" placeholder="mm/dd/aaaa - mm/dd/aaaa" autocomplete="off" />
	                                </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="shipper_id" class="control-label gcore-label-top">@lang('general.shipper'):</label>
                                    <v-select name="shipper" v-model="shipper_id" label="name" :filterable="false" :options="shippers" @search="onSearchShippers" placeholder="@lang('general.shipper')"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="consignee_id" class="control-label gcore-label-top">@lang('general.consignee'):</label>
                                    <v-select name="consignee" v-model="consignee_id" label="name" :filterable="false" :options="consignees" @search="onSearchConsignees" placeholder="@lang('general.consignee')"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="status_id" class="control-label gcore-label-top">@lang('general.state'):</label>
                                    <v-select name="status" v-model="status_id" label="descripcion" :options="status" placeholder="@lang('general.state')"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label class="control-label gcore-label-top">&nbsp;</label>
                                    <a class="btn btn-primary" @click="search()"><i class="fal fa-search"></i>@lang('general.search')</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
		                        <div class="table-responsive">
		                            <table id="tbl-consulta" class="table table-striped table-hover table-bordered" style="width: 100%;">
		                                <thead>
		                                    <tr>
		                                        <th>@lang('general.receipt')</th>
		                                        <th>@lang('general.state')</th> 
		                                        <th>@lang('general.date')</th>
		                                        <th>@lang('general.shipper')</th>
		                                        <th>@lang('general.consignee')</th>
		                                        <th>#@lang('general.boxes')</th>
		                                        <th>@lang('general.weigh')</th>
		                                        <th>@lang('general.volume')</th>
		                                    </tr>
		                                </thead>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right;" colspan="5">@lang('general.totals'):</th>
                                                <th id="Tcajas"></th>
                                                <th id="Tpeso"></th>
                                                <th id="Tvolumen"></th>
                                            </tr>
                                        </tfoot>
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

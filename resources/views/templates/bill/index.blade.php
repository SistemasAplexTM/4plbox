@extends('layouts.app')
@section('title', 'Bill of lading')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.bill_of_lading')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.bill_of_lading')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 120px;
    }
</style>
<div class="row" id="bill">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    @lang('general.bill_of_lading')
                </h5>
                <div class="ibox-tools">
                    <a href="{{ url('bill/create') }}" data-toggle="tooltip" title="Crear bill of lading" class="btn btn-primary" >Nuevo <i class="fa fa-plus" style="font-size: small;"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-bill" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('general.bill_of_lading')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.point_of_origin')</th>
                                <th>@lang('general.loading_dock')</th>
                                <th>@lang('general.foreign_port_of_discharge')</th>
                                <th>@lang('general.weight') Kl</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                        </thead>
                    </table>
                </div>             
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/bill/index.js') }}"></script>
@endsection
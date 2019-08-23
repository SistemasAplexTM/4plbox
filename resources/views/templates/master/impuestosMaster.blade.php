@extends('layouts.app')
@section('title', 'Guía master')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Impuestos Master</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('master.home')</a>
            </li>
            <li class="active">
                <strong>Impuestos Master</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">

</style>
<div class="row" id="master_hijas">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    @lang('master.registered_guides')
                </h5>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-master" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Master AWB</th>
                                <th>@lang('master.airline')</th>
                                <th>@lang('master.date')</th>
                                <th>@lang('master.rate')</th>
                                <th>@lang('master.weight') Lb</th>
                                <th>@lang('master.weight') Kl</th>
                                <th>@lang('master.consignee')</th>
                                <th>@lang('master.destination')</th>
                                <th>@lang('master.manifest')</th>
                                <th>@lang('master.actions')</th>
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
<script src="{{ asset('js/templates/master/master_impuestos.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', 'Shipper')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.shipper')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.shipper')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row" id="shipper">
    <form id="formShipper" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.shipper_registration') </h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <form-csc :payload="payload" @updatetable="updateTable"
                        @cancel="shipper_id = null; payload.field_id = null"></form-csc>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.shipper')</h5>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-shipper" class="table table-striped table-hover table-bordered"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.phone')</th>
                                    <th>@lang('general.city')</th>
                                    <th>Zip</th>
                                    <th>@lang('general.agency')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.phone')</th>
                                    <th>@lang('general.city')</th>
                                    <th>Zip</th>
                                    <th>@lang('general.agency')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/shipper.js') }}"></script>
@endsection
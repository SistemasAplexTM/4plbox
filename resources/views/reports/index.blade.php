@extends('layouts.app')
@section('title', trans('layouts.reports'))
@section('breadcrumb')
{{-- bread crumbs --}}
<style type="text/css">
    .dataTables_wrapper{
        padding-bottom: 200px;
        padding-right: 30px;
    }
    .actions_btn{
        text-align: center;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('layouts.reports')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('documents.home')</a>
            </li>
            <li class="active">
                <strong>@lang('layouts.reports')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="reports">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('layouts.reports')</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="row" style="">
                            <div class="col-lg-2" >
                                <div class="col-lg-12">
                                    <a href="#" class="list-group-item active" style="text-align: center; background-color:#2196f3;border-color: #2196f3 ">
                                      @lang('documents.types_of_documents')
                                    </a>
                                    <div class="btn-group-vertical" id="listaDocumentos" style="width: 100%;">
                                        <button type="button" id="btn1" onclick="" class="btn btn-default btn-block" style="text-align:left;">
                                          <i class="fal fa-cubes"></i> Carga
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table id="tbl-report" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th><i class="fal fa-calendar"></i> @lang('documents.date')</th>
                                                    <th><i class="fal fa-file"></i> #@lang('documents.documents')</th>
                                                    <th><i class="fal fa-user"></i> @lang('documents.client_consignee')</th>
                                                    <th><i class="fal fa-cubes"></i> @lang('documents.consolidated')</th>
                                                    <th><i class="fal fa-plane"></i> @lang('master.master_guide')</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/reports/index.js') }}"></script>
@endsection

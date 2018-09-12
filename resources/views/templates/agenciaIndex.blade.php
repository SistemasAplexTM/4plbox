@extends('layouts.app')
@section('title', 'Agencia')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.agencies')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.agencies')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="agencia">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.agencies')</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('agencia.create') }}">
                        <button type="button" class="btn btn-primary btn-sm">
                            <span class="fa fa-plus" aria-hidden="true"></span> @lang('general.new')
                        </button>
                    </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-agencia" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.responsable')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.state')</th>
                                    <th>@lang('general.country')</th>
                                    <th>@lang('general.phone')</th>
                                    <th>Logo</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.responsable')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.state')</th>
                                    <th>@lang('general.country')</th>
                                    <th>@lang('general.phone')</th>
                                    <th>Logo</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>             
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/agencia.js') }}"></script>
@endsection
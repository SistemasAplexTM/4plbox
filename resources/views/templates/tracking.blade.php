@extends('layouts.app')
@section('title', 'Tracking')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.tracking')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.tracking')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    .v-select{
        background-color:#FFFFFF;
    }
    .v-select .dropdown li {
      border-bottom: 1px solid rgba(112, 128, 144, 0.1);
    }

    .v-select .dropdown li:last-child {
      border-bottom: none;
    }

    .v-select .dropdown li a {
      padding: 10px 20px;
      width: 100%;
      font-size: 1.25em;
      color: #3c3c3c;
    }

    .v-select .dropdown-menu .active > a {
      color: #fff;
    }
    .dropdown-toggle>input[type="search"] {
    width: 100px !important;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 100% !important;
    }
</style>
    <div class="row" id="tracking">
        <form id="formtracking" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.track_record')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" :class="{ 'has-error': errors.has('tracking') }">
                                    <div class="col-sm-4">
                                        <label for="tracking" class="control-label gcore-label-top">@lang('general.tracking'):</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="tracking" placeholder="@lang('general.number_of_tracking')" type="text" v-model="tracking" v-validate.disable="'required|unique'" v-on:keyup.enter="searchTracking()">
                                            <small class="help-block error" v-show="errors.has('tracking')">
                                                @{{ errors.first('tracking') }}
                                            </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(env('APP_CLIENT') != 'worldcargo')
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('contenido') }">
                                        <div class="col-sm-4">
                                            <label for="contenido" class="control-label gcore-label-top">@lang('general.content'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="contenido" placeholder="@lang('general.content')" type="text" v-model="contenido">
                                            <small class="help-block error" v-show="errors.has('contenido')">
                                                @{{ errors.first('contenido') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">           
                            <div class="col-lg-12">
                                <div class="form-group" :class="{ 'has-error': errors.has('consignee_id') }">
                                    <div class="col-sm-4">
                                        <label for="consignee_id" class="control-label gcore-label-top">@lang('general.client'):</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <v-select name="consignee_id" :options="consignees" placeholder="@lang('general.client')" label="name" v-model="consignee_id">
                                        </v-select>
                                         <small class="help-block error" v-show="errors.has('consignee_id')">
                                            @{{ errors.first('consignee_id') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" v-if="instruccion || email">           
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="alert alert-info alert-dismissible" role="alert" id="msn_sendmail" style="margin-bottom: 0px;">
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="icon_close"><span aria-hidden="true">&times;</span></button>
                                          <strong>@lang('genral.instruction'):</strong> @{{ instruccion }}<br>
                                          <strong>@lang('general.email'):</strong> @{{ email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
            
                    <div class="ibox-title">
                        <h5>@lang('general.registered_tracking')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-tracking" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>@lang('general.date')</th>
                                        <th>@lang('general.client')</th>
                                        <th>@lang('general.tracking')</th>
                                        <th>Warehouse</th>
                                        {{-- <th>@lang('general.content')</th> --}}
                                        <th>@lang('general.office')</th>
                                        <th>@lang('general.actions')</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>@lang('general.date')</th>
                                        <th>@lang('general.client')</th>
                                        <th>@lang('general.tracking')</th>
                                        <th>Warehouse</th>
                                        {{-- <th>@lang('general.content')</th> --}}
                                        <th>@lang('general.office')</th>
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
<script src="{{ asset('js/templates/tracking.js') }}"></script>
@endsection
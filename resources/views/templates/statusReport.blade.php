@extends('layouts.app')
@section('title', 'Reportar estatus')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            @lang('general.report_status')
        </h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">
                   @lang('general.home')
                </a>
            </li>
            <li class="active">
                <strong>
                    @lang('general.report_status')
                </strong>
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
    .dropdown-toggle>input[type="search"] {
        width: 100px !important;
    }
    .v-select .dropdown-menu .active > a {
      color: #fff;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 100% !important;
    }
</style>
<div class="row" id="statusReport">
    <form action="" class="form-horizontal" enctype="multipart/form-data" id="formstatusreport" method="post" role="form">
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                       @lang('general.register_report')
                    </h5>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                <div :class="{'has-error': listErrors.status_id}" class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label gcore-label-top" for="status_id">
                                           @lang('general.status'):
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <v-select :on-change="setTransport" :options="status" @click="deleteError('status_id')" @focus="deleteError('status_id')" label="name" name="status_id" placeholder=" @lang('general.status')" v-model="status_id">
                                        </v-select>
                                        <small class="help-block result-status_id" id="msn1" v-show="listErrors.status_id">
                                              @lang('general.obligatory_field')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="transport">
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                <div :class="{'has-error': listErrors.transportadora}" class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label gcore-label-top" for="transportadora">
                                            @lang('general.conveyor'):
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input @click="deleteError('transportadora')" @focus="deleteError('transportadora')" class="form-control" id="transportadora" name="transportadora" placeholder=" @lang('general.conveyor')" style="" type="text" v-model="transportadora" value=""/>
                                        <small class="help-block result-transportadora" id="msn1" v-show="listErrors.transportadora">
                                            @lang('general.obligatory_field')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                <div :class="{'has-error': listErrors.num_transportadora}" class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label gcore-label-top" for="num_transportadora">
                                          @lang('general.conveyor_number'):
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input @click="deleteError('num_transportadora')" @focus="deleteError('num_transportadora')" class="form-control" id="num_transportadora" name="num_transportadora" placeholder="@lang('general.conveyor_number')" style="" type="text" v-model="num_transportadora" value=""/>
                                        <small class="help-block result-num_transportadora" id="msn1" v-show="listErrors.num_transportadora">
                                            @lang('general.obligatory_field')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                <div :class="{'has-error': listErrors.codigo}" class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label gcore-label-top" for="codigo">
                                            # @lang('general.guide')/ WRH:
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input @click="deleteError('codigo')" @focus="deleteError('codigo')" class="form-control" id="codigo" name="codigo" placeholder="" style="" type="text" v-model="codigo" value=""/>
                                        <small class="help-block result-codigo" id="msn1" v-show="listErrors.codigo">
                                            @lang('general.obligatory_field')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label gcore-label-top" for="observacion">
                                          @lang('general.observation'):
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="observacion" name="observacion" v-model="observacion">
                                        </textarea>
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
    </form>
    <div class="col-lg-7">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                 @lang('general.list_of_status')
                </h5>
                <div class="ibox-tools">
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="tbl-statusReport" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>
                                 @lang('general.date')
                                </th>
                                <th>
                                    @lang('general.guide') / WRH
                                </th>
                                <th>
                                  @lang('general.consolidated')
                                </th>
                                <th>
                                    @lang('general.observation')
                                </th>
                                <th>
                                    @lang('general.status')
                                </th>
                                <th>
                                   @lang('general.user')
                                </th>
                                <th>
                                   @lang('general.actions')
                                </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>
                                    @lang('general.date')
                                   </th>
                                   <th>
                                       @lang('general.guide') / WRH
                                   </th>
                                   <th>
                                     @lang('general.consolidated')
                                   </th>
                                   <th>
                                       @lang('general.observation')
                                   </th>
                                   <th>
                                       @lang('general.status')
                                   </th>
                                   <th>
                                      @lang('general.user')
                                   </th>
                                   <th>
                                      @lang('general.actions')
                                   </th>
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
<script src="{{ asset('js/templates/statusReport.js') }}">
</script>
@endsection

@extends('layouts.app')
@section('title', 'Status')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.status')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.status')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="status">
        <form id="formstatuss" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.state_registration')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.descripcion}">
                                        <div class="col-sm-5">
                                            <label for="descripcion" class="control-label gcore-label-top">@lang('general.description'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="descripcion" name="descripcion" id="descripcion" value="" placeholder="@lang('general.description')" class="form-control" type="text" style="" @click="deleteError('descripcion')" @focus="deleteError('descripcion')"/>
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.color}">
                                        <div class="col-sm-5">
                                            <label for="color" class="control-label gcore-label-top">Color:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="color" name="color" id="color" value="" title="Color"  class="form-control" type="color" style="" @click="deleteError('color')" @focus="deleteError('color')"/>
                                            <small id="msn1" class="help-block result-color" v-show="listErrors.color">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.email}">
                                        <div class="col-sm-5">
                                            <label for="email" class="control-label gcore-label-top">@lang('general.send_email'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="i-checks">
                                              <label><input type="radio" value="1" id="email_s" name="email" @click="showEmailTemplate"> @lang('general.yes') </label>
                                              <label><input type="radio" value="0" id="email_n" name="email" @click="showEmailTemplate" checked=""> @lang('general.not')</label>
                                            </div>
                                            <small id="msn1" class="help-block result-email" v-show="listErrors.email">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.email_plantilla_id}">
                                        <div class="col-sm-5">
                                            <label for="email_plantilla_id" class="control-label gcore-label-top">@lang('general.mail_template'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <v-select :options="plantillas" name="email_plantilla_id" v-model="email_plantilla_id" label="name" placeholder="@lang('general.mail_template')">
                                                <template slot="option" slot-scope="option">
                                                    <span class="fa fa-envelope"></span>
                                                    <label style="font-size: 15px;"> @{{ option.name }}</label>
                                                    <div>@{{ option.descripcion_plantilla }}</div>
                                                </template>
                                            </v-select>
                                            <small id="msn1" class="help-block result-email" v-show="listErrors.email_plantilla_id">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.view_client}">
                                        <div class="col-sm-5">
                                            <label for="view_client" class="control-label gcore-label-top">@lang('general.view_client'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                          <div class="i-checks">
                                            <label><input type="radio" value="1" id="view_client_s" name="view_client"> @lang('general.yes') </label>
                                            <label><input type="radio" value="0" id="view_client_n" name="view_client" checked=""> @lang('general.not')</label>
                                          </div>
                                            <small id="msn1" class="help-block result-view_client" v-show="listErrors.view_client">@lang('general.obligatory_field')</small>
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
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.status')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-status" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.description')</th>
                                    <th>Color</th>
                                    <th>@lang('general.send_email')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.description')</th>
                                    <th>Color</th>
                                    <th>@lang('general.send_email')</th>
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
<script src="{{ asset('js/templates/status.js') }}"></script>
@endsection

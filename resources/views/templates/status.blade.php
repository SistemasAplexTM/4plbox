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
  <style>
    .help-block{
      color: #ed5565;
    }
  </style>
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
                                        <label for="descripcion" class="control-label gcore-label-top">@lang('general.name'):</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input v-model="descripcion" name="descripcion" id="descripcion" v-validate.disable="'required'" placeholder="@lang('general.name')" class="form-control" type="text"/>
                                        <small class="help-block error" v-show="errors.has('descripcion')">
                                            @{{ errors.first('descripcion') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': listErrors.descripcion}">
                                    <div class="col-sm-5">
                                        <label for="descripcion_general" class="control-label gcore-label-top">@lang('general.description'):</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input v-model="descripcion_general" name="descripcion_general" id="descripcion_general" v-validate.disable="'required'" placeholder="@lang('general.description')" class="form-control" type="text" />
                                        <small class="help-block error" v-show="errors.has('descripcion_general')">
                                            @{{ errors.first('descripcion_general') }}
                                        </small>
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
                            <transition name="fade">
                                <div class="col-lg-12" v-show="showTemplate">
                                    <div class="form-group" :class="{'has-error': listErrors.email_plantilla_id}">
                                        <div class="col-sm-5">
                                            <label for="email_plantilla_id" class="control-label gcore-label-top">@lang('general.mail_template'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <el-select v-model="email_plantilla_id" name="email_plantilla_id" filterable placeholder="@lang('general.mail_template')" size="medium">
                                              <el-option
                                                v-for="item in plantillas"
                                                :key="item.id"
                                                :label="item.name"
                                                :value="item.id">
                                                <div style=""><i class="fal fa-envelope"></i> @{{ item.name }}</div>
                                              </el-option>
                                            </el-select>
                                            <small id="msn1" class="help-block result-email" v-show="listErrors.email_plantilla_id">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                            </transition>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-sm-5">
                                            <label for="icon" class="control-label gcore-label-top">@lang('general.icon'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="i-checks">
                                              <el-select
                                                v-model="value9"
                                                size="medium"
                                                filterable
                                                placeholder="Buscar Icono"
                                                loading-text="Cargando..."
                                                no-data-text="No hay datos"
                                                no-match-text="No coinciden datos"
                                                value-key="value"
                                                @change= "setIconPrefix">
                                                <el-option
                                                  v-for="item in list"
                                                  :key="item.value"
                                                  :label="item.label"
                                                  :value="item">
                                                  <span style="float: left;"><i :class="item.value" style="font-size: 25px;"></i></span>
                                                  <span style="float: right; color: #8492a6; font-size: 13px">@{{ item.label }}</span>
                                                </el-option>
                                                <i slot="prefix" :class="icon_selected" style="margin-top: 8px;font-size: 20px;"></i>
                                              </el-select>
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
                                    <th>@lang('general.name')</th>
                                    <th>Color</th>
                                    <th>@lang('general.send_email')</th>
                                    <th>@lang('general.view_client')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
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

@extends('layouts.app')
@section('title', 'Transportadoras locales')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transportadoras locales</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>Transportadoras locales</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="transportadoras_locales">
        <form enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      <h5>Registro de transportadoras locales</h5>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.localizacion_id}">
                              <div class="col-sm-3">
                                <label for="localizacion_id" class="control-label gcore-label-top">País:<samp id="require">*</samp></label>
                              </div>
                              <div class="col-sm-9">
                                <el-select v-model="pais_id" placeholder="País" name="pais_id" filterable>
                                  <el-option
                                    v-for="item in paises"
                                    :key="item.id"
                                    :label="item.descripcion"
                                    :value="item.id">
                                  </el-option>
                                </el-select>
                                <small id="msn1" class="help-block result-localizacion_id" v-show="listErrors.localizacion_id"></small>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                <div class="col-sm-3">
                                    <label for="nombre" class="control-label gcore-label-top">Nombre:<samp id="require">*</samp></label>
                                </div>
                                <div class="col-sm-9">
                                    <input v-model="nombre" name="nombre" id="nombre" placeholder="Nombre" class="form-control" type="text" style="" @click="deleteError('nombre')" />
                                    <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.url_rastreo}">
                              <div class="col-sm-3">
                                  <label for="url_rastreo" class="control-label gcore-label-top">URL Rastreo:<samp id="require">*</samp></label>
                              </div>
                              <div class="col-sm-9">
                                  <input v-model="url_rastreo" name="url_rastreo" id="url_rastreo" placeholder="URL Rastreo" class="form-control" type="text" style="" @click="deleteError('url_rastreo')" />
                                  <small id="msn1" class="help-block result-url_rastreo" v-show="listErrors.url_rastreo"></small>
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
                        <h5>Transportadoras locales</h5>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-transportadoras_locales" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                      <th>@lang('general.name')</th>
                                      <th>URL Rastreo</th>
                                      <th>Pais</th>
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
<script src="{{ asset('js/templates/transportadoras_locales.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', ucwords($type))
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ ucwords($type) }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>{{ $type }}</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="transport">
        <form id="formaerolineas" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <input v-model="tipo" type="text" id="tipo" name="tipo[]" value="{{ $type }}" hidden="">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.register_of'){{ $type }}</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.localizacion_id}">
                                        <div class="col-sm-4">
                                            <label for="localizacion_id" class="control-label gcore-label-top">@lang('general.city'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="localizacion_id_input" value="">
                                            <select v-model="localizacion_id" name="localizacion_id" id="localizacion_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('localizacion_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-localizacion_id" v-show="listErrors.localizacion_id"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="@lang('general.name')" class="form-control" type="text" style="" @click="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.direccion}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">@lang('general.address'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="direccion" name="direccion" id="direccion" value="" placeholder="@lang('general.address')" class="form-control" type="text" style="" @click="deleteError('direccion')" />
                                            <small id="msn1" class="help-block result-direccion" v-show="listErrors.direccion"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.zip}">
                                        <div class="col-sm-4">
                                            <label for="zip" class="control-label gcore-label-top">@lang('general.code_zip'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="zip" name="zip" id="zip" value="" placeholder="@lang('general.code_zip')" class="form-control" type="text" style="" @click="deleteError('zip')" />
                                            <small id="msn1" class="help-block result-zip" v-show="listErrors.zip"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.codigo}">
                                        <div class="col-sm-4">
                                            <label for="codigo" class="control-label gcore-label-top">@lang('general.code'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="codigo" name="codigo" id="codigo" placeholder="@lang('general.code')" class="form-control" type="text" maxlength="5" @click="deleteError('codigo')" />
                                            <small id="msn1" class="help-block result-codigo" v-show="listErrors.codigo"></small>
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
                    <h5><span style="text-transform: capitalize;">{{ $type }}</span></h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-aerolineas" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.state')</th>
                                    <th>@lang('general.country')</th>
                                    <th>@lang('general.zip_code')</th>
                                    <th>@lang('general.code')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.state')</th>
                                    <th>@lang('general.country')</th>
                                    <th>@lang('general.zip_code')</th>
                                    <th>@lang('general.code')</th>
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
<script src="{{ asset('js/templates/transport.js') }}"></script>
@endsection

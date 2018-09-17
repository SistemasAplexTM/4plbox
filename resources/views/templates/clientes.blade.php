@extends('layouts.app')
@section('title', 'Clientes')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.customers')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.customers')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="clientes">
        <form id="formclientes" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.customer_registration')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.names'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="@lang('general.names')" class="form-control" type="text" style="" @click="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.direccion}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">@lang('general.address'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="direccion" name="direccion" id="direccion" value="" placeholder="@lang('general.address')" class="form-control" type="text" style="" @click="deleteError('direccion')" />
                                            <small id="msn1" class="help-block result-direccion" v-show="listErrors.direccion"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.telefono}">
                                        <div class="col-sm-4">
                                            <label for="telefono" class="control-label gcore-label-top">@lang('general.phone'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="telefono" name="telefono" id="telefono" value="" placeholder="@lang('general.phone')" class="form-control" type="text" style="" @click="deleteError('telefono')" />
                                            <small id="msn1" class="help-block result-telefono" v-show="listErrors.telefono"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.correo}">
                                        <div class="col-sm-4">
                                            <label for="correo" class="control-label gcore-label-top">@lang('general.email')</label>
                                        </div>
                                        <div class="col-sm-8"  :class="{ 'has-error': errors.has('correo') }">
                                            <input v-model="correo" name="correo" id="correo" value="" placeholder="@lang('general.email')" class="form-control" type="text" style="" @click="deleteError('correo')" v-validate.disable="'unique'" />
                                            <small id="msn1" class="help-block result-correo" v-show="listErrors.correo"></small>
                                            <small class="help-block has-error" :class="{ 'small': errors.has('correo') }">@{{ errors.first('correo') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.localizacion_id}">
                                        <div class="col-sm-4">
                                            <label for="localizacion_id" class="control-label gcore-label-top">@lang('general.city'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="localizacion_id_input" value="">
                                            <input type="hidden" id="depto" value="">
                                            <input type="hidden" id="pais" value="">
                                            <select v-model="localizacion_id" name="localizacion_id" id="localizacion_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('localizacion_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-localizacion_id" v-show="listErrors.localizacion_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.zona}">
                                        <div class="col-sm-4">
                                            <label for="zona" class="control-label gcore-label-top">@lang('general.zone'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="zona" name="zona" id="zona" value="" placeholder="@lang('general.zone')" class="form-control" type="text" style="" @click="deleteError('zona')" />
                                            <small id="msn1" class="help-block result-zona" v-show="listErrors.zona"></small>
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
                        <h5>@lang('general.customers')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-clientes" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>@lang('general.name')</th> 
                                        <th>@lang('general.phone')</th>
                                        <th>@lang('general.address')</th>
                                        <th>@lang('general.city')</th>
                                        <th>@lang('general.zone')</th>
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
<script src="{{ asset('js/templates/clientes.js') }}"></script>
@endsection

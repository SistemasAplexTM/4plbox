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
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.agencia_id}">
                                        <div class="col-sm-4">
                                            <label for="agencia_id" class="control-label gcore-label-top">@lang('general.agency'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="agencia_id_input" value="">
                                            <select v-model="agencia_id" name="agencia_id" id="agencia_id" placeholder="@lang('general.agency')" class="form-control js-data-example-ajax select2-container" @click="deleteError('agencia_id')" >
                                            </select>
                                            <small id="msn1" class="help-block result-agencia_id" v-show="listErrors.agencia_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.primer_nombre}">
                                        <div class="col-sm-4">
                                            <label for="primer_nombre" class="control-label gcore-label-top">@lang('general.names'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="primer_nombre" name="primer_nombre" id="primer_nombre" value="" placeholder="@lang('general.names')" class="form-control" type="text" style="" @click="deleteError('primer_nombre')" />
                                            <small id="msn1" class="help-block result-primer_nombre" v-show="listErrors.primer_nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="display: none">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.segundo_nombre}">
                                        <div class="col-sm-4">
                                            <label for="segundo_nombre" class="control-label gcore-label-top">@lang('general.second_name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="segundo_nombre" name="segundo_nombre" id="segundo_nombre" value="" placeholder="@lang('general.second_name')" class="form-control" type="text" style="" @click="deleteError('segundo_nombre')" />
                                            <small id="msn1" class="help-block result-segundo_nombre" v-show="listErrors.segundo_nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.primer_apellido}">
                                        <div class="col-sm-4">
                                            <label for="primer_apellido" class="control-label gcore-label-top">@lang('general.surnames'):<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="primer_apellido" name="primer_apellido" id="primer_apellido" value="" placeholder="@lang('general.surnames')" class="form-control" type="text" style="" @click="deleteError('primer_apellido')" />
                                            <small id="msn1" class="help-block result-primer_apellido" v-show="listErrors.primer_apellido"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="display: none">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.segundo_apellido}">
                                        <div class="col-sm-4">
                                            <label for="segundo_apellido" class="control-label gcore-label-top">@lang('general.second_name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="segundo_apellido" name="segundo_apellido" id="segundo_apellido" value="" placeholder="@lang('general.second_name')" class="form-control" type="text" style="" @click="deleteError('segundo_apellido')" />
                                            <small id="msn1" class="help-block result-segundo_apellido" v-show="listErrors.segundo_apellido"></small>
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
                                            <label for="correo" class="control-label gcore-label-top">@lang('general.email'):</label>
                                        </div>
                                        <div class="col-sm-8" :class="{ 'has-error': errors.has('correo') }">
                                            <input v-model="correo" placeholder ="Example@example.com" name="correo"  class="form-control" type="text" v-validate.disable="'unique'" @click="deleteError('correo')" />
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
                                            <select v-model="localizacion_id" name="localizacion_id" id="localizacion_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('localizacion_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-localizacion_id" v-show="listErrors.localizacion_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.zip}">
                                        <div class="col-sm-4">
                                            <label for="zip" class="control-label gcore-label-top">@lang('general.code_zip'):</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input v-model="zip" name="zip" id="zip" value="" placeholder="@lang('general.code_zip')" class="form-control" type="text" style="" @click="deleteError('zip')" />
                                            <small id="msn1" class="help-block result-zip" v-show="listErrors.zip"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <contactos-component :table="'shipper'" :parametro="parametro"></contactos-component>
                            @include('layouts.buttons')
                        </div>
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
                            <table id="tbl-shipper" class="table table-striped table-hover table-bordered" style="width: 100%;">
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
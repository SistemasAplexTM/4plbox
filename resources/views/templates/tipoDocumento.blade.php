@extends('layouts.app')
@section('title', 'Tipo documento')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.types_of_documents')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.types_of_documents')</strong>
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
    width: 150px !important;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 100% !important;
    }
    span.label{
        font-size: 14px;
    }
    .bootstrap-tagsinput{
        line-height: 26px;
    }
</style>
    <div class="row" id="tipoDocumento">
        <form id="formtipoDocumento" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.record_type_of_document')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="@lang('general.document_type')" class="form-control" type="text" @click="deleteError('nombre')" @focus="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.prefijo}">
                                        <div class="col-sm-4">
                                            <label for="prefijo" class="control-label gcore-label-top">@lang('general.prefix'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="prefijo" name="prefijo" id="prefijo" value="" placeholder="Ej: TD" class="form-control" type="text" @click="deleteError('prefijo')" @focus="deleteError('prefijo')" />
                                            <small id="msn1" class="help-block result-prefijo" v-show="listErrors.prefijo">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.icono}">
                                        <div class="col-sm-4">
                                            <label for="icono" class="control-label gcore-label-top">@lang('general.icon'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select data-live-search="true" id="icono" name="icono" class="form-control ajaxLoadFontAwesome" style="font-family:'FontAwesome'">
                                            </select>
                                            <small id="msn1" class="help-block result-icono" v-show="listErrors.icono">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('email_plantilla_id') }">
                                    <div class="col-sm-4">
                                        <label for="email_plantilla_id" class="control-label gcore-label-top">@lang('general.mail_template'):</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <v-select :options="plantillas" name="email_plantilla_id" v-model="email_plantilla_id" label="name" placeholder="@lang('general.mail_template')">
                                            <template slot="option" slot-scope="option">
                                                <span class="fa fa-envelope"></span>
                                                <label style="font-size: 15px;"> @{{ option.name }}</label>
                                                <div>@{{ option.descripcion_plantilla }}</div>
                                            </template>
                                        </v-select>
                                        <small v-show="errors.has('email_plantilla_id')" class="error">@{{ errors.first('email_plantilla_id') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="mostrar_correos_add">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': listErrors.email_copia}">
                                    <div class="col-sm-4">
                                        <label for="email_copia" class="control-label gcore-label-top">CC:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-model="email_copia" class="email_copia" id="email_copia" name="email_copia" class="form-control" type="text" placeholder="@lang('general.mail_with_copy_to')" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': listErrors.email_copia_oculta}">
                                    <div class="col-sm-4">
                                        <label for="email_copia_oculta" class="control-label gcore-label-top">BCC:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-model="email_copia_oculta" class="email_copia" id="email_copia_oculta" name="email_copia_oculta" class="form-control" type="text" placeholder="@lang('general.mail_with_copy_to')" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.consecutivo_inicial}">
                                        <div class="col-sm-4">
                                            <label for="consecutivo_inicial" class="control-label gcore-label-top">@lang('general.initial_consecutive'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="consecutivo_inicial" name="consecutivo_inicial" id="consecutivo_inicial" value="" class="form-control" type="number" min="1" @click="deleteError('consecutivo_inicial')" @focus="deleteError('consecutivo_inicial')" />
                                            <small id="msn1" class="help-block result-consecutivo_inicial" v-show="listErrors.consecutivo_inicial">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-12" style="padding-bottom: 20px;">
                            <div style="text-align: center;margin-bottom: 40px;" class="label label-danger"><i class="fa fa-lock"></i> @lang('general.security_settings')</div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.credenciales}">
                                    	<div class="col-sm-12">
                                            <small>@lang('general.select_the_credentials')</small>
                                        </div>
                                        <div class="col-sm-12">
                                            <select class="js-example-basic-multiple form-control" id="credenciales" name="credenciales[]" multiple="multiple" @click="deleteError('credenciales')" @focus="deleteError('credenciales')">
											</select>
                                            <small id="msn1" class="help-block result-credenciales" v-show="listErrors.credenciales">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-12" style="padding-bottom: 20px;padding-top: 20px;">
                            <div style="text-align: center;margin-bottom: 20px;margin-top: 20px" class="label label-success"><i class="fa fa-share-alt"></i> Funcionalidades</div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.funcionalidades}">
                                    	<div class="col-sm-12">
                                            <small>@lang('general.select_the_additional_functionalities')</small>
                                        </div>
                                        <div class="col-sm-12">
                                        	<select class="js-example-basic-multiple form-control" id="funcionalidades" name="funcionalidades[]" multiple="multiple" @click="deleteError('funcionalidades')" @focus="deleteError('funcionalidades')">
											</select>
                                            <small id="msn1" class="help-block result-funcionalidades" v-show="listErrors.funcionalidades">@lang('general.obligatory_field')</small>
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
                    <h5>@lang('general.types_of_documents')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-tipoDocumento" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.prefix')</th>
                                    <th>@lang('general.description')</th>
                                    <th>@lang('general.mail_template')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.prefix')</th>
                                    <th>@lang('general.description')</th>
                                    <th>@lang('general.mail_template')</th>
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
<script src="{{ asset('js/templates/tipoDocumento.js') }}"></script>
@endsection
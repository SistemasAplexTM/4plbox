@extends('layouts.app')
@section('title', 'Ciudades')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.cities')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.cities')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="ciudad">
        <form id="formciudad" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.registration_of_cities')</h5>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.pais_id}">
                                        <div class="col-sm-4">
                                            <label for="pais_id" class="control-label gcore-label-top">@lang('general.country'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="pais_id_input" value="">
                                            <select v-model="pais_id" name="pais_id" id="pais_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('pais_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-pais_id" v-show="listErrors.pais_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.deptos_id}">
                                        <div class="col-sm-4">
                                            <label for="deptos_id" class="control-label gcore-label-top">@lang('general.department_state'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="deptos_id_input" value="">
                                            <select v-model="deptos_id" name="deptos_id" id="deptos_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('deptos_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-deptos_id" v-show="listErrors.deptos_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.description')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="@lang('general.name')" class="form-control" type="text" style="" @click="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
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
                                            <input v-model="prefijo" name="prefijo" id="prefijo" value="" placeholder="@lang('general.prefix')" class="form-control" type="text" style="" @click="deleteError('prefijo')" />
                                            <small id="msn1" class="help-block result-prefijo" v-show="listErrors.prefijo"></small>
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
                    <h5>@lang('general.cities')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-ciudad" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.prefix')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.department')</th>
                                    <th>@lang('general.country')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.prefix')</th>
                                    <th>@lang('general.city')</th>
                                    <th>@lang('general.department')</th>
                                    <th>@lang('general.country')</th>
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
<script src="{{ asset('js/templates/ciudad.js') }}"></script>
@endsection

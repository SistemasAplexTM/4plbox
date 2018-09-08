@extends('layouts.app')
@section('title', 'Roles')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Roles</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>Roles</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="roles">
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.record_of_roles')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-6">
                                    <div class="form-group" :class="{'has-error': listErrors.name}">
                                        <div class="col-sm-12">
                                            <label for="name" class="control-label gcore-label-top">@lang('general.name'):</label>
                                            <input v-model="name" name="name" id="name" placeholder="Nombre del rol" class="form-control" type="text" v-validate.disable="'required'" v-on:keyup="slugGenerate()"/>
                                            <small class="help-block has-error" :class="{ 'small': errors.has('name') }">@{{ errors.first('name') }}</small>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                    <div class="form-group" :class="{'has-error': listErrors.slug}">
                                        <div class="col-sm-12">
                                            <label for="slug" class="control-label gcore-label-top">@lang('general.slug_for_the_url'):</label>
                                            <input v-model="slug" name="slug" id="slug" placeholder="Slug" class="form-control" type="text" v-validate.disable="'required'"/>
                                            <small class="help-block has-error" :class="{ 'small': errors.has('slug') }">@{{ errors.first('slug') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.description}">
                                        <div class="col-sm-12">
                                            <label for="description" class="control-label gcore-label-top">@lang('general.description'):</label>
                                            <input v-model="description" name="description" id="description" placeholder="Descripción del rol" class="form-control" type="text" />
                                            <small class="help-block has-error" :class="{ 'small': errors.has('description') }">@{{ errors.first('description') }}</small>
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
	        <div class="col-lg-5">
	            <div class="ibox float-e-margins">
	                <div class="ibox-title">
	                    <h5>Roles</h5>
	                    <div class="ibox-tools">

	                    </div>
	                </div>
	                <div class="ibox-content">
	                    <!--***** contenido ******-->
	                    <div class="table-responsive">
	                        <table id="tbl-rol" class="table table-striped table-hover table-bordered" style="width: 100%;">
	                            <thead>
	                                <tr>
	                                    <th>@lang('general.role')</th>
	                                    <th>@lang('general.actions')</th>
	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                            <tfoot>
	                                <tr>
	                                   <th>@lang('general.role')</th>
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
<script src="{{ asset('js/templates/permissions/rol.js') }}"></script>
@endsection
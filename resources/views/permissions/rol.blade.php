@extends('layouts.app')
@section('title', 'Roles')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Roles</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
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
                        <h5>Registro de roles</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-6">
                                    <div class="form-group" :class="{'has-error': listErrors.name}">
                                        <div class="col-sm-12">
                                            <label for="name" class="control-label gcore-label-top">Nombre:</label>
                                            <input v-model="name" name="name" id="name" placeholder="Nombre del rol" class="form-control" type="text" v-validate.disable="'required'" v-on:keyup="slugGenerate()"/>
                                            <small class="help-block has-error" :class="{ 'small': errors.has('name') }">@{{ errors.first('name') }}</small>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                    <div class="form-group" :class="{'has-error': listErrors.slug}">
                                        <div class="col-sm-12">
                                            <label for="slug" class="control-label gcore-label-top">Slug para la URL:</label>
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
                                            <label for="description" class="control-label gcore-label-top">Descripción:</label>
                                            <input v-model="description" name="description" id="description" placeholder="Descripción del rol" class="form-control" type="text" />
                                            <small class="help-block has-error" :class="{ 'small': errors.has('description') }">@{{ errors.first('description') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.special}">
                                        <div class="col-sm-12">
                                            <label class="control-label gcore-label-top">Permiso especial:</label>
                                        </div>
                                        <div class="col-sm-12">
                                        	<div class="radio radio-info radio-inline">
	                                            <input type="radio" id="inlineRadio1" v-bind:value="null" name="special" checked="" v-model="special">
	                                            <label for="inlineRadio1"> Ninguno </label>
	                                        </div>
	                                        <div class="radio radio-info radio-inline">
	                                            <input type="radio" id="inlineRadio2" value="all-access" name="special" v-model="special">
	                                            <label for="inlineRadio2"> Acceso total </label>
	                                        </div>
	                                        <div class="radio radio-info radio-inline">
	                                            <input type="radio" id="inlineRadio3" value="no-access" name="special" v-model="special">
	                                            <label for="inlineRadio3"> Sin acceso </label>
	                                        </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-12" style="margin-top: 20px;">
        						<form id="permissions" role="form">
	                        		<table id="tbl-permissions" class="table table-hover table-responsive table-striped">
	                        			<thead>
	                        				<tr>
	                        					<th class="text-center" colspan="3">Listado de permisos</th>
	                        				</tr>
	                        				<tr>
	                        					<th></th>
	                        					<th>Nombre</th>
	                        					<th>Descripción</th>
	                        				</tr>
	                        			</thead>
	                        		</table>
        						</form>
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
	                                    <th>Rol</th>
	                                    <th>Acciones</th>
	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                            <tfoot>
	                                <tr>
	                                    <th>Rol</th>
	                                    <th>Acciones</th>
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
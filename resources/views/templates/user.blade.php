@extends('layouts.app')
@section('title', 'Usuario')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Usuarios</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Usuarios</strong>
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
    width: 100px !important;
	}
	.dropdown-toggle>input[type="search"]:focus:valid {
	    width: 100% !important;
	}
</style>
    <div class="row" id="user">
        <form id="formUsuario" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de usuarios</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('agencia_id') }">
                                        <div class="col-sm-5">
                                            <label for="agencia_id" class="control-label gcore-label-top">Agencia:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <v-select name="agencia_id" v-model="agencia_id" label="name" :options="agencias" v-validate.disable="'required'" placeholder="Agencias"></v-select>
                                            <small class="help-block">@{{ errors.first('agencia_id') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div> 
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('name') }">
                                        <div class="col-sm-5">
                                            <label for="name" class="control-label gcore-label-top">Nombre:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="name" name="name" placeholder="Nombre del usuario" class="form-control" type="text" v-validate.disable="mostrar_password ? 'required' : 'required'"/>
                                            <small class="help-block">@{{ errors.first('name') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('email') }">
                                        <div class="col-sm-5">
                                            <label for="email" class="control-label gcore-label-top">Email:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="email" name="email" placeholder="example@example.com" class="form-control" type="email" v-validate.disable="mostrar_password ? 'required|email|unique' : 'required|email'"/>
                                            <small class="help-block">@{{ errors.first('email') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="mostrar_password">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('password') }">
                                        <div class="col-sm-5">
                                            <label for="password" class="control-label gcore-label-top">Contraseña:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="password" name="password" value="" placeholder="**********" class="form-control" type="password" v-validate="'required|min:6'"/>
                                            <small class="help-block">@{{ errors.first('password') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="mostrar_password">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('password_confirm') }">
                                        <div class="col-sm-5">
                                            <label for="password_confirm" class="control-label gcore-label-top">Confirme contraseña:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="password_confirm" name="password_confirm" value="" placeholder="**********" class="form-control" type="password" v-validate="'required|confirmed:password'"/>
                                            <small class="help-block">@{{ errors.first('password_confirm') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('rol_id') }">
                                        <div class="col-sm-5">
                                            <label for="rol_id" class="control-label gcore-label-top">Rol:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <v-select name="rol_id" v-model="rol_id" label="name" :options="roles" v-validate.disable="'required'" placeholder="Roles"></v-select>
                                            <small class="help-block">@{{ errors.first('rol_id') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-sm-5">
                                            <label for="actived" class="control-label gcore-label-top">Activado:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="checkbox checkbox-success">
                                                <input id="actived" type="checkbox" v-model="actived">
                                                <label for="checkbox2"></label>
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
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Usuario</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-user" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Credencial</th>
                                    <th>Agencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Credencial</th>
                                    <th>Agencia</th>
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
<script src="{{ asset('js/templates/user.js') }}"></script>
@endsection
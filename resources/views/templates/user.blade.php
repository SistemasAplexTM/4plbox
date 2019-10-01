@extends('layouts.app')
@section('title', 'Usuario')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.users')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.users')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
  .tab-pane{
    padding-top: 15px;
  }
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
                        <h5>@lang('general.user_registration')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('agencia_id') }">
                                        <div class="col-sm-5">
                                            <label for="agencia_id" class="control-label gcore-label-top">@lang('general.agency'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <v-select name="agencia_id" v-model="agencia_id" label="name" :options="agencias" v-validate.disable="'required'" placeholder="@lang('general.agency')"></v-select>
                                            <small class="help-block">@{{ errors.first('agencia_id') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('name') }">
                                        <div class="col-sm-5">
                                            <label for="name" class="control-label gcore-label-top">@lang('general.name'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="name" name="name" placeholder="@lang('general.name')" class="form-control" type="text" v-validate.disable="mostrar_password ? 'required' : 'required'"/>
                                            <small class="help-block">@{{ errors.first('name') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('email') }">
                                        <div class="col-sm-5">
                                            <label for="email" class="control-label gcore-label-top">@lang('general.email'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="email" name="email" placeholder="example@example.com" class="form-control" type="email" v-validate.disable="mostrar_password ? 'required|email|unique' : 'required|email'"/>
                                            <small class="help-block">@{{ errors.first('email') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="changue_password">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="col-sm-5">
                                        <label for="" class="control-label gcore-label-top">&nbsp;</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <a @click="changePassword()"><i class="fal fa-key"></i> @lang('general.change_password')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="mostrar_password || !changue_password">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('password') }">
                                        <div class="col-sm-5">
                                            <label for="password" class="control-label gcore-label-top">@lang('general.password'):</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="password" name="password"ref="password" placeholder="**********" class="form-control" type="password" v-validate="'required|min:6'"/>
                                            <small class="help-block">@{{ errors.first('password') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="mostrar_password || !changue_password">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{ 'has-error': errors.has('password_confirm') }">
                                        <div class="col-sm-5">
                                            <label for="password_confirm" class="control-label gcore-label-top">@lang('general.confirm_password'):</label>
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
                                            <label for="rol_id" class="control-label gcore-label-top">@lang('general.role'):</label>
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
                                            <label for="actived" class="control-label gcore-label-top">@lang('general.activated'):</label>
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
            <div class="col-lg-8">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <h5>@lang('general.user')</h5>
                      <div class="ibox-tools">

                      </div>
                  </div>
                  <div class="ibox-content">
                      <!--***** contenido ******-->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="users" class="active">
                          <a href="#users" aria-controls="users" role="tab" data-toggle="tab"><i class="fal fa-users"></i> Usuarios</a>
                        </li>
                        @if(Auth::user()->isRole('admin'))
                          <li role="agency" @click="getUsersTable(2, 'user_agency')">
                            <a href="#agency" aria-controls="agency" role="tab" data-toggle="tab"><i class="fal fa-user-friends"></i> Usuarios Agencias</a>
                          </li>
                        @endif
                        <li role="users_locker" @click="getUsersTable(3, 'users_locker')">
                          <a href="#users_locker" aria-controls="users_locker" role="tab" data-toggle="tab"><i class="fal fa-clipboard-user"></i> Usuarios Casillero</a>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active in fade" id="users">
                          <div class="table-responsive">
                              <table id="tbl-user" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                  <thead>
                                      <tr>
                                          <th>@lang('general.name')</th>
                                          <th>@lang('general.email')</th>
                                          <th>@lang('general.credential')</th>
                                          <th>@lang('general.agency')</th>
                                          <th>@lang('general.actions')</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                              </table>
                          </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="agency">
                          <div class="table-responsive">
                              <table id="tbl-user_agency" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                  <thead>
                                      <tr>
                                          <th>@lang('general.name')</th>
                                          <th>@lang('general.email')</th>
                                          <th>@lang('general.credential')</th>
                                          <th>@lang('general.agency')</th>
                                          <th>@lang('general.actions')</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                              </table>
                          </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="users_locker">
                          <div class="table-responsive">
                              <table id="tbl-users_locker" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                  <thead>
                                      <tr>
                                          <th>@lang('general.name')</th>
                                          <th>@lang('general.email')</th>
                                          <th>@lang('general.credential')</th>
                                          <th>@lang('general.agency')</th>
                                          <th>@lang('general.actions')</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                              </table>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/user.js') }}"></script>
@endsection

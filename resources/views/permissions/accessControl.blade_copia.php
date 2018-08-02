@extends('layouts.app')
@section('title', 'Control Acceso')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            Control de acceso
        </h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    Home
                </a>
            </li>
            <li class="active">
                <strong>
                    Control de acceso
                </strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    .checkbox{
		margin-top: 0px;
		margin-bottom: 0px;
	}
	.chk_all_group{
		float: right;
	}
	.chk_all{
		/*margin-right: 3.2%;*/
		margin-right: 18.5px;
	}
</style>
<div class="row" id="access_control">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    Control de acceso de usuarios
                </h5>
                <div class="ibox-tools">
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="row">
                    <div class="col-lg-7">
                        <div aria-multiselectable="true" class="panel-group" id="accordion" role="tablist">
                            <div class="panel panel-default" v-for="role in roles">
                                <form v-bind:id="'form' + role.id">
                                    <div class="panel-heading info" role="tab" v-bind:id="'heading' + role.id">
                                        <div class="row">
                                            
                                        </div>
                                        <div class="row">
	                                        <div class="col-lg-12">
	                                            <h4 class="panel-title expand" style="float: left;">
	                                                <i class="icon_control fa fa-plus">
	                                                </i>
	                                                <a @click="getPermisionsRole(role.id)" aria-expanded="false" data-parent="#accordion" data-toggle="collapse" role="button" v-bind:aria-controls="'collapse' + role.id" v-bind:href="'#collapse' + role.id">
	                                                    @{{ role.name }}
	                                                </a>
	                                            </h4>
	                                            {{-- CHK ALL --}}
	                                            <div class="chk_all_group">
	                                                <div class="checkbox checkbox-success checkbox-inline chk_all">
	                                                    <input @click='checkAll("c", role.id)' v-bind:aria-label="'all_c' + role.id" v-bind:id="'all_c' + role.id" type="checkbox" v-model="checkAll_c[role.id]">
	                                                        <label>
	                                                        </label>
	                                                    </input>
	                                                </div>
	                                                <div class="checkbox checkbox-success checkbox-inline chk_all">
	                                                    <input @click='checkAll("r", role.id)' aria-label="all_r" id="all_r" type="checkbox" v-model="checkAll_r[role.id]">
	                                                        <label>
	                                                        </label>
	                                                    </input>
	                                                </div>
	                                                <div class="checkbox checkbox-success checkbox-inline chk_all">
	                                                    <input @click='checkAll("u", role.id)' aria-label="all_u" id="all_u" type="checkbox" v-model="checkAll_u[role.id]">
	                                                        <label>
	                                                        </label>
	                                                    </input>
	                                                </div>
	                                                <div class="checkbox checkbox-success checkbox-inline chk_all">
	                                                    <input @click='checkAll("d", role.id)' aria-label="all_d" id="all_d" type="checkbox" v-model="checkAll_d[role.id]">
	                                                        <label>
	                                                        </label>
	                                                    </input>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
                                    </div>
                                    <div class="panel-collapse collapse" role="tabpanel" v-bind:aria-labelledby="'heading' + role.id" v-bind:id="'collapse' + role.id">
                                        <div class="panel-body">
                                            <table class="table table-striped table-hover" id="permissions" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <span>
                                                                Módulos del sistema
                                                            </span>
                                                        </th>
                                                        <th>
                                                            C
                                                        </th>
                                                        <th>
                                                            R
                                                        </th>
                                                        <th>
                                                            U
                                                        </th>
                                                        <th>
                                                            D
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="permisionsRole in permisionsRoles">
                                                        <td>
                                                            @{{ permisionsRole.module }}
                                                        </td>
                                                        <td>
                                                            <div class="checkbox checkbox-primary">
                                                                <input @change='updateCheckall("c")' aria-label="c" class="chk_c" type="checkbox" v-bind:id="'c_' + permisionsRole.c" v-bind:value="permisionsRole.c" v-model="chk_c">
                                                                    <label>
                                                                    </label>
                                                                </input>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="checkbox checkbox-primary">
                                                                <input @change='updateCheckall("r")' aria-label="r" class="chk_r" type="checkbox" v-bind:id="'r_' + permisionsRole.r" v-bind:value="permisionsRole.r" v-model="chk_r">
                                                                    <label>
                                                                    </label>
                                                                </input>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="checkbox checkbox-primary">
                                                                <input @change='updateCheckall("u")' aria-label="u" class="chk_u" type="checkbox" v-bind:id="'u_' + permisionsRole.u" v-bind:value="permisionsRole.u" v-model="chk_u">
                                                                    <label>
                                                                    </label>
                                                                </input>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="checkbox checkbox-primary">
                                                                <input @change='updateCheckall("d")' aria-label="d" class="chk_d" type="checkbox" v-bind:id="'d_' + permisionsRole.d" v-bind:value="permisionsRole.d" v-model="chk_d">
                                                                    <label>
                                                                    </label>
                                                                </input>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5">
                                                            <button @click="savePermissions(role.id)" class="ladda-button btn btn-success pull-right" data-style="expand-right" type="button">
                                                                <i class="fa fa-save">
                                                                </i>
                                                                Guardar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{--
                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/permissions/accessControl.js') }}">
</script>
@endsection

@extends('layouts.app')
@section('title', 'Empleados')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Clientes</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>Clientes</strong>
            </li>
        </ol>
    </div>
</div>
<style>
  .help-block{
    color: #ed5565;
  }
</style>
@endsection

@section('content')
    <div class="row" id="clientes">
        <form id="formclientes" enctype="multipart/form-data" class="form-horizontal" role="form">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de clientes</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': errors.has('documento')}">
                                        <div class="col-sm-4">
                                            <label for="pa" class="control-label gcore-label-top">Documento:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="documento" name="documento" id="documento" v-validate.disable="'required'" placeholder="Documento" class="form-control" type="text" @click="deleteError('documento')" @focus="deleteError('documento')" />
                                            <small class="help-block error result-documento" v-show="errors.has('documento')">
                                                @{{ errors.first('documento') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': errors.has('nombre')}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">Nombre:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" v-validate.disable="'required'" placeholder="Nombre" class="form-control" type="text" @click="deleteError('nombre')" @focus="deleteError('nombre')" />
                                            <small class="help-block error result-nombre" v-show="errors.has('nombre')">
                                                @{{ errors.first('nombre') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': errors.has('direccion')}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">Dirección:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="direccion" name="direccion" id="direccion" v-validate.disable="'required'" placeholder="Dirección" class="form-control" type="text" @click="deleteError('direccion')" @focus="deleteError('direccion')"/>
                                            <small class="help-block error result-direccion" v-show="errors.has('direccion')">
                                                @{{ errors.first('direccion') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': errors.has('telefono')}">
                                        <div class="col-sm-4">
                                            <label for="telefono" class="control-label gcore-label-top">Teléfono:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="telefono" name="telefono" id="telefono" v-validate.disable="'required'" placeholder="Teléfono" class="form-control" type="text" @click="deleteError('telefono')" @focus="deleteError('telefono')"/>
                                            <small class="help-block error result-telefono" v-show="errors.has('telefono')">
                                                @{{ errors.first('telefono') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label for="correo" class="control-label gcore-label-top">Correo:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="correo" name="correo" id="correo" value="" placeholder="Correo" class="form-control" type="text"/>

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
                      <h5>Clientes</h5>
                      <div class="ibox-tools">

                      </div>
                  </div>
                  <div class="ibox-content">
                      <!--***** contenido ******-->
                      <div class="table-responsive">
                          <table id="tbl-clientes" class="table table-striped table-hover table-bordered" style="width: 100%;">
                              <thead>
                                  <tr>
                                      <th>Documento</th>
                                      <th>Nombre</th>
                                      <th>Dirección</th>
                                      <th>Teléfono</th>
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
<script src="{{ asset('js/templates/radicadoClientes.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', 'Radicados')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Radicados</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>Radicados</strong>
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
    <div class="row" id="radicado">
        <form id="formradicado" enctype="multipart/form-data" class="form-horizontal" role="form">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de radicados</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('fecha')}">
                                    <div class="col-sm-4">
                                        <label for="fecha" class="control-label gcore-label-top">Fecha:</label>
                                    </div>
                                    <div class="col-sm-8">
                                      <el-date-picker
                                        size="small"
                                        v-model="fecha"
                                        type="date"
                                        placeholder="Seleccionar"
                                        format="yyyy-MM-dd"
                                        value-format="yyyy-MM-dd"
                                        name="fecha"
                                        v-validate.disable="'required'"
                                        @focus="deleteError('fecha')">
                                      </el-date-picker>
                                      <small class="help-block error result-fecha" v-show="errors.has('fecha')">
                                          @{{ errors.first('fecha') }}
                                      </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('cliente_id')}">
                                    <div class="col-sm-4">
                                        <label for="cliente_id" class="control-label gcore-label-top">Cliente:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <el-select v-model="cliente_id" filterable clearable placeholder="Cliente" size="small"
                                        name="cliente_id"
                                        v-validate.disable="'required'"
                                        @focus="deleteError('cliente_id')">
                                          <el-option
                                            v-for="item in clientes"
                                            :key="item.id"
                                            :label="item.nombre"
                                            :value="item.id">
                                          </el-option>
                                        </el-select>
                                        <small class="help-block error result-cliente_id" v-show="errors.has('cliente_id')">
                                            @{{ errors.first('cliente_id') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('empleado_id')}">
                                    <div class="col-sm-4">
                                        <label for="empleado_id" class="control-label gcore-label-top">Empleado:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <el-select v-model="empleado_id" filterable clearable placeholder="Empleado" size="small"
                                        name="empleado_id"
                                        v-validate.disable="'required'"
                                        @focus="deleteError('empleado_id')">
                                          <el-option
                                            v-for="item in empleados"
                                            :key="item.id"
                                            :label="item.nombre"
                                            :value="item.id">
                                          </el-option>
                                        </el-select>
                                        <small class="help-block error result-empleado_id" v-show="errors.has('empleado_id')">
                                            @{{ errors.first('empleado_id') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-4">
                              <el-input-number size="small" v-model="cantidad" :min="1" controls-position="right"></el-input-number>
                            </div>
                            <div class="col-lg-6">
                              <el-input type="textarea" v-model="descripcion" autosize></el-input>
                            </div>
                            <div class="col-lg-2">
                              <el-tooltip class="item" effect="dark" content="Agregar" placement="top">
                                <el-button type="primary" size="small" @click="agregarDetalle"><i class="fal fa-plus"></i></el-button>
                              </el-tooltip>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                              <table class="table table-hover table-mail">
                                <tbody>
                                  <tr class="" v-for="(val, index) in detalle">
                                      <td class="">@{{ val.cantidad }}</td>
                                      <td class="" style="width: 80%">@{{ val.descripcion }}</td>
                                      <td class="" style="float: right;">
                                        <el-tooltip class="item" effect="dark" content="Eliminar" placement="top">
                                          <el-button type="danger" size="mini" icon="el-icon-delete" circle @click="detalle.splice(index, 1)">
                                          </el-button>
                                        </el-tooltip>
                                      </td>
                                  </tr>
                                </tbody>
                              </table>
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
                      <h5>Radicados</h5>
                      <div class="ibox-tools">

                      </div>
                  </div>
                  <div class="ibox-content">
                      <!--***** contenido ******-->
                      <div class="table-responsive">
                          <table id="tbl-radicado" class="table table-striped table-hover table-bordered" style="width: 100%;">
                              <thead>
                                  <tr>
                                      <th>Documento</th>
                                      <th>Fecha</th>
                                      <th>Cliente</th>
                                      <th>Empleado</th>
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
<script src="{{ asset('js/templates/radicado.js') }}"></script>
@endsection

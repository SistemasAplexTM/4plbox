@extends('layouts.app')
@section('title', 'Mintic')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Mintic</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>Mintic</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="mintic">
        <div class="col-lg-4">
          <form class="form-horizontal">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mintic</h5>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                              <div class="col-sm-12">
                                    <label for="descripcion" class="control-label gcore-label-top">Warehouse:</label>
                                    <div class="input-group">
                                      <input v-model="warehouse" @keyup.enter="addWarehouse" placeholder="Núm. warehouse" class="form-control" :disabled="disabled" ref="wrh" type="text" autofocus/>
                                      <span class="input-group-btn">
                                        <button @click="addWarehouse" type="button" class="btn btn-primary">
                                          <i class="fal fa-plus"></i>
                                        </button>
                                      </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                              <div class="col-sm-12">
                                    <label for="descripcion" class="control-label gcore-label-top">Descripción:</label>
                                    <input v-model="desc" placeholder="Mintic #" class="form-control" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <a href="#" class="btn btn-success" @click="create()" :disabled="detail.length <= 0 "> <i class="fal fa-save"></i> Crear Mintic</a>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="table-responsive">
                          <table id="tbl-pais" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                              <tr>
                                <th>Warehouse totales @{{ cantidad }}</th>
                                <th>@lang('general.actions')</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(value, index) in detail">
                                <td>
                                  @{{ value.warehouse }}
                                </td>
                                <td style="width: 100px;">
                                  <a href="#" class="text-danger" @click="detail.splice(index, 1)">
                                    <i class="fal fa-trash"></i>
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
          </form>
        </div>
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mintics</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tbl-mintic" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Guias Mintic</th>
                                    <th>Mintic</th>
                                    <th>Fecha</th>
                                    {{-- <th>@lang('general.actions')</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                  <th>Guias Mintic</th>
                                  <th>Mintic</th>
                                  <th>Fecha</th>
                                    {{-- <th>@lang('general.actions')</th> --}}
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
<script src="{{ asset('js/templates/mintic.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', 'Guía master')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('master.master_guide')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('master.home')</a>
            </li>
            <li class="active">
                <strong>@lang('master.master_guide')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 230px;
    }
    .el-select-dropdown{
      z-index: 9999!important;
    }
</style>
<div class="row" id="master_list">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    @lang('master.registered_guides')
                </h5>
                <div class="ibox-tools">
                    <a class="btn btn-primary" data-toggle="tooltip" title="Crear nueva master"><span data-toggle="modal" data-target="#modalCreateMaster">@lang('master.new') <i class="fal fa-plus" style="font-size: small;"></i></span></a>
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-master" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Master AWB</th>
                                <th>@lang('master.airline')</th>
                                <th>@lang('master.date')</th>
                                <th>@lang('master.rate')</th>
                                <th>@lang('master.weight') Lb</th>
                                <th>@lang('master.weight') Kl</th>
                                <th>@lang('master.consignee')</th>
                                <th>@lang('master.destination')</th>
                                <th>@lang('master.manifest')</th>
                                <th>@lang('master.actions')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CAMBIAR STATUS CONSOLIDADO -->
    <div class="modal fade bs-example" id="modalCreateMaster" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fal fa-file" style="font-size: 20px;"></i> Creación de Guias Master
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="formGuiasAgrupar">
                        <p>Si desea asociar un consolidado a esta master, por favor seleccionelo.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="status_id">Consolidado</label>
                                    <el-select
                                      clearable
                                      v-model="consolidado_id"
                                      filterable
                                      placeholder="Buscar Consolidado"
                                      :loading="loading"
                                      loading-text="Cargando..."
                                      no-data-text="No hay datos"
                                      value-key="id">
                                      <el-option
                                        v-for="item in options"
                                        :key="item.id"
                                        :label="item.consolidado"
                                        :value="item">
                                        <span style="float: left">Consolidado # @{{ item.consolidado }}</span>
                                        <span style="float: right; color: #8492a6; font-size: 13px"><i class="fal fa-calendar-alt"></i> @{{ item.fecha }} <i class="fal fa-map-marker-alt"></i> @{{ item.ciudad }}</span>
                                      </el-option>
                                    </el-select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i> Cerrar</button>
                    <a @click="createMaster()" class="btn btn-primary"><i class="fal fa-plus"></i> @lang('master.create')</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/master/master_list.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', 'Bill of lading')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.bill_of_lading')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.bill_of_lading')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 120px;
    }
    .el-select-dropdown{
      z-index: 9999!important;
    }
</style>
<div class="row" id="bill">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    @lang('general.bill_of_lading')
                </h5>
                <div class="ibox-tools">
                    {{-- <a href="{{ url('bill/create') }}" data-toggle="tooltip" title="Crear bill of lading" class="btn btn-primary" >Nuevo <i class="fal fa-plus" style="font-size: small;"></i></a> --}}
                    <a class="btn btn-primary" data-toggle="tooltip" title="Crear nuevo BL"><span data-toggle="modal" data-target="#modalCreateBL">@lang('master.new') <i class="fal fa-plus" style="font-size: small;"></i></span></a>
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-bill" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('general.bill_of_lading')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.point_of_origin')</th>
                                <th>@lang('general.loading_dock')</th>
                                <th>@lang('general.foreign_port_of_discharge')</th>
                                <th>@lang('general.weight') Kl</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL CREAR BL -->
        <div class="modal fade bs-example" id="modalCreateBL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <i class="fal fa-file" style="font-size: 20px;"></i> Creación de Bill of Lading
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form id="formGuiasAgrupar">
                            <p>Si desea asociar un consolidado a esta Bill of Lading, por favor seleccionelo.</p>
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
                        <a @click="createBill()" class="btn btn-primary"><i class="fal fa-plus"></i> @lang('master.create')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/bill/index.js') }}"></script>
@endsection

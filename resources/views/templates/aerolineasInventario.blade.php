@extends('layouts.app')
@section('title', 'Inventario de aerolíneas')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.airline_inventory')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.airline_inventory')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="aerolineaInventario">
        <form id="formarancel" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.airline_inventory_record')</h5>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('aerolinea_id') }">
                                    <div class="col-sm-4">
                                        <label for="aerolinea_id" class="control-label gcore-label-top">@lang('general.airline')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <v-select :options="aerolineas" v-validate.disable="'required'" name="aerolinea_id" v-model="aerolinea_id" label="nombre" placeholder="@lang('general.airlines')"></v-select>
                                        <small v-show="errors.has('aerolinea_id')" class="error">@{{ errors.first('aerolinea_id') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('guia') }">
                                    <div class="col-sm-4">
                                        <label for="guia" class="control-label gcore-label-top">@lang('general.guide')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-validate.disable="'required'" v-model="guia" name="guia" id="guia" value="" placeholder="@lang('general.guide_number')" class="form-control" type="text" />
                                        <small v-show="errors.has('guia')" class="error">@{{ errors.first('guia') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('cantidad') }">
                                    <div class="col-sm-4">
                                        <label for="cantidad" class="control-label gcore-label-top">@lang('general.quantity')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-validate.disable="'required'" v-model="cantidad" name="cantidad" id="cantidad" value="" placeholder="@lang('general.quantity')" class="form-control" type="number" min="1" />
                                        <small v-show="errors.has('cantidad')" class="error">@{{ errors.first('cantidad') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('fecha') }">
                                    <div class="col-sm-4">
                                        <label for="fecha" class="control-label gcore-label-top">Fecha:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-validate.disable="'required'" v-model="fecha" name="fecha" id="fecha" type="date" class="form-control" type="text" />
                                        <small v-show="errors.has('fecha')" class="error">@{{ errors.first('fecha') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
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
                    <h5>@lang('general.airline_inventories')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-aerolinea_inventario" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.group')</th>
                                    <th>@lang('general.airline')</th>
                                    <th>@lang('general.guide')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.group')</th>
                                    <th>@lang('general.airline')</th>
                                    <th>@lang('general.guide')</th>
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
<script src="{{ asset('js/templates/aerolineaInventario.js') }}"></script>
@endsection
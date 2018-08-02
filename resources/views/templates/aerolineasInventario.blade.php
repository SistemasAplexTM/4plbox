@extends('layouts.app')
@section('title', 'Inventario de aerolíneas')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Inventario de aerolíneas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Inventario de aerolínea</strong>
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
                        <h5>Registro de inventario de aerolínea</h5>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('aerolinea_id') }">
                                    <div class="col-sm-4">
                                        <label for="aerolinea_id" class="control-label gcore-label-top">Aerolínea:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <v-select :options="aerolineas" v-validate.disable="'required'" name="aerolinea_id" v-model="aerolinea_id" label="nombre" placeholder="Aerolineas"></v-select>
                                        <small v-show="errors.has('aerolinea_id')" class="error">@{{ errors.first('aerolinea_id') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('guia') }">
                                    <div class="col-sm-4">
                                        <label for="guia" class="control-label gcore-label-top">Guía:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-validate.disable="'required'" v-model="guia" name="guia" id="guia" value="" placeholder="Número de guía" class="form-control" type="text" />
                                        <small v-show="errors.has('guia')" class="error">@{{ errors.first('guia') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group" :class="{'has-error': errors.has('cantidad') }">
                                    <div class="col-sm-4">
                                        <label for="cantidad" class="control-label gcore-label-top">Cantidad:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input v-validate.disable="'required'" v-model="cantidad" name="cantidad" id="cantidad" value="" placeholder="Cantidad" class="form-control" type="number" min="1" />
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
                    <h5>Inventarios aerolínea</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-aerolinea_inventario" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Grupo</th>
                                    <th>Aerolínea</th>
                                    <th>Guía</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Grupo</th>
                                    <th>Aerolínea</th>
                                    <th>Guía</th>
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
<script src="{{ asset('js/templates/aerolineaInventario.js') }}"></script>
@endsection
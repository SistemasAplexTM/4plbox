@extends('layouts.app')
@section('title', 'Servicios')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Servicios</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Servicios</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="servicios">
        <form id="formservicios" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de Servicios</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-sm-5" :class="{'has-error': listErrors.tipo_embarque_id}">
                                            <label for="tipo_embarque_id" class="control-label gcore-label-top">Tipo embarque:</label>
                                        </div>
                                        <div class="col-sm-7">
                                           <select id="tipo_embarque_id" name="tipo_embarque_id" class="form-control"  @click="deleteError('tipo_embarque_id')">
                                                <option value="" data-seguro="">Seleccione</option>
                                            </select>
                                            <small id="msn1" class="help-block result-tipo_embarque_id" v-show="listErrors.tipo_embarque_id" style="color:#ed5565"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-5">
                                            <label for="nombre" class="control-label gcore-label-top">Nombre:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="Ingrese el nombre del servicio" class="form-control" type="text" @click="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.tarifa}">
                                        <div class="col-sm-5">
                                            <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
                                        </div>
                                        <div class="col-sm-7">
                                        	<div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><li class="fa fa-usd"></li></span>
		                                            <input v-model="tarifa" name="tarifa" id="tarifa" value="" placeholder="Ingrese la tarifa" class="form-control" type="text" @click="deleteError('tarifa')" />
                                            </div>
		                                    <small id="msn1" class="help-block result-tarifa" v-show="listErrors.tarifa"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.peso_minimo}">
                                        <div class="col-sm-5">
                                            <label for="peso_minimo" class="control-label gcore-label-top">Tarifa minima:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><li class="fa fa-usd"></li></span>
                                                    <input v-model="peso_minimo" name="peso_minimo" id="peso_minimo" value="" placeholder="Valor minimo de la tarifa" class="form-control" type="text" @click="deleteError('peso_minimo')" />
                                            </div>
                                            <small id="msn1" class="help-block result-peso_minimo" v-show="listErrors.peso_minimo"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.cobro_opcional}">
                                        <div class="col-sm-5">
                                            <label for="cobro_opcional" class="control-label gcore-label-top">Cobro opcional:</label>
                                        </div>
                                        <div class="col-sm-7">
                                        	<div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><li class="fa fa-usd"></li></span>
		                                            <input v-model="cobro_opcional" name="cobro_opcional" id="cobro_opcional" value="" placeholder="Ingrese el cobro opcional" class="form-control" type="text" @click="deleteError('cobro_opcional')" />
                                            </div>
		                                    <small id="msn1" class="help-block result-cobro_opcional" v-show="listErrors.cobro_opcional"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.seguro}">
                                        <div class="col-sm-5">
                                            <label for="seguro" class="control-label gcore-label-top">Seguro:</label>
                                        </div>
                                        <div class="col-sm-7">
                                        	<div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><li class="fa fa-dollar"></li></span>
		                                            <input v-model="seguro" name="seguro" id="seguro" value="" placeholder="Ingrese el porcentaje del seguro" class="form-control" type="text" @click="deleteError('seguro')" />
                                            </div>
		                                    <small id="msn1" class="help-block result-seguro" v-show="listErrors.seguro"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.impuesto}">
                                        <div class="col-sm-5">
                                            <label for="impuesto" class="control-label gcore-label-top">Impuesto:</label>
                                        </div>
                                        <div class="col-sm-7">
                                        	<div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><li class="fa fa-percent"></li></span>
		                                            <input v-model="impuesto" name="impuesto" id="impuesto" value="" placeholder="Ingrese el porcentaje del impuesto" class="form-control" type="text" @click="deleteError('impuesto')" />
                                            </div>
		                                    <small id="msn1" class="help-block result-impuesto" v-show="listErrors.impuesto"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.cobro_peso_volumen}">
                                        <div class="col-sm-5">
                                            <label for="cobro_peso_volumen" class="control-label gcore-label-top">
                                                <div class="col-sm-12" data-trigger="hover"  data-container="body" data-toggle="popover" data-placement="top" data-content="Active el volumen para cobrar los envios con volumen adicional." style="padding-left: 0px; padding-right: 0px;">
                                                    Cobro Peso / Volumen:
                                                    <i class="fa fa-question-circle" style="cursor: pointer; color: coral;"></i>
                                                </div>
                                        </label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input v-model="cobro_peso_volumen" name="cobro_peso_volumen" id="cobro_peso_volumen" class="form-control" type='checkbox' data-toggle="toggle" data-size='mini' data-on="Peso" data-off="Volumen" data-width="80" data-style="ios" data-onstyle="primary" data-offstyle="warning" @click="deleteError('cobro_peso_volumen')" />
                                            <small id="msn1" class="help-block result-cobro_peso_volumen" v-show="listErrors.cobro_peso_volumen"></small>
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
                    <h5>Servicios</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-servicios" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><li class="fa fa-ship"></li><li class="fa fa-plane"></li> Tipo embarque</th>
                                    <th><li class="fa fa-cubes"></li>Servicio</th>
                                    <th><li class="fa fa-dollar"></li> Tarifa</th>
                                    <th><li class="fa fa-dollar"></li> Tarifa minima</th>
                                    <th><li class="fa fa-dollar"></li> Cobro opcional</th>
                                    <th><li class="fa fa-dollar"></li> Seguro</th>
                                    <th><li class="fa fa-percent"></li> Impuesto</th>
                                    <th>Peso / Volumen</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><li class="fa fa-ship"></li><li class="fa fa-plane"></li> Tipo embarque</th>
                                    <th><li class="fa fa-cubes"></li> Servicio</th>
                                    <th><li class="fa fa-dollar"></li> Tarifa</th>
                                    <th><li class="fa fa-dollar"></li> Tarifa minima</th>
                                    <th><li class="fa fa-dollar"></li> Cobro opcional</th>
                                    <th><li class="fa fa-dollar"></li> Seguro</th>
                                    <th><li class="fa fa-percent"></li> Impuesto</th>
                                    <th>Peso / Volumen</th>
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
<script src="{{ asset('js/templates/servicios.js') }}"></script>
@endsection
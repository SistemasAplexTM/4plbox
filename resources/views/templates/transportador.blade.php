@extends('layouts.app')
@section('title', 'Transportador')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transportador</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Transportador</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="transportador">
        <form id="formtransportador" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de transportador</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">Nombre:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="Ingrese el nombre del transportador" class="form-control" type="text" @click="deleteError('nombre')" @focus="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.direccion}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">Dirección:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="direccion" name="direccion" id="direccion" value="" placeholder="Dirección del transportador" class="form-control" type="text" @click="deleteError('direccion')" @focus="deleteError('direccion')" />
		                                    <small id="msn1" class="help-block result-direccion" v-show="listErrors.direccion"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.telefono}">
                                        <div class="col-sm-4">
                                            <label for="telefono" class="control-label gcore-label-top">Teléfono:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="telefono" name="telefono" id="telefono" value="" placeholder="Ingrese el telefono" class="form-control" type="text" @click="deleteError('telefono')" @focus="deleteError('telefono')" />
                                            <small id="msn1" class="help-block result-telefono" v-show="listErrors.telefono"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.email}">
                                        <div class="col-sm-4">
                                            <label for="email" class="control-label gcore-label-top">Email:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="email" name="email" id="email" value="" placeholder="Ingrese el email" class="form-control" type="emial" @click="deleteError('email')" @focus="deleteError('email')" />
		                                    <small id="msn1" class="help-block result-email" v-show="listErrors.email"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.contacto}">
                                        <div class="col-sm-4">
                                            <label for="contacto" class="control-label gcore-label-top">Contacto:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="contacto" name="contacto" id="contacto" value="" placeholder="Ingrese el nombre del contacto" class="form-control" type="text" @click="deleteError('contacto')" @focus="deleteError('contacto')" />
		                                    <small id="msn1" class="help-block result-contacto" v-show="listErrors.contacto"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.ciudad}">
                                        <div class="col-sm-4">
                                            <label for="ciudad" class="control-label gcore-label-top">Ciudad:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="ciudad" name="ciudad" id="ciudad" value="" placeholder="Ingrese la ciudad del transportador" class="form-control" type="text" @click="deleteError('ciudad')" @focus="deleteError('ciudad')"/>
		                                    <small id="msn1" class="help-block result-ciudad" v-show="listErrors.ciudad"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.estado}">
                                        <div class="col-sm-4">
                                            <label for="estado" class="control-label gcore-label-top">Estado / Depto:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="estado" name="estado" id="estado" value="" placeholder="Ingrese el estado del transportador" class="form-control" type="text" @click="deleteError('estado')" @focus="deleteError('estado')" />
		                                    <small id="msn1" class="help-block result-estado" v-show="listErrors.estado"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.pais}">
                                        <div class="col-sm-4">
                                            <label for="pais" class="control-label gcore-label-top">País:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="pais" name="pais" id="pais" value="" placeholder="Ingrese el pais del transportador" class="form-control" type="text" @click="deleteError('pais')" @focus="deleteError('pais')" />
		                                    <small id="msn1" class="help-block result-pais" v-show="listErrors.pais"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.zip}">
                                        <div class="col-sm-4">
                                            <label for="zip" class="control-label gcore-label-top">Código ZIP:</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="zip" name="zip" id="zip" value="" placeholder="Ingrese el zip-code del transportador" class="form-control" type="text" @click="deleteError('zip')" @focus="deleteError('zip')" />
		                                    <small id="msn1" class="help-block result-zip" v-show="listErrors.zip"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" style="float: right;" :class="{'has-error': listErrors.zip}">
                                        <div class="checkbox checkbox-success checkbox-inline">
                                            <input type="checkbox" id="shipper" name="shipper" v-model="shipper">
                                            <label for="shipper"><i class="fa fa-user"></i>  Shipper </label>
                                        </div>
                                       <div class="checkbox checkbox-success checkbox-inline">
                                            <input type="checkbox" id="consignee" name="consignee" v-model="consignee">
                                            <label for="consignee"><i class="fa fa-user-o"></i>  Consignee </label>
                                        </div>
                                       <div class="checkbox checkbox-success checkbox-inline">
                                            <input type="checkbox" id="carrier" name="carrier" v-model="carrier">
                                            <label for="carrier"><i class="fa fa-plane"></i>  Carrier </label>
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
        </form>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Transportadores</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-transportador" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Ciudad</th>
                                    <th>Zip</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Ciudad</th>
                                    <th>Zip</th>
                                    <th>Contacto</th>
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
<script src="{{ asset('js/templates/transportador.js') }}"></script>
@endsection
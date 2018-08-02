@extends('layouts.app')
@section('title', 'Consignee')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Consignee</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Consignee (Destinatario)</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70"></script>
    <div class="row" id="consignee">
        <form id="formconsignee" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de consignee (Destinatario)</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.agencia_id}">
                                        <div class="col-sm-4">
                                            <label for="agencia_id" class="control-label gcore-label-top">Agencia:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="agencia_id_input" value="">
                                            <select v-model="agencia_id" name="agencia_id" id="agencia_id" placeholder="" class="form-control js-data-example-ajax select2-container" @click="deleteError('agencia_id')" >
                                            </select>
                                            <small id="msn1" class="help-block result-agencia_id" v-show="listErrors.agencia_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="ident">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.tipo_identificacion_id}">
                                        <div class="col-sm-4">
                                            <label for="tipo_identificacion_id" class="control-label gcore-label-top">Tipo identificación:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="tipo_identificacion_id_input" value="">
                                            <select v-model="tipo_identificacion_id" name="tipo_identificacion_id" id="tipo_identificacion_id" placeholder="" class="form-control js-data-example-ajax select2-container" @click="deleteError('agencia_id')" >
                                            </select>
                                            <small id="msn1" class="help-block result-tipo_identificacion_id" v-show="listErrors.tipo_identificacion_id"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" v-if="ident">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.documento}">
                                        <div class="col-sm-4">
                                            <label for="documento" class="control-label gcore-label-top">Documento:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="documento" name="documento" id="documento" value="" placeholder="N° documento" class="form-control" type="text" style="" @click="deleteError('documento')" />
                                            <small id="msn1" class="help-block result-documento" v-show="listErrors.documento"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.primer_nombre}">
                                        <div class="col-sm-4">
                                            <label for="primer_nombre" class="control-label gcore-label-top">Nombres:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="primer_nombre" name="primer_nombre" id="primer_nombre" value="" placeholder="Nombre" class="form-control" type="text" style="" @click="deleteError('primer_nombre')" />
                                            <small id="msn1" class="help-block result-primer_nombre" v-show="listErrors.primer_nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="display: none">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.segundo_nombre}">
                                        <div class="col-sm-4">
                                            <label for="segundo_nombre" class="control-label gcore-label-top">Segundo Nombre:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="segundo_nombre" name="segundo_nombre" id="segundo_nombre" value="" placeholder="Segundo nombre" class="form-control" type="text" style="" @click="deleteError('segundo_nombre')" />
                                            <small id="msn1" class="help-block result-segundo_nombre" v-show="listErrors.segundo_nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.primer_apellido}">
                                        <div class="col-sm-4">
                                            <label for="primer_apellido" class="control-label gcore-label-top">Apellidos:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="primer_apellido" name="primer_apellido" id="primer_apellido" value="" placeholder="Apellidos" class="form-control" type="text" style="" @click="deleteError('primer_apellido')" />
                                            <small id="msn1" class="help-block result-primer_apellido" v-show="listErrors.primer_apellido"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="display: none">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.segundo_apellido}">
                                        <div class="col-sm-4">
                                            <label for="segundo_apellido" class="control-label gcore-label-top">Segundo Apellido:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="segundo_apellido" name="segundo_apellido" id="segundo_apellido" value="" placeholder="Segundo apellido" class="form-control" type="text" style="" @click="deleteError('segundo_apellido')" />
                                            <small id="msn1" class="help-block result-segundo_apellido" v-show="listErrors.segundo_apellido"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.direccion}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">Dirección:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="direccion" name="direccion" id="direccion" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('direccion')" />
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
                                            <input v-model="telefono" name="telefono" id="telefono" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('telefono')" />
                                            <small id="msn1" class="help-block result-telefono" v-show="listErrors.telefono"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.correo}">
                                        <div class="col-sm-4">
                                            <label for="correo" class="control-label gcore-label-top">Correo:</label>
                                        </div>
                                        <div class="col-sm-8"  :class="{ 'has-error': errors.has('correo') }">
                                            <input v-model="correo" name="correo" id="correo" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('correo')" v-validate.disable="'unique'" />
                                            <small id="msn1" class="help-block result-correo" v-show="listErrors.correo"></small>
                                            <small class="help-block has-error" :class="{ 'small': errors.has('correo') }">@{{ errors.first('correo') }}</small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.localizacion_id}">
                                        <div class="col-sm-4">
                                            <label for="localizacion_id" class="control-label gcore-label-top">Ciudad:<samp id="require">*</samp></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="localizacion_id_input" value="">
                                            <input type="hidden" id="depto" value="">
                                            <input type="hidden" id="pais" value="">
                                            <select v-model="localizacion_id" name="localizacion_id" id="localizacion_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('localizacion_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-localizacion_id" v-show="listErrors.localizacion_id"></small>
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
                                            <div class="input-group">
                                                <input v-model="zip" name="zip" id="zip" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('zip')" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" @clicK="getZipCode()" data-toggle="tooltip" data-placement="top" title="Generar" type="button"><span class="fa fa-map-marker"></span></button>
                                                </span>
                                            </div><!-- /input-group -->
                                            <small id="msn1" class="help-block result-zip" v-show="listErrors.zip"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">                        
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label for="emailsend" class="control-label gcore-label-top">Enviar Email:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="checkbox checkbox-success checkbox-inline">
                                                <input v-model="emailsend" type="checkbox" id="emailsend" name="emailsend">
                                                <label for="emailsend"><i class="fa fa-envelope"></i> Enviar email con datos de su casillero. </label>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="display: none">                            
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.tarifa}">
                                        <div class="col-sm-4">
                                            <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input v-model="tarifa" name="tarifa" id="tarifa" value="0" placeholder="0.00" class="form-control" type="number" style="" @click="deleteError('tarifa')" />
                                            <small id="msn1" class="help-block result-tarifa" v-show="listErrors.tarifa"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="row">
                            <contactos-component :table="'consignee'" :parametro="parametro"></contactos-component>
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Consignee (Destinatarios)</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-consignee" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>PO BOX</th>
                                        <th>Nombre</th> 
                                        <th>Teléfono</th>
                                        <th>Ciudad</th>
                                        <th>Agencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PO BOX</th>
                                        <th>Nombre</th> 
                                        <th>Teléfono</th>
                                        <th>Ciudad</th>
                                        <th>Agencia</th>                                
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>             
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/consignee.js') }}"></script>
<script src="{{ asset('js/templates/documento/postalCode.js') }}"></script>
@endsection

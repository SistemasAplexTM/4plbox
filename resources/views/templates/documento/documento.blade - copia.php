@extends('layouts.app')
@section('title', ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre)
@section('breadcrumb')
<link href="https://cdn.datatables.net/keytable/2.3.2/css/keyTable.dataTables.min.css" rel="stylesheet" media='print'>
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li >
                <a href="{{ route('documento.index') }}">{{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</a>
            </li>
            <li class="active">
                <strong>Crear {{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</strong>
            </li>
        </ol>
    </div>
</div>
<style type="text/css">
    .help-block{
        color: #ed5565;
    }
    .small{
        display: inline-block;
    }
    .panel{
        background-color: transparent;
        border: 0px;
    }
    #linkE, #linkM, #linkEc, #linkMc{
        color: #1ab394;
    }

    .popover-content{
        font-size: 12px;
    }
    .pasos_guia{
        background-color: #f7f7f7;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
    }
    .btn-actions{
        padding:5px;
    }
    .v-select{
        background-color:#FFFFFF;
    }
    .v-select .dropdown li {
      border-bottom: 1px solid rgba(112, 128, 144, 0.1);
    }

    .v-select .dropdown li:last-child {
      border-bottom: none;
    }

    .v-select .dropdown li a {
      padding: 10px 20px;
      width: 100%;
      font-size: 1.25em;
      color: #3c3c3c;
    }

    .v-select .dropdown-menu .active > a {
      color: #fff;
    }
    .dropdown-toggle>input[type="search"] {
    width: 100px !important;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 100% !important;
    }
    .addtrackings{
        padding-top: 1px;
        padding-bottom: 1px;
    }
    #tbl-trackings_wrapper{
        padding-bottom: 0px;
    }
</style>
<link href="{{ asset('css/plugins/dataTables/keyTable.dataTables.min.css') }}">
@endsection

@section('content')
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/plugins/dataTables/keyTable.dataTables.min.css') }}"></script> --}}
    {{-- DEFAULT VALUES --}}
    <?php $id_pa = 234; ?> {{-- id de la posicion por defecto --}}
    <div class="row" id="documento">
        <modalshipper-component></modalshipper-component>
        <modalconsignee-component></modalconsignee-component>
        <modalarancel-component></modalarancel-component>
        <modalcargosadd-component :showmodal="showmodalAdd"></modalcargosadd-component>
        <div class="col-lg-12">
            <form class="" id="formDocumento" name="formDocumento" class=" form-horizontal" role="form" action="{{ url('documento/updatedDocument') }}/{{  $documento->id }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" id="id_documento" name="id_documento"  value="{{ $documento->id }}" readonly="">
                <input type="hidden" class="form-control" name="document_type" id="document_type" data-liquidado="{{ $documento->liquidado }}"  value="consolidado" readonly="" v-model="document_type">
                <div class="col-lg-12" style="padding-left: 0px; padding-right: 0px;">
                    <div class="col-lg-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label for="agencia_id" class="">Agencia</label>
                                <select id="agencia_id" name="agencia_id" class="form-control" style="font-size: 17px;font-weight: 900;">
                                    @if(isset($agencias) and $agencias)
                                        @role('admin')
                                            @foreach($agencias as $agencia)
                                                <option {{ ($documento->agencia_id == $agencia['id']) ? 'selected' : ((Auth::user()->agencia_id == $agencia['id']) ? 'selected ' : '') }} value="{{ $agencia['id'] }}">{{ $agencia['descripcion'] }}</option>
                                            @endforeach
                                        @else
                                            @foreach($agencias as $agencia)
                                                @if(Auth::user()->agencia_id == $agencia['id'])
                                                    <option {{ ($documento->agencia_id == $agencia['id']) ? 'selected' : ((Auth::user()->agencia_id == $agencia['id']) ? 'selected ' : '') }} value="{{ $agencia['id'] }}">{{ $agencia['descripcion'] }}</option>
                                                @endif
                                            @endforeach
                                        @endrole
                                    @endif
                                </select>
                        </div>
                    </div>
                    @if(isset($agencia) and $agencia)
                        <div class="col-lg-6" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="num_guia" class="">Número de Documento</label>
                                <input type="text" id="num_guia" name="num_guia" class="form-control" readonly="" value="{{ $documento->consecutivo }}" style="background-color: #FFFFFF; font-size: 20px; font-weight: bold; color: forestgreen;">
                            </div>
                        </div>
                    @endif
                </div>
                {{-- FORMULARIO DE CONSOLIDADO --}}
                <formconsolidado-component :app_type="'{{ env('APP_TYPE') }}'" :documento="{{ json_encode($documento) }}" :contactos="contactos" :restore="restoreShipperConsignee" :agrupar="datosAgrupar" :removeragrupado="removerAgrupado" :permission='permissions' v-if="mostrar.includes(24)"></formconsolidado-component>

                {{-- CONSIGNEE Y SHIPPER --}}
                <div class="row form_doc" style="display: none">
                    <div class="col-lg-6" style="margin-bottom: 20px;" v-if="mostrar.includes(25)">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><span class="fa fa-arrow-circle-up"> </span> Remitente (Shipper) <span style="color: coral; display: none;" id="msnEditarShip">Preparado para edición...</span></h5>
                                
                            </div>
                            <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                <div class="row">
                                    <div class="col-sm-12"  data-container="body" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Para registrar un nuevo Shipper, hacer clic en el icono (Reset Shipper) e ingresar los nuevos datos." style="padding-left: 0px; padding-right: 0px;">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nombre: </label> 
                                            <div class="col-sm-10">
                                                <div class="input-group"  style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreR') }">
                                                    <input type="search" data-id="nomBuscarShipper" id="nombreR" name="nombreR" placeholder="Digite para buscar..." class="form-control" onkeyup="deleteError($(this).parent());" v-model="nombreR" v-validate="'required'">
                                                    <span class="input-group-btn">
                                                        <button id="btnBuscarShipper" @click="modalShipper(true)" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Shipper"><span class="fa fa-search"></span> Buscar</button>
                                                        <button id="btnEditShipper" @click="editFormsShipperConsignee(0)" class="btn btn-success" type="button" data-toggle='tooltip' title="Editar Shipper"><span class="fa fa-edit"></span>&nbsp;</button>
                                                        <button id="btnResetShipper" @click="resetFormsShipperConsignee(0)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset Shipper"><span class="fa fa-refresh"></span>&nbsp;</button>
                                                    </span>
                                                </div>
                                                <small class="help-block has-error">@{{ errors.first('nombreR') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="control-label col-sm-2">Dirección: </label> 
                                    <div class="col-sm-10" :class="{ 'has-error': errors.has('direccionR') }">
                                        <input type="text" id="direccionR" name="direccionR" placeholder="Dirección" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());" v-model="direccionR" v-validate="'required'">
                                        <small class="help-block has-error">@{{ errors.first('direccionR') }}</small>
                                    </div>
                                </div>

                                    <!-- /Grupo Doble 1 -->
                                     <div class="row">
                                        <label class="control-label col-sm-2">Email: </label> 
                                        <div class="col-sm-5" :class="{ 'has-error': errors.has('emailR') }">
                                            <input type="email" placeholder="Example@example.com" id="emailR" name="emailR" class="form-control" v-validate.disable="'unique_s'">
                                            <small class="help-block has-error">@{{ errors.first('emailR') }}</small>
                                        </div>
                                        <label class="control-label col-sm-1">Teléfono: </label>
                                        <div class="col-sm-4">
                                            <input type="tel" data-mask="(999) 999-9999" placeholder="Teléfono" id="telR" name="telR" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());">
                                            <small class="help-block has-error">@{{ errors.first('telR') }}</small>
                                        </div>
                                        <small class="help-block" style="display: none;"></small>
                                    </div>
                                    <!-- /Fin Grupo Doble 1 -->

                                    <!-- /Grupo Doble 2 -->
                                     <div class="row">
                                        <label class="control-label col-sm-2">Ciudad: </label>
                                        <div class="col-sm-5" onclick ="deleteError($(this));">
                                            <input type="hidden" id="localizacion_id_input" value="">
                                            <input type="hidden" id="prefijoR" name="prefijoR" value="">
                                            <select name="localizacion_id" id="localizacion_id" class="form-control js-data-example-ajax select2-container">
                                            </select>
                                            {{-- <v-select name="localizacion_id" v-model="localizacion_id" label="name" :filterable="false" :options="citys" @search="onSearchCity" v-validate="'required'">
                                                </v-select> --}}
                                            <small class="help-block has-error" id="msn_l1" style="display: none;">Campo obligatorio</small>
                                        </div>
                                        <label class="control-label col-sm-1">Zip: </label>
                                        <div class="col-sm-4">
                                            <input type="number" placeholder="Zip" id="zipR" name="zipR" class="form-control" onkeyup="deleteError($(this).parent());">
                                        </div>
                                        <small class="help-block" style="display: none;"></small>
                                    </div>
                                    <!-- /Fin Grupo Doble 2 -->
                                    <div class="row">
                                        <input type="checkbox" id="opEditarShip" name="opEditarShip" style="display: none;">

                                        <input type="hidden" class="" id="shipper_id" name="shipper_id" value="{{ isset($documento->shipper_id) ? $documento->shipper_id : '' }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="enviarEmailRemitente" name="enviarEmailRemitente" value="t" style="margin-left: -50px;">
                                                    <label for="enviarEmailRemitente"> Enviar Email <i class="fa fa-envelope-open"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-lg-6" style="margin-bottom: 20px;" v-if="mostrar.includes(26)">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>
                                    <span class="fa fa-arrow-circle-down"> </span> Destinatario (Consignee) 
                                    <span style="color: coral; display: none;" id="msnEditarCons">Preparado para edición...</span>
                                    <label class="po">PO#</label>
                                    <input type="text" id="poBoxD" name="poBoxD" class="" value="" style="border-color: transparent;">
                                </h5>
                            </div>
                            <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                <div class="row">
                                    <div class="col-sm-12"  data-container="body" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Para registrar un nuevo Consignee para este Shipper, hacer clic en el icono (Reset Consignee) e ingresar los nuevos datos." style="padding-left: 0px; padding-right: 0px;">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nombre: </label>
                                            <div class="col-sm-10">
                                                <input type="hidden" value="" id="urlBuscarConsignee">
                                                <div class="input-group" style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreD') }">
                                                    <input type="search" data-id="nomBuscarConsignee" class="form-control" id="nombreD" name="nombreD" placeholder="Digite para buscar..." onkeyup="deleteError($(this).parent());" v-model="nombreD" v-validate="'required'">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" @click="modalConsignee(true)" id="btnBuscarConsignee" type="button" data-toggle='tooltip' title="Buscar consignee"><span class="fa fa-search"></span> Buscar</button>
                                                        <button id="btnEditConsignee" @click="editFormsShipperConsignee(1)" class="btn btn-success" type="button" data-toggle='tooltip' title="Editar consignee"><span class="fa fa-edit"></span>&nbsp;</button>
                                                        <button id="btnResetConsignee" @click="resetFormsShipperConsignee(1)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset consignee"><span class="fa fa-refresh"></span>&nbsp;</button>
                                                        </span>
                                                </div><!-- /input-group -->
                                                <small class="help-block has-error">@{{ errors.first('nombreD') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Dirección: </label> 
                                            <div class="col-sm-10" :class="{ 'has-error': errors.has('direccionD') }">
                                                <input type="text" placeholder="Dirección" id="direccionD" name="direccionD" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());" v-model="direccionD" v-validate="'required'">
                                                <small class="help-block has-error">@{{ errors.first('direccionD') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <!-- /Grupo Doble 1 -->
                                        <label class="control-label col-sm-2">Email: </label> 
                                        <div class="col-sm-5" :class="{ 'has-error': errors.has('emailD') }">
                                            <input type="email" placeholder="Example@example.com" id="emailD" name="emailD" class="form-control" v-validate.disable="'unique_c'">
                                            <small class="help-block has-error" :class="{ 'small': errors.has('emailD') }">@{{ errors.first('emailD') }}</small>
                                        </div>
                                        <label class="control-label col-sm-1">Teléfono: </label>
                                        <div class="col-sm-4">
                                            <input type="tel" data-mask="(999) 999-9999" id="telD" name="telD" placeholder="Teléfono" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());">
                                            <small class="help-block has-error">@{{ errors.first('telD') }}</small>
                                        </div>
                                    <!-- /Fin Grupo Doble 1 -->
                                    </div>
                                    <div class="row">
                                    <!-- /Grupo Doble 2 -->
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Ciudad: </label>
                                            <div class="col-sm-5" onclick ="deleteError($(this));">
                                                <input type="hidden" id="localizacion_id_input_c" value="">
                                                <input type="hidden" id="prefijoD" name="prefijoD" value="">
                                                <input type="hidden" id="deptoD" value="">
                                                <input type="hidden" id="paisD" value="">
                                                <select name="localizacion_id_c" id="localizacion_id_c" class="form-control js-data-example-ajax select2-container" @click="deleteError('localizacion_id_c')">
                                                </select>
                                                <small class="help-block has-error" id="msn_l2" style="display: none;">Campo obligatorio</small>
                                            </div>
                                            <label class="control-label col-sm-1">C.P: </label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <input type="number" placeholder="Código Postal" id="zipD" name="zipD" class="form-control" onkeyup="deleteError($(this).parent());">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" id="buttonPostalCode" data-toggle="tooltip" data-placement="top" title="Generar" type="button"><span class="fa fa-map-marker"></span></button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>
                                            <small class="help-block" style="display: none;"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- /Fin Grupo Doble 2 -->
                                        <input type="checkbox" id="opEditarCons" name="opEditarCons" style="display: none;">
                                        <input type="hidden" class="" id="consignee_id" name="consignee_id"  value="{{ isset($documento->consignee_id) ? $documento->consignee_id : '' }}">
                                    </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-success checkbox-inline">
                                                <input type="checkbox" id="enviarEmailDestinatario" name="enviarEmailDestinatario" value="t" style="margin-left: -50px;">
                                                <label for="enviarEmailDestinatario"> Enviar Email <i class="fa fa-envelope-open"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="row form_doc" style="display: none" v-if="mostrar.includes(22) || mostrar.includes(23)">
                    <div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Datos de la carga</h5>
                                <div class="ibox-tools">
                                    
                                </div>
                            </div>
                            <!--************************************* DATOS DE CARGA PARA GUIA ****************************-->
                            <div class="form-horizontal">
                                <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                    <div class="col-lg-12">
                                        <div class="row pasos_guia" id="detalle_guia">
                                            <div class="row">
                                            <div class="col-sm-2">
                                                    <div class="form-group"  id="Valpeso">
                                                        <label class="peso">Peso</label>
                                                        <input type="number" class="form-control" onkeyup="deleteError($(this).parent());" id="peso" name="peso" maxlength="4" placeholder="Lb" value="">
                                                        <small class="help-block" id="Hpeso" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                            </div>
                                            <div class="col-sm-4">
                                                    <div class="form-group"  id="Valdim">
                                                        <label class="dimensiones">Dimensiones (L x W x H)</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="L" maxlength="4" id="largo" onkeyup="deleteError($(this).parent());" name="largo" value="0">
                                                            <span class="input-group-addon">x</span>
                                                            <input type="text" class="form-control" placeholder="W" maxlength="4" id="ancho" onkeyup="deleteError($(this).parent());" name="ancho" value="0">
                                                            <span class="input-group-addon">x</span>
                                                            <input type="text" class="form-control" placeholder="H" maxlength="4" id="alto" onkeyup="deleteError($(this).parent());" name="alto" value="0">                                                    
                                                        </div>
                                                        <small class="help-block" id="Hdim" style="display: none">Estos Datos son obligatorios</small>
                                                    </div>
                                            </div>
                                            <div :class="[mostrar.includes(22) ? 'col-sm-6' : 'col-sm-6' ]">
                                                <label class="contiene">Contenido</label>
                                                    <div class="form-group"  id="Valconti">
                                                        <label class="contiene" style="display: none;"></label>
                                                        <input type="text" onkeyup="deleteError($(this).parent());" id="contiene" name="contiene" class="form-control" value="" placeholder="Contenido">
                                                        <small class="help-block" id="Hcontiene" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                           <div class="col-sm-2">
                                                <label class="valDeclarado">Declarado</label>
                                                    <div class="form-group" id="ValDecla">
                                                        <label style="display: none;" for="" class=""></label>
                                                        <input type="number" onkeyup="deleteError($(this).parent());" placeholder=" Declarado" onkeyup="deleteError($(this).parent());" id="valDeclarado" name="valDeclarado" class="form-control" value="">
                                                        <small class="help-block" id="HvalDeclarado" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                            </div>
                                           <div class="col-sm-2" v-if="mostrar.includes(34)">
                                                <label class="valDeclarado">Piezas</label>
                                                    <div class="form-group" id="ValDecla">
                                                        <label style="display: none;" for="" class=""></label>
                                                        <input type="number" onkeyup="deleteError($(this).parent());" placeholder=" Piezas" onkeyup="deleteError($(this).parent());" id="valPiezas" name="valPiezas" class="form-control" value="1">
                                                        <small class="help-block" id="Hpiezas" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                            </div>
                                            <div class="col-sm-2">
                                                    <div class="form-group" id="Valtipoem">
                                                        <label for="tipo_empaque_id" class="">Empaque</label>
                                                        <select  onchange="deleteError($(this).parent());" id="tipo_empaque_id" name="tipo_empaque_id" class="form-control">
                                                            @if(isset($empaques) and $empaques)
                                                                @foreach($empaques as $empaque)
                                                                    <option value="{{ $empaque['id'] }}">{{ $empaque['nombre'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block" id="HtipoE" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                            </div>                                    
                                            <div class="col-sm-4" v-if="mostrar.includes(16)">
                                                <label for="pa" class="">Posicion arancelaria</label>
                                                    <div class="form-group" id="Errpa">
                                                        <label style="display: none;" for="" class=""></label>
                                                        <input type="hidden" value="" id="urlBuscarPA">
                                                        <div class="input-group">
                                                            <span class="input-group-btn" onclick="deleteError($(this).parent());">
                                                                <button class="btn btn-primary" id="btnBuscarPA" type="button" @click="modalArancel()"><small><span class="fa fa-search"></span> P.A (Adu.)</small></button>
                                                            </span>
                                                            <input type="text" placeholder="seleccionar" class="form-control" readonly="" value="" id="pa" name="pa" onkeyup="deleteError($(this).parent());">
                                                        </div><!-- /input-group -->
                                                        <small class="help-block" id="Hpa" style="display: none">Este campo es obligatorio</small>
                                                    </div>
                                               
                                                    <input type="hidden" placeholder="0" class="form-control" readonly="" value="{{ $id_pa }}" id="pa_id" name="pa_id">
                                                <!--<div class="col-sm-2">-->
                                                <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="arancel" name="arancel">
                                                <!--</div>-->
                                                <!--<div class="col-sm-2">-->
                                                <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="iva" name="iva">
                                                
                                            </div>
                                            <!--</div>-->
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="btn_add" class="control-label" style="padding-top: 2px;">&nbsp;</label>
                                                        <!--para quitar el efecto de bloqueo del boton, quitar la clase btnBlock-->
                                                        <button class="btn btn-info btn-sm btnBlock" type="button" id="btn_add" value="0" @click="addDetail()" style="width: 100%"><span class="fa fa-plus" ></span> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row pasos_guia" id="grilla_guia">
                                            <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered table-hover" id="whgTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 17%;" v-if="mostrar.includes(23)">Código</th>
                                                                        <th style="width: 10%;">Peso (Lb)</th>
                                                                        <th style="">Contiene</th>
                                                                        <th style="width: 15%;">PA</th>
                                                                        <th style="width: 10%;" v-if="mostrar.includes(23)">Valor US$</th>
                                                                        <th style="width: 20%;">Acción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="background: white;">
                                                                    @foreach ($detalle as $key)
                                                                        <tr id="fila{{ $key->id }}">
                                                                            <td v-if="mostrar.includes(23)">
                                                                                <input type="text" id="numGuia{{ $key->id }}" name="numGuia[]" value="{{ $key->num_warehouse }}" class="form-control" readonly style="font-size: small;">
                                                                            </td>
                                                                            <td class="">
                                                                                <input type="number" id="pesoD{{ $key->id }}" name="pesoD[]" value="{{ $key->peso }}" class="form-control cp_peso" readonly="" onkeyup="totalizeDocument();">
                                                                                <input type="hidden" id="volumen{{ $key->id }}" name="volumen[]" value="{{ $key->volumen }}" class="form-control cp_volumen">
                                                                                <input type="hidden" id="dimensiones{{ $key->id }}" name="dimensiones[]" value="{{ $key->dimensiones }}" class="form-control" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" id="contiene{{ $key->id }}" name="contiene[]" value="{{ $key->contenido }}" class="form-control" readonly="">
                                                                                <input type="hidden" id="tempaque{{ $key->id }}" name="tempaque[]" value="{{ $key->tipo_empaque_id }}" class="form-control" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" id="pa{{ $key->id }}" name="pa[]" value="{{ $key->nom_pa }}" class="form-control" readonly >
                                                                                <input type="hidden" id="id_pa{{ $key->id }}" name="id_pa[]" value="{{ $key->id_pa }}" class="form-control" readonly >
                                                                            </td>
                                                                            <td v-if="mostrar.includes(23)">
                                                                                <input type="number" id="valorDeclarado{{ $key->id }}" name="valorDeclarado[]" value="{{ $key->valor }}" class="form-control cp_declarado" readonly="" onkeyup="totalizeDocument(this);" style="border-color:{{ ($key->valor == '' || $key->valor == 0) ? 'coral' : '' }}">
                                                                                <input type="hidden" id="arancel{{ $key->id }}" name="arancel[]" class="form-control" value="{{ $key->valor }}" readonly="">
                                                                                <input type="hidden" id="iva{{ $key->id }}" name="iva[]" class="form-control" value="{{ $key->valor }}" readonly="">
                                                                            </td>
                                                                            <td>
                                                                                <a class="btn btn-info btn-xs btn-actions addTrackings" type="button" id="btn_addtracking{{ $key->id }}" data-toggle='tooltip' title='Agregar tracking' @click="addTrackings({{ $key->id }})"><i class="fa fa-barcode"></i> <span id="cant_tracking{{ $key->id }}">{{ $key->cantidad }}</span></a>

                                                                                <a class="btn btn-primary btn-xs btn-actions" type="button" id="btn_confirm{{ $key->id }}" onclick="saveTableDetail({{ $key->id }})" data-toggle='tooltip' title='Guardar' style="display:none;"><i class="fa fa-check"></i></a>

                                                                                <a class="btn btn-success btn-xs btn-actions" type="button" id="btn_edit{{ $key->id }}" onclick="editTableDetail({{ $key->id }})" data-toggle='tooltip' title='Editar'><i class="fa fa-edit"></i></a>

                                                                                <a class="btn btn-danger btn-xs btn-actions" type="button" id="btn_remove{{ $key->id }}" onclick="eliminar({{ $key->id }}, true)" data-toggle='tooltip' title='Eliminar' style="display: {{ ($key->consolidado == 1) ? 'none' : 'inline-block' }}"><i class="fa fa-times"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="" v-if="mostrar.includes(16)">
                                                                                    <div class="form-group">
                                                                                        <label class="">Pieza(s)</label>
                                                                                        <input type="text" onkeyup="deleteError($(this).parent());" id="piezas" name="piezas" class="form-control" readonly="" value="{{ isset($documento->piezas) ? $documento->piezas : '' }}">
                                                                                    </div>
                                                                            </div>
                                                                        </td>
                                                                        <td colspan="3">
                                                                           <div class="col-sm-6" v-if="mostrar.includes(16)">
                                                                                <div class="form-group">
                                                                                    <label class="">Peso total</label>
                                                                                    <input type="text" onkeyup="deleteError($(this).parent());" id="pesoDim" name="pesoDim" class="form-control" readonly="" value="{{ isset($documento->peso) ? $documento->peso : '' }}">
                                                                                </div>
                                                                            </div> 
                                                                            <div class="col-sm-6" v-if="mostrar.includes(16)">
                                                                                    <div class="form-group">
                                                                                        <label class="">Volumen</label>
                                                                                        <input type="text" onkeyup="deleteError($(this).parent());" id="volumen" name="volumen" class="form-control" readonly="" value="{{ isset($documento->volumen) ? $documento->volumen : '' }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div> 
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="" v-if="mostrar.includes(16)">
                                                                                    <div class="form-group">
                                                                                        <label class="">$ Declarado total</label>
                                                                                        <input type="text" onkeyup="deleteError($(this).parent());" id="valor_declarado_tbl" class="form-control" readonly="" value="0">
                                                                                    </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div id="noEnviar" class="col-lg-12" style="text-align: center; color: red; display: none;">Para poder registrar es necesario almenos un dato en el detalle</div>

                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row pasos_guia" id="generales_guia">
                                            <div class="col-lg-4" v-if="mostrar.includes(17)">
                                                    <div class="form-group">
                                                        <label for="" class="">Tipo de Pago</label>
                                                        <select id="tipo_pago_id" name="tipo_pago_id" class="form-control" onchange="deleteError($(this).parent());">
                                                           @if(isset($tipoPagos) and $tipoPagos)
                                                                @foreach($tipoPagos as $tipoPago)
                                                                    <option {{ (isset($documento->tipo_pago_id) and $documento->tipo_pago_id === $tipoPago['id']) ? 'selected' : '' }} value="{{ $tipoPago['id'] }}">{{ $tipoPago['descripcion'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-4" v-if="mostrar.includes(12)">
                                                    <div class="form-group">
                                                        <label for="forma_pago_id" class="">Forma de Pago</label>
                                                        <select id="forma_pago_id" name="forma_pago_id" class="form-control" onchange="deleteError($(this).parent());">
                                                            @if(isset($formaPagos) and $formaPagos)
                                                                @foreach($formaPagos as $formaPago)
                                                                    <option {{ (isset($documento->forma_pago_id) and $documento->forma_pago_id === $formaPago['id']) ? 'selected' : '' }} value="{{ $formaPago['id'] }}">{{ $formaPago['nombre'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-4" v-if="mostrar.includes(18)">
                                                    <div class="form-group">
                                                        <label for="grupo_id" class="">Grupo</label>
                                                        <select id="grupo_id" name="grupo_id" class="form-control" onchange="deleteError($(this).parent());">
                                                            @if(isset($grupos) and $grupos)
                                                                @foreach($grupos as $grupo)
                                                                    <option {{ (isset($documento->grupo_id) and $documento->grupo_id === $grupo['id']) ? 'selected' : '' }} value="{{ $grupo['id'] }}">{{ $grupo['nombre'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>Comentarios: </label>
                                                        <textarea class="form-control" rows="3" style="height: 65px;" id="observaciones" name="observaciones">{{ isset($documento->observaciones) ? $documento->observaciones : '' }}</textarea>
                                                    </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox1" name="factura" value="1" {{ (isset($documento->factura) and $documento->factura != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox1"> Factura </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox2" name="carga_peligrosa" value="1" {{ (isset($documento->carga_peligrosa) and $documento->carga_peligrosa != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox2"> Carga Peligrosa </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox3" name="re_empacado" value="1" {{ (isset($documento->re_empacado) and $documento->re_empacado != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox3"> Re-Empacado </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox4" name="mal_empacado" value="1" {{ (isset($documento->mal_empacado) and $documento->mal_empacado != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox4"> Mal empacada </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox5" name="rota" value="1" {{ (isset($documento->rota) and $documento->rota != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox5"> Rota </label>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <input type="hidden" id="id" name="id" value="">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-sm-12 col-sm-offset-0 guardar">
                                                    <button type="button" id="saveForm" class="ladda-button btn btn-success" data-style="expand-right" @click="saveDocument()"><i class="fa fa-save fa-fw"></i> Guardar Cambios</button>
                                                    {{-- el href de la impresion lo pongo desde la funcion showHiddeFields desde vue --}}
                                                    
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-print"></i>  Imprmir <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="" id="printDocument" class="" data-style="expand-right" target="blank_"><i class="fa fa-print fa-fw"></i> Imprimir {{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</a></li>
                                                            <li><a href="" id="printLabel" class="" data-style="expand-right" target="blank_"><i class="fa fa-print fa-fw"></i> Imprimir Label</a></li>
                                                            <li role="separator" class="divider"></li>
                                                            <li><a href="" id="invoice" class="" data-style="expand-right" target="blank_"><i class="fa fa-print fa-fw"></i> Invoice</a></li>
                                                        </ul>
                                                    </div>

                                                    <a href="{{ route('documento.index') }}" type="button" class="btn btn-white"><i class="fa fa-times fa-fw"></i> Cancelar </a>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Totales</h5>
                                <div class="ibox-tools">
                                    <label>Liquidar</label>
                                    <input type='checkbox' data-toggle="toggle" id='show-totales' name="liquidar" @click="showTotals()" data-size='mini' data-on="Si" data-off="No" data-width="50" data-style="ios" data-onstyle="primary" data-offstyle="danger" {{ ($documento->liquidado != 0) ? 'checked="checked"' : '' }}>
                                </div>
                            </div>
                            <!-- TOTALES -->
                            <div class="form-horizontal" v-if="showFieldsTotals">
                                <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                    <div class="col-lg-12" style="">
                                        <div class="col-lg-12">
                                            <div class="form-group"  style="margin-top: 15px;">
                                                <div class="col-sm-6">
                                                    <label for="tipo_embarque_id" class="">Tipo Embarque - <span class="fa fa-ship"></span><span class="fa fa-plane"></span></label>
                                                    <select id="tipo_embarque_id" name="tipo_embarque_id" class="form-control" onchange="deleteError($(this).parent());llenarSelectServicio($(this).val())">
                                                        @if(isset($embarques) and $embarques)
                                                            @foreach($embarques as $embarque)
                                                                <option value="{{ $embarque['id'] }}" {{ (isset($documento->tipo_embarque_id) and $documento->tipo_embarque_id === $embarque['id']) ? 'selected' : '' }}>{{ $embarque['nombre'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="help-block" style="display: none">Campo obligatorio</small>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="servicios_id" class="">Tipo de Servicio</label>
                                                    <select onchange="calculateServiceType();" id="servicios_id" name="servicios_id" class="form-control" onchange="deleteError($(this).parent());"  onclick="deleteError($(this).parent());">
                                                        <option value="">Seleccione tipo embarque</option>
                                                        @if(isset($servicios) and $servicios)
                                                            @foreach($servicios as $servicio)
                                                                <option value="{{ $servicio['id'] }}" data-cobvol="{{ $servicio['cobro_peso_volumen'] }}" data-tarifamin="{{ $servicio['peso_minimo'] }}" data-tarifa="{{ $servicio['tarifa'] }}" data-seguro="{{ $servicio['seguro'] }}" data-c_opcional="{{ $servicio['cobro_opcional'] }}" data-t_age="{{ $servicio['tarifa_agencia'] }}" data-seg_age="{{ $servicio['seguro_agencia'] }}" data-impuesto_age="{{ $servicio['impuesto'] }}" {{ (isset($documento->servicios_id) and $documento->servicios_id === $servicio['id']) ? 'selected' : '' }}>{{ $servicio['nombre'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="help-block" style="display: none">Campo obligatorio</small>
                                                </div>
                                            </div>

                                            <div class="form-group" v-if="mostrar.includes(22)">
                                                <div class="col-sm-4">
                                                    <label class="">Peso</label>
                                                    <input type="text" onkeyup="deleteError($(this).parent());" id="pesoDim" name="pesoDim" class="form-control" readonly="" value="{{ isset($documento->peso) ? $documento->peso : '' }}">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="">Volumen</label>
                                                    <input type="text" onkeyup="deleteError($(this).parent());" id="volumen" name="volumen" class="form-control" readonly="" value="{{ isset($documento->volumen) ? $documento->volumen : '' }}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="">Pieza(s)</label>
                                                    <input type="text" onkeyup="deleteError($(this).parent());" id="piezas" name="piezas" class="form-control" readonly="" value="{{ isset($documento->piezas) ? $documento->piezas : '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group"  style="margin-top: 15px;" v-if="mostrar.includes(20)">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="peso_total">Peso Lb: </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="0" class="form-control" readonly="" id="peso_total" name="peso_total" value="{{ isset($documento->peso) ? $documento->peso : '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group" v-if="mostrar.includes(20)">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="peso_cobrado">Peso Cobrado Lb: </label>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="0" class="form-control" readonly="" id="peso_cobrado" name="peso_cobrado" value="{{ isset($documento->peso_cobrado) ? $documento->peso_cobrado : '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group" v-if="mostrar.includes(20)">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="valor_libra">Valor Lb: (<span id="valorLibra">{{ (isset($documento->valor_libra) and $documento->valor_libra != 0)  ? $documento->valor_libra : 0 }}</span>)</label>
                                                    <input type="hidden" id="valor_libra2" name="valor_libra2" value="{{ (isset($documento->valor_libra) and $documento->valor_libra != 0)  ? $documento->valor_libra : 0 }}">
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$ </span>
                                                        <input style="background-color: #FFFF99;" type="number" placeholder="0" value="{{ isset($documento->valor) ? $documento->valor : '' }}" onkeyup="totalizeDocument();" class="form-control" id="valor_libra" name="valor_libra">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" v-if="mostrar.includes(20)">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="impuesto"><div class="col-sm-12" data-trigger="hover"  data-container="body" data-toggle="popover" data-placement="left" data-content="Valor por el cual se calculara el impuesto sobre el valor declarado. (Por defecto 28%)" style="padding-left: 0px; padding-right: 0px;"><i class="fa fa-question-circle" style="cursor: pointer; color: coral;"></i> % Impuesto: </div></label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">% </span>
                                                        <input type="number" placeholder="0" value="{{ isset($documento->impuesto) ? $documento->impuesto : '' }}" onkeyup="totalizeDocument();" class="form-control" id="impuesto" name="impuesto" style="background-color:#FFFF99;border-color: cornflowerblue;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding-top: 5px;" v-if="mostrar.includes(20)">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                    <label class="control-label" for="valor_declarado">Declarado: </label>
                                                </div>
                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                    <label class="control-label" for="pa_aduana">Impuesto: </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" readonly="" style="font-size: 12px;" placeholder="$" value="{{ isset($documento->valor_declarado) ? $documento->valor_declarado : '' }}" onkeyup="" class="form-control" id="valor_declarado" name="valor_declarado">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" placeholder="0" class="form-control" value="{{ isset($documento->pa_aduana) ? $documento->pa_aduana : '' }}" readonly="" id="pa_aduana" name="pa_aduana">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="flete"><div class="col-sm-12"  data-container="body" data-trigger="hover"  data-toggle="popover" data-placement="left" data-content="Si el calculo es sobre el volumen (Vol), se evaluara quien es mayor (Peso o Volumen), si es mayor el volumen, se multiplicara por la tarifa. Si es mayor el peso, la diferencia (Peso-Volumen) sera multiplicada por la tarifa." style="padding-left: 0px; padding-right: 0px;"><i class="fa fa-question-circle" style="cursor: pointer; color: coral;"></i> Flete: (<span id="cobrarPor"></span>)</div></label>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" placeholder="0" value="{{ isset($documento->flete) ? $documento->flete : '' }}" class="form-control" readonly="" id="flete" name="flete">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="seguro_valor">Valor Asegurado $US: </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="seguro">Seguro: </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input style="background-color: #FFFF99;" type="number" onkeyup="totalizeDocument();" class="form-control" placeholder="0" maxlength="4" id="seguro_valor" name="seguro_valor" value="{{ isset($documento->seguro) ? $documento->seguro : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" placeholder="0" class="form-control" readonly="" value="" id="seguro" name="seguro">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <button class="btn btn-info" id="btnBuscarCargosAdd" type="button" @click="modalAdditionalCharges()"><span class="fa fa-plus"></span> Cargos Ad.</button>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" placeholder="0" class="form-control" readonly="" id="cargos_add" name="cargos_add" value="{{ isset($documento->cargos_add) ? $documento->cargos_add : '' }}" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="descuento">Descuento: </label>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input style="background-color: #FFFF99;" type="number" placeholder="0" class="form-control" value="{{ isset($documento->descuento) ? $documento->descuento : '' }}" onkeyup="totalizeDocument();" id="descuento" name="descuento">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label" for="total">Total: </label>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" placeholder="0" readonly="" value="{{ isset($documento->total) ? $documento->total : '' }}" class="form-control" id="total" name="total">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL AGREGAR TRACKINGS --}}
    <div class="modal fade bs-example" id="modalTrackingsAdd" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h2 class="modal-title" id="myModalLabel"><i class="fa fa-barcode"></i> Agregar trackings</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>Trackings disponibles</h2>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="tbl-trackings"  style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tracking</th>
                                                <th>Contenido</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h2>Trackings asociados</h2>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="tbl-trackings-used" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tracking</th>
                                                <th>Contenido</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/plugins/dataTables/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/fnFilterClear.js') }}"></script>
<script src="{{ asset('js/templates/documento/documento.js') }}"></script>
<script src="{{ asset('js/templates/documento/totalizar.js') }}"></script>
<script src="{{ asset('js/templates/documento/postalCode.js') }}"></script>
@endsection
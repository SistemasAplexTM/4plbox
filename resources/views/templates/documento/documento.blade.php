@extends('layouts.app')
@section('title', ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre)
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('documents.home')</a>
            </li>
            <li >
                <a href="{{ route('documento.index') }}">{{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</a>
            </li>
            <li class="active">
                <strong>@lang('documents.create') {{ ($documento->liquidado == 1) ? 'Guia' : $documento->tipo_nombre }}</strong>
            </li>
        </ol>
    </div>
</div>
<style type="text/css">
  @-webkit-keyframes ripple {
    0% {
      opacity: 0;
    }
    30% {
      opacity: 1;
    }
    100% {
      opacity: 0;
      padding-bottom: 200%;
      width: 200%;
    }
  }
  .text-write{
    background-color: #ffffd1;
  }
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
    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
      opacity: 0;
    }
    #trackings{
        width: 50% !important;
    }
    #window-load{
        display: none;
        background-color: #f2f2f2;
        height: 100%;
        opacity: 0.8;
        position: absolute;
        width: 98%;
        z-index: 9999;
    }
    #loading{
        font-size: 50;
        position: absolute;
        left: 47%;
        top: 40%;
    }
    table.table td a{
      margin: 0!important;
    }
    .totales_lb{
      border: 1px solid darkgrey;
      border-radius: 5px;
    }
    .total_title{
        font-weight: bold;
        margin-bottom: 5px;
    }
    table.dataTable tbody tr.selected{
      background-color: #d4e4fb;
    }
    .btn-change{
      width: 100%;
    }

</style>
<link href="{{ asset('css/plugins/dataTables/keyTable.dataTables.min.css') }}">
@endsection

@section('content')

    {{-- DEFAULT VALUES --}}
    <?php $id_pa = ((env('APP_CLIENT') != 'worldcargo') ? 234 : 1) ?> {{-- id de la posicion por defecto --}}
    <div class="row" id="documento">
        <modalshipper-component></modalshipper-component>
        <modalconsignee-component></modalconsignee-component>
        <modalarancel-component></modalarancel-component>
        <modalcargosadd-component :showmodal="showmodalAdd"></modalcargosadd-component>
        <products-cuba-component v-if="mostrar.includes(66)" :id_document="{{ $documento->id }}" :points="total_points" :data_p="data_points" @get="getProductsCuba($event)"></products-cuba-component>

        <form class="" id="formDocumento" name="formDocumento" class=" form-horizontal" role="form" action="{{ url('documento/updatedDocument') }}/{{  $documento->id }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" id="date" name="date" readonly="">
                <input type="hidden" class="form-control" id="id_documento" name="id_documento"  value="{{ $documento->id }}" readonly="">
                <input type="hidden" class="form-control" id="option" name="option"  value="" readonly="">
                <input type="hidden" class="form-control" name="document_type" id="document_type" data-liquidado="{{ $documento->liquidado }}"  value="consolidado" readonly="" v-model="document_type">

                <div class="col-lg-12" style="">
                    <div class="col-lg-12">
                        <div class="col-lg-6" style="padding-left: 0px;">
                            <div class="form-group">
                                <label for="agencia_id" class="">@lang('documents.agency')</label>
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
                                    <label for="num_guia" class="">@lang('documents.document_number')</label>
                                    <input type="text" id="num_guia" name="num_guia" class="form-control" readonly="" value="{{ $documento->consecutivo }}" style="background-color: #FFFFFF; font-size: 20px; font-weight: bold; color: forestgreen;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- FORMULARIO DE CONSOLIDADO --}}
                @if(!Auth::user()->isRole('bodega'))
                    <formconsolidado-component :pais_id_config="pais_id_config" :app_type="'{{ env('APP_TYPE') }}'" :app_client="'{{ env('APP_CLIENT') }}'" :documento="{{ json_encode($documento) }}" :contactos="contactos" :restore="restoreShipperConsignee" :agrupar="datosAgrupar" :removeragrupado="removerAgrupado" :permission='permissions' v-if="mostrar.includes(24)" :close_document="close"></formconsolidado-component>
                @else
                    <consol_bodega-component :app_type="'{{ env('APP_TYPE') }}'" :documento="{{ json_encode($documento) }}" :contactos="contactos" :restore="restoreShipperConsignee" :agrupar="datosAgrupar" :removeragrupado="removerAgrupado" :permission='permissions' :ref_boxes='refreshBoxes' v-if="mostrar.includes(24)" :close_document="close"></consol_bodega-component>
                @endif

                {{-- CONSIGNEE Y SHIPPER --}}
                <div class="col-lg-12 form_doc" style="display: none">
                    <div class="col-lg-6" style="margin-bottom: 20px;" v-if="mostrar.includes(25)">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><span class="fa fa-arrow-circle-up"> </span> @lang('documents.sender_shipper') <span style="color: coral; display: none;" id="msnEditarShip">@lang('documents.prepared_for_editing')</span></h5>

                            </div>
                            <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                <div class="row">
                                    <div class="col-sm-12"  data-container="body" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Para registrar un nuevo Shipper, hacer clic en el icono (Reset Shipper) e ingresar los nuevos datos." style="padding-left: 0px; padding-right: 0px;">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">@lang('documents.name'): </label>
                                            <div class="col-sm-10">
                                                <div class="input-group"  style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreR') }">
                                                    <input type="search" data-id="nomBuscarShipper" id="nombreR" name="nombreR" placeholder="@lang('documents.type_to_search')" class="form-control" onkeyup="deleteError($(this).parent());" v-model="nombreR" v-validate="'required'">
                                                    <span class="input-group-btn">
                                                        <button id="btnBuscarShipper" @click="modalShipper(true)" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Shipper"><span class="fa fa-search"></span> @lang('documents.search')</button>
                                                        <button id="btnEditShipper" @click="editFormsShipperConsignee(0)" class="btn btn-success" type="button" data-toggle='tooltip' title="Editar Shipper"><span class="fa fa-edit"></span>&nbsp;</button>
                                                        <button id="btnResetShipper" @click="resetFormsShipperConsignee(0)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset Shipper"><span class="fa fa-sync"></span>&nbsp;</button>
                                                    </span>
                                                </div>
                                                <small class="help-block has-error">@{{ errors.first('nombreR') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="control-label col-sm-2">@lang('documents.address'): </label>
                                    <div class="col-sm-10" :class="{ 'has-error': errors.has('direccionR') }">
                                        <input type="text" id="direccionR" name="direccionR" placeholder="@lang('documents.address')" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());" v-model="direccionR" v-validate="'required'">
                                        <small class="help-block has-error">@{{ errors.first('direccionR') }}</small>
                                    </div>
                                </div>

                                    <!-- /Grupo Doble 1 -->
                                     <div class="row">
                                        <label class="control-label col-sm-2">@lang('documents.email'):</label>
                                        <div class="col-sm-5" :class="{ 'has-error': errors.has('emailR') }">
                                            <input type="email" placeholder="Example@example.com" id="emailR" name="emailR" class="form-control" v-validate.disable="'unique_s'">
                                            <small class="help-block has-error">@{{ errors.first('emailR') }}</small>
                                        </div>
                                        <label class="control-label col-sm-1">@lang('documents.phone'): </label>
                                        <div class="col-sm-4">
                                            <input type="tel" data-mask="(999) 999-9999" placeholder="@lang('documents.phone')" id="telR" name="telR" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());">
                                            <small class="help-block has-error">@{{ errors.first('telR') }}</small>
                                        </div>
                                        <small class="help-block" style="display: none;"></small>
                                    </div>
                                    <!-- /Fin Grupo Doble 1 -->

                                    <!-- /Grupo Doble 2 -->
                                     <div class="row">
                                        <label class="control-label col-sm-2">@lang('documents.city'): </label>
                                        <div class="col-sm-5" onclick ="deleteError($(this));">
                                            <input type="hidden" id="localizacion_id" name="localizacion_id" value="">
                                            <city-component @get="setCity($event, true)" :data="citys" :disabled="disabled_s" :selected="city_selected_s"></city-component>
                                            <small class="help-block has-error" id="msn_l1" style="display: none;">@lang('documents.obligatory_field')</small>
                                        </div>
                                        <label class="control-label col-sm-1">@lang('documents.zip'): </label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Zip" id="zipR" name="zipR" class="form-control" onkeyup="deleteError($(this).parent());">
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
                                                    <label for="enviarEmailRemitente"> @lang('documents.send_email') <i class="fa fa-envelope-open"></i></label>
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
                                    <span class="fa fa-arrow-circle-down"> </span> @lang('documents.addressee_consignee')
                                    <span style="color: coral; display: none;" id="msnEditarCons">@lang('documents.prepared_for_editing')</span>
                                    <label class="po">PO#</label>
                                    <input type="text" id="poBoxD" name="poBoxD" class="" value="{{ isset($documento->po_box) ? $documento->po_box : '' }}" style="border-color: transparent;color: blue;" readonly="">
                                </h5>
                            </div>
                            <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                <div class="row">
                                    <div class="col-sm-12"  data-container="body" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Para registrar un nuevo Consignee para este Shipper, hacer clic en el icono (Reset Consignee) e ingresar los nuevos datos." style="padding-left: 0px; padding-right: 0px;">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">@lang('documents.name'): </label>
                                            <div class="col-sm-10">
                                                <input type="hidden" value="" id="urlBuscarConsignee">
                                                <div class="input-group" style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreD') }">
                                                    <input type="search" data-id="nomBuscarConsignee" class="form-control" id="nombreD" name="nombreD" placeholder="@lang('documents.type_to_search')" onkeyup="deleteError($(this).parent());" v-model="nombreD" v-validate="'required'">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" @click="modalConsignee(true)" id="btnBuscarConsignee" type="button" data-toggle='tooltip' title="Buscar consignee"><span class="fa fa-search"></span>@lang('documents.search')</button>
                                                        <button id="btnEditConsignee" @click="editFormsShipperConsignee(1)" class="btn btn-success" type="button" data-toggle='tooltip' title="Editar consignee"><span class="fa fa-edit"></span>&nbsp;</button>
                                                        <button id="btnResetConsignee" @click="resetFormsShipperConsignee(1)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset consignee"><span class="fa fa-sync"></span>&nbsp;</button>
                                                        </span>
                                                </div><!-- /input-group -->
                                                <small class="help-block has-error">@{{ errors.first('nombreD') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">@lang('documents.address'): </label>
                                            <div class="col-sm-10" :class="{ 'has-error': errors.has('direccionD') }">
                                                <input type="text" placeholder="@lang('documents.address')" id="direccionD" name="direccionD" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());" v-model="direccionD" v-validate="'required'">
                                                <small class="help-block has-error">@{{ errors.first('direccionD') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <!-- /Grupo Doble 1 -->
                                        <label class="control-label col-sm-2">@lang('documents.email'): </label>
                                        <div class="col-sm-5" :class="{ 'has-error': errors.has('emailD') }">
                                            <input type="email" placeholder="Example@example.com" id="emailD" name="emailD" class="form-control" v-validate.disable="'unique_c'" v-model="emailD">
                                            <small class="help-block has-error" :class="{ 'small': errors.has('emailD') }">@{{ errors.first('emailD') }}</small>
                                        </div>
                                        <label class="control-label col-sm-1">@lang('documents.phone'):</label>
                                        <div class="col-sm-4">
                                            <input type="tel" data-mask="(999) 999-9999" id="telD" name="telD" placeholder="@lang('documents.phone')" class="form-control" style="margin-bottom: 5px;" onkeyup="deleteError($(this).parent());">
                                            <small class="help-block has-error">@{{ errors.first('telD') }}</small>
                                        </div>
                                    <!-- /Fin Grupo Doble 1 -->
                                    </div>
                                    <div class="row">
                                    <!-- /Grupo Doble 2 -->
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">@lang('documents.city'): </label>
                                            <div class="col-sm-5" onclick ="deleteError($(this));">
                                              <input type="hidden" id="localizacion_id_c" name="localizacion_id_c" value="">
                                                <city-component @get="setCity($event, false)" :data="citys" :disabled="disabled_c" :selected="city_selected_c"></city-component>
                                                <small class="help-block has-error" id="msn_l2" style="display: none;">@lang('documents.obligatory_field')</small>
                                            </div>
                                            <label class="control-label col-sm-1">C.P: </label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" placeholder="@lang('general.postal_code')" id="zipD" name="zipD" class="form-control" onkeyup="deleteError($(this).parent());">
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
                                                <input type="checkbox" id="enviarEmailDestinatario" name="enviarEmailDestinatario" value="t" style="margin-left: -50px;" v-model="enviarEmailDestinatario">
                                                <label for="enviarEmailDestinatario"> @lang('documents.send_email') <i class="fa fa-envelope-open"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 form_doc" style="display: none" v-if="mostrar.includes(22) || mostrar.includes(23)">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>@lang('documents.totals')</h5>
                                <div class="ibox-tools">
                                    <label>@lang('documents.liquidate') </label>
                                    @if(env('APP_LIQUIDADO') == 1)
                                        <input type='checkbox' data-toggle="toggle" id='show-totales' name="liquidar" @click="showTotals()" data-size='mini' data-on="Si" data-off="No" data-width="50" data-style="ios" data-onstyle="primary" data-offstyle="danger" {{ ($documento->liquidado === null || $documento->liquidado !== 0) ? 'checked="checked"' : '' }}>
                                    @else
                                        <input type='checkbox' data-toggle="toggle" id='show-totales' name="liquidar" @click="showTotals()" data-size='mini' data-on="Si" data-off="No" data-width="50" data-style="ios" data-onstyle="primary" data-offstyle="danger" {{ ($documento->liquidado !== 0 and $documento->liquidado !== null) ? 'checked="checked"' : '' }}>
                                    @endif
                                </div>
                            </div>
                            <!-- TOTALES -->
                            <transition name="fade">
                                <div class="form-horizontal" v-if="showFieldsTotals">
                                    <div class="ibox-content col-lg-12" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                        <div class="form-group"  style="margin-top: 15px;">
                                            <div class="col-sm-6">
                                                <label for="tipo_embarque_id" class="">@lang('documents.type_boarding') - <span class="fa fa-ship"></span><span class="fa fa-plane"></span></label>
                                                <select id="tipo_embarque_id" name="tipo_embarque_id" class="form-control text-write" onchange="deleteError($(this).parent());llenarSelectServicio($(this).val())">
                                                    @if(isset($embarques) and $embarques)
                                                        @foreach($embarques as $embarque)
                                                            <option value="{{ $embarque['id'] }}" {{ (isset($documento->tipo_embarque_id) and $documento->tipo_embarque_id === $embarque['id']) ? 'selected' : '' }}>{{ $embarque['nombre'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="help-block" style="display: none">@lang('documents.type_boarding')</small>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="servicios_id" class="">@lang('documents.type_of_service')</label>
                                                <select onchange="calculateServiceType();" id="servicios_id" name="servicios_id" class="form-control text-write" data-servicio_id="{{ $documento->servicios_id }}">
                                                    <option value="">@lang('documents.select_type_of_boarding')</option>
                                                </select>
                                                <small class="help-block" style="display: none">@lang('documents.obligatory_field')</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                          <div class="form-group totales_lb">
                                            <div class="col-sm-6">
                                              <h1 class="total_title">TOTAL</h1>
                                            </div>
                                            <div class="col-sm-6">
                                              <h1 class="total_title">$ <span class="total_lb"></span></h1>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group" v-if="mostrar.includes(22)">
                                            <div class="col-sm-4">
                                                <label class="">@lang('documents.weight')</label>
                                                <input type="text" onkeyup="deleteError($(this).parent());" id="pesoDim" name="pesoDim" class="form-control" readonly="" value="{{ isset($documento->peso) ? $documento->peso : '' }}">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="">@lang('documents.volume')</label>
                                                <input type="text" onkeyup="deleteError($(this).parent());" id="volumen" name="volumen" class="form-control" readonly="" value="{{ isset($documento->volumen) ? $documento->volumen : '' }}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="">@lang('documents.pieces')</label>
                                                <input type="text" onkeyup="deleteError($(this).parent());" id="piezas" name="piezas" class="form-control" readonly="" value="{{ isset($documento->piezas) ? $documento->piezas : '' }}">
                                            </div>
                                        </div>

                                        <div class="form-group"  style="margin-top: 15px;" >
                                            <div class="col-sm-6">
                                                <label class="control-label" for="peso_total">@lang('documents.weight') Lb: </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" placeholder="0" class="form-control" readonly="" id="peso_total" name="peso_total" value="{{ isset($documento->peso) ? $documento->peso : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label" for="peso_cobrado">@lang('documents.charged_weight') Lb: </label>
                                            </div>

                                            <div class="col-sm-6">
                                                <input type="text" placeholder="0" class="form-control" readonly="" id="peso_cobrado" name="peso_cobrado" value="{{ isset($documento->peso_cobrado) ? $documento->peso_cobrado : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label" for="valor_libra">@lang('documents.value') Lb: (<span id="valorLibra">{{ (isset($documento->valor_libra) and $documento->valor_libra != 0)  ? $documento->valor_libra : 0 }}</span>)</label>
                                                <input type="hidden" id="valor_libra2" name="valor_libra2" value="{{ (isset($documento->valor_libra) and $documento->valor_libra != 0)  ? $documento->valor_libra : 0 }}">
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon">$ </span>
                                                    <input type="number" placeholder="0" value="{{ isset($documento->valor) ? $documento->valor : '' }}" onkeyup="totalizeDocument();" class="form-control text-write" id="valor_libra" name="valor_libra">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label" for="impuesto"><div class="col-sm-12" data-trigger="hover"  data-container="body" data-toggle="popover" data-placement="right" data-content="Valor por el cual se calculara el impuesto sobre el valor declarado. (Por defecto 28%)" style="padding-left: 0px; padding-right: 0px;"><i class="fa fa-question-circle" style="cursor: pointer; color: coral;"></i> % @lang('documents.tax'): </div></label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon">%</span>
                                                    <input type="number" placeholder="0" value="{{ isset($documento->impuesto) ? $documento->impuesto : '' }}" onkeyup="totalizeDocument();" class="form-control text-write" id="impuesto" name="impuesto" style="border-color: cornflowerblue;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6" style="padding-right: 0px;">
                                                <label class="control-label" for="valor_declarado">@lang('documents.declared'): </label>
                                            </div>
                                            <div class="col-sm-6" style="padding-right: 0px;">
                                                <label class="control-label" for="pa_aduana">@lang('documents.tax'): </label>
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
                                                <label class="control-label" for="flete"><div class="col-sm-12"  data-container="body" data-trigger="hover"  data-toggle="popover" data-placement="right" data-content="Si el calculo es sobre el volumen (Vol), se evaluara quien es mayor (Peso o Volumen), si es mayor el volumen, se multiplicara por la tarifa. Si es mayor el peso, la diferencia (Peso-Volumen) sera multiplicada por la tarifa." style="padding-left: 0px; padding-right: 0px;"><i class="fa fa-question-circle" style="cursor: pointer; color: coral;"></i> @lang('documents.freight'): (<span id="cobrarPor"></span>)</div></label>
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
                                                <label class="control-label" for="seguro_valor">@lang('documents.insured_value') $US: </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label" for="seguro">@lang('documents.insurance'): </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="number" onkeyup="totalizeDocument();" class="form-control text-write" placeholder="0" maxlength="4" id="seguro_valor" name="seguro_valor" value="{{ isset($documento->seguro) ? $documento->seguro : '' }}">
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
                                                <button class="btn btn-info" id="btnBuscarCargosAdd" type="button" @click="modalAdditionalCharges()"><span class="fa fa-plus"></span> @lang('documents.charges') </button>
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
                                                <label class="control-label" for="descuento">@lang('documents.discount'): </label>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="number" placeholder="0" class="form-control text-write" value="{{ isset($documento->descuento) ? $documento->descuento : '' }}" onkeyup="totalizeDocument();" id="descuento" name="descuento">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display: none;">
                                            <div class="col-sm-6">
                                                <label class="control-label" for="total">@lang('documents.total'): </label>
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
                            </transition>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>@lang('documents.load_data')</h5>
                                <div class="ibox-tools">

                                </div>
                            </div>
                            <!--************************************* DATOS DE CARGA PARA GUIA ****************************-->
                            <div class="form-horizontal">
                                <div class="ibox-content" :class="[mostrar.includes(22) ? 'wrh' : 'guia' ]">
                                    <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row pasos_guia" id="detalle_guia">
                                          <div class="row">
                                              <div class="col-sm-2">
                                                      <div class="form-group"  id="Valpeso">
                                                          <label class="peso">@lang('documents.weight')</label>
                                                          <input type="number" class="form-control text-write" onkeyup="deleteError($(this).parent());removeBtnBlock(this.value)" id="peso" name="peso" maxlength="4" placeholder="Lb" value="">
                                                          <small class="help-block" id="Hpeso" style="display: none">@lang('documents.these_data_are_required')</small>
                                                      </div>
                                              </div>
                                              <div class="col-sm-4">
                                                      <div class="form-group"  id="Valdim">
                                                          <label class="dimensiones">@lang('documents.dimensions') (L x W x H)</label>
                                                          <div class="input-group">
                                                              <input type="text" class="form-control text-write" placeholder="L" maxlength="4" id="largo" onkeyup="deleteError($(this).parent());" name="largo" value="0">
                                                              <span class="input-group-addon">x</span>
                                                              <input type="text" class="form-control text-write" placeholder="W" maxlength="4" id="ancho" onkeyup="deleteError($(this).parent());" name="ancho" value="0">
                                                              <span class="input-group-addon">x</span>
                                                              <input type="text" class="form-control text-write" placeholder="H" maxlength="4" id="alto" onkeyup="deleteError($(this).parent());" name="alto" value="0">
                                                          </div>
                                                          <small class="help-block" id="Hdim" style="display: none">@lang('documents.these_data_are_required')</small>
                                                      </div>
                                              </div>
                                              <div :class="[mostrar.includes(22) ? 'col-sm-6' : 'col-sm-6' ]">
                                                  <label class="contiene">@lang('documents.content')</label>
                                                  <label class="total_points" v-if="total_points > 0">(Pts. @{{ total_points }})</label>
                                                      <div class="form-group"  id="Valconti">
                                                          <label class="contiene" style="display: none;"></label>
                                                          <div class="input-group">
                                                              <input type="text" onkeyup="deleteError($(this).parent());" id="contiene" name="contiene" class="form-control text-write" value="" placeholder="@lang('documents.content')" autocomplete="off">
                                                              <span class="input-group-btn">
                                                                  <button v-if="show_btn_products" style="font-size: 19.9px!important;" id="btn-searchProducts" @click="modalSearchProducts()" class="btn btn-warning" type="button" data-toggle='tooltip' title="Productos"><span class="fa fa-map-pin"></span></button>
                                                                  <button v-else style="font-size: 19.9px!important;" id="btn-searchTracking" @click="modalSearchTracking()" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar trackings"><span class="fa fa-truck"></span></button>
                                                              </span>
                                                          </div>
                                                          <small class="help-block" id="Hcontiene" style="display: none">@lang('documents.obligatory_field')</small>
                                                      </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-2" v-if="mostrar.includes(34)">
                                                  <label class="valDeclarado">@lang('documents.pieces')</label>
                                                      <div class="form-group" id="ValDecla">
                                                          <label style="display: none;" for="" class=""></label>
                                                          <input type="number" onkeyup="deleteError($(this).parent());" placeholder="@lang('documents.pieces')" onkeyup="deleteError($(this).parent());" id="valPiezas" name="valPiezas" class="form-control text-write" value="1">
                                                          <small class="help-block" id="Hpiezas" style="display: none">>@lang('documents.obligatory_field')</small>
                                                      </div>
                                              </div>
                                              <div class="col-sm-2">
                                                      <div class="form-group" id="Valtipoem">
                                                          <label for="tipo_empaque_id" class="">@lang('documents.packing')</label>
                                                          <select  onchange="deleteError($(this).parent());" id="tipo_empaque_id" name="tipo_empaque_id" class="form-control text-write">
                                                              @if(isset($empaques) and $empaques)
                                                                  @foreach($empaques as $empaque)
                                                                      <option value="{{ $empaque['id'] }}">{{ $empaque['nombre'] }}</option>
                                                                  @endforeach
                                                              @endif
                                                          </select>
                                                          <small class="help-block" id="HtipoE" style="display: none">@lang('documents.obligatory_field')</small>
                                                      </div>
                                              </div>
                                              <template v-show="mostrar.includes(16)">
                                                 {{-- <div class="col-sm-6" v-show="!showFieldsTotals">
                                                      <label class="tracking">Tracking</label>
                                                          <div class="form-group">
                                                              <input type="text" onkeyup="deleteError($(this).parent());" placeholder="Numero de tracking" onkeyup="deleteError($(this).parent());" id="tracking" name="tracking" class="form-control" value="">
                                                          </div>
                                                  </div> --}}
                                                 <div class="col-sm-2" v-show="showFieldsTotals">
                                                      <label class="valDeclarado">@lang('documents.declared')</label>
                                                          <div class="form-group" id="ValDecla">
                                                              <label style="display: none;" for="" class=""></label>
                                                              <input type="number" onkeyup="deleteError($(this).parent());" placeholder="@lang('documents.declared')" onkeyup="deleteError($(this).parent());" id="valDeclarado" name="valDeclarado" class="form-control text-write" value="">
                                                              <small class="help-block" id="HvalDeclarado" style="display: none">@lang('documents.obligatory_field')</small>
                                                          </div>
                                                  </div>
                                                  <div class="col-sm-4" v-show="showFieldsTotals">
                                                      <label for="pa" class="">@lang('documents.tariff_position')</label>
                                                          <div class="form-group" id="Errpa">
                                                              <label style="display: none;" for="" class=""></label>
                                                              <div class="input-group">
                                                                  <span class="input-group-btn" onclick="deleteError($(this).parent());">
                                                                      <button class="btn btn-primary" id="btnBuscarPA" type="button" @click="modalArancel()"><small><span class="fa fa-search"></span> P.A (Adu.)</small></button>
                                                                  </span>
                                                                  <input type="text" placeholder="@lang('general.select')" class="form-control" readonly="" value="" id="pa" name="pa" onkeyup="deleteError($(this).parent());">
                                                              </div><!-- /input-group -->
                                                              <small class="help-block" id="Hpa" style="display: none">
                                                              @lang('documents.obligatory_field')</small>
                                                          </div>

                                                      <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="pa_id" name="pa_id">
                                                      <!--<div class="col-sm-2">-->
                                                      <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="arancel" name="arancel">
                                                      <!--</div>-->
                                                      <!--<div class="col-sm-2">-->
                                                      <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="iva" name="iva">
                                                  </div>
                                              </template>
                                              <!--</div>-->
                                                  <div class="col-lg-2">
                                                      <div class="form-group">
                                                          <div class="input-group">
                                                              <!--para quitar el efecto de bloqueo del boton, quitar la clase btnBlock-->
                                                              {{-- <button class="btn btn-info btn-sm btnBlock" type="button" id="btn_add" value="0" @click="addDetail()" style="width: 100%;"><span class="fa fa-plus" ></span> @lang('documents.add')</button> --}}


                                                          <!-- Split button -->
                                                              <label for="btn_add" class="control-label" style="padding-top: 2px;width: 100%">&nbsp;</label>
                                                              <div class="btn-group">
                                                                <button type="button" :disabled="disabled_client" class="btn btn-info btn-sm btnBlock" id="btn_add" value="0" @click="addDetail()">@lang('documents.add')</button>
                                                                <button type="button" :disabled="disabled_client" class="btn btn-info btn-sm dropdown-toggle btnBlock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 35px;">
                                                                  <span class="caret"></span>
                                                                  <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                  <li><a @click="addDetail(true)">Agregar por cantidad</a></li>
                                                                </ul>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                          </div>
                                        </div>
                                        <div class="row pasos_guia" id="grilla_guia">
                                            <div class="">
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped  table-hover" id="whgTable" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item</th>
                                                                        <th style="width: 10%;">@lang('documents.code')</th>
                                                                        <th style="width: 7%;">@lang('documents.pieces')</th>
                                                                        <th style="width: 17%;">@lang('documents.weight')(Lb)</th>
                                                                        <th style="">@lang('documents.contains')</th>
                                                                        <th style="width: 15%;">PA</th>
                                                                        <th style="width: 10%;">@lang('documents.value') US$</th>
                                                                        <th style="width: 10%;">@lang('documents.points')</th>
                                                                        <th style="width: 13%;">@lang('documents.action')</th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot style="background-color: paleturquoise;">
                                                                    <tr>
                                                                        <td colspan="9">
                                                                            <table style="width: 100%;">
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="col-lg-12">
                                                                                        <label class="">@lang('documents.pieces')(s)</label>
                                                                                            <input type="text" onkeyup="deleteError($(this).parent());" id="piezas" name="piezas" class="form-control" readonly="" value="{{ isset($documento->piezas) ? $documento->piezas : 0 }}" style="width: 100px;">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="col-sm-12">
                                                                                            <label class="">Total lbs</label>
                                                                                            <input type="text" onkeyup="deleteError($(this).parent());" id="pesoDim" name="pesoDim" class="form-control" readonly="" value="{{ isset($documento->peso) ? $documento->peso : 0 }}" style="width: 100px;">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="col-sm-12">
                                                                                            <label class="">Total @lang('documents.volume')</label>
                                                                                            <input type="text" onkeyup="deleteError($(this).parent());" id="volumen" name="volumen" class="form-control" readonly="" value="{{ isset($documento->volumen) ? $documento->volumen : 0 }}" style="width: 100px;">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="col-sm-12">
                                                                                            <label class="">@lang('documents.cubic_foot')</label>
                                                                                            <input type="text" id="pie_ft" name="pie_ft" class="form-control" readonly="" value="{{ (isset($documento->volumen)) ? number_format(($documento->volumen * 166 / 1728), 2) : 0 }}" style="width: 100px;">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="col-lg-12">
                                                                                            <label class="">Total @lang('documents.declared')</label>
                                                                                            <input type="text" onkeyup="deleteError($(this).parent());" id="valor_declarado_tbl" class="form-control" readonly="" value="0" style="width: 100px;">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div id="noEnviar" class="col-lg-12" style="text-align: center; color: red; display: none;">@lang('documents.message_register')</div>

                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row pasos_guia" id="generales_guia">
                                            <div class="col-lg-4" v-if="mostrar.includes(17)">
                                                    <div class="form-group">
                                                        <label for="" class="">@lang('documents.payment_type')</label>
                                                        <select id="tipo_pago_id" name="tipo_pago_id" class="form-control text-write" onchange="deleteError($(this).parent());">
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
                                                        <label for="forma_pago_id" class="">@lang('documents.way_to_pay')</label>
                                                        <select id="forma_pago_id" name="forma_pago_id" class="form-control text-write" onchange="deleteError($(this).parent());">
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
                                                        <label for="grupo_id" class="">@lang('documents.group')</label>
                                                        <select id="grupo_id" name="grupo_id" class="form-control text-write" onchange="deleteError($(this).parent());">
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
                                                        <label>@lang('documents.comments'): </label>
                                                        <textarea class="form-control text-write" rows="3" style="height: 65px;" id="observaciones" name="observaciones">{{ isset($documento->observaciones) ? $documento->observaciones : '' }}</textarea>
                                                    </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox1" name="factura" value="1" {{ (isset($documento->factura) and $documento->factura != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox1"> @lang('documents.bill') </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox2" name="carga_peligrosa" value="1" {{ (isset($documento->carga_peligrosa) and $documento->carga_peligrosa != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox2">@lang('documents.dangerous_load')</label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox3" name="re_empacado" value="1" {{ (isset($documento->re_empacado) and $documento->re_empacado != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox3">@lang('documents.packed') </label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox4" name="mal_empacado" value="1" {{ (isset($documento->mal_empacado) and $documento->mal_empacado != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox4"> @lang('documents.badly_packed')</label>
                                                </div>
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox5" name="rota" value="1" {{ (isset($documento->rota) and $documento->rota != 0) ? 'checked' : '' }}>
                                                    <label for="inlineCheckbox5"> @lang('documents.broken')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="id" name="id" value="">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-sm-12 col-sm-offset-0 guardar">
                                                    @if(env('APP_CLIENT') == 'jexpress')
                                                        <button type="button" class="btn btn-success ladda-button" id="saveForm" data-style="expand-right" @click="saveDocument('all')"><i class="fa fa-save fa-fw"></i>@lang('documents.save_changes')</button>
                                                    @else
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-success ladda-button" id="saveForm" data-style="expand-right" @click="saveDocument('print')"><i class="fa fa-save fa-fw"></i> @lang('documents.save_changes_print')</button>
                                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 35px;">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a @click="saveDocument()"><i class="fa fa-print"></i> @lang('documents.save_changes')</a></li>
                                                            <li><a @click="saveDocument('email')"><i class="fa fa-envelope"></i> @lang('documents.save_changes_email')</a></li>
                                                            <li><a @click="saveDocument('all')"><i class="fa fa-mail-bulk"></i> @lang('documents.save_changes_email_print')</a></li>
                                                        </ul>
                                                    </div>
                                                    @endif

                                                    <a href="{{ route('documento.index') }}" type="button" class="btn btn-white"><i class="fa fa-times fa-fw"></i> @lang('documents.cancel') </a>
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

        {{-- MODAL AGREGAR TRACKINGS --}}
        @include('templates/documento/modals/modalTracking')
        {{-- MODAL AGREGAR TRACKINGS --}}
        @include('templates/documento/modals/modalChangeShipperConsignee', ['shippers' => $shippers, 'consignees' => $consignees])

        {{-- MODAL AGREGAR PUNTOS --}}
        <points-component v-if="mostrar.includes(66)" :id_detail="points_id_detail"></points-component>
    </div>
@endsection

@section('scripts')
{!! $wcpScript; !!}
{{-- <script src="{{ asset('js/plugins/dataTables/dataTables.keyTable.min.js') }}"></script> --}}
{{-- <script src="{{ asset('js/plugins/dataTables/fnFilterClear.js') }}"></script> --}}
<script src="{{ asset('js/templates/documento/documentoForm/objVue.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoForm/documento.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoForm/totalizar.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoForm/postalCode.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoForm/editableConfig.js') }}"></script>
@endsection

@extends('layouts.app')
@section('title', trans('layouts.documents'))
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.configuration') @lang('layouts.documents')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('layouts.documents')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
  <div class="row" id="configDocument">
    <modalshipper-component></modalshipper-component>
    <modalconsignee-component></modalconsignee-component>
    <div class="col-lg-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>@lang('layouts.documents')</h5>
            </div>
            <div class="ibox-content">
              <ul>
                <li>Warehouse</li>
              </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-10">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Nombre del documento seleccionado</h5>
        </div>
        <div class="ibox-content">
          <div class="tabs-container">
              <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#tab-1"> Valores por defecto</a></li>
                  <li class=""><a data-toggle="tab" href="#tab-2">Acciones por defecto</a></li>
              </ul>
              <div class="tab-content">
                  <div id="tab-1" class="tab-pane active">
                      <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-6 b-r"><h3 class="m-t-none m-b">Shipper</h3>
                              <p>El shipper que seleccione, se cargará por defecto en todos los documetos que cree.</p>
                              <div class="form-group">
                                <div class="input-group"  style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreR') }">
                                  <input type="hidden" class="" id="shipper_id" name="shipper_id" value="{{ isset($documento->shipper_id) ? $documento->shipper_id : '' }}">
                                  <input type="search" data-id="nomBuscarShipper" id="nombreR" name="nombreR" placeholder="@lang('documents.type_to_search')" class="form-control" onkeyup="deleteError($(this).parent());" v-model="nombreR" v-validate="'required'">
                                  <span class="input-group-btn">
                                    <button id="btnBuscarShipper" @click="modalShipper(true)" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Shipper"><span class="fal fa-search"></span> @lang('documents.search')</button>
                                    {{-- <button id="btnResetShipper" @click="resetFormsShipperConsignee(0)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset Shipper"><span class="fal fa-sync"></span>&nbsp;</button> --}}
                                  </span>
                                </div>
                                <small class="help-block has-error">@{{ errors.first('nombreR') }}</small>
                              </div>
                                <ul>
                                  <div class="col-lg-6">
                                    <li>@{{ dataShipper.nombre_full }}</li>
                                    <li>@{{ dataShipper.correo }}</li>
                                  </div>
                                  <div class="col-lg-6">
                                    <li>@{{ dataShipper.direccion }}</li>
                                    <li>@{{ dataShipper.phone }}</li>
                                  </div>
                                </ul>
                            </div>
                            <div class="col-sm-6"><h3 class="m-t-none m-b">Consignee</h3>
                              <p>El consignee que seleccione, se cargará por defecto en todos los documetos que cree.</p>
                              <div class="form-group">
                                <div class="input-group"  style="margin-bottom: 5px;" :class="{ 'has-error': errors.has('nombreD') }">
                                  <input type="search" data-id="nomBuscarConsignee" id="nombreD" name="nombreD" placeholder="@lang('documents.type_to_search')" class="form-control" onkeyup="deleteError($(this).parent());" v-model="nombreD" v-validate="'required'">
                                  <span class="input-group-btn">
                                    <button id="btnBuscarConsignee" @click="modalConsignee(true)" class="btn btn-primary" type="button" data-toggle='tooltip' title="Buscar Consignee"><span class="fal fa-search"></span> @lang('documents.search')</button>
                                    {{-- <button id="btnResetSConsignee" @click="resetFormsShipperConsignee(0)" class="btn btn-default" type="button" data-toggle='tooltip' title="Reset Consignee"><span class="fal fa-sync"></span>&nbsp;</button> --}}
                                  </span>
                                </div>
                                <small class="help-block has-error">@{{ errors.first('nombreR') }}</small>
                              </div>
                                <ul>
                                  <div class="col-lg-6">
                                    <li>@{{ dataConsignee.nombre_full }}</li>
                                    <li>@{{ dataConsignee.correo }}</li>
                                  </div>
                                  <div class="col-lg-6">
                                    <li>@{{ dataConsignee.direccion }}</li>
                                    <li>@{{ dataConsignee.phone }}</li>
                                  </div>
                                </ul>
                              </div>
                          </div>
                          <br>
                          <hr>
                          <div class="row">
                            <div class="col-lg-12">
                              <h3>Observación por defecto</h3>
                              <div class="form-group">
                                <textarea name="name" rows="4" cols="20" class="form-control" v-model="observDefault"></textarea>
                              </div>
                              <button @click="saveDefaultObserv" class="btn btn-primary" type="button" data-toggle='tooltip' title="Guardar"><span class="fal fa-save"></span></button>
                            </div>
                          </div>
                      </div>
                  </div>
                  <div id="tab-2" class="tab-pane">
                      <div class="panel-body">
                          <strong>Donec quam felis</strong>

                          <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                              and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                          <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                              sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                      </div>
                  </div>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/config/configDocument.js') }}"></script>
@endsection

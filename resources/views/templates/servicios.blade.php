@extends('layouts.app')
@section('title', 'Servicios')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.services')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.services')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row" id="servicios">
    <modalarancel-component></modalarancel-component>
    <form id="formservicios" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.registration_of _services')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-sm-5" :class="{'has-error': listErrors.tipo_embarque_id}">
                                    <label for="tipo_embarque_id"
                                        class="control-label gcore-label-top">@lang('general.boarding_type'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <select id="tipo_embarque_id" name="tipo_embarque_id" class="form-control"
                                        @click="deleteError('tipo_embarque_id')">
                                        <option value="" data-seguro="">@lang('general.select')</option>
                                    </select>
                                    <small id="msn1" class="help-block result-tipo_embarque_id"
                                        v-show="listErrors.tipo_embarque_id" style="color:#ed5565"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-sm-5" :class="{'has-error': listErrors.country}">
                                    <label for="country"
                                        class="control-label gcore-label-top">@lang('general.country'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <el-select autocomplete="off" name="country_id" v-model="country_id" filterable
                                        placeholder="Pais" v-validate.disable="'required'" size="medium">
                                        <el-option v-for="item in countries" :key="item.id" :label="item.descripcion"
                                            :value="item.id"></el-option>
                                    </el-select>
                                    <small id="msn1" class="help-block result-country" v-show="listErrors.country"
                                        style="color:#ed5565"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                <div class="col-sm-5">
                                    <label for="nombre"
                                        class="control-label gcore-label-top">@lang('general.name'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <input v-model="nombre" name="nombre" id="nombre" value=""
                                        placeholder="@lang('general.enter_the_name_of_the_service')"
                                        class="form-control" type="text" @click="deleteError('nombre')" />
                                    <small id="msn1" class="help-block result-nombre"
                                        v-show="listErrors.nombre"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.tarifa}">
                                <div class="col-sm-5">
                                    <label for="tarifa"
                                        class="control-label gcore-label-top">@lang('general.rate'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <li class="fal fa-dollar-sign"></li>
                                        </span>
                                        <input v-model="tarifa" name="tarifa" id="tarifa" value=""
                                            placeholder="@lang('general.enter_the_rate')" class="form-control"
                                            type="text" @click="deleteError('tarifa')" />
                                    </div>
                                    <small id="msn1" class="help-block result-tarifa"
                                        v-show="listErrors.tarifa"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.peso_minimo}">
                                <div class="col-sm-5">
                                    <label for="peso_minimo"
                                        class="control-label gcore-label-top">@lang('general.minimum_fee'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <li class="fal fa-dollar-sign"></li>
                                        </span>
                                        <input v-model="peso_minimo" name="peso_minimo" id="peso_minimo" value=""
                                            placeholder="@lang('general.minimum_value_of_the_rate')"
                                            class="form-control" type="text" @click="deleteError('peso_minimo')" />
                                    </div>
                                    <small id="msn1" class="help-block result-peso_minimo"
                                        v-show="listErrors.peso_minimo"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.cobro_opcional}">
                                <div class="col-sm-5">
                                    <label for="cobro_opcional"
                                        class="control-label gcore-label-top">@lang('general.optional_charge'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <li class="fal fa-dollar-sign"></li>
                                        </span>
                                        <input v-model="cobro_opcional" name="cobro_opcional" id="cobro_opcional"
                                            value="" placeholder="@lang('general.enter_the_optional_charge')"
                                            class="form-control" type="text" @click="deleteError('cobro_opcional')" />
                                    </div>
                                    <small id="msn1" class="help-block result-cobro_opcional"
                                        v-show="listErrors.cobro_opcional"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.seguro}">
                                <div class="col-sm-5">
                                    <label for="seguro"
                                        class="control-label gcore-label-top">@lang('general.insurance'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <li class="fal fa-dollar-sign"></li>
                                        </span>
                                        <input v-model="seguro" name="seguro" id="seguro" value=""
                                            placeholder="@lang('general.enter_the_insurance_percentage')"
                                            class="form-control" type="text" @click="deleteError('seguro')" />
                                    </div>
                                    <small id="msn1" class="help-block result-seguro"
                                        v-show="listErrors.seguro"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.impuesto}">
                                <div class="col-sm-5">
                                    <label for="impuesto"
                                        class="control-label gcore-label-top">@lang('general.tax'):</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <li class="fal fa-percent"></li>
                                        </span>
                                        <input v-model="impuesto" name="impuesto" id="impuesto" value=""
                                            placeholder="@lang('general.enter_the_tax_percentage')" class="form-control"
                                            type="text" @click="deleteError('impuesto')" />
                                    </div>
                                    <small id="msn1" class="help-block result-impuesto"
                                        v-show="listErrors.impuesto"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" :class="{'has-error': listErrors.cobro_peso_volumen}">
                                <div class="col-sm-5">
                                    <label for="cobro_peso_volumen" class="control-label gcore-label-top">
                                        <div class="col-sm-12" data-trigger="hover" data-container="body"
                                            data-toggle="popover" data-placement="top"
                                            data-content="Active el volumen para cobrar los envios con volumen adicional."
                                            style="padding-left: 0px; padding-right: 0px;">
                                            @lang('general.collection_weight_volume'):
                                            <i class="fal fa-question-circle"
                                                style="cursor: pointer; color: coral;"></i>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <input v-model="cobro_peso_volumen" name="cobro_peso_volumen"
                                        id="cobro_peso_volumen" class="form-control" type='checkbox'
                                        data-toggle="toggle" data-size='mini' data-on="Peso" data-off="Volumen"
                                        data-width="80" data-style="ios" data-onstyle="primary" data-offstyle="warning"
                                        @click="deleteError('cobro_peso_volumen')" />
                                    <small id="msn1" class="help-block result-cobro_peso_volumen"
                                        v-show="listErrors.cobro_peso_volumen"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" id="Errpa">
                                <div class="col-sm-5">
                                    <label for="pa" class="">@lang('documents.tariff_position')</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-btn" onclick="deleteError($(this).parent());">
                                            <button class="btn btn-primary" id="btnBuscarPA" type="button"
                                                @click="modalArancel()" style="height: 33px;"><small><span
                                                        class="fal fa-search"></span></small></button>
                                        </span>
                                        <input type="text" placeholder="@lang('general.select')" class="form-control"
                                            readonly="" value="" id="pa" name="pa"
                                            onkeyup="deleteError($(this).parent());">
                                    </div><!-- /input-group -->
                                    <small class="help-block" id="Hpa" style="display: none">
                                        @lang('documents.obligatory_field')</small>
                                </div>
                            </div>
                            <input type="hidden" placeholder="0" class="form-control" readonly="" value="" id="pa_id"
                                name="pa_id">
                        </div>
                    </div>

                    <div class="row">
                        @include('layouts.buttons')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.services')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-servicios" class="table table-striped table-hover table-bordered"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.boarding_type')</th>
                                    <th>@lang('general.services')</th>
                                    <th>@lang('general.rate')</th>
                                    <th>@lang('general.minimum_fee')</th>
                                    <th>@lang('general.optional_charge')</th>
                                    <th>@lang('general.insurance')</th>
                                    <th>@lang('general.tax')</th>
                                    <th>@lang('general.weight_volume')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/servicios.js') }}"></script>
@endsection
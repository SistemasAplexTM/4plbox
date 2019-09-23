@extends('layouts.app')
@section('title', $name)
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $name }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>{{ $name }}</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="maestraMultiple">
        <form id="formmaestraMultiples" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        	<input v-model="modulo" type="text" id="modulo" value="{{ $type }}" hidden="">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.register_of') {{ $name }}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre[]" id="nombre" value="" placeholder="@lang('general.name')" class="form-control" type="text" @click="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre">@lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.descripcion}">
                                        <div class="col-sm-4">
                                            <label for="descripcion" class="control-label gcore-label-top">{{ ($type != 'Forma_de_Pago' and $type != 'Tipos_de_Pago') ? 'Descripción:' : 'Abreviatura:' }}</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="descripcion" name="descripcion[]" id="descripcion" value="" placeholder="@lang('general.description')" class="form-control" type="text" @click="deleteError('descripcion')" />
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion"> @lang('general.obligatory_field')</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.existing_records')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-maestraMultiple" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>@lang('general.name')</th>
                                        <th>{{ ($type != 'Forma_de_Pago' and $type != 'Tipos_de_Pago') ? 'Descripción' : 'Abreviatura' }}</th>
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
<script src="{{ asset('js/templates/maestraMultiple.js') }}"></script>
@endsection

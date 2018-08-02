@extends('layouts.app')
@section('title', 'Arancel')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Posiciones arancelarias</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Posiciones arancelarias</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="arancel">
        <form id="formarancel" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de posicione arancelaria</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.pa}">
                                        <div class="col-sm-4">
                                            <label for="pa" class="control-label gcore-label-top">P.A:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="pa" name="pa" id="pa" value="" placeholder="Código de la posicion arancelaria" class="form-control" type="text" @click="deleteError('pa')" @focus="deleteError('pa')" />
                                            <small id="msn1" class="help-block result-pa" v-show="listErrors.pa">* Campo obligatorio</small>
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
                                            <label for="descripcion" class="control-label gcore-label-top">Descripcion:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea v-model="descripcion" name="descripcion" id="descripcion" placeholder="Descripcion de la P.A" class="form-control" @click="deleteError('descripcion')" @focus="deleteError('descripcion')"></textarea>
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion">* Campo obligatorio</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.iva}">
                                        <div class="col-sm-4">
                                            <label for="iva" class="control-label gcore-label-top">IVA:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="iva" name="iva" id="iva" value="" placeholder="Porcentaje arancel EJ: 0.19 => 19%" class="form-control" type="text" @click="deleteError('iva')" @focus="deleteError('iva')"/>
                                            <small id="msn1" class="help-block result-iva" v-show="listErrors.iva">* Campo obligatorio</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.arancel}">
                                        <div class="col-sm-4">
                                            <label for="arancel" class="control-label gcore-label-top">Arancel:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="arancel" name="arancel" id="arancel" value="" placeholder="Porcentaje arancel EJ: 0.05 => 5%" class="form-control" type="text" @click="deleteError('arancel')" @focus="deleteError('arancel')"/>
                                            <small id="msn1" class="help-block result-arancel" v-show="listErrors.arancel">* Campo obligatorio</small>
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
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Posiciones arancelarias</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-arancel" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Codigo P.A</th>
                                    <th>Descripcion</th>
                                    <th><i class="fa fa-percent"></i>Iva</th>
                                    <th><i class="fa fa-percent"></i>Arancel</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Codigo P.A</th>
                                    <th>Descripcion</th>
                                    <th><i class="fa fa-percent"></i>Iva</th>
                                    <th><i class="fa fa-percent"></i>Arancel</th>
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
<script src="{{ asset('js/templates/arancel.js') }}"></script>
@endsection
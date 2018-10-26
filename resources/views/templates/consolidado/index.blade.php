@extends('layouts.app')

@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Consolidados</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Consolidados</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="departamento">
        <form id="formdepartamento" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de consolidados</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.descripcion}">
                                        <div class="col-sm-4">
                                            <label for="descripcion" class="control-label gcore-label-top">Descripcion:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="descripcion" name="descripcion[]" id="descripcion" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('descripcion')" />
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.pais_id}">
                                        <div class="col-sm-4">
                                            <label for="pais_id" class="control-label gcore-label-top">País:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="pais_id_input" value="">
                                            <select v-model="pais_id" name="pais_id" id="pais_id" class="form-control js-data-example-ajax select2-container" @click="deleteError('pais_id')">
                                            </select>
                                            <small id="msn1" class="help-block result-pais_id" v-show="listErrors.pais_id"></small>
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
                    <h5>Consolidados</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-departamento" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>País</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>País</th>
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
<script src="{{ asset('js/templates/consolidado/main.js') }}"></script>
@endsection
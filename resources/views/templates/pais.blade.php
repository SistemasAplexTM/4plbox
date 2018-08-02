@extends('layouts.app')
@section('title', 'Paises')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Pais</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Pais</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="pais">
        <form id="formpais" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de pais</h5>
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
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion">* Campo obligatorio</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.prefijo}">
                                        <div class="col-sm-4">
                                            <label for="prefijo" class="control-label gcore-label-top">prefijo:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="prefijo" name="prefijo" id="prefijo" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('prefijo')" />
                                            <small id="msn1" class="help-block result-prefijo" v-show="listErrors.prefijo">* Campo obligatorio</small>
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
                    <h5>Paises</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-pais" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Prefijo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Prefijo</th>
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
<script src="{{ asset('js/templates/pais.js') }}"></script>
@endsection
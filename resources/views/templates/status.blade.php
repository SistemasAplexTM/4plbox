@extends('layouts.app')
@section('title', 'Status')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Status</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Status</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="status">
        <form id="formstatuss" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Registro de status</h5>
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
                                            <input v-model="descripcion" name="descripcion" id="descripcion" value="" placeholder="" class="form-control" type="text" style="" @click="deleteError('descripcion')" @focus="deleteError('descripcion')"/>
                                            <small id="msn1" class="help-block result-descripcion" v-show="listErrors.descripcion">* Campo obligatorio</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.color}">
                                        <div class="col-sm-4">
                                            <label for="color" class="control-label gcore-label-top">Color:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="color" name="color" id="color" value="" placeholder="" class="form-control" type="color" style="" @click="deleteError('color')" @focus="deleteError('color')"/>
                                            <small id="msn1" class="help-block result-color" v-show="listErrors.color">* Campo obligatorio</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.email}">
                                        <div class="col-sm-4">
                                            <label for="email" class="control-label gcore-label-top">Enviar Email:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="i-checks"><label>
                                                <input type="radio" value="1" id="email_s" name="email"> <i></i> Si </label>
                                            </div>
                                            <div class="i-checks"><label> 
                                                <input type="radio" value="0" id="email_n" name="email" checked=""> <i></i> No </label>
                                            </div>
                                            <small id="msn1" class="help-block result-email" v-show="listErrors.email">* Campo obligatorio</small>
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
                    <h5>Status</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-status" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Color</th>
                                    <th>Enviar Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Color</th>
                                    <th>Enviar Email</th>
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
<script src="{{ asset('js/templates/status.js') }}"></script>
@endsection